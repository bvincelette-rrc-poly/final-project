<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取表单数据
    $username = $_POST['username'];
    $password = $_POST['password'];

    include 'includes/db.php';

    // 从数据库中查找用户名
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 检查用户名和密码
    if ($user && password_verify($password, $user['password'])) {
        // 登录成功，保存用户信息到 session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; // 保存角色信息

        // 登录成功后，根据角色跳转
        if ($_SESSION['role'] == 'admin') {
            header("Location: admin/admin_dashboard.php"); // 管理员页面
        } else {
            header("Location: index.php"); // 普通用户首页
        }
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!-- 登录表单 -->
<form method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" required>

    <label for="password">Password:</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>
