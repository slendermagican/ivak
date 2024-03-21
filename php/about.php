<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php
    include "../components/header.php";
    ?>

    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-4">About Us</h1>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <p class="text-lg mb-4">
                    Welcome to our website dedicated to solving quizzes! We are proud to present quizzes created by none other than Ivaylo Ivanov, widely regarded as the greatest programmer of all time, simply the GOAT!
                </p>
                <p class="text-lg mb-4">
                    Our quizzes cover a wide range of topics and are designed to challenge and entertain. Each quiz is carefully crafted to provide an engaging experience for our users.
                </p>
                <p class="text-lg mb-4">
                    All quiz information is gathered with the assistance of ChatGPT, one of the most advanced language models ever created.
                </p>
                <p class="text-lg mb-4">
                    Images used in our quizzes are found on the web and are selected to enhance the visual appeal and relevance of each quiz.
                </p>
            </div>
        </div>
    </main>

    <?php
    include "../components/footer.php";
    ?>
</body>

</html>