<?php
session_start();

// 检查用户是否登录，并且是否为管理员
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // 如果用户不是管理员，重定向到首页
    header("Location: index.php");
    exit();
}

// 如果是管理员，显示管理员页面内容
?>

<h1>Welcome, Admin!</h1>
<!-- 这里可以是管理员的操作界面 -->
