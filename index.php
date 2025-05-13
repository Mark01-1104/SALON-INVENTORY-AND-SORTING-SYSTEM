<?php
?>


<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css"> 
</head>

<body>

<header>
    <div class="header-left">
        <span>James Pascual Salon - Inventory</span>
    </div>

    <div class="header-right">
        <?php
        if (!isset($_SESSION['user_id'])) {
            echo '<a href="pages/login.php">Login</a>';
        }
        ?>
    </div>
</header>

<div class="logo">
    <img src="images/logo.jpg" alt="Logo">
</div>

</body>
</html>
