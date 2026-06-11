<?php
session_start();
include 'service/database.php';

if (isset($_POST['buy'])) {
    $machine_id = $_SESSION['machine_id'] ?? 1;
    $name       = mysqli_real_escape_string($db, $_POST['name']);
    $size       = mysqli_real_escape_string($db, $_POST['size']);
    $price      = (int)$_POST['price'];
    $image      = mysqli_real_escape_string($db, $_POST['image']);

    $sql = "INSERT INTO `cart_items` (`machine_id`, `name`, `size`, `price`, `image`) 
            VALUES ('$machine_id', '$name', '$size', '$price', '$image')";

    if (!mysqli_query($db, $sql)) {
        die("Error: " . mysqli_error($db));
    }

    header("Location: http://localhost:8000/cart/?machine_id=" . $machine_id);
    exit();
}
?>