<?php
session_start();
include "db.php";

if (!isset($_SESSION['verify_email'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$email = $_SESSION['verify_email'];

if (isset($_POST['verify'])) {

    $code = trim($_POST['code']);

    
    $sql = "
        SELECT id, verification_code, code_created_at 
        FROM users 
        WHERE email='$email' AND is_verified=0
    ";

    $result = mysqli_query($conn, $sql);

    
    if (!$result) {
        die("DB ERROR: " . mysqli_error($conn));
    }

    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $error = "Invalid verification request";
    } elseif ($user['verification_code'] != $code) {
        $error = "Wrong verification code";
    } elseif (strtotime($user['code_created_at']) < strtotime("-10 minutes")) {
        $error = "Verification code expired";
    } else {

        
        mysqli_query(
            $conn,
            "UPDATE users 
             SET is_verified=1, verification_code=NULL, code_created_at=NULL
             WHERE id={$user['id']}"
        );

        unset($_SESSION['verify_email']);
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify Account</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container" style="max-width:600px;">
    <h2>Verify Account</h2>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="code" placeholder="Enter 6-digit code" required>
        <button type="submit" name="verify">Verify</button>
    </form>
</div>

</body>
</html>
``