<?php
    $db_server = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "quizzicledb";

    try{
        $conn = mysqli_connect( $db_server,
                                $db_user,
                                $db_password,
                                $db_name);

        // echo"Connected succesfully";

    }catch(mysqli_sql_exception){
        echo"Could not connect";
    }                         
?>
