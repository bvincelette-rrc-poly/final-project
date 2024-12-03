<?php
// 数据库连接配置
$host = 'localhost'; // 数据库主机地址
$dbname = 'gallery'; // 数据库名，请确保这个数据库已经创建
$user = 'root';      // 数据库用户名
$pass = '';          // 数据库密码

try {
    // 创建数据库连接
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    
    // 设置 PDO 错误模式为异常，便于调试
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 设置默认的字符集为 utf8mb4，支持更多字符
    $pdo->exec("SET NAMES utf8mb4");

} catch (PDOException $e) {
    // 捕获错误并输出异常信息111
    die("Connection failed: " . $e->getMessage());
}
?>
