<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT news.*, categories.name as category_name, users.name as user_name 
                        FROM news 
                        JOIN categories ON news.category_id = categories.id 
                        JOIN users ON news.user_id = users.id 
                        WHERE news.id = ? AND news.status = 'active'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$news = $result->fetch_assoc();

if (!$news) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($news['title']); ?></title>
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
        <a href="logout.php" style="background-color: #e74c3c;">تسجيل خروج</a>
    </div>

    <div class="container">
        <h1><?php echo htmlspecialchars($news['title']); ?></h1>

        <p style="color: gray; font-size: 14px; text-align: center;">
            الفئة: <?php echo htmlspecialchars($news['category_name']); ?> |
            بواسطة: <?php echo htmlspecialchars($news['user_name']); ?> |
            تاريخ: <?php echo $news['created_at']; ?>
        </p>

        <div style="text-align: center; margin: 20px 0;">
            <?php if (!empty($news['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" alt="صورة الخبر" style="max-width: 100%; border-radius: 8px;">
            <?php else: ?>
                <img src="uploads/default.webp" alt="صورة افتراضية" style="max-width: 100%; border-radius: 8px;">
            <?php endif; ?>
        </div>

        <div style="font-size: 18px; line-height: 1.8; background-color: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd;">
            <p><?php echo nl2br(htmlspecialchars($news['details'])); ?></p>
        </div>

        <br>
        <div style="text-align: center;">
            <a href="dashboard.php" class="btn">العودة للرئيسية</a>
        </div>
    </div>
</body>

</html>