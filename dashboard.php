<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

$query = "SELECT news.*, categories.name as category_name, users.name as user_name 
        FROM news 
        JOIN categories ON news.category_id = categories.id 
        JOIN users ON news.user_id = users.id 
        WHERE news.status = 'active' 
        ORDER BY news.created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
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
        <h1>مرحبا بك يا <?php echo htmlspecialchars($_SESSION['user_name']); ?> في نظام إدارة الأخبار</h1>

        <h2>أحدث الأخبار</h2>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; background: #fff;">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p style="color: gray; font-size: 14px;">
                        الفئة: <?php echo htmlspecialchars($row['category_name']); ?> |
                        بواسطة: <?php echo htmlspecialchars($row['user_name']); ?> |
                        تاريخ: <?php echo $row['created_at']; ?>
                    </p>
                    <?php if (!empty($row['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="صورة الخبر" style="max-width: 200px; border-radius: 8px; display: block; margin-bottom: 10px;">
                    <?php else: ?>
                        <img src="uploads/default.webp" alt="صورة افتراضية" style="max-width: 200px; border-radius: 8px; display: block; margin-bottom: 10px;">
                    <?php endif; ?>
                    <p>
                        <?php
                        $short_text = mb_substr($row['details'], 0, 100, "UTF-8");
                        echo htmlspecialchars($short_text) . " ...";
                        ?>
                    </p>
                    <a href="view_single_news.php?id=<?php echo $row['id']; ?>" class="btn" style="background-color: #3498db; margin-top: 10px;">اقرأ المزيد</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>لا توجد أخبار لعرضها حاليا.</p>
        <?php endif; ?>
    </div>
</body>

</html>