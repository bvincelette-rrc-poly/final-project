<?php
// 启动会话，确保你能访问到 $_SESSION
session_start();

// 检查用户是否已经登录以及是否具有管理员权限
function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        // 如果不是管理员，重定向到首页或登录页面
        header("Location: index.php");
        exit();
    }
}

// 检查普通用户
function checkUser() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
        // 如果不是普通用户，重定向到首页或登录页面
        header("Location: index.php");
        exit();
    }
}

// 如果你有更多角色需求，可以继续扩展
// 比如检查某个自定义角色：
// function checkEditor() {
//    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'editor') {
//        header("Location: index.php");
//        exit();
//    }
// }
?>
