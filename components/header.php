<!-- header.php -->

<header class="bg-gray-800 text-white p-4">
    <h1 class="text-3xl font-bold">Quizzicle</h1>
    <nav class="mt-4">
        <ul class="flex space-x-4">
            <li><a href="/ivak/php/about.php" class="hover:text-gray-300"><i class="fas fa-info-circle"></i> About</a></li>
            <li><a href="/ivak/php/categories.php" class="hover:text-gray-300"><i class="fas fa-th"></i> Categories</a></li>
            <li><a href="/ivak/php/index.php" class="hover:text-gray-300"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="/ivak/php/leaderboard.php" class="hover:text-gray-300"><i class="fas fa-trophy"></i> Leaderboard</a></li>

            <?php
            // Check if the user is logged in
            if (!empty($_SESSION)) {
                // If logged in, customize navigation for logged-in users
                if ($_SESSION["isAdmin"]) {
                    echo '<li><a href="../admin/index.php" class="hover:text-gray-300"><i class="fas fa-user-shield"></i> Admin Dashboard</a></li>';
                }
                echo '<li><a href="../php/logout.php" id="logout" class="hover:text-gray-300"><i class="fas fa-sign-out-alt"></i> Logout</a></li>';
            } else {
                // If not logged in, show login and register links
                echo '<li><a href="login.php" id="login" class="hover:text-gray-300"><i class="fas fa-sign-in-alt"></i> Login</a></li>';
                echo '<li><a href="register.php" id="register" class="hover:text-gray-300"><i class="fas fa-user-plus"></i> Register</a></li>';
            }
            ?>
        </ul>
    </nav>
    <hr class="my-4 border-gray-600">
</header>
