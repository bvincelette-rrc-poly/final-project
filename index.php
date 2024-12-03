<?php
include 'includes/header.php';
include 'includes/db.php';

// 获取搜索关键字
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';

// 获取排序和分页参数
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'created_at';
$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'desc';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page > 0 ? $page : 1; // 确保页码是正整数

// 每页显示的图片数量
$photos_per_page = 3;

// 构建搜索条件
$search_condition = '';
if (!empty($search_keyword)) {
    $search_condition = "WHERE description LIKE :search_keyword";
}

// 计算总页数
$total_photos_sql = "SELECT COUNT(*) FROM photos $search_condition";
$stmt = $pdo->prepare($total_photos_sql);
if (!empty($search_keyword)) {
    $stmt->bindValue(':search_keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
}
$stmt->execute();
$total_photos = $stmt->fetchColumn();
$total_pages = ceil($total_photos / $photos_per_page);

// 调整SQL查询以包含分页和搜索
$offset = ($page - 1) * $photos_per_page;
$sql = "SELECT * FROM photos $search_condition ORDER BY $sort_by $sort_order LIMIT $photos_per_page OFFSET $offset";

$stmt = $pdo->prepare($sql);
if (!empty($search_keyword)) {
    $stmt->bindValue(':search_keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
}
$stmt->execute();
$photos = $stmt->fetchAll();

// 防止SQL注入攻击，确保排序字段有效
$allowed_sort_fields = ['title', 'created_at', 'description'];
if (!in_array($sort_by, $allowed_sort_fields)) {
    $sort_by = 'created_at'; // 默认排序字段
}

// 防止SQL注入攻击，确保排序顺序有效
$allowed_sort_orders = ['asc', 'desc'];
if (!in_array($sort_order, $allowed_sort_orders)) {
    $sort_order = 'desc'; // 默认排序顺序
}

// 检查用户是否为管理员
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1; // 1是管理员
}
?>

<!-- 引入Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ03RIBQ4M3uW94W5WmA1I8yyLleE1JTH0grZyGFLxB6t38yLvFJtTSKiMO6" crossorigin="anonymous">

<!-- 引入TinyMCE的CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<script>
  tinymce.init({
    selector: 'textarea',  // 选择需要编辑器的textarea
    plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
    menubar: false
  });
</script>

<!-- 页面中的CSS样式 -->
<style>
    .btn, .pagination a, .sort-buttons a {
        background-color: black;
        color: white;
        border: 1px solid black;
        padding: 10px 20px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn:hover, .pagination a:hover, .sort-buttons a:hover {
        background-color: red;
        color: white;
        border-color: red;
    }

    .pagination a.active {
        background-color: red;
        color: white;
        border-color: red;
    }

    .photo-img {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
        margin-bottom: 10px;
    }

    .gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .photo {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }

    .sort-buttons a {
        margin-right: 10px;
        text-decoration: none;
        color: white;
    }

    .sort-buttons a:hover {
        text-decoration: underline;
        color: red;
    }
</style>

<div class="container">
    <!-- 搜索表单 -->
    <form method="GET" class="row mb-4">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" placeholder="Search photos by description..." value="<?= htmlspecialchars($search_keyword); ?>" />
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <form method="GET" class="row">
        <div class="col-md-4">
            <label for="sort" class="form-label">Sort by:</label>
            <select name="sort_by" id="sort" class="form-select" onchange="this.form.submit()">
                <option value="title" <?php echo $sort_by == 'title' ? 'selected' : ''; ?>>Title</option>
                <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>Date</option>
                <option value="description" <?php echo $sort_by == 'description' ? 'selected' : ''; ?>>Description</option>
            </select>
        </div>
        <div class="col-md-4">
            <select name="sort_order" class="form-select" onchange="this.form.submit()">
                <option value="asc" <?php echo $sort_order == 'asc' ? 'selected' : ''; ?>>Ascending</option>
                <option value="desc" <?php echo $sort_order == 'desc' ? 'selected' : ''; ?>>Descending</option>
            </select>
        </div>
    </form>

    <div class="gallery row">
        <?php foreach ($photos as $photo): ?>
            <div class="col-md-4 photo">
                <a href="photo.php?id=<?= $photo['id']; ?>" class="d-block mb-3">
                    <img src="uploads/<?= htmlspecialchars($photo['filename']) ?>" alt="<?= htmlspecialchars($photo['description']) ?>" class="photo-img img-fluid">
                </a>
                <p><?= htmlspecialchars($photo['description']) ?></p>

                <?php if (isAdmin()): ?>
                    <!-- 仅管理员显示删除按钮 -->
                    <button type="button" class="btn btn-danger delete-button" data-photo-id="<?= $photo['id']; ?>">Delete</button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- 分页导航 -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="index.php?page=<?php echo $page - 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>&search=<?php echo urlencode($search_keyword); ?>" class="btn">
                Previous <small>(Page <?php echo $page - 1; ?>)</small>
            </a>
        <?php endif; ?>

        <span>Page:</span>
        <form method="GET" class="page-select-form d-inline-block">
            <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>">
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>">
            <input type="hidden" name="search" value="<?= htmlspecialchars($search_keyword); ?>">
            <select name="page" class="form-select d-inline-block" onchange="this.form.submit()">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $i == $page ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </form>

        <?php if ($page < $total_pages): ?>
            <a href="index.php?page=<?php echo $page + 1; ?>&sort_by=<?php echo $sort_by; ?>&sort_order=<?php echo $sort_order; ?>&search=<?php echo urlencode($search_keyword); ?>" class="btn">
                Next <small>(Page <?php echo $page + 1; ?>)</small>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- 引入Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0czv/s2J3tJ44Bkg9OaFkwzz4niJpGsW7TNhC03RRak4rST7" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $(".delete-button").click(function(e) {
        e.preventDefault();
        var photoId = $(this).data("photo-id");

        if (confirm("Are you sure you want to delete this photo?")) {
            $.ajax({
                url: "delete.php",
                type: "POST",
                data: { photo_id: photoId },
                success: function(response) {
                    alert(response);
                    location.reload(); // 删除成功后刷新页面
                },
                error: function() {
                    alert("Error deleting the photo.");
                }
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
