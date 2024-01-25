<?php
session_start();
include "../db/connection.php";

$loginFeedback = ""; // Variable to store login feedback

if (isset($_POST["login"])) {
    $loginIdentifier = mysqli_real_escape_string($conn, $_POST["identifier"]); // Either username or email
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);

    // Check if the provided identifier (username or email) and password match
    $loginQuery = "SELECT * FROM users WHERE (username='$loginIdentifier' OR email='$loginIdentifier') AND password='$pass'";
    $result = mysqli_query($conn, $loginQuery);

    if (mysqli_num_rows($result) > 0) {
        // Identifier (username or email) and password are correct, proceed with login
        $userData = mysqli_fetch_assoc($result);

        //not saving the passoword in the session because it's a sensitive data
        $_SESSION["isAdmin"] = $userData["isAdmin"];
        $_SESSION["username"] = $userData["username"];
        $_SESSION["email"] = $userData["email"];
        // $_SESSION["password"] = $pass;

        // Close the session write operation
        session_write_close();

        // Use JavaScript for redirection
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    } else {
        // Identifier (username or email) or password is incorrect, set feedback message
        $loginFeedback = "Invalid username/email or password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>

<body class="bg-gray-100">
    <?php
    include "../components/header.php";
    ?>

    <main class="container mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md">
        <!-- Display login feedback here -->
        <?php if (!empty($loginFeedback)) : ?>
            <div class="mb-4 text-red-500">
                <?php echo $loginFeedback; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-4">
                <label for="identifier" class="block text-gray-700 font-bold mb-2">Username or Email:</label>
                <input type="text" id="identifier" name="identifier" class="w-full p-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded">
            </div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <input type="submit" name="login" value="Login" class="bg-blue-500 text-white p-2 rounded">
                </div>
                <div>
                    <a href="forgot_password.php" class="text-blue-500">Forgotten Password?</a>
                </div>
            </div>
        </form>

        <div class="text-center">
            <p>Don't have an account? <a href="register.php" class="text-blue-500">Create one</a></p>
        </div>
    </main>

    <?php
    include "../components/footer.php";
    ?>
</body>

</html>
