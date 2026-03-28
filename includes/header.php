<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>StreetGraphs</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
    <header>
        <div style="display: flex; align-items: center;">
            <a href="index.php" style="display: flex; align-items: center; text-decoration: none;">
               <img src="uploads/logo.png" alt="StreetGraphs Logo" class="logo">

                <h1 style="margin: 0; color: white; font-size: 26px;">StreetGraphs</h1>
            </a>
        </div>
        <nav class="center-nav">
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="cart.php">Cart</a>
                <a href="my_orders.php">My Orders</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
    <main>
