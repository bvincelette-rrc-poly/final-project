<?php
session_start();

// 检查用户是否登录并且是否是管理员
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php"); // 用户未登录或者不是管理员，重定向到登录页面
    exit();
}

include '../includes/db.php';

// 处理搜索
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 每页显示的记录数
$records_per_page = 3;

// 获取当前页码
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $records_per_page;

// 搜索查询语句
$sql = "SELECT * FROM users WHERE username LIKE :search LIMIT :offset, :records_per_page";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// 获取总记录数，用于分页
$sql_count = "SELECT COUNT(*) FROM users WHERE username LIKE :search";
$stmt_count = $pdo->prepare($sql_count);
$stmt_count->bindValue(':search', '%' . $search . '%');
$stmt_count->execute();
$total_records = $stmt_count->fetchColumn();

// 计算总页数
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/style_user.css">
</head>
<body>
    <div class="container">
        <!-- 左侧导航栏 -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="view_all_photos.php">View All Photos</a></li>
                <li><a href="site_settings.php">Site Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- 主体内容区 -->
        <div class="main-content">
            <h1>Manage Users</h1>
            <a class="back-button" href="admin_dashboard.php">Back to Dashboard</a>

            <!-- 搜索表单 -->
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>" />
                <button type="submit">Search</button>
            </form>

            <!-- 用户列表表格 -->
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- 分页 -->
            <div class="pagination">
                <!-- 上一页和第一页 -->
                <?php if ($current_page > 1): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&page=1">First page</a>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $current_page - 1; ?>">Previous</a>
                <?php endif; ?>

                <!-- 页码按钮 -->
                <?php 
                // 设定显示的分页范围，当前页周围的页面
                $start_page = max(1, $current_page - 2);
                $end_page = min($total_pages, $current_page + 2);
                for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" <?php if ($i == $current_page) echo 'class="active"'; ?>>
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <!-- 下一页和最后一页 -->
                <?php if ($current_page < $total_pages): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $current_page + 1; ?>">Next</a>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $total_pages; ?>">Last</a>
                <?php endif; ?>

                <!-- 页面跳转下拉菜单 -->
                <form method="GET" action="" style="display:inline-block; margin-left: 10px;">
                    <select name="page" onchange="this.form.submit()">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php if ($i == $current_page) echo 'selected'; ?>>
                                Page <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
