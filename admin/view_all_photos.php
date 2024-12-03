<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 获取所有照片
$stmt = $pdo->query("SELECT * FROM photos");
$photos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Photos</title>
</head>
<body>
    <h1>All Photos</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Actions</th>
        </tr>

        <?php foreach ($photos as $photo): ?>
        <tr>
            <td><?php echo htmlspecialchars($photo['id']); ?></td>
            <td><img src="../uploads/<?php echo htmlspecialchars($photo['filename']); ?>" alt="photo" width="100"></td>
            <td>
                <a href="delete.php?id=<?php echo $photo['id']; ?>">Delete</a>
                <a href="edit.php?id=<?php echo $photo['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
