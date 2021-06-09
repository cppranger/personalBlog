<?php
    include_once "templates/connect.php";
    $type = $_REQUEST["type"];
    $id = $_REQUEST["id"];
    $sql = "DELETE FROM '$type' WHERE id = '$id'";
    $send = $connection -> query($sql);
    echo '<script>location.replace("/admin.php");</script>'; 
    exit();
?>