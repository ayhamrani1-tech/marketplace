<?php
session_start();
include "db.php";

$error = "";

if (isset($_POST['register'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match";
    } else {

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email already exists";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $code = rand(100000, 999999);
            $created_at = date("Y-m-d H:i:s");

            mysqli_query(
                $conn,
                "INSERT INTO users 
                (name, email, phone, password, is_verified, verification_code, code_created_at)
                VALUES 
                ('$name', '$email', '$phone', '$hash', 0, '$code', '$created_at')"
            );

            $_SESSION['verify_email'] = $email;

            header("Location: verify.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Create Account</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container auth-container">

<h2>Create Account</h2>

<!-- ✅ error -->
<?php if ($error): ?>
<div class="error"><?= $error ?></div>
<?php endif; ?>

<!-- ✅ form -->
<form method="POST">

    <input name="name" placeholder="Full Name" required>

    <input name="email" type="email" placeholder="Email" required>

    <input name="phone" placeholder="077xxxxxxx" required>

    <input name="password" type="password" placeholder="Password" required>

    <input name="confirm_password" type="password" placeholder="Confirm Password" required>

    <button name="register">Create Account</button>

</form>

<p style="margin-top:15px;">
    Already have an account?
    <a href="login.php">Login</a>
</p>

</div>

</body>
</html>