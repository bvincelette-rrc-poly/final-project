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

    // 获取照片的当前信息
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE id = ?");
    $stmt->execute([$photo_id]);
    $photo = $stmt->fetch();

    if (!$photo) {
        echo "Photo not found.";
        exit();
    }

    // 处理表单提交（更新照片描述或其他字段）
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $description = $_POST['description'];

        // 更新照片描述
        $stmt = $pdo->prepare("UPDATE photos SET description = ? WHERE id = ?");
        $stmt->execute([$description, $photo_id]);

        // 更新成功后跳转回照片查看页面
        header("Location: view_all_photos.php");
        exit();
    }
} else {
    echo "No photo ID provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photo</title>
</head>
<body>
    <h1>Edit Photo</h1>
    <form method="POST">
        <label for="description">Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($photo['description']); ?></textarea>
        <button type="submit">Update</button>
    </form>
    <a href="view_all_photos.php">Back to All Photos</a>
</body>
</html>
