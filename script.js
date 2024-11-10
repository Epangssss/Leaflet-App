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
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            closeAddMarkerPopup();
            loadMarkers();  // Memuat ulang marker setelah penambahan berhasil
            formData.delete('image'); // Menghapus file gambar dari FormData
        } else {
            alert('Error: ' + data.message);
            }
        })
});


 function showAddMarkerPopup() {
    if (marker) {
        // Set judul dan kosongkan field form
        document.getElementById('popupTitle').innerText = "Add Marker";
        document.getElementById('addLocationName').value = '';
        document.getElementById('addDeskripsi').value = '';
        document.getElementById('addLatitude').value = marker.getLatLng().lat;
        document.getElementById('addLongitude').value = marker.getLatLng().lng;
        document.getElementById('markerId').value = '';
        
        // Panggil loadCategories untuk memastikan kategori terbaru dimuat ke dropdown
        loadCategories();

        // Tampilkan popup
        document.getElementById('addMarkerPopup').classList.add('open');
    } else {
        alert('Please click on the map to choose a location.');
    }
}



//bookmark
let categories = [];
let markers = [];

// Fungsi untuk menampilkan atau menyembunyikan sidebar bookmark
function toggleBookmarkSidebar() {
    const sidebar = document.getElementById("bookmarkSidebar");
    sidebar.style.display = sidebar.style.display === "block" ? "none" : "block";
}

// Fungsi untuk menutup sidebar
function closeBookmarkSidebar() {
    document.getElementById("bookmarkSidebar").style.display = "none";
}

// Fungsi untuk mengambil kategori dan marker dari server
function fetchCategoriesAndMarkers() {
    fetch("get_categories_and_markers.php")
        .then(response => response.json())
        .then(data => {
            categories = data.categories; // Data kategori
            markers = data.markers; // Data marker
            renderCategoryList(); // Render kategori saat data diterima
            displayMarkers(markers); // Tampilkan semua marker pada awalnya
        })
        .catch(error => console.error("Error fetching categories and markers:", error));
}

// Panggil fungsi setelah mendefinisikan
fetchCategoriesAndMarkers();

// Fungsi untuk menampilkan daftar kategori dalam bentuk kotak
function renderCategoryList() {
    const categoryList = document.getElementById("categoryList");
    categoryList.innerHTML = ""; // Mengosongkan daftar kategori

    categories.forEach(category => {
        const categoryBox = document.createElement("div");
        categoryBox.classList.add("category-box"); // Tambahkan kelas CSS untuk kategori
        categoryBox.textContent = category.nama_kategori; // Menampilkan nama kategori
        categoryBox.onclick = () => showMarkerListByCategory(category.id_kategori); // Ketika kategori dipilih, tampilkan daftar marker
        categoryList.appendChild(categoryBox);
    });
}



// Fungsi untuk menampilkan daftar marker berdasarkan kategori
function showMarkerListByCategory(categoryId) {
    const categoryList = document.getElementById("categoryList");
    categoryList.innerHTML = ""; // Kosongkan daftar kategori atau marker sebelumnya

    // Filter marker berdasarkan kategori yang dipilih
    const filteredMarkers = markers.filter(marker => marker.kategori_id === categoryId);

    // Tampilkan daftar marker berdasarkan kategori yang dipilih
    filteredMarkers.forEach(marker => {
        const markerItem = document.createElement("div");
        markerItem.classList.add("marker-item"); // Kelas CSS untuk styling marker list
        markerItem.textContent = marker.name; // Tampilkan nama marker
        markerItem.onclick = () => {
            map.setView([marker.latitude, marker.longitude], 15); // Zoom ke marker saat diklik
        };
        categoryList.appendChild(markerItem);
    });

    // Buat tombol kembali ke daftar kategori dan tempatkan di bawah daftar marker
    const backButton = document.createElement("button");
    backButton.textContent = "Kembali";
    backButton.onclick = renderCategoryList;
    backButton.classList.add("back-button"); // Tambahkan kelas untuk styling

    categoryList.appendChild(backButton);

}

// Fungsi untuk menampilkan marker pada peta
function displayMarkers(markersToDisplay) {
    // Hapus semua marker dari map sebelum menambahkan yang baru
    map.eachLayer(layer => {
        if (layer instanceof L.Marker) {
            map.removeLayer(layer);
        }
    });

    markersToDisplay.forEach(marker => {
        L.marker([marker.latitude, marker.longitude])
            .addTo(map)
            .bindPopup(marker.name);
    });
}












    let currentSlide = 0;
    const itemsPerPage = 5;

    // function showHistoryPopup() {
    //      closeAddMarkerPopup();
    //     fetch('get_markers.php')
    //         .then(response => response.json())
    //         .then(data => {
    //             console.log("Markers data:", data); // Log data untuk debugging
    //             populateHistoryList(data, currentSlide);
    //             document.getElementById('historyPopup').classList.add('open');

    //                  document.addEventListener('click', handleOutsideClick);
    //         });
    // }

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


// Fungsi untuk menyimpan perubahan marker
function saveMarkerChanges() {
    const id = document.getElementById('markerId').value;  // Ambil ID marker dari hidden input
    const name = document.getElementById('editLocationName').value;
    const latitude = document.getElementById('editLatitude').value;
    const longitude = document.getElementById('editLongitude').value;
    const deskripsi = document.getElementById('editDeskripsi').value;
    const kategori =document.getElementById('editCategorySelect').value;

    // Validasi input sebelum dikirim
    if (!name || !latitude || !longitude || !deskripsi || !kategori) {
        alert('All fields are required!');
        return;
    }

    const formData = new FormData();
    formData.append('markerId', id);
    formData.append('name', name);
    formData.append('latitude', latitude);
    formData.append('longitude', longitude);
    formData.append('description', deskripsi);
    formData.append('kategori', kategori);

    // Jika ada gambar baru
    const imageInput = document.getElementById('editImage');
    if (imageInput.files.length > 0) {
        formData.append('image', imageInput.files[0]);
    }

    // Mengirimkan data menggunakan Fetch API
    fetch('edit_marker.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            updateMarkerTable();  // Update tabel setelah data berhasil disimpan
            closeEditMarkerPopup();  // Menutup popup setelah edit
        } else {
            alert(data.message);
        }
    })  
    .catch(error => console.error('Error:', error));
}


