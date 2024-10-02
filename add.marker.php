<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Marker</title>
</head>
<body>
    <h1>Add Marker</h1>
    <form action="save_marker.php" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>

        <label for="latitude">Latitude:</label>
        <input type="text" name="latitude" required><br>

        <label for="longitude">Longitude:</label>
        <input type="text" name="longitude" required><br>

        <label for="image">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required><br>

        <button type="submit">Add Marker</button>
    </form>
</body>
</html>
