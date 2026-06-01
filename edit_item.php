<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn,
    "SELECT * FROM items WHERE id=$id AND user_id=$user_id"
);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (isset($_POST['update'])) {

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $phone = trim($_POST['phone']);

    $image_name = $item['image']; // الصورة القديمة

    if (!is_numeric($price) || $price < 0) {
        $error = "Invalid price";
    } elseif (!preg_match('/^(077|078|079)[0-9]{7}$/', $phone)) {
        $error = "Invalid phone number";
    } else {

        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg','jpeg','png','gif'])) {

                $image_name = time() . "_" . $_FILES['image']['name'];

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    "uploads/" . $image_name
                );
            }
        }

        mysqli_query($conn,
            "UPDATE items 
             SET title='$title',
                 description='$description',
                 price='$price',
                 phone='$phone',
                 image='$image_name'
             WHERE id=$id AND user_id=$user_id"
        );

        header("Location: dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Item</title>

<link rel="stylesheet" href="css/style.css">

</head>
<body>

<div class="navbar">
    <a href="dashboard.php">My Items</a>
    <a href="all_items.php">All Items</a>
    <a href="add_item.php">Add Item</a>
    <a href="favourites.php">Favourites</a>
    <a href="chats.php">Chats</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">

<h2>Edit Item</h2>

<?php if ($error): ?>
    <div class="error"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

    <input type="text" name="title"
           value="<?= htmlspecialchars($item['title']) ?>"
           required>

    <textarea name="description" required><?= htmlspecialchars($item['description']) ?></textarea>

    <input type="number" name="price"
           value="<?= $item['price'] ?>"
           required>

    <input type="text" name="phone"
           value="<?= htmlspecialchars($item['phone']) ?>"
           required>

   
    <?php if (!empty($item['image'])): ?>
        <img src="uploads/<?= $item['image'] ?>" style="width:120px; margin-bottom:10px;">
    <?php endif; ?>

    <input type="file" name="image">

    <button type="submit" name="update">Update</button>

</form>

</div>
</body>
</html>
``