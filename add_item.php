<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if (isset($_POST['add'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $is_free = isset($_POST['is_free']);
    $price = $is_free ? 0 : $_POST['price'];
    $phone = trim($_POST['phone']);
    $user_id = $_SESSION['user_id'];

    if (!$is_free && (!is_numeric($price) || $price < 0)) {
        $error = "Invalid price";
    }

    if (!preg_match('/^(077|078|079)[0-9]{7}$/', $phone)) {
        $error = "Invalid phone number";
    }

   
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $error = "Image is required";
    }

    $image_name = "";

    if ($error === "" && isset($_FILES['image']) && $_FILES['image']['error'] === 0) {

        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg','jpeg','png','gif'])) {

            $image_name = time() . "_" . $_FILES['image']['name'];

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                "uploads/" . $image_name
            );
        } else {
            $error = "Invalid image format";
        }
    }

    if ($error === "") {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO items (title, description, price, phone, user_id, image)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ssdsis",
            $title, $description, $price, $phone, $user_id, $image_name
        );

        mysqli_stmt_execute($stmt);

        header("Location: all_items.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Item</title>
<link rel="stylesheet" href="css/style.css">

<script>
function toggleFree() {
    const checkbox = document.getElementById("is_free");
    const priceInput = document.getElementById("price");
    if (checkbox.checked) {
        priceInput.value = 0;
        priceInput.disabled = true;
    } else {
        priceInput.disabled = false;
    }
}
</script>
</head>

<body>

<div class="navbar">
    <a href="dashboard.php">My Items</a>
    <a href="all_items.php">All Items</a>
    <a href="add_item.php">Add Item</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
<h2>Add Item</h2>

<?php if ($error) echo "<div class='error'>$error</div>"; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="title" placeholder="Title" required>

    <textarea name="description" placeholder="Description" required></textarea>

    <div class="free-option">
        <label>
            <input type="checkbox" name="is_free" id="is_free" onchange="toggleFree()">
            Mark as Free
        </label>
    </div>

    <input type="number" name="price" id="price" placeholder="Price">

    <input type="text" name="phone" placeholder="077xxxxxxx" required>

   
    <input type="file" name="image" required>

    <button type="submit" name="add">Add Item</button>
</form>
</div>

</body>
</html>
