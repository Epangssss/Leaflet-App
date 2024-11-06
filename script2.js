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
    event.preventDefault();

    var formData = new FormData(this);
    formData.append('deskripsi', document.getElementById('addDeskripsi').value);

    fetch('save_marker.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                closeAddMarkerPopup();
                loadMarkers();
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
        document.getElementById('addDeskripsi').value = ''; // Clear description input
        document.getElementById('addLatitude').value = marker.getLatLng().lat;
        document.getElementById('addLongitude').value = marker.getLatLng().lng;
        document.getElementById('markerId').value = '';
        document.getElementById('addMarkerPopup').classList.add('open');
    } else {
        alert('Please click on the map to choose a location.');
    }
}

let bookmarks = JSON.parse(localStorage.getItem("bookmarks")) || [];

// Function to toggle the bookmark sidebar display
function toggleBookmarkSidebar() {
    const sidebar = document.getElementById("bookmarkSidebar");
    sidebar.style.display = sidebar.style.display === "block" ? "none" : "block";
    renderBookmarkList();
}

// Function to close the bookmark sidebar
function closeBookmarkSidebar() {
    document.getElementById("bookmarkSidebar").style.display = "none";
}

// Function to add a new bookmark without duplicates
function addBookmark(name, latitude, longitude) {
    // Check if the bookmark already exists
    const exists = bookmarks.some(bookmark => 
        bookmark.name === name && bookmark.latitude === latitude && bookmark.longitude === longitude
    );

    if (!exists) {
        bookmarks.push({ name, latitude, longitude });
        localStorage.setItem("bookmarks", JSON.stringify(bookmarks));
        renderBookmarkList();
    }
}

// Function to render the bookmark list in the sidebar
function renderBookmarkList() {
    const list = document.getElementById("bookmarkList");
    list.innerHTML = ""; 

    bookmarks.forEach((bookmark, index) => {
        const listItem = document.createElement("li");
        listItem.textContent = bookmark.name;
        listItem.onclick = () => goToBookmark(bookmark.latitude, bookmark.longitude);
        list.appendChild(listItem);
    });
}

// Function to navigate to a bookmark location on the map
function goToBookmark(latitude, longitude) {
    map.setView([latitude, longitude], 15); 
}

// Add initial bookmarks if they don't already exist
addBookmark("Pantai Papuma", -8.44210000, 113.77630000);
addBookmark("Pantai Teluk Love", -8.42430000, 113.78120000);





function loadMarkers() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            data.forEach(function (location) {
                var marker = L.marker([location.latitude, location.longitude]).addTo(map);
                marker.bindTooltip(location.name, { permanent: true, direction: 'top' }).openTooltip();

                var popupContent = '<b>' + location.name + '</b><br>' +
                    (location.deskripsi ? '<p>' + location.deskripsi + '</p>' : '') + // Show description if available
                    'Latitude: ' + location.latitude + '<br>' +
                    'Longitude: ' + location.longitude + '<br>' +
                    (location.image_url ? '<img src="' + location.image_url + '" alt="' + location.name + '" style="width: 100%; height: auto;" />' : '') +
                    '<br><button onclick="editMarker(' + location.id + ')">Edit</button>' +
                    '<button onclick="deleteMarker(' + location.id + ')">Delete</button>';

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
            
// Load markers from database
function loadMarkers() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            console.log("Markers data:", data);

            // Remove existing markers
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer);
                }
            });

            // Add new markers
            data.forEach(function (location) {
                var marker = L.marker([location.latitude, location.longitude]).addTo(map);

                // Create tooltip with location name
                marker.bindTooltip(location.name, { 
                    permanent: true, 
                    direction: 'top' 
                }).openTooltip();
                

                // Create popup content with all information
                var popupContent = `
                    <div style="text-align: left; min-width: 200px;">
                        <h3 style="margin: 0 0 10px 0;">${location.name}</h3>
                        <div style="margin-bottom: 10px;">
                            <strong>Description:</strong><br>
                            <p style="margin: 5px 0;">${location.deskripsi || 'No description available'}</p>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <strong>Coordinates:</strong><br>
                            Lat: ${location.latitude}<br>
                            Lng: ${location.longitude}
                        </div>
                        ${location.image_url ? `
                            <div style="margin-bottom: 10px;">
                                <img src="${location.image_url}" alt="Location image" 
                                     style="width: 100%; height: auto; border-radius: 4px;" />
                            </div>
                        ` : ''}
                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <button onclick="editMarker(${location.id})" 
                                    style="flex: 1; padding: 5px;">Edit</button>
                            <button onclick="deleteMarker(${location.id})" 
                                    style="flex: 1; padding: 5px;">Delete</button>
                        </div>
                    </div>
                `;
                marker.bindPopup(popupContent);
            });
        })
        .catch(error => console.error('Error loading markers:', error));
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



function saveMarker(id, name, description) {
    fetch('edit_marker.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            'markerId': id,
            'name': name,
            'description': description // Kirim deskripsi baru
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // Menampilkan pesan hasil
        if (data.status === 'success') {
            loadMarkers(); // Reload markers setelah diedit
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

    // Function to update the marker table
function updateMarkerTable() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#markerTable tbody');
            tableBody.innerHTML = '';

            data.forEach(marker => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${marker.name}</td>
                    <td>${marker.latitude}</td>
                    <td>${marker.longitude}</td>
                    <td>${marker.deskripsi || 'No description'}</td>
                    <td>
                        <button onclick="editMarkerFromTable(${marker.id})" class="edit-btn">Edit</button>
                        <button onclick="deleteMarkerFromTable(${marker.id})" class="delete-btn">Delete</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error loading marker table:', error));
}

// Function to edit marker from table
function editMarkerFromTable(id) {
    fetch(`get_markers.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('popupTitle').innerText = "Edit Marker";
                document.getElementById('addLocationName').value = data.name;
                document.getElementById('addDeskripsi').value = data.deskripsi || '';
                document.getElementById('addLatitude').value = data.latitude;
                document.getElementById('addLongitude').value = data.longitude;
                document.getElementById('markerId').value = data.id;
                document.getElementById('addMarkerPopup').classList.add('open');
            }
        })
        .catch(error => console.error('Error:', error));
}

// Function to delete marker from table
function deleteMarkerFromTable(id) {
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
            if (data.status === 'success') {
                updateMarkerTable();
                loadMarkers();
            }
            alert(data.message);
        })
        .catch(error => console.error('Error:', error));
    }
}

// Modify your existing loadMarkers function to also update the table
const originalLoadMarkers = loadMarkers;
loadMarkers = function() {
    originalLoadMarkers();
    updateMarkerTable();
};

// Update form submission to refresh table
document.getElementById('markerForm').addEventListener('submit', function (event) {
    event.preventDefault();

    var formData = new FormData(this);
    formData.append('deskripsi', document.getElementById('addDeskripsi').value);

    fetch('save_marker.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeAddMarkerPopup();
            loadMarkers();
            updateMarkerTable();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});


        // Load markers when the page loads
        loadMarkers();

