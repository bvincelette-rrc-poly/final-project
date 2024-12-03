<?php
include 'includes/header.php';

$success_message = ''; // 用于存放成功提示

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 获取表单数据
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $role = $_POST['role']; // 获取角色信息（user 或 admin）

    include 'includes/db.php';
    
    // 检查用户名是否已存在
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user_exists = $stmt->fetchColumn();

    if ($user_exists) {
        echo "The username already exists, please select a different username.";
    } else {
        // 插入新用户，包含角色信息
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role]);

        // 注册成功后，设置成功提示信息
        $success_message = "Registration Successful！！！<a href='login.php'>login！！！</a>。";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

    <h2>Register</h2>

    <!-- 显示注册成功的提示信息 -->
    <?php if ($success_message): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <!-- 选择注册角色 -->
        <label for="role">Role:</label>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Administrator</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <!-- 返回首页按钮 -->
    <form action="index.php" method="get">
        <button type="submit">Back to Home</button>
    </form>

</body>
</html>

<?php
include 'includes/footer.php';
?>
