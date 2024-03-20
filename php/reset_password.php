<?php
session_start();
include "../db/connection.php";

// Check if the token is provided in the URL
if (!isset($_GET['token'])) {
    header("Location: forgot_password.php");
    exit();
}

$token = $_GET['token'];

// Check if the form for resetting password is submitted
if (isset($_POST["reset_password"])) {
    $newPassword = $_POST["new_password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validate password
    if ($newPassword != $confirmPassword) {
        $resetFeedback = "Passwords do not match. Please try again.";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Fetch user data based on the provided token
        $fetchQuery = "SELECT * FROM users WHERE reset_token='$token'";
        $fetchResult = mysqli_query($conn, $fetchQuery);

        if ($fetchResult && mysqli_num_rows($fetchResult) > 0) {
            // Update password in the database
            $updateQuery = "UPDATE users SET password='$hashedPassword', reset_token=NULL WHERE reset_token='$token'";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                // Password updated successfully
                $resetFeedback = "Your password has been reset successfully. You can now <a href='login.php' class='text-blue-500'>login</a> with your new password.";
            } else {
                // Error updating password
                $resetFeedback = "Error resetting password. Please try again later.";
            }
        } else {
            // Token not found or expired
            $resetFeedback = "Invalid or expired reset token. Please request a new password reset.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>
<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <main class="container flex-grow mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md">
        <h1 class="text-3xl font-bold mb-4">Reset Password</h1>
        <p class="text-gray-600 mb-8">Please enter your new password to reset your password.</p>
        
        <!-- Display reset feedback here -->
        <?php if (!empty($resetFeedback)) : ?>
            <div class="mb-4 <?php echo strpos($resetFeedback, 'Error') !== false ? 'text-red-500' : 'text-green-500'; ?>">
                <?php echo $resetFeedback; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=$token"; ?>" method="post">
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700 font-bold mb-2">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 font-bold mb-2">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <input type="submit" name="reset_password" value="Reset Password" class="bg-blue-500 text-white p-2 rounded">
            </div>
        </form>

        <div class="text-center">
            <p>Remember your password? <a href="login.php" class="text-blue-500">Login here</a></p>
        </div>
    </main>

    <?php include "../components/footer.php"; ?>
</body>
</html>
