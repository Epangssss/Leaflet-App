<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map with Popup and Delete</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bookmark.css">

</head>

<body>
    <h1 style="margin-left: 80px;">Leaflet Map untuk SIG</h1>
    <div id="map"></div>
    <div id="addMarkerIcon" class="icon-btn" onclick="showAddMarkerPopup()">➕</div>
    <!-- <div id="historyIcon" class="icon-btn" onclick="showHistoryPopup()">🗒️</div> -->

    <!-- Search Input -->
    <div id="searchContainer">
        <input type="text" id="searchLocation" placeholder="Search Location" />
        <button onclick="searchLocation()">Search</button>
    </div>

    <!-- Popup untuk menambah marker -->
    <div id="addMarkerPopup" class="popup-container">
        <div class="popup-header">
            <h3 id="popupTitle">Add Marker</h3>
            <button onclick="closeAddMarkerPopup()">✖</button>
        </div>
        <form id="markerForm" action="save_marker.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" id="addLocationName" placeholder="Location Name" required />
            <textarea name="deskripsi" id="addDeskripsi" placeholder="Deskripsi Lokasi"></textarea>
            <input type="text" name="latitude" id="addLatitude" placeholder="Latitude" readonly />
            <input type="text" name="longitude" id="addLongitude" placeholder="Longitude" readonly />
            <input type="file" name="image" accept="image/*" />

            <!-- Dropdown Kategori -->
            <label for="kategori_id">Kategori:</label>
            <select name="kategori_id" id="kategori_id">
                <!-- Opsi kategori akan dimuat secara dinamis oleh JavaScript -->
            </select><br>

            <input type="hidden" name="markerId" id="markerId" />
            <div class="popup-footer">
                <button type="submit">Save</button>
                <button type="button" onclick="closeAddMarkerPopup()">Cancel</button>
            </div>
        </form>
    </div>




    <!-- Popup for editing marker -->
    <div id="editMarkerPopup" class="popup-container">
        <div class="popup-header">
            <h3 id="popupTitle">Edit Marker</h3>
            <button onclick="closeEditMarkerPopup()">✖</button>
        </div>
        <form id="markerForm" action="edit_marker.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" id="editLocationName" placeholder="Location Name" required />
            <textarea name="deskripsi" id="editDeskripsi" placeholder="Deskripsi Lokasi"></textarea>
            <input type="text" name="latitude" id="editLatitude" placeholder="Latitude" />
            <input type="text" name="longitude" id="editLongitude" placeholder="Longitude" />
            <input type="file" name="image" id="editImage" accept="image/*" />

            <label for="editCategorySelect">Kategori:</label>
            <select name="kategori" id="editCategorySelect" required>
            <option value="">Pilih Kategori</option>

            </select>
            <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('get_categories.php')
            .then(response => response.json())
            .then(data => {
                const select = document.getElementById('editCategorySelect');
                
                data.forEach(kategori => {
                    const option = document.createElement('option');
                    option.value = kategori.id_kategori;
                    option.textContent = kategori.nama_kategori;
                    select.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching kategori:', error));
    });
</script>

            <input type="hidden" name="markerId" id="editMarkerId" />
            <div class="popup-footer">
                <button type="button" id="saveChangesButton" onclick="saveMarkerChanges()">Save Changes</button>
                <button type="button" onclick="closeEditMarkerPopup()">Cancel</button>
            </div>
        </form>
    </div>



    <!-- Popup for history markers
    <div id="historyPopup" class="popup-container historyPopup">
        <div class="popup-header">
            <h3>History</h3>
            <button onclick="closeHistoryPopup()">✖</button>
        </div>
        <ul id="historyList"></ul>
        <div class="history-navigation">
            <button onclick="previousSlide()" id="prevButton" disabled>Previous</button>
            <button onclick="nextSlide()" id="nextButton">Next</button>
        </div>
    </div> -->


    <div id="bookmarkIcon" class="icon-btn" onclick="toggleBookmarkSidebar()">📌</div>

    <!-- Sidebar untuk daftar kategori -->
    <div id="bookmarkSidebar" class="sidebar">
        <h2>bookmark</h2>

        <!-- Daftar kategori yang akan ditampilkan dalam kotak persegi panjang -->
        <div id="categoryList"></div>

        <button onclick="closeBookmarkSidebar()">Close</button>
    </div>


 
    <div id="markerTableContainer">
        <h2>Marker Data</h2>

        <!-- Input pencarian -->
        <input type="text" id="searchInput" placeholder="Search..." onkeyup="searchTable()" />

        <table id="markerTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    

    <!-- Include Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="script.js"></script>

</body>

</html>