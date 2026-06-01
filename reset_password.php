<?php
include "db.php";

$token = $_GET['token'] ?? "";
$error = "";

$stmt = mysqli_prepare(
    $conn,
    "SELECT id FROM users WHERE reset_token=? AND token_expires>NOW()"
);
mysqli_stmt_bind_param($stmt,"s",$token);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) !== 1) die("Invalid or expired link");

if (isset($_POST['reset'])) {

    $pass = $_POST['password'];
    $conf = $_POST['confirm'];

    if ($pass !== $conf) {
        $error = "Passwords do not match";
    } else {
        $hash = password_hash($pass,PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE users SET password=?, reset_token=NULL, token_expires=NULL WHERE reset_token=?"
        );
        mysqli_stmt_bind_param($stmt,"ss",$hash,$token);
        mysqli_stmt_execute($stmt);

        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="auth-container">
<div class="auth-card">
<h2>Reset Password</h2>

<?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

<form method="POST">
<input type="password" name="password" placeholder="New Password" required>
<input type="password" name="confirm" placeholder="Confirm Password" required>
<button type="submit" name="reset">Reset</button>
</form>

</div>
</div>
</body>
</html>