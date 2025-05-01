<?php
    include "../config/database.php";

    $sql = "INSERT INTO devices (serial_number, mcu_type, location) VALUES('87654321', 'Test', 'lokasi')";

    if (mysqli_query($conn, $sql)) {
        echo "Data berhasil di tambahkan";
    } else {
        echo "Data gagal ditambahkan";
    }

?>