<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

// Message display
if (isset($message)) {
    if (is_array($message)) {
        foreach ($message as $msg) {
            echo '
            <div class="message">
                <span>' . strip_tags($msg, '<a><b><strong><i><em>') . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    } else {
        echo '
        <div class="message">
            <span>' . strip_tags($message, '<a><b><strong><i><em>') . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Custom CSS for ShopSphere Logo with Infinite Animation -->
<style>
    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
    }
    .logo img {
        height: 55px;
    }
    .logo-text {
        font-weight: bold;
        font-size: 36px;
        color: #d32f2f;
        position: relative;
        overflow: hidden;
    }
    .logo-text::after {
        content: '';
        position: absolute;
        top: 0;
        left: -150%;
        width: 50%;
        height: 100%;
        background: linear-gradient(120deg, transparent, rgba(255,255,255,0.6), transparent);
        animation: shine 2s linear infinite;
    }

    @keyframes shine {
        0% {
            left: -150%;
        }
        50% {
            left: 150%;
        }
        100% {
            left: 150%;
        }
    }
</style>

<header class="header">
    <section class="flex">

        <a href="home.php" class="logo">
            <img src="images/logo 1.png" alt="ShopSphere Logo">
            <span class="logo-text">ShopSphere</span>
        </a>

        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="menu.php">Menu</a>
            <a href="orders.php">Orders</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <?php
            $total_cart_items = 0;
            if ($user_id !== null) {
                $count_cart_items = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
                $count_cart_items->execute([$user_id]);
                $total_cart_items = $count_cart_items->rowCount();
            }
            ?>
            <a href="search.php"><i class="fas fa-search"></i></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="menu-btn" class="fas fa-bars"></div>
        </div>

        <div class="profile">
            <?php
            if ($user_id !== null) {
                $select_profile = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $select_profile->execute([$user_id]);

                if ($select_profile->rowCount() > 0) {
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
                    <div class="flex">
                        <a href="profile.php" class="btn">Profile</a>
                        <a href="components/user_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
                    </div>
                    <?php
                }
            } else {
                ?>
                <p class="name">Please login first!</p>
                <a href="login.php" class="btn">Login</a>
                <p class="account">
                    <a href="login.php">Login</a> or
                    <a href="register.php">Register</a>
                </p>
                <?php
            }
            ?>
        </div>

    </section>
</header>