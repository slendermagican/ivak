<header class="bg-gray-800 text-white p-4">
    <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="flex justify-between items-center md:w-auto w-full mb-4 md:mb-0">
            <a href="/ivak/php/index.php" class="text-3xl font-bold hover:text-gray-300 flex items-center">
                <img src="https://i.imgur.com/877s03C.png" alt="Quizzicle Logo" class="w-10 h-10 mr-2 rounded-[50%]">Quizzicle
            </a>
            <div class="md:hidden">
                <button id="mobile-menu-btn" class="focus:outline-none">
                    <i class="fas fa-bars text-white"></i>
                </button>
            </div>
        </div>
        <nav id="menu" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
            <a href="/ivak/php/about.php" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                <i class="fas fa-info-circle mr-2"></i> About
            </a>
            <a href="/ivak/php/categories.php" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                <i class="fas fa-list-alt mr-2"></i> Categories
            </a>
            <a href="/ivak/php/leaderboard.php" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                <i class="fas fa-trophy mr-2"></i> Leaderboard
            </a>
            <a href="../php/feedback.php"  class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                    <i class="fa fa-comments-o mr-2"></i> Feedback
                </a>
            <?php if (!empty($_SESSION)) : ?>
                <?php if ($_SESSION["isAdmin"]) : ?>
                    <a href="../admin/index.php" class="w-full md:w-auto bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                        <i class="fas fa-user-shield mr-2"></i> Admin Dashboard
                    </a>
                <?php endif; ?>
                <a href="../php/logout.php" id="logout" class="w-full md:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            <?php else : ?>
                <a href="login.php" id="login" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
                <a href="register.php" id="register" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block hover:text-gray-300">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        var mobileMenuBtn = document.getElementById("mobile-menu-btn");
        var menu = document.getElementById("menu");

        mobileMenuBtn.addEventListener("click", function() {
            menu.classList.toggle("hidden");
        });
    });
</script>