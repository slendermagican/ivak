<?php
session_start();
include "../db/connection.php";

// Initialize error and success message variables
$emailToRemove = '';
$errorMsg = '';
$successMsg = '';

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Process form submission to fetch user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emailToRemove"])) {
    $emailToRemove = mysqli_real_escape_string($conn, $_POST["emailToRemove"]);

    // Fetch user details based on email
    $userQuery = "SELECT * FROM users WHERE email = '$emailToRemove'";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult) {
        die("Error: " . mysqli_error($conn));
    }

    $userData = mysqli_fetch_assoc($userResult);

    // Check if user data is empty
    if (!$userData) {
        $errorMsg = 'No user found with the specified email.';
    } else {
        // Delete user from the database
        $deleteQuery = "DELETE FROM users WHERE email = '$emailToRemove'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            $successMsg = 'User deleted successfully.';
        } else {
            $errorMsg = 'Error deleting user: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Remove User</title>
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
        <section class="flex-grow p-4 w-3/4 flex-1 bg-gray-200 rounded-2xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Remove User</h1>
            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="emailToRemove" class="block text-gray-700 text-sm font-bold mb-2">Email to Remove:</label>
                    <input type="email" name="emailToRemove" value="<?php echo $emailToRemove; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Delete User</button>
                </div>
            </form>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>