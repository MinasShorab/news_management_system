<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $details = $_POST['details'];
    $user_id = $_SESSION['user_id'];

    $image_name = "";

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        } else {
            $error = "حدث خطأ أثناء رفع الصورة.";
            $image_name = "";
        }
    }

    if (!isset($error)) {
        $stmt = $conn->prepare("INSERT INTO news (title, category_id, details, image, user_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sissi", $title, $category_id, $details, $image_name, $user_id);

        if ($stmt->execute()) {
            $msg = "تم إضافة الخبر بنجاح!";
        } else {
            $error = "حدث خطأ أثناء حفظ الخبر في قاعدة البيانات.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>إضافة خبر</title>
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
        <h1>إضافة خبر جديد</h1>
        <?php if (isset($msg)) {
            echo "<div class='alert' style='background: lightgreen;'>$msg</div>";
        } ?>
        <?php if (isset($error)) {
            echo "<div class='alert'>$error</div>";
        } ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label>عنوان الخبر:</label>
            <input type="text" name="title" required>

            <label>الفئة:</label>
            <select name="category_id" required>
                <option value="">اختر فئة</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <label>تفاصيل الخبر:</label>
            <textarea name="details" rows="5" required></textarea>

            <label>صورة الخبر (اختياري):</label>
            <input type="file" name="image" accept="image/*">

            <input type="submit" value="حفظ الخبر">
        </form>
    </div>
</body>

</html>