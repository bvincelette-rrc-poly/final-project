<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 获取照片 ID
if (isset($_GET['id'])) {
    $photo_id = $_GET['id'];

    // 删除照片文件（可选，避免浪费空间）
    $stmt = $pdo->prepare("SELECT filename FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch();

    if ($photo) {
        $filename = $photo['filename'];
        $file_path = "../uploads/" . $filename;

        // 删除文件
        if (file_exists($file_path)) {
            unlink($file_path); // 删除文件
        }

        // 删除数据库记录
        $stmt = $pdo->prepare("DELETE FROM photos WHERE id = ?");
        $stmt->execute([$photo_id]);

        // 删除成功后跳转回照片查看页面
        header("Location: view_all_photos.php");
        exit();
    } else {
        echo "Photo not found.";
    }
} else {
    echo "No photo ID provided.";
}
?>
