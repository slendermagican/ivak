<?php
session_start();
include "../db/connection.php";

// Check if the user is an admin, if not, redirect them to the login page
if ($_SESSION["isAdmin"] != 1) {
    header("Location: ../../ivak/php/index.php");
    exit();
}

// Initialize variables
$emailToEdit = '';
$errorMsg = '';
$successMsg = '';
$userData = null;

// Process form submission to fetch user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emailToEdit"])) {
    $emailToEdit = mysqli_real_escape_string($conn, $_POST["emailToEdit"]);

    // Fetch user details based on email
    $userQuery = "SELECT * FROM users WHERE email = '$emailToEdit'";
    $userResult = mysqli_query($conn, $userQuery);

    if (!$userResult) {
        die("Error: " . mysqli_error($conn));
    }

    $userData = mysqli_fetch_assoc($userResult);
}


// Process form submission to update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateUser"])) {
    // Validate and sanitize form data
    $emailToEdit = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $newEmail = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $newUsername = mysqli_real_escape_string($conn, $_POST["username"]);
    $newPassword = $_POST["password"]; // Retrieve new password from form

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $isAdmin = isset($_POST["isAdmin"]) ? 1 : 0;

    // Update user details in the database with hashed password
    $updateQuery = "UPDATE users SET email = '$newEmail', username = '$newUsername', password = '$hashedPassword', isAdmin = $isAdmin WHERE email = '$emailToEdit'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $successMsg = 'User details updated successfully.';
        $userData['email'] = $newEmail;
        $userData['username'] = $newUsername;
        $userData['isAdmin'] = $isAdmin;
    } else {
        $errorMsg = 'Error updating user details: ' . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Edit User</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">Edit User</h1>

            <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <?php if (!empty($errorMsg)) : ?>
                    <p class="text-red-500"><?php echo $errorMsg; ?></p>
                <?php endif; ?>

                <?php if (!empty($successMsg)) : ?>
                    <p class="text-green-500"><?php echo $successMsg; ?></p>
                <?php endif; ?>

                <div class="mb-4">
                    <label for="emailToEdit" class="block text-gray-700 text-sm font-bold mb-2">Email to Edit:</label>
                    <input type="email" name="emailToEdit" value="<?php echo $emailToEdit; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Fetch User Details</button>
                </div>
            </form>

            <?php if ($userData) : ?>
                <form method="POST" class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" name="email" value="<?php echo $userData['email']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                        <input type="text" name="username" value="<?php echo $userData['username']; ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                        <input type="password" name="password" placeholder="Enter new password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="isAdmin" class="block text-gray-700 text-sm font-bold mb-2">Is Admin:</label>
                        <input type="checkbox" name="isAdmin" <?php echo $userData['isAdmin'] ? 'checked' : ''; ?> class="mr-2 leading-tight"> Yes
                    </div>

                    <div class="text-center">
                        <button type="submit" name="updateUser" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update User</button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../components/footer.php"; ?>

</body>

</html>