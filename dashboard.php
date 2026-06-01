<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Get my items */
$result = mysqli_query(
    $conn,
    "SELECT * FROM items WHERE user_id = $user_id ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Items</title>

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
    <h2>My Items</h2>

    <?php if (mysqli_num_rows($result) === 0): ?>
        <p>You have no items yet.</p>
    <?php endif; ?>

    <div class="cards">
        <?php while ($item = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">

            <!-- ✅ صورة فقط (بدون No Image) -->
            <?php if (!empty($item['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['image']); ?>" alt="Item image">
            <?php endif; ?>

            <div class="card-content">
                <h3><?= htmlspecialchars($item['title']); ?></h3>
                <p><?= htmlspecialchars($item['description']); ?></p>

                <div class="card-footer">
                    <?php if ((float)$item['price'] == 0): ?>
                        <span class="free-badge">FREE</span>
                    <?php else: ?>
                        <span class="price"><?= $item['price']; ?> JD</span>
                    <?php endif; ?>
                </div>

                <!-- ✅ Edit & Delete -->
                <div style="margin-top:10px; display:flex; gap:10px;">
                    
                    <a href="edit_item.php?id=<?= $item['id'] ?>"
                       style="color:#4f46e5; font-weight:bold;">
                       ✏️ Edit
                    </a>

                    <a href="delete_item.php?id=<?= $item['id'] ?>"
                       onclick="return confirm('Are you sure?')"
                       style="color:red; font-weight:bold;">
                       🗑️ Delete
                    </a>

                </div>

            </div>

        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
``