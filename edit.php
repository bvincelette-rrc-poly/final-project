// edit.php
<?php
include 'includes/header.php';
include 'includes/db.php';

$id = $_GET['id']; // Get the photo ID from the URL
$stmt = $pdo->prepare("SELECT * FROM photos WHERE id = ?");
$stmt->execute([$id]);
$photo = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];

    // Update the photo data in the database
    $stmt = $pdo->prepare("UPDATE photos SET description = ?, category_id = ? WHERE id = ?");
    $stmt->execute([$description, $category_id, $id]);

    header("Location: index.php");
}
?>

<form method="POST">
    <label for="description">Description:</label>
    <textarea name="description" required><?php echo $photo['description']; ?></textarea>
    
    <label for="category_id">Category:</label>
    <select name="category_id">
        <option value="1" <?php echo $photo['category_id'] == 1 ? 'selected' : ''; ?>>Nature</option>
        <option value="2" <?php echo $photo['category_id'] == 2 ? 'selected' : ''; ?>>Architecture</option>
    </select>

    <button type="submit">Save Changes</button>
</form>

<?php include 'includes/footer.php'; ?>
