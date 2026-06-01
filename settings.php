<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";


$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($result);

/* Update account info */
if (isset($_POST['save_info'])) {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    mysqli_query($conn,
        "UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$user_id"
    );
    $message = "Account updated successfully";
}


if (isset($_POST['change_pass'])) {
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($current, $user['password'])) {
        $message = "Current password incorrect";
    } elseif ($new !== $confirm) {
        $message = "Passwords do not match";
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn,
            "UPDATE users SET password='$hash' WHERE id=$user_id"
        );
        $message = "Password updated";
    }
}


if (isset($_POST['delete_account'])) {
    mysqli_query($conn, "DELETE FROM users WHERE id=$user_id");
    session_destroy();
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Settings</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
<h2>Account Settings</h2>

<?php if ($message): ?>
    <div class="success"><?php echo $message; ?></div>
<?php endif; ?>

<h3>Account Information</h3>
<form method="POST">
    <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" required>
    <button name="save_info">Save Changes</button>
</form>

<hr>

<h3>Change Password</h3>
<form method="POST">
    <input type="password" name="current_password" placeholder="Current Password" required>
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
    <button name="change_pass">Update Password</button>
</form>

<hr>

<h3 style="color:red;">Danger Zone</h3>
<form method="POST">
    <button name="delete_account" style="background:red;">
        Delete My Account
    </button>
</form>

</div>
</body>
</html>