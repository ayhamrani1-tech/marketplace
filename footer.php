<?php
include "db.php";

/* =====================
   FILTER INPUTS
===================== */
$search     = $_GET['search'] ?? '';
$price_from = $_GET['price_from'] ?? '';
$price_to   = $_GET['price_to'] ?? '';
$free_only  = $_GET['free_only'] ?? '';
$sort       = $_GET['sort'] ?? 'new';

/* =====================
   BUILD QUERY
===================== */
$sql = "SELECT * FROM items WHERE 1";

/* Search */
if ($search !== '') {
    $s = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (title LIKE '%$s%' OR description LIKE '%$s%')";
}

/* Price from */
if ($price_from !== '' && is_numeric($price_from)) {
    $sql .= " AND price >= " . (int)$price_from;
}

/* Price to */
if ($price_to !== '' && is_numeric($price_to)) {
    $sql .= " AND price <= " . (int)$price_to;
}

/* FREE only */
if ($free_only === '1') {
    $sql .= " AND price = 0";
}

/* SORT */
if ($sort === 'price_asc') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort === 'price_desc') {
    $sql .= " ORDER BY price DESC";
} else {
    $sql .= " ORDER BY id DESC"; // newest
}

/* =====================
   RUN QUERY
===================== */
$result = mysqli_query($conn, $sql);
if (!$result) {
    die(mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>All Items</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <a href="my_items.php">My Items</a>
    <a href="all_items.php">All Items</a>
    <a href="add_item.php">Add Item</a>
    <a href="logout.php">Logout</a>
</div>

<!-- FILTER -->
<form method="GET" class="fb-search">

    <input type="text" name="search" placeholder="Search items"
           value="<?php echo htmlspecialchars($search); ?>">

    <input type="number" name="price_from" placeholder="Price from"
           value="<?php echo htmlspecialchars($price_from); ?>">

    <input type="number" name="price_to" placeholder="Price to"
           value="<?php echo htmlspecialchars($price_to); ?>">

    <!-- FREE -->
    <label style="display:flex;align-items:center;gap:6px;">
        <input type="checkbox" name="free_only" value="1"
            <?php if ($free_only === '1') echo 'checked'; ?>>
        FREE only
    </label>

    <!-- SORT -->
    <select name="sort">
        <option value="new" <?php if ($sort === 'new') echo 'selected'; ?>>Newest</option>
        <option value="price_asc" <?php if ($sort === 'price_asc') echo 'selected'; ?>>Cheapest</option>
        <option value="price_desc" <?php if ($sort === 'price_desc') echo 'selected'; ?>>Most Expensive</option>
    </select>

    <button type="submit">Filter</button>

</form>

<!-- ITEMS -->
<div class="fb-container">

<?php if (mysqli_num_rows($result) === 0): ?>
    <div class="no-results">No items found.</div>
<?php endif; ?>

<?php while ($item = mysqli_fetch_assoc($result)): ?>

<div class="fb-card">

    <?php
        echo !empty($item['image'])
            ? '<img src="uploads/' . htmlspecialchars($item['image'])="fb-title"><?php echo htmlspecialchars($item['title']); ?></div>
        <div class="fb-desc"><?php echo htmlspecialchars($item['description']); ?></div>

        <div class="fb-footer">
            <?php if ((int)$item['price'] === 0): ?>
                <span class="fb-price fb-free">FREE</span>
            <?php else: ?>
                <span class="fb-price"><?php echo (int)$item['price']; ?> JD</span>
            <?php endif; ?>

            <span class="fb-phone"><?php echo htmlspecialchars($item['phone']); ?></span>
        </div>
    </div>

</div>

<?php endwhile; ?>

</div>

</body>
</html>