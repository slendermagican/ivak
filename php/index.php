<?php
session_start();
?>

<?php
    include "../components/header.php";
  
     ?>
     
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://kit.fontawesome.com/5b1a9e5fe0.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
<main> 


    
</main>
</body>

</html>
<?php
foreach($_SESSION as $key=>$value){
    echo "{$key}={$value} <br>";
}
?>

<?php
    include "../components/footer.php";
?>