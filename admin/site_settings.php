<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $site_title = $_POST['site_title'];
    $site_description = $_POST['site_description'];

    $stmt = $pdo->prepare("UPDATE settings SET site_title = ?, site_description = ? WHERE id = 1");
    $stmt->execute([$site_title, $site_description]);

    echo "Settings updated successfully!";
}

// 获取现有的站点设置
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Settings</title>
</head>
<body>
    <h1>Site Settings</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>

    <form method="POST">
        <label for="site_title">Site Title:</label>
        <input type="text" name="site_title" value="<?php echo htmlspecialchars($settings['site_title']); ?>" required><br>

        <label for="site_description">Site Description:</label>
        <input type="text" name="site_description" value="<?php echo htmlspecialchars($settings['site_description']); ?>" required><br>

        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
