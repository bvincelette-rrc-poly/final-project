<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 获取用户ID
if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    // 删除用户
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // 删除成功后，跳转回用户管理页面
    header("Location: manage_users.php");
    exit();
} else {
    echo "No user ID provided.";
    exit();
}
?>
