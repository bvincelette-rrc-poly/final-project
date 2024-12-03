<?php
session_start();
session_unset();  // 清空 session 变量
session_destroy();  // 销毁 session

header("Location: ../login.php"); // 登出后重定向到登录页面
exit();
?>
