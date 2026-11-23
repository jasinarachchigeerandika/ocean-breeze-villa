


<?php
if (!isset($basePath)) {
    $basePath = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ocean Breeze Villa</title>

    <link rel="stylesheet"
          href="<?php echo $basePath; ?>assets/css/style.css">
</head>

<body>

<header class="navbar">

    <!-- Website Logo -->
    <a href="<?php echo $basePath; ?>index.php" class="logo">
        Ocean Breeze Villa
    </a>

    <!-- Navigation Menu -->
    <nav class="nav-links">

        <a href="<?php echo $basePath; ?>index.php">
            Home
        </a>

        <a href="<?php echo $basePath; ?>rooms.php">
            Rooms
        </a>

        <a href="<?php echo $basePath; ?>availability.php">
            Availability
        </a>

        <?php if (is_logged_in()): ?>

            <?php if (is_admin()): ?>

                <a href="<?php echo $basePath; ?>admin/dashboard.php">
                    Admin Dashboard
                </a>

            <?php else: ?>

                <a href="<?php echo $basePath; ?>my-bookings.php">
                    My Bookings
                </a>

            <?php endif; ?>

            <a href="<?php echo $basePath; ?>logout.php"
               class="btn btn-primary">
                Logout
            </a>

        <?php else: ?>

            <a href="<?php echo $basePath; ?>register.php">
                Register
            </a>

            <a href="<?php echo $basePath; ?>login.php">
                Login
            </a>

        <?php endif; ?>

    </nav>

</header>
