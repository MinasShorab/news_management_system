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
        WHERE news.status = 'deleted' 
        ORDER BY news.id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>عرض الأخبار المحذوفة</title>
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
        <h1>الأخبار المحذوفة سلة المحذوفات</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>الفئة</th>
                    <th>الكاتب</th>
                    <th>تاريخ الحذف/الإنشاء</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr style="background-color: #ffe6e6;">
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="50" height="50" alt="صورة">
                            <?php else: ?>
                                <img src="uploads/default.webp" width="50" height="50" alt="صورة افتراضية">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>لا توجد أخبار محذوفة.</p>
        <?php endif; ?>
    </div>
</body>

</html>