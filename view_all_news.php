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
        ORDER BY news.id DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>عرض جميع الأخبار</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete(id) {
            if (confirm('هل أنت متأكد من حذف هذا الخبر؟')) {
                window.location.href = 'delete_news.php?id=' + id;
            }
        }
    </script>
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
        <h1>جميع الأخبار الحالية</h1>

        <?php if (isset($_GET['msg'])): ?>
            <div class='alert' style='background: lightgreen;'><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>الصورة</th>
                    <th>العنوان</th>
                    <th>الفئة</th>
                    <th>الكاتب</th>
                    <th>التاريخ</th>
                    <th>إجراءات</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
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
                        <td>
                            <a href="edit_news.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">تعديل ✎</a>
                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger">حذف 🗑</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>لا توجد أخبار لعرضها.</p>
        <?php endif; ?>
    </div>
</body>

</html>