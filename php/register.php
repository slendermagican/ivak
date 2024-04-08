<?php
session_start();
include "../db/connection.php";

$emailError = $usernameError = $passwordError = ""; // Define Error variables

if (isset($_POST["register"])) {
    $user = mysqli_real_escape_string($conn, $_POST["username"]);
    $pass = mysqli_real_escape_string($conn, $_POST["password"]);
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // Basic input validation
    if (empty($user)) {
        $usernameError = "Username is required.";
    }
    if (empty($pass)) {
        $passwordError = "Password is required.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Valid email is required.";
    }

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE username='$user' OR email='$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['username'] === $user) {
            $usernameError = "Username already exists. Please choose a different one.";
        }
        if ($row['email'] === $email) {
            $emailError = "Email already exists. Please use a different one.";
        }
    }

    // If no errors, proceed with registration
    if (empty($emailError) && empty($usernameError) && empty($passwordError)) {
        // Hash the password
        $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

        $insertQuery = "INSERT INTO users (username, password, email) VALUES ('$user', '$hashedPassword', '$email')";
        $result = mysqli_query($conn, $insertQuery);

        if (!$result) {
            die("Error: " . mysqli_error($conn));
        }
        // Get the last inserted ID
        $userId = mysqli_insert_id($conn);
        $_SESSION["user_id"] = $userId;
        $_SESSION["isAdmin"] = 0;

        echo "Registration successful!";
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="/styles/style.css">
</head>

<body class="bg-gray-100 flex flex-col h-screen">
    <?php include "../components/header.php"; ?>

    <main class="container mx-auto my-8 p-8 bg-white rounded shadow-md max-w-md flex-1">
        <h1 class="text-3xl font-bold mb-4">Registration</h1>
        <p class="text-gray-600 mb-8">Create an account to access our services.</p>
        <form action="register.php" method="post">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded">
                <p class="text-red-500"><?php echo $emailError; ?></p>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold mb-2">Username:</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded">
                <p class="text-red-500"><?php echo $usernameError; ?></p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded">
                <p class="text-red-500"><?php echo $passwordError; ?></p>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <a href="login.php" class="text-blue-500">Already have an account?</a>
                </div>
                <div>
                    <input type="submit" name="register" value="Register" class="bg-blue-500 text-white p-2 w-24 rounded">
                </div>
            </div>
        </form>
    </main>

    <?php include "../components/footer.php"; ?>
</body>

</html>