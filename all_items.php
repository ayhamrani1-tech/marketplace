<?php
session_start();
include "db.php";

$sql = "SELECT * FROM items ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>All Items</title>

<link rel="stylesheet" href="css/style.css">

<style>
.heart-btn{
    background:none;
    border:none;
    font-size:24px;
    cursor:pointer;
}

.chat-btn{
    display:inline-block;
    margin-top:8px;
    color:#4f46e5;
    font-weight:bold;
}

/* زر البحث */
.search-btn {
  display: inline-block;
  background: #e4e6eb;
  padding: 12px 18px;
  border-radius: 30px;
  color: #333;
  font-size: 16px;
  margin-bottom: 20px;
}

.search-btn:hover {
  background: #d8dadf;
}
</style>

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

<h2>All Items</h2>

<!-- ✅ زر البحث -->
<a href="search.php" class="search-btn">🔍 Search Marketplace</a>

<div class="cards">

<?php while($item = mysqli_fetch_assoc($result)): ?>

<?php
$is_fav = false;

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $iid = $item['id'];

    $fav = mysqli_query($conn,
        "SELECT id FROM favourites WHERE user_id=$uid AND item_id=$iid"
    );

    if (mysqli_num_rows($fav) > 0) {
        $is_fav = true;
    }
}
?>

<div class="product-card">

<!-- ✅ Image -->
<?php if (!empty($item['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="">
<?php else: ?>
    <div class="no-image">No Image</div>
<?php endif; ?>

<div class="card-content">

<h3><?= htmlspecialchars($item['title']) ?></h3>

<p><?= htmlspecialchars($item['description']) ?></p>

<div class="card-footer">
<?= $item['price']==0
    ? '<span class="free-badge">FREE</span>'
    : '<span class="price">'.$item['price'].' JD</span>' ?>
</div>

<!-- ❤️ Favourite -->
<?php if (isset($_SESSION['user_id'])): ?>
<form method="POST" action="toggle_favourite.php">
    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
    <button class="heart-btn">
        <?= $is_fav ? '❤️' : '🤍' ?>
    </button>
</form>
<?php endif; ?>

<!-- 💬 Chat -->
<?php if (
    isset($_SESSION['user_id']) 
    && $_SESSION['user_id'] != $item['user_id']
): ?>
<a class="chat-btn"
   href="chat.php?user=<?= $item['user_id'] ?>&item=<?= $item['id'] ?>">
   💬 Chat
</a>
<?php endif; ?>

</div>
</div>

<?php endwhile; ?>

</div>
</div>

</body>
</html>