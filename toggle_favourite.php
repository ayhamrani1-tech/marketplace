<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if (!isset($_POST['item_id'])) {
    header("Location: all_items.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = (int)$_POST['item_id'];


$check = mysqli_query($conn,
    "SELECT id FROM favourites WHERE user_id=$user_id AND item_id=$item_id"
);

if (mysqli_num_rows($check) > 0) {
    
    mysqli_query($conn,
        "DELETE FROM favourites WHERE user_id=$user_id AND item_id=$item_id"
    );
} else {
    
    mysqli_query($conn,
        "INSERT INTO favourites (user_id, item_id)
         VALUES ($user_id, $item_id)"
    );
}


$redirect = $_SERVER['HTTP_REFERER'] ?? 'all_items.php';
header("Location: $redirect");
exit;