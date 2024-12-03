<?php
include 'includes/db.php';

$photo_id = $_GET['photo_id'];

$comments = $pdo->prepare("SELECT * FROM comments WHERE photo_id = ? ORDER BY created_at DESC");
$comments->execute([$photo_id]);

while ($comment = $comments->fetch()) {
    echo "<p>" . htmlspecialchars($comment['comment']) . "</p>";
}
?>
