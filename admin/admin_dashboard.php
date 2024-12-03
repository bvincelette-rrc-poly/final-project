<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 查询总照片数
$photos_stmt = $pdo->prepare("SELECT COUNT(*) FROM photos"); // 假设你有一个名为 'photos' 的表
$photos_stmt->execute();
$total_photos = $photos_stmt->fetchColumn();

// 查询总用户数
$users_stmt = $pdo->prepare("SELECT COUNT(*) FROM users");
$users_stmt->execute();
$total_users = $users_stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- 左侧导航栏 -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <!-- 新增的回到主页按钮 -->
            <a href="../index.php" class="home-button">Go to Homepage</a>
            <ul>
                <li><a href="view_all_photos.php">View All Photos</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- 右侧主内容区 -->
        <div class="main-content">
            <h1>Welcome to the Admin Dashboard</h1>
            <p>Manage the site efficiently and perform administrative tasks here.</p>

            <div class="stats">
                <div class="stat-box">
                    <h3>Total Photos</h3>
                    <p><?php echo $total_photos; ?></p> <!-- 显示数据库中的总照片数 -->
                </div>
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p><?php echo $total_users; ?></p> <!-- 显示数据库中的总用户数 -->
                </div>
                
            </div>

        </div>
    </div>
</body>
</html>
