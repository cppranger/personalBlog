<?php
    include_once "templates/connect.php";
    $id_article = $_REQUEST["id_article"];
    $text = $_REQUEST["text"];
    $author = $_REQUEST["author"];
    $sql = "INSERT INTO `articles_comments` (`id`, `author`, `text`, `pubdate`, `article_id`) VALUES (NULL, '$author', '$text', current_timestamp(), '$id_article')";
    $send = $connection -> query($sql);
    echo '<script>location.replace("/post.php?id_article=' . $id_article . '");</script>'; 
    exit();
?>