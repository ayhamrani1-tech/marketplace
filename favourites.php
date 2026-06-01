<?php
session_start();
include "db.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$result = mysqli_query(
    $conn,
    "SELECT items.* 
     FROM items
     INNER JOIN favourites 
     ON items.id = favourites.item_id
     WHERE favourites.user_id = $user_id"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Favourites</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">My Items</a>
    <a href="all_items.php">All Items</a>
    <a href="favourites.php">Favourites</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2>My Favourites</h2>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p>No favourites yet.</p>
    <?php endif; ?>

    <div class="cards">
        <?php while ($item = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">

            <?php if (!empty($item['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="">
            <?php else: ?>
                <div class="no-image">No Image</div>
            <?php endif; ?>

            <div class="card-content">
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?></p>

                
                <form method="POST" action="toggle_favourite.php">
                    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                    <button type="submit" class="heart-btn">❤️</button>
                </form>
            </div>

        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>