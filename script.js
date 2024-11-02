    var map = L.map('map').setView([-8.1665, 113.6926], 13);

        // Load tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        var marker;

        // Event listener for map click to add marker
        map.on('click', function (e) {
            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            document.getElementById('addLatitude').value = e.latlng.lat;
            document.getElementById('addLongitude').value = e.latlng.lng;
        });

        document.getElementById('markerForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            var formData = new FormData(this);

            fetch('save_marker.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message); // Show success message
                        closeAddMarkerPopup(); // Close the popup after confirmation
                        loadMarkers(); // Reload markers to reflect the new addition
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });

            function showAddMarkerPopup() {
                    
                if (marker) {
                    document.getElementById('popupTitle').innerText = "Add Marker";
                    document.getElementById('addLocationName').value = '';
                    document.getElementById('addLatitude').value = marker.getLatLng().lat;
                    document.getElementById('addLongitude').value = marker.getLatLng().lng;
                    document.getElementById('markerId').value = ''; // Clear hidden marker ID
                    document.getElementById('addMarkerPopup').classList.add('open');

                     
                } else {
                    alert('Please click on the map to choose a location.');
                }
                
            }


        function loadMarkers() {
                fetch('get_markers.php')
                    .then(response => response.json())
                    .then(data => {
                        // Remove existing markers from the map
                        map.eachLayer(function (layer) {
                            if (layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });

                        // Iterate through each location in the fetched data
                        data.forEach(function (location) {
                            var marker = L.marker([location.latitude, location.longitude]).addTo(map);
                            marker.bindTooltip(location.name, { permanent: true, direction: 'top' }).openTooltip();

                            // Add image to the popup content
                            var popupContent = '<b>' + location.name + '</b><br>' +
                                'Latitude: ' + location.latitude + '<br>' +
                                'Longitude: ' + location.longitude + '<br>' +
                                '<img src="' + location.image_url + '" alt="' + location.name + '" style="width: 100%; height: auto;" />' +
                                '<br><button onclick="editMarker(' + location.id + ')">Edit</button>' +
                                '<button onclick="deleteMarker(' + location.id + ')">Delete</button>';

                            // Bind the popup to the marker
                            marker.bindPopup(popupContent);
                        });
                    });
            }


    let currentSlide = 0;
    const itemsPerPage = 5;

    function showHistoryPopup() {
         closeAddMarkerPopup();
        fetch('get_markers.php')
            .then(response => response.json())
            .then(data => {
                console.log("Markers data:", data); // Log data untuk debugging
                populateHistoryList(data, currentSlide);
                document.getElementById('historyPopup').classList.add('open');

                     document.addEventListener('click', handleOutsideClick);
            });
    }

function closeAddMarkerPopup() {
    document.getElementById('addMarkerPopup').classList.remove('open');
    document.removeEventListener('click', handleOutsideClick); // Hapus listener
}

// Menutup popup History
function closeHistoryPopup() {
    document.getElementById('historyPopup').classList.remove('open');
    document.removeEventListener('click', handleOutsideClick); // Hapus listener
}

// Fungsi untuk menutup popup jika klik terjadi di luar popup
function handleOutsideClick(event) {
    const addMarkerPopup = document.getElementById('addMarkerPopup');
    const historyPopup = document.getElementById('historyPopup');

    if (addMarkerPopup.classList.contains('open') && !addMarkerPopup.contains(event.target)) {
        closeAddMarkerPopup();
    }

    if (historyPopup.classList.contains('open') && !historyPopup.contains(event.target)) {
        closeHistoryPopup();
    }
}
            
function loadMarkers() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            console.log("Markers data for map:", data); // Log data untuk debugging
            // Remove existing markers from the map
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Iterate through each location in the fetched data
            data.forEach(function (location) {
                var marker = L.marker([location.latitude, location.longitude]).addTo(map);
                marker.bindTooltip(location.name, { permanent: true, direction: 'top' }).openTooltip();

                // Add image to the popup content
                var popupContent = '<b>' + location.name + '</b><br>' +
                    'Latitude: ' + location.latitude + '<br>' +
                    'Longitude: ' + location.longitude + '<br>' +
                    '<img src="' + location.image_url + '" alt="' + location.name + '" style="width: 100%; height: auto;" />' +
                    '<br><button onclick="editMarker(' + location.id + ')">Edit</button>' +
                    '<button onclick="deleteMarker(' + location.id + ')">Delete</button>';

                // Bind the popup to the marker
                marker.bindPopup(popupContent);
            });
        });
}


function populateHistoryList(data, slide) {
    const historyList = document.getElementById('historyList');
    historyList.innerHTML = ''; // Kosongkan tampilan sebelumnya

    // Tentukan batas awal dan akhir data untuk ditampilkan pada slide aktif
    const start = slide * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedData = data.slice(start, end);

    // Tampilkan 5 item di daftar berdasarkan data yang difilter
    paginatedData.forEach(location => {
        const listItem = document.createElement('li');
        listItem.textContent = location.name;
        listItem.onclick = function () {
            map.setView([location.latitude, location.longitude], 15); // Zoom ke marker
        };
        historyList.appendChild(listItem);
    });

    // Atur tombol navigasi (Previous dan Next)
    document.getElementById('prevButton').disabled = slide === 0;
    document.getElementById('nextButton').disabled = end >= data.length;
}

function nextSlide() {
    currentSlide++;
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            populateHistoryList(data, currentSlide);
        });
}

function previousSlide() {
    currentSlide--;
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            populateHistoryList(data, currentSlide);
        });
}


        function searchLocation() {
            var location = document.getElementById('searchLocation').value;

            if (!location) {
                alert('Please enter a location to search');
                return;
            }

            fetch(`https://nominatim.openstreetmap.org/search?q=${location}&format=json&addressdetails=1&limit=5`)
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        alert('No results found.');
                    } else {
                        // Display results in console or handle them as needed
                        data.forEach(result => {
                            console.log(result);
                        });
                        map.setView([data[0].lat, data[0].lon], 15);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

    function editMarker(id) {
        fetch(`get_markers.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const newName = prompt("Edit Marker Name:", data.name); // Meminta input dari pengguna
                    if (newName !== null) { // Memeriksa apakah pengguna menekan Cancel
                        saveMarker(id, newName); // Memanggil fungsi untuk menyimpan marker
                    }
                } else {
                    alert('Error fetching marker data.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function saveMarker(id, name) {
        fetch('edit_marker.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'markerId': id,
                'name': name
            })
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message); // Menampilkan pesan hasil
                if (data.status === 'success') {
                    loadMarkers(); // Reload markers after editing
                }
            })
            .catch(error => console.error('Error:', error));
    }





 function deleteMarker(id) {
        if (confirm('Are you sure you want to delete this marker?')) {
            fetch('delete_marker.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'id': id
                })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message); // Menampilkan pesan
                    if (data.status === 'success') {
                        loadMarkers(); // Reload markers after deletion
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }





        // Load markers when the page loads
        loadMarkers();