<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    if ($check_email->get_result()->num_rows > 0) {
        $error = "البريد الإلكتروني مستخدم بالفعل!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "حدث خطأ أثناء التسجيل.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>إنشاء حساب جديد</h1>
        <?php if (isset($error)) { echo "<div class='alert'>$error</div>"; } ?>
        <form method="POST" action="">
            <label>الاسم:</label>
            <input type="text" name="name" required>
            
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" required>
            
            <label>كلمة المرور:</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="تسجيل">
        </form>
        <p>لديك حساب بالفعل؟ <a href="index.php">سجل دخولك هنا</a></p>
    </div>
</body>
</html>
