<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE news SET status = 'deleted' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: view_all_news.php?msg=تم حذف الخبر بنجاح");
    } else {
        header("Location: view_all_news.php?msg=حدث خطأ أثناء الحذف");
    }
} else {
    header("Location: view_all_news.php");
}
exit();
