$(function() {


    $.getJSON("/data/map.php", function(data) {


        })
        .done(function(data) {
            var addressPoints = [];
            $.each(data.locations, function(b, v) {
                var res = [];
                res = v.latlon.split(",");
                addressPoints.push(res, "100");

            });
            console.log(data);
            var map = L.map('map').setView([39.828175, -98.5795], 4);

            var tiles = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map);

            var options = {
                minOpacity: .35
            };

            var addressPoints = addressPoints.map(function(p) {
                return [p[0], p[1]];
            });

            var heat = L.heatLayer(addressPoints, options).addTo(map);

        });

    setInterval(function() {
        // Get a new day's data every 10 intervals
        if (intervalCounter == 10) {
            intervalCounter = 0;
            getAnotherDay();
        } else {
            intervalCounter++;
        }

        // Create new array for the next frame's points, remove old points, add new points, then update and push to map
        var newData = [];

    }, 100);

});
