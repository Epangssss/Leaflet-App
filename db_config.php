    <?php
    // db_config.php

    $host = 'localhost';
    $dbname = 'leaflet';
    $username = 'root'; // Username default untuk XAMPP
    $password = ''; // Kosongkan jika tidak ada password

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
