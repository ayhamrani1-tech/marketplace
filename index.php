<?php
include "db.php";
$result = mysqli_query($conn, "SELECT * FROM items");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Marketplace</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="container">
    <h2>Available Items</h2>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <div class="item">
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p><strong>Price:</strong> <?php echo $row['price']; ?></p>
        </div>
    <?php } ?>
</div>

</body>
</html>