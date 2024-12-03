<?php
// 引入数据库连接和头部
include 'includes/header.php';
include 'includes/db.php';

// 从数据库中查询所有照片
$stmt = $pdo->query("SELECT * FROM photos");
$photos = $stmt->fetchAll();

// 遍历所有照片并展示
foreach ($photos as $photo) {
    echo '<div class="photo">';
    echo '<img src="uploads/' . $photo['filename'] . '" alt="' . $photo['title'] . '" />';
    echo '<p>' . $photo['title'] . '</p>';
    echo '</div>';
}

// 引入页面底部
include 'includes/footer.php';
?>
