<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 获取用户ID
$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id <= 0) {
    die("Invalid user ID.");
}

// 获取用户信息
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password']; // 如果没有新密码则保持原密码

    // 更新用户信息
    $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ?, password = ? WHERE id = ?");
    $stmt->execute([$username, $role, $password, $user_id]);

    // 重定向回用户管理页面
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <a href="manage_users.php">Back to Manage Users</a>

    <!-- 用户编辑表单 -->
    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="role">Role:</label>
        <select name="role" required>
            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
        </select>

        <!-- 新密码输入框 -->
        <label for="password">New Password (Leave empty to keep current):</label>
        <input type="password" name="password">

        <button type="submit">Update User</button>
    </form>

</body>
</html>
