<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);

    if ($stmt->execute()) {
        $msg = "تم إضافة الفئة بنجاح!";
    } else {
        $error = "حدث خطأ أثناء الإضافة.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>إضافة فئة</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="menu">
        <a href="dashboard.php">الرئيسية</a>
        <a href="add_category.php">إضافة فئة</a>
        <a href="view_categories.php">عرض الفئات</a>
        <a href="add_news.php">إضافة خبر</a>
        <a href="view_all_news.php">عرض جميع الأخبار</a>
        <a href="view_deleted_news.php">عرض الأخبار المحذوفة</a>
        <a href="logout.php" style="background-color: red;">تسجيل خروج</a>
    </div>

    <div class="container">
        <h1>إضافة فئة جديدة</h1>
        <?php if (isset($msg)) {
            echo "<div class='alert' style='background: lightgreen;'>$msg</div>";
        } ?>
        <?php if (isset($error)) {
            echo "<div class='alert'>$error</div>";
        } ?>

        <form method="POST" action="">
            <label>اسم الفئة (مثال: أخبار رياضية):</label>
            <input type="text" name="name" required>

            <input type="submit" value="حفظ الفئة">
        </form>
    </div>
</body>

</html>