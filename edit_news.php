<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: view_all_news.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();

if (!$news) {
    header("Location: view_all_news.php");
    exit();
}

$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $details = $_POST['details'];

    $image_name = $news['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $new_image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_name = $new_image_name;
        }
    }

    $update_stmt = $conn->prepare("UPDATE news SET title = ?, category_id = ?, details = ?, image = ? WHERE id = ?");
    $update_stmt->bind_param("sissi", $title, $category_id, $details, $image_name, $id);

    if ($update_stmt->execute()) {
        $msg = "تم تعديل الخبر بنجاح!";
        $news['title'] = $title;
        $news['category_id'] = $category_id;
        $news['details'] = $details;
        $news['image'] = $image_name;
    } else {
        $error = "حدث خطأ أثناء التعديل.";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>تعديل خبر</title>
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
        <h1>تعديل الخبر</h1>
        <?php if (isset($msg)) {
            echo "<div class='alert' style='background: lightgreen;'>$msg</div>";
        } ?>
        <?php if (isset($error)) {
            echo "<div class='alert'>$error</div>";
        } ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <label>عنوان الخبر:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($news['title']); ?>" required>

            <label>الفئة:</label>
            <select name="category_id" required>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $news['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>تفاصيل الخبر:</label>
            <textarea name="details" rows="5" required><?php echo htmlspecialchars($news['details']); ?></textarea>

            <label>صورة الخبر (ارفع صورة جديدة لتغيير القديمة):</label>
            <input type="file" name="image" accept="image/*">
            <?php if (!empty($news['image'])): ?>
                <p>الصورة الحالية: <img src="uploads/<?php echo htmlspecialchars($news['image']); ?>" width="100"></p>
            <?php else: ?>
                <p>الصورة الحالية: بدون صورة (افتراضية)</p>
            <?php endif; ?>

            <input type="submit" value="تحديث الخبر">
        </form>
    </div>
</body>

</html>