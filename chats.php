<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


$result = mysqli_query($conn,
    "SELECT conversations.*, items.title AS item_title
     FROM conversations
     INNER JOIN items ON items.id = conversations.item_id
     WHERE conversations.user1 = $user_id 
        OR conversations.user2 = $user_id
     ORDER BY conversations.created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Chats</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">My Items</a>
    <a href="all_items.php">All Items</a>
    <a href="chats.php">Chats</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <h2>My Chats</h2>

    <?php if (mysqli_num_rows($result) == 0): ?>
        <p>No chats yet.</p>
    <?php endif; ?>

    <?php while ($c = mysqli_fetch_assoc($result)): ?>
        <?php
           
            $other_user = ($c['user1'] == $user_id) ? $c['user2'] : $c['user1'];
        ?>
        <div class="product-card" style="padding:15px;margin-bottom:15px;">
            <strong>Item:</strong> <?= htmlspecialchars($c['item_title']) ?><br>
            <strong>Chat with user #<?= $other_user ?></strong><br><br>

            <a href="chat.php?user=<?= $other_user ?>&item=<?= $c['item_id'] ?>">
                💬 Open Chat
            </a>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>