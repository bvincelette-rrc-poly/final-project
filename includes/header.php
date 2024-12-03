<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <!-- 引入 Bootstrap 5 的 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* 自定义 hover 效果 */
        .nav-link:hover {
            color: #f8f9fa !important; /* 悬停时改变颜色为白色 */
            background-color: #007bff;  /* 悬停时背景变为蓝色 */
            border-radius: 5px;         /* 增加圆角效果 */
        }
    </style>
</head>
<body>
    <header class="bg-dark text-white p-4">
        <div class="container">
            <h1 class="display-4">Welcome to the Photo Gallery</h1>
            <nav>
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="upload.php">Upload Photo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="register.php">Register</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <!-- Bootstrap JS (可选，用于某些动态效果) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
