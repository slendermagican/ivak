<?php
session_start();
include "../db/connection.php";

$token = mysqli_real_escape_string($conn, $_GET["token"]);
$resetFeedback = "";

if (isset($_POST["reset_password"])) {
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Fetch user data from the database before nullifying the reset token
    $getUserQuery = "SELECT * FROM users WHERE reset_token='$token'";
    $getUserResult = mysqli_query($conn, $getUserQuery);

    if (!$getUserResult) {
        die("Error: " . mysqli_error($conn));
    }

    $userData = mysqli_fetch_assoc($getUserResult);

    // Update the password and clear the reset token
    $updateQuery = "UPDATE users SET password='$password', reset_token=NULL WHERE reset_token='$token'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if (!$updateResult) {
        die("Error: " . mysqli_error($conn));
    }

    // Set session variables after the password reset
    $_SESSION["username"] = $userData["username"];
    $_SESSION["email"] = $userData["email"];
    $_SESSION["isAdmin"] = $userData["isAdmin"];

    // Use JavaScript for redirection
    echo '<script>window.location.href = "index.php";</script>';
    exit();
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

<body class="bg-gray-100">
    <?php
    include "../components/header.php";
    ?>

    <main class="container mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md">
        <!-- Display reset feedback here -->
        <?php if (!empty($resetFeedback)) : ?>
            <div class="mb-4 text-green-500">
                <?php echo $resetFeedback; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=$token"; ?>" method="post">
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">New Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <input type="submit" name="reset_password" value="Reset Password" class="bg-blue-500 text-white p-2 rounded">
            </div>
        </form>

        <div class="text-center">
            <p>Remember your password? <a href="login.php" class="text-blue-500">Login here</a></p>
        </div>
    </main>

    <?php
    include "../components/footer.php";
    ?>
</body>

</html>
