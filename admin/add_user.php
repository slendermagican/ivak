<?php
session_start();
include "../db/connection.php";

// Initialize error and success message variables
$errorMsg = '';
$successMsg = '';

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Fetch all user data from the database
$userQuery = "SELECT * FROM users";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult) {
    die("Error: " . mysqli_error($conn));
}

// Fetch all user data into an associative array
$users = mysqli_fetch_all($userResult, MYSQLI_ASSOC);

// Process form submission to add a new user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form data
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $isAdmin = isset($_POST["isAdmin"]) ? 1 : 0;

    // Check if the email or username already exists
    $checkQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (!$checkResult) {
        die("Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($checkResult) > 0) {
        $errorMsg = 'User with the same email or username already exists.';
    } else {
        // Insert new user into the database with hashed password
        $insertQuery = "INSERT INTO users (email, username, password, isAdmin) VALUES ('$email', '$username', '$hashedPassword', $isAdmin)";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $successMsg = 'User added successfully.';
        } else {
            die("Error: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Add User</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col h-screen">

    <?php include "../components/header.php"; ?>

    <main class="flex-grow p-4 flex flex-row gap-3">
        <!-- Aside (Navigation on the left) -->
        <aside class="w-1/4">
            <?php include "../components/admin_nav.php"; ?>
        </aside>

        <!-- Section (Content on the right) -->
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl overflow-x-hidden">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Add User</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)): ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)): ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                    <input type="text" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="mb-4">
                    <label for="isAdmin" class="block text-gray-700 text-sm font-bold mb-2">Is Admin:</label>
                    <input type="checkbox" name="isAdmin" class="mr-2 leading-tight"> Yes
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add User</button>
                </div>
            </form>

        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>
