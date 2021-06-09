<?php 
    include_once "./templates/generation.php";
    $id_article = $_REQUEST["id_article"];
    $comment = $_REQUEST["comment"];
    $views = getViewsCount ($connection);
    postViewCount($connection, $id_article);
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
        generate_menu ($views);
    ?>
 
    <div class="container">
        <?php
            generate_post($connection, $id_article);
        ?>
    </div>

    <div class="comments container">
        <hr>

        <form action="sendComment.php">
            <div class="form-group">
                <label for="author">Name</label>
                <input type="text" class="form-control" id="nameArea" name="author" placeholder="Author's name can't be null" required>
            </div>
            <div class="form-group">
                <input type="hidden" name="id_article" value="<?php echo $id_article ?>">
                <label for="commentArea">Message</label>
                <textarea class="form-control" id="commentArea" name="text" rows="3" placeholder="Comment can't be null too" required></textarea>
                <button type="submit" class="btn btn-dark" name="sendComment" style="margin-top: 1%;">Submit</button>
            </div>
        </form>
        
        <hr>
        
        <?php 
            generate_comment($connection, $id_article);
        ?>
    </div>

    <?php
        add_scripts ();
    ?>
</body>
</html>