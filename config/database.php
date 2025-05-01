<?php
    $server_name = "localhost";
    $username = "root";
    $password = "";
    $database = "sistem_iotclass";

    $conn = mysqli_connect($server_name, $username, $password, $database);

    if(!$conn) {
        die("connection failed: " . mysqli_connect_error());
    };

    // echo "connection success";

?>