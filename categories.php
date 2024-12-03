<?php
include 'includes/header.php';
include 'includes/db.php';

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

foreach ($categories as $category) {
    echo "<h2>{$category['name']}</h2>";
  
}

include 'includes/footer.php';
?>
