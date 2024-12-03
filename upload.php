<?php
include 'includes/header.php';
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $file = $_FILES['photo'];

    $allowed = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed)) {
        echo "Invalid file type.";
        exit;
    }

    $filename = uniqid() . '-' . basename($file['name']);
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        $stmt = $pdo->prepare("INSERT INTO photos (filename, description, category_id) VALUES (?, ?, ?)");
        $stmt->execute([$filename, $description, $category_id]);

        header("Location: index.php");
    } else {
        echo "File upload failed.";
    }
}

?>

<form method="POST" enctype="multipart/form-data">
    <label for="description">Description:</label>
    <textarea name="description" required></textarea>
    
    <label for="category_id">Category:</label>
    <select name="category_id">
        <option value="1">Nature</option>
        <option value="2">Architecture</option>
    </select>
    
    <label for="photo">Upload Photo:</label>
    <input type="file" name="photo" required>

    <button type="submit">Upload</button>
</form>

<?php include 'includes/footer.php'; ?>
