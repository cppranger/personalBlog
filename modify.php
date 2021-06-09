<?php
    include_once "./templates/generation.php";
    $views = getViewsCount ($connection);
    $type = $_REQUEST["type"];
    $id = $_REQUEST["id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Personal Blog</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
    <?php 
        generate_menu ($views, "Admin");
    ?>

    <div>
        <?php
            echo $type . ' ' . $id;
            generate_modify($connection, $type, $id);
        ?>
    </div>

    <?php
        add_scripts ();
    ?>
</body>
</html>