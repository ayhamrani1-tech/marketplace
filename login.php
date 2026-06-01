<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, password, is_verified FROM users WHERE email = ?"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {

        if ($user['is_verified'] == 0) {
            $_SESSION['verify_email'] = $email;
            header("Location: verify.php");
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;

    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container auth-container">

<h2>Login</h2>

<!-- ✅ error -->
<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<!-- ✅ form -->
<form method="POST">

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

</form>

<p style="margin-top:15px;">
    Don’t have an account?
    <a href="register.php">Create Account</a>
</p>

</div>

</body>
</html>