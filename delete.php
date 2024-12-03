<?php
include 'includes/db.php';

// 检查是否提交了删除请求
if (isset($_POST['photo_id']) && isAdmin()) {
    $photo_id = (int) $_POST['photo_id'];

    // 获取要删除照片的信息
    $stmt = $pdo->prepare("SELECT filename FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch();

    if ($photo) {
        // 删除数据库中的照片记录
        $deleteStmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
        $deleteStmt->execute([$photo_id]);

        // 删除服务器上的文件
        $filePath = 'uploads/' . $photo['filename'];
        if (file_exists($filePath)) {
            unlink($filePath); // 删除文件
        }

        echo "Photo deleted successfully.";
    } else {
        echo "Photo not found.";
    }
} else {
    echo "You do not have permission to delete this photo.";
}
?>
