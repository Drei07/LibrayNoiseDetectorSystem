// PH LEVEL GAUGE
am5.ready(function () {
    var SoundSensorDevice1Gauge = am5.Root.new("SoundSensorDevice1");

    // Set themes
    SoundSensorDevice1Gauge.setThemes([
        am5themes_Animated.new(SoundSensorDevice1Gauge)
    ]);

    // Create SoundSensorDevice1 chart
    var SoundSensorDevice1chart = SoundSensorDevice1Gauge.container.children.push(am5radar.RadarChart.new(SoundSensorDevice1Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var SoundSensorDevice1axisRenderer = am5radar.AxisRendererCircular.new(SoundSensorDevice1Gauge, {
        innerRadius: -40
    });

    SoundSensorDevice1axisRenderer.grid.template.setAll({
        stroke: SoundSensorDevice1Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var SoundSensorDevice1xAxis = SoundSensorDevice1chart.xAxes.push(am5xy.ValueAxis.new(SoundSensorDevice1Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 120,
        strictMinMax: true,
        renderer: SoundSensorDevice1axisRenderer
    }));

    // Add clock hand
    var SoundSensorDevice1axisDataItem = SoundSensorDevice1xAxis.makeDataItem({});
    var SoundSensorDevice1clockHand = am5radar.ClockHand.new(SoundSensorDevice1Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var SoundSensorDevice1bullet = SoundSensorDevice1axisDataItem.set("bullet", am5xy.AxisBullet.new(SoundSensorDevice1Gauge, {
        sprite: SoundSensorDevice1clockHand
    }));

    SoundSensorDevice1xAxis.createAxisRange(SoundSensorDevice1axisDataItem);

    var SoundSensorDevice1label = SoundSensorDevice1chart.radarContainer.children.push(am5.Label.new(SoundSensorDevice1Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    SoundSensorDevice1axisDataItem.set("value", 0);

    SoundSensorDevice1bullet.get("sprite").on("rotation", function () {
        var value = SoundSensorDevice1axisDataItem.get("value");
        var fill = am5.color(0x000000);
        SoundSensorDevice1xAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        SoundSensorDevice1label.set("text", Math.round(value).toString());

        SoundSensorDevice1clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        SoundSensorDevice1clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var SoundSensorDevice1current = 0;
    var SoundSensorDevice1target = 0;
    var SoundSensorDevice1animationStartTime = performance.now(); // Timestamp to track animation start time
    var SoundSensorDevice1animationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with smooth animation
    function SoundSensorDevice1Update(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            SoundSensorDevice1target = parsed;

            function animate() {
                if (Math.abs(SoundSensorDevice1current - SoundSensorDevice1target) < 0.5) {
                    SoundSensorDevice1current = SoundSensorDevice1target; // Prevents tiny oscillations
                } else {
                    // Smooth step interpolation
                    SoundSensorDevice1current += (SoundSensorDevice1target - SoundSensorDevice1current) * 0.1;
                    SoundSensorDevice1axisDataItem.set("value", Number(SoundSensorDevice1current.toFixed(0)));

                    requestAnimationFrame(animate);
                }
            }

            requestAnimationFrame(animate);
        } else {
            console.error('Invalid SoundSensorDevice1 level:', level);
        }
    }

    // Setup WiFi status check and monitoring
    function SoundSensorDevice1setupWiFiStatusCheckAndEnableMonitoring() {
        function updateWifiStatus() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        var wifiStatusElement = document.getElementById('wifi_status');
                        if (wifiStatusElement) {
                            wifiStatusElement.innerText = data.wifi_status;
                            wifiStatusElement.style.color = (data.wifi_status.toLowerCase() === 'connected') ? 'green' : 'red';
                        }
                    } else {
                        console.error("WiFi status update failed");
                    }
                }
            };
            xhr.open('POST', 'controller/receive_data.php', true);
            xhr.send();
        }

        function SoundSensorDevice1LevelFetchData() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
        
                    // Extract dB values per device
                    var soundDevice1 = data.find(device => device.DeviceID === "DEVICE1")?.dbValue || "N/A";
                    var soundDevice2 = data.find(device => device.DeviceID === "DEVICE2")?.dbValue || "N/A";
                    var soundDevice3 = data.find(device => device.DeviceID === "DEVICE3")?.dbValue || "N/A";
        
                    // Update UI elements
                    document.getElementById('soundDevice1').textContent = soundDevice1 + " dB";
                    document.getElementById('soundDevice2').textContent = soundDevice2 + " dB";
                    document.getElementById('soundDevice3').textContent = soundDevice3 + " dB";
        
                    // Call functions to process the values if necessary
                    SoundSensorDevice1Update(soundDevice1);
                    SoundSensorDevice2Update(soundDevice2);
                    SoundSensorDevice3Update(soundDevice3);
                }
            };
        
            xhr.open('POST', 'controller/receive_data.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify({}));
        }
        
        // Initial fetch and setup for periodic updates
        SoundSensorDevice1LevelFetchData();
        setInterval(SoundSensorDevice1LevelFetchData, 500);
        updateWifiStatus();
    }

    // Call the setup function
    SoundSensorDevice1setupWiFiStatusCheckAndEnableMonitoring();

    // Create axis ranges bands for soil moisture sensor (0-1000)
    var SoundSensorDevice1bandsData = [{
        title: "Very Quiet",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 20
    }, {
        title: "Quiet",
        color: "#b0d136", // Light Green
        lowScore: 20,
        highScore: 40
    }, {
        title: "Moderate",
        color: "#f3eb0c", // Yellow
        lowScore: 40,
        highScore: 60
    }, {
        title: "Loud",
        color: "#fdae19", // Orange
        lowScore: 60,
        highScore: 80
    }, {
        title: "Very Loud",
        color: "#f04922", // Red
        lowScore: 80,
        highScore: 100
    }, {
        title: "Extremely Loud",
        color: "#f02222", // Red
        lowScore: 100,
        highScore: 120
    }];
    
    

    am5.array.each(SoundSensorDevice1bandsData, function (data) {
        var range = SoundSensorDevice1xAxis.createAxisRange(SoundSensorDevice1xAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: SoundSensorDevice1Gauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    SoundSensorDevice1chart.appear(1000, 100);

    //SoundSensorDevice2-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    var SoundSensorDevice2Gauge = am5.Root.new("SoundSensorDevice2");

    // Set themes
    SoundSensorDevice2Gauge.setThemes([
        am5themes_Animated.new(SoundSensorDevice2Gauge)
    ]);

    // Create SoundSensorDevice2 chart
    var SoundSensorDevice2chart = SoundSensorDevice2Gauge.container.children.push(am5radar.RadarChart.new(SoundSensorDevice2Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var SoundSensorDevice2axisRenderer = am5radar.AxisRendererCircular.new(SoundSensorDevice2Gauge, {
        innerRadius: -40
    });

    SoundSensorDevice2axisRenderer.grid.template.setAll({
        stroke: SoundSensorDevice2Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var SoundSensorDevice2xAxis = SoundSensorDevice2chart.xAxes.push(am5xy.ValueAxis.new(SoundSensorDevice2Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 120,
        strictMinMax: true,
        renderer: SoundSensorDevice2axisRenderer
    }));

    // Add clock hand
    var SoundSensorDevice2axisDataItem = SoundSensorDevice2xAxis.makeDataItem({});
    var SoundSensorDevice2clockHand = am5radar.ClockHand.new(SoundSensorDevice2Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var SoundSensorDevice2bullet = SoundSensorDevice2axisDataItem.set("bullet", am5xy.AxisBullet.new(SoundSensorDevice2Gauge, {
        sprite: SoundSensorDevice2clockHand
    }));

    SoundSensorDevice2xAxis.createAxisRange(SoundSensorDevice2axisDataItem);

    var SoundSensorDevice2label = SoundSensorDevice2chart.radarContainer.children.push(am5.Label.new(SoundSensorDevice2Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    SoundSensorDevice2axisDataItem.set("value", 0);

    SoundSensorDevice2bullet.get("sprite").on("rotation", function () {
        var value = SoundSensorDevice2axisDataItem.get("value");
        var fill = am5.color(0x000000);
        SoundSensorDevice2xAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        SoundSensorDevice2label.set("text", Math.round(value).toString());

        SoundSensorDevice2clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        SoundSensorDevice2clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var SoundSensorDevice2current = 0;
    var SoundSensorDevice2target = 0;
    var SoundSensorDevice2animationStartTime = performance.now(); // Timestamp to track animation start time
    var SoundSensorDevice2animationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with smooth animation
    function SoundSensorDevice2Update(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            SoundSensorDevice2target = parsed;

            function animate() {
                if (Math.abs(SoundSensorDevice2current - SoundSensorDevice2target) < 0.5) {
                    SoundSensorDevice2current = SoundSensorDevice2target; // Prevents tiny oscillations
                } else {
                    // Smooth step interpolation
                    SoundSensorDevice2current += (SoundSensorDevice2target - SoundSensorDevice2current) * 0.1;
                    SoundSensorDevice2axisDataItem.set("value", Number(SoundSensorDevice2current.toFixed(0)));

                    requestAnimationFrame(animate);
                }
            }

            requestAnimationFrame(animate);
        } else {
            console.error('Invalid SoundSensorDevice2 level:', level);
        }
    }

    // Create axis ranges bands for soil moisture sensor (0-1000)
    var SoundSensorDevice2bandsData = [{
        title: "Very Quiet",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 20
    }, {
        title: "Quiet",
        color: "#b0d136", // Light Green
        lowScore: 20,
        highScore: 40
    }, {
        title: "Moderate",
        color: "#f3eb0c", // Yellow
        lowScore: 40,
        highScore: 60
    }, {
        title: "Loud",
        color: "#fdae19", // Orange
        lowScore: 60,
        highScore: 80
    }, {
        title: "Very Loud",
        color: "#f04922", // Red
        lowScore: 80,
        highScore: 100
    }, {
        title: "Extremely Loud",
        color: "#f02222", // Red
        lowScore: 100,
        highScore: 120
    }];

    am5.array.each(SoundSensorDevice2bandsData, function (data) {
        var range = SoundSensorDevice2xAxis.createAxisRange(SoundSensorDevice2xAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: SoundSensorDevice2Gauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    SoundSensorDevice2chart.appear(1000, 100);

    //SoundSensorDevice3-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    var SoundSensorDevice3Gauge = am5.Root.new("SoundSensorDevice3");

    // Set themes
    SoundSensorDevice3Gauge.setThemes([
        am5themes_Animated.new(SoundSensorDevice3Gauge)
    ]);

    // Create SoundSensorDevice3 chart
    var SoundSensorDevice3chart = SoundSensorDevice3Gauge.container.children.push(am5radar.RadarChart.new(SoundSensorDevice3Gauge, {
        panX: false,
        panY: false,
        startAngle: 160,
        endAngle: 380
    }));

    // Create axis and its renderer
    var SoundSensorDevice3axisRenderer = am5radar.AxisRendererCircular.new(SoundSensorDevice3Gauge, {
        innerRadius: -40
    });

    SoundSensorDevice3axisRenderer.grid.template.setAll({
        stroke: SoundSensorDevice3Gauge.interfaceColors.get("background"),
        visible: true,
        strokeOpacity: 1
    });

    var SoundSensorDevice3xAxis = SoundSensorDevice3chart.xAxes.push(am5xy.ValueAxis.new(SoundSensorDevice3Gauge, {
        maxDeviation: 0,
        min: 0,
        max: 120,
        strictMinMax: true,
        renderer: SoundSensorDevice3axisRenderer
    }));

    // Add clock hand
    var SoundSensorDevice3axisDataItem = SoundSensorDevice3xAxis.makeDataItem({});
    var SoundSensorDevice3clockHand = am5radar.ClockHand.new(SoundSensorDevice3Gauge, {
        pinRadius: am5.percent(25),
        radius: am5.percent(65),
        bottomWidth: 30
    });

    var SoundSensorDevice3bullet = SoundSensorDevice3axisDataItem.set("bullet", am5xy.AxisBullet.new(SoundSensorDevice3Gauge, {
        sprite: SoundSensorDevice3clockHand
    }));

    SoundSensorDevice3xAxis.createAxisRange(SoundSensorDevice3axisDataItem);

    var SoundSensorDevice3label = SoundSensorDevice3chart.radarContainer.children.push(am5.Label.new(SoundSensorDevice3Gauge, {
        fill: am5.color(0xffffff),
        centerX: am5.percent(50),
        textAlign: "center",
        centerY: am5.percent(50),
        fontSize: "1.3em"
    }));

    SoundSensorDevice3axisDataItem.set("value", 0);

    SoundSensorDevice3bullet.get("sprite").on("rotation", function () {
        var value = SoundSensorDevice3axisDataItem.get("value");
        var fill = am5.color(0x000000);
        SoundSensorDevice3xAxis.axisRanges.each(function (range) {
            if (value >= range.get("value") && value <= range.get("endValue")) {
                fill = range.get("axisFill").get("fill");
            }
        });

        SoundSensorDevice3label.set("text", Math.round(value).toString());

        SoundSensorDevice3clockHand.pin.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
        SoundSensorDevice3clockHand.hand.animate({
            key: "fill",
            to: fill,
            duration: 500,
            easing: am5.ease.out(am5.ease.cubic)
        });
    });

    // Variables for animation control
    var SoundSensorDevice3current = 0;
    var SoundSensorDevice3target = 0;
    var SoundSensorDevice3animationStartTime = performance.now(); // Timestamp to track animation start time
    var SoundSensorDevice3animationDuration = 1000; // Duration for smooth animation (in milliseconds)

    // Function to update the gauge with smooth animation
    function SoundSensorDevice3Update(level) {
        var parsed = parseFloat(level);
        if (!isNaN(parsed)) {
            SoundSensorDevice3target = parsed;

            function animate() {
                if (Math.abs(SoundSensorDevice3current - SoundSensorDevice3target) < 0.5) {
                    SoundSensorDevice3current = SoundSensorDevice3target; // Prevents tiny oscillations
                } else {
                    // Smooth step interpolation
                    SoundSensorDevice3current += (SoundSensorDevice3target - SoundSensorDevice3current) * 0.1;
                    SoundSensorDevice3axisDataItem.set("value", Number(SoundSensorDevice3current.toFixed(0)));

                    requestAnimationFrame(animate);
                }
            }

            requestAnimationFrame(animate);
        } else {
            console.error('Invalid SoundSensorDevice3 level:', level);
        }
    }

    // Create axis ranges bands for SoundSensorDevice3 sensor (0-100)
    var SoundSensorDevice3bandsData = [{
        title: "Very Quiet",
        color: "#6699ff", // Light Blue
        lowScore: 0,
        highScore: 20
    }, {
        title: "Quiet",
        color: "#b0d136", // Light Green
        lowScore: 20,
        highScore: 40
    }, {
        title: "Moderate",
        color: "#f3eb0c", // Yellow
        lowScore: 40,
        highScore: 60
    }, {
        title: "Loud",
        color: "#fdae19", // Orange
        lowScore: 60,
        highScore: 80
    }, {
        title: "Very Loud",
        color: "#f04922", // Red
        lowScore: 80,
        highScore: 100
    }, {
        title: "Extremely Loud",
        color: "#f02222", // Red
        lowScore: 100,
        highScore: 120
    }];

    am5.array.each(SoundSensorDevice3bandsData, function (data) {
        var range = SoundSensorDevice3xAxis.createAxisRange(SoundSensorDevice3xAxis.makeDataItem({}));
        range.setAll({
            value: data.lowScore,
            endValue: data.highScore
        });
        range.get("axisFill").setAll({
            visible: true,
            fill: am5.color(data.color),
            fillOpacity: 0.8
        });
        range.get("label").setAll({
            text: data.title,
            inside: true,
            radius: 15,
            fontSize: "9px",
            fill: SoundSensorDevice3Gauge.interfaceColors.get("background")
        });
    });

    // Make chart animate on load
    SoundSensorDevice3chart.appear(1000, 100);
});