let markerData = []; // Untuk menyimpan data marker

// Fungsi untuk fetch marker data dan update tabel
function fetchMarkerData() {
    fetch('get_markers.php') // Ganti dengan URL yang benar untuk mengambil data marker
        .then(response => response.json())
        .then(data => {
            markerData = data; // Menyimpan data marker yang diterima
            updateMarkerTable(); // Memperbarui tabel setelah data diterima
        })
        .catch(error => console.error('Error fetching marker data:', error));
}

// Function to update the marker table
function updateMarkerTable() {
    fetch('get_markers.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#markerTable tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Reassign IDs sequentially for display purposes only
            data.forEach((marker, index) => {
                const row = document.createElement('tr');
                row.setAttribute('data-original-id', marker.id);

                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td class="clickable" onclick="focusMarkerOnMap(${marker.latitude}, ${marker.longitude})">${marker.name}</td>
                    <td class="clickable" onclick="focusMarkerOnMap(${marker.latitude}, ${marker.longitude})">${marker.latitude}</td>
                    <td class="clickable" onclick="focusMarkerOnMap(${marker.latitude}, ${marker.longitude})">${marker.longitude}</td>
                    <td>${marker.deskripsi || 'No description'}</td>
                    <td>${marker.kategori || 'No category'}</td> <!-- Kategori kolom baru -->
                    <td>
                        <button onclick="editMarkerFromTable(${marker.id})" class="edit-btn">Edit</button>
                        <button onclick="deleteMarkerFromTable(${marker.id})" class="delete-btn">Delete</button>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
            console.log('Table updated');  // Log untuk memastikan tabel terupdate
             loadCategories();
        })
        .catch(error => console.error('Error loading marker table:', error));
}




// Fungsi untuk mengarahkan ke marker berdasarkan latitude dan longitude
function focusMarkerOnMap(latitude, longitude) {
    // Misalnya jika menggunakan Leaflet, kita bisa mengarahkan peta ke lokasi tersebut
    const map = window.map; // Pastikan variabel map sudah ada dan sesuai dengan instansi peta Anda
    map.setView([latitude, longitude], 14);  // Menetapkan titik pusat peta ke koordinat yang dipilih
}
function searchTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase(); // Ambil nilai input pencarian
    const tableRows = document.getElementById('markerTable').getElementsByTagName('tr');

    // Loop melalui setiap baris tabel (selain header) dan sembunyikan baris yang tidak sesuai pencarian
    for (let i = 1; i < tableRows.length; i++) {
        const row = tableRows[i];
        const cells = row.getElementsByTagName('td');
        let rowContainsSearchTerm = false;

        // Cek apakah salah satu kolom mengandung kata kunci pencarian
        for (let j = 0; j < cells.length; j++) {
            if (cells[j].textContent.toLowerCase().includes(searchInput)) {
                rowContainsSearchTerm = true;
                break;
            }
        }

        // Tampilkan atau sembunyikan baris berdasarkan pencarian
        if (rowContainsSearchTerm) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

document.getElementById('searchInput').addEventListener('keyup', searchTable);


function editMarkerFromTable(originalId) {
    fetch(`get_markers.php?id=${originalId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Mengisi form dengan data marker yang ada
                document.getElementById('popupTitle').innerText = "Edit Marker";
                document.getElementById('editLocationName').value = data.name || '';
                document.getElementById('editDeskripsi').value = data.deskripsi || '';
                document.getElementById('editLatitude').value = data.latitude || '';
                document.getElementById('editLongitude').value = data.longitude || '';
                document.getElementById('markerId').value = originalId;  // Set the hidden marker ID
                document.getElementById('editMarkerPopup').classList.add('open');
                 loadCategories();
                
            }
        })
        .catch(error => console.error('Error:', error));
}

function closeEditMarkerPopup() {
    document.getElementById('editMarkerPopup').classList.remove('open');
}


// Function to delete marker from table
function deleteMarkerFromTable(originalId) {
    if (confirm('Are you sure you want to delete this marker?')) {
        fetch('delete_marker.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ 'id': originalId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                loadMarkers(); // Reload markers
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}
}


function loadCategories() {
    var kategoriSelect = document.getElementById('kategori_id');

    // Menggunakan Fetch API untuk mengambil data kategori
    fetch('load_categories.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memuat kategori: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            // Menghapus semua opsi di dropdown terlebih dahulu
            kategoriSelect.innerHTML = '<option value="">Pilih Kategori</option>';

            // Menambahkan kategori ke dalam dropdown
            data.forEach(function(category) {
                var option = document.createElement('option');
                option.value = category.id_kategori;
                option.textContent = category.nama_kategori;
                kategoriSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            alert('Gagal memuat kategori. Silakan coba lagi.');
        });
}

// Memanggil fungsi loadCategories saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
});


fetchMarkerData();

// Modify your existing loadMarkers function to also update the table
const originalLoadMarkers = loadMarkers;
loadMarkers = function() {
    originalLoadMarkers();
    updateMarkerTable();
};


        // Load markers when the page loads
        loadMarkers();





