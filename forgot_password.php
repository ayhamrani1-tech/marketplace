<?php
include "db.php";
$error = "";

if (isset($_POST['send'])) {
    $email = trim($_POST['email']);
    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", time()+3600);

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE users SET reset_token=?, token_expires=? WHERE email=?"
    );
    mysqli_stmt_bind_param($stmt,"sss",$token,$expires,$email);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        $link = "http://localhost/marketplace/reset_password.php?token=$token";
        echo "Copy this link and open it (simulation):<br>$link";
        exit;
    } else {
        $error = "Email not found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-container">
<div class="auth-card">
<h2>Forgot Password</h2>

<?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

<form method="POST">
<input type="email" name="email" placeholder="University Email" required>
<button type="submit" name="send">Send Reset Link</button>
</form>

</div>
</div>
</body>
</html>
