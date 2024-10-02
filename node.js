// Fetch markers from the database and add them to the map
fetch('get_markers.php')
    .then(response => response.json())
    .then(data => {
        data.forEach(function (location) {
            // Add marker for each location from the database
            var marker = L.marker([location.latitude, location.longitude]).addTo(map);

            // Create a tooltip with the location name (always visible)
            marker.bindTooltip(location.name, { permanent: true, direction: 'top' }).openTooltip();
        });
    });
