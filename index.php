<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "كلمة المرور خاطئة!";
        }
    } else {
        $error = "البريد الإلكتروني غير موجود!";
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>تسجيل الدخول</h1>
        <?php if (isset($error)) { echo "<div class='alert'>$error</div>"; } ?>
        <form method="POST" action="">
            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" required>
            
            <label>كلمة المرور:</label>
            <input type="password" name="password" required>
            
            <input type="submit" value="دخول">
        </form>
        <p>ليس لديك حساب؟ <a href="register.php">أنشئ حساب جديد</a></p>
    </div>
</body>
</html>
