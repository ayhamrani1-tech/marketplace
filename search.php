<?php
session_start();
include "db.php";

$search     = $_GET['search'] ?? '';
$price_from = $_GET['price_from'] ?? '';
$price_to   = $_GET['price_to'] ?? '';
$free_only  = $_GET['free_only'] ?? '';
$sort       = $_GET['sort'] ?? 'new';

$sql = "SELECT * FROM items WHERE 1";

if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $sql .= " AND title LIKE '%$s%'";
}

if ($price_from !== '') {
    $sql .= " AND price >= " . (int)$price_from;
}

if ($price_to !== '') {
    $sql .= " AND price <= " . (int)$price_to;
}

if ($free_only === '1') {
    $sql .= " AND price = 0";
}

$sql .= ($sort === 'cheap') ? " ORDER BY price ASC" : " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">

<h2>Search</h2>

<!-- ✅ زر الرجوع -->
<a href="all_items.php" style="display:inline-block;margin-bottom:15px;color:#4f46e5;font-weight:bold;">
⬅ Back to All Items
</a>

<form method="GET">

  <div class="search-box">
    <span class="icon">🔍</span>
    <input 
      type="text" 
      name="search" 
      placeholder="Search Marketplace"
      value="<?= htmlspecialchars($search) ?>"
    >
  </div>

  <input type="number" name="price_from" placeholder="Price from"
         value="<?= htmlspecialchars($price_from) ?>">

  <input type="number" name="price_to" placeholder="Price to"
         value="<?= htmlspecialchars($price_to) ?>">

  <label>
    <input type="checkbox" name="free_only" value="1"
    <?= $free_only==='1'?'checked':'' ?>>
    FREE
  </label>

  <select name="sort">
    <option value="new" <?= $sort==='new'?'selected':'' ?>>Newest</option>
    <option value="cheap" <?= $sort==='cheap'?'selected':'' ?>>Cheapest</option>
  </select>

  <button>Filter</button>

  <!-- ✅ Reset -->
  <a href="search.php" class="reset-btn">Reset</a>

</form>

<hr>

<div class="cards">

<?php if(mysqli_num_rows($result) == 0): ?>
  <p>No items found 😢</p>
<?php endif; ?>

<?php while($item = mysqli_fetch_assoc($result)): ?>

<?php
$is_fav = false;

if (isset($_SESSION['user_id'])) {
    $uid = $_SESSION['user_id'];
    $iid = $item['id'];

    $fav = mysqli_query($conn,
        "SELECT id FROM favourites WHERE user_id=$uid AND item_id=$iid"
    );

    if (mysqli_num_rows($fav) > 0) $is_fav = true;
}
?>

<div class="product-card">

<!-- ✅ صورة -->
<?php if (!empty($item['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="">
<?php else: ?>
    <div class="no-image">No Image</div>
<?php endif; ?>

<div class="card-content">

<h3><?= htmlspecialchars($item['title']) ?></h3>

<p><?= htmlspecialchars($item['description'] ?? '') ?></p>

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
<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $item['user_id']): ?>
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
``