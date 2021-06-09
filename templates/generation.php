<?php
include_once "connect.php";

function generate_menu ($views=0, $active="Home") {
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid" style="margin-left: 3%;">
            <a class="navbar-brand" href="/">Personal Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <? if ($active=="Home") echo 'active "aria-current="Home"';?>"href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <? if ($active=="Admin") echo 'active "aria-current="Admin"';?>"href="admin.php">Admin Panel</a>
                    </li>
                </ul>
            </div>
            <span class="visit-info float-end text-light">
                <span>Visitors overall: <? echo $views[0] ?></span>
                <span> Visitors today: <? echo $views[1] ?></span>
            </span>
        </div>
    </nav>

<?php
}

function generate_posts ($mysqli, $category=0) {
    if ($category == 0) {
        $sql = "SELECT * FROM articles";
    }

    else {
        $sql = "SELECT * FROM articles WHERE categorie_id = '".$category."'";
    }
    
    $sql_result = $mysqli -> query($sql);
    ?>

    <div class="container center-block" style="margin: 3%; max-width:95%;">
    <div class="row row-cols-3">

    <?php

    if ($sql_result -> num_rows > 0) {
        while($sql_result_article = $sql_result -> fetch_assoc()) {
            $sql_category = "SELECT title FROM `articles_categories` WHERE id = '".$sql_result_article[categorie_id]."'";
            $sql_category_result = $mysqli -> query($sql_category);
            $category = mysqli_fetch_assoc($sql_category_result);
            ?>
            <div class="card text-white bg-secondary mb-3" style="max-width: 18rem; margin-left: 1%; margin-right: 1%;">
                <div class="card-header" style="text-color: white;">Category: <? echo $category['title'] ?></div>
                <div class="card-body">
                    <h5 class="card-title"><a href="post.php?id_article=<?= $sql_result_article['id'] ?>"><?= $sql_result_article['title'] ?></a></h5>
                    <p class="card-text"><?= substr($sql_result_article['text'], 0, 100) . '...' ?></p>
                </div>
            </div>
            <?php
        }
    } else {
        ?> <h3>No articles!</h3> <?php
    }
}

function generate_post ($mysqli, $id_article) {
    $sql = "SELECT * FROM `articles` WHERE id = '$id_article'";
    $sql_result = $mysqli -> query($sql);
    if ($sql_result -> num_rows === 1) {
        $sql_result_article = $sql_result -> fetch_assoc()?>
        <h1 style="margin-top: 3%;"><?= $sql_result_article['title'] ?></h1>
        <p align="justify" style="text-indent: 5%;"><?= $sql_result_article['text'] ?></p>
        <p><span>Published: <?= substr($sql_result_article['pubdate'], 0, 11) ?></span> <span>Views: <? echo $sql_result_article['views'] ?></span></p>
        <?php
    }
}

function generate_comment ($mysqli, $id_article) {
    $sql = "SELECT * FROM `articles_comments` WHERE article_id = $id_article";
    $sql_result = $mysqli -> query($sql);
    if ($sql_result -> num_rows > 0) {
        while ($sql_result_comment = $sql_result -> fetch_assoc()) {
            ?>
            <div class="comment" align="justify">
                <p><b><? echo $sql_result_comment['text'] ?></b></p>
                <p>Author: <? echo $sql_result_comment['author'] ?> </p>
            </div>
            <hr>
            <?php
        }
    } else {
        ?> <h3>No comments!</h3> <?php
    }
}

function generate_sidebar($mysqli) {
    $sql = "SELECT * FROM `articles_categories`";
    $sql_result = $mysqli -> query($sql);
    if ($sql_result -> num_rows > 0) {
        ?> <br><h3>Categories:</h3><ul class="list-group"> <?
        while ($sql_result_category = $sql_result -> fetch_assoc()) {
            
            $sql_count = "SELECT COUNT(title) FROM `articles` WHERE categorie_id = '".$sql_result_category[id]."'";
            $sql_count_result = $mysqli -> query($sql_count);
            $count = mysqli_fetch_assoc($sql_count_result);            
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center"><a href="category.php?id_cat=<?= $sql_result_category['id'] ?>"><?= $sql_result_category['title'] ?></a>
                    <span class="badge badge-primary badge-pill"><? echo $count['COUNT(title)'] ?></span>
                </li>
            <?php
        }
    } else {
        ?> <h3>No articles!</h3> <?php
    }
}

function add_scripts () {
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>';
}

function indexViewsCount($mysqli) {
    $sql = "SELECT * FROM `views`";
    $sql_result = $mysqli -> query($sql);
    $all_views = $sql_result -> fetch_assoc();
    $all_views = $all_views[views];
    $all_views++;
    $daily_views = $sql_result -> fetch_assoc();
    $daily_views_date = $daily_views[lastModified];
    $today = date("Y-m-d"); 
    if ($daily_views_date < $today) {
        $sql = "UPDATE `views` SET `views` = 0 WHERE `type` = 'today'";
        $daily_views = 1;           
    }
    else {
        $daily_views = $daily_views[views];
        $daily_views++;
        $sql = "UPDATE `views` SET `views` = '".$all_views."' WHERE `type` = 'allTime'";
    }
    $send = $mysqli -> query($sql);
    $sql = "UPDATE `views` SET `views` = '".$daily_views."' WHERE `type` = 'today'";
    $send = $mysqli -> query($sql);
    return [$all_views, $daily_views];
}

function getViewsCount($mysqli) {
    $sql = "SELECT * FROM `views`";
    $sql_result = $mysqli -> query($sql);
    $all_views = $sql_result -> fetch_assoc();
    $all_views = $all_views[views];
    $daily_views = $sql_result -> fetch_assoc();
    $daily_views = $daily_views[views];
    return [$all_views, $daily_views];
}

function postViewCount($mysqli, $id_post) {
    $sql = "SELECT `views` FROM `articles` WHERE `id` = '".$id_post."'";
    $sql_result = $mysqli -> query($sql);
    $views = $sql_result -> fetch_assoc();
    $views = $views[views];
    $views++;
    $sql = "UPDATE `articles` SET `views` = '".$views."' WHERE `articles`.`id` = '".$id_post."'";
    $sql_result = $mysqli -> query($sql);
    return $views;
}

function generate_admin_panel ($mysqli) {
    ?>
<div class="conteiner" style="margin-left: 10%; margin-right: 10%;">
    <br>
    <h3>Articles</h3>
    <table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col">id</th>
            <th scope="col">title</th>
            <th scope="col">text</th>
            <th scope="col">category</th>
            <th scope="col">published</th>
            <th scope="col">views</th>
            <th scope="col">actions</th>
        </tr>
    </thead>
    <?php
        $sql = "SELECT * FROM articles";
        $sql_result = $mysqli -> query($sql);
    ?>
    <tbody>
        <?
        if ($sql_result -> num_rows > 0) {
            $id = 1;
            while($sql_result_article = $sql_result -> fetch_assoc()) {
                $sql_category = "SELECT title FROM `articles_categories` WHERE id = '".$sql_result_article[categorie_id]."'";
                $sql_category_result = $mysqli -> query($sql_category);
                $category = mysqli_fetch_assoc($sql_category_result);
                ?>
                <tr>
                    <th scope="row"><? echo $id ?></th>
                    <td><a href="post.php?id_article=<?= $sql_result_article['id'] ?>"><?= $sql_result_article['title'] ?></a></td>
                    <td><?= substr($sql_result_article['text'], 0, 15) . '...' ?></td>
                    <td><? echo $category['title'] ?></td>
                    <td><?= substr($sql_result_article['pubdate'], 0, 11) ?></td>
                    <td><? echo $sql_result_article['views'] ?></td>
                    <td>actions</td>
                    </tr>
                <?php
                $id++;
            }
        }
        ?>
    </tbody>
    </table>

    <hr>
    <h3>Categories</h3>
    <table class="table">
    <thead class="thead-light">
    <?
        $sql = "SELECT * FROM `articles_categories`";
        $sql_result = $mysqli -> query($sql);
    ?>
        <tr>
            <th scope="col">id</th>
            <th scope="col">title</th>
            <th scope="col">post count</th>
            <th scope="col">actions</th>
        </tr>

    </thead>
    <tbody>
    <?
            if ($sql_result -> num_rows > 0) {
                $id = 1;
                while ($sql_result_category = $sql_result -> fetch_assoc()) {
                    $sql_count = "SELECT COUNT(title) FROM `articles` WHERE categorie_id = '".$sql_result_category['id']."'";
                    $sql_count_result = $mysqli -> query($sql_count);
                    $count = mysqli_fetch_assoc($sql_count_result); 
        ?>
        <tr>
            <th scope="row"><? echo $id ?></th>
            <td><a href="category.php?id_cat=<?= $sql_result_category['id'] ?>"><?= $sql_result_category['title'] ?></a></td>
            <td><? echo $count['COUNT(title)'] ?></td>
            <td>actions</td>
        </tr>
        <?
        $id++;
                }
            }
        ?>
    </tbody>
    </table>

    <hr>
    <h3>Comments</h3>
    <table class="table">
    <thead class="thead-light">
    <?php
        $sql = "SELECT * FROM `articles_comments`";
        $sql_result = $mysqli -> query($sql);
    ?>
        <tr>
            <th scope="col">id</th>
            <th scope="col">author</th>
            <th scope="col">text</th>
            <th scope="col">published</th>
            <th scope="col">article</th>
            <th scope="col">actions</th>
        </tr>

    </thead>
    <tbody>
    <?
            if ($sql_result -> num_rows > 0) {
                $id = 1;
                while ($sql_result_comment = $sql_result -> fetch_assoc()) {
                    $sql_title = "SELECT `title` FROM `articles` WHERE id = '".$sql_result_comment['article_id']."'";
                    $sql_title_result = $mysqli -> query($sql_title);
                    $title = mysqli_fetch_assoc($sql_title_result); 
        ?>
        <tr>
            <th scope="row"><? echo $id ?></th>
            <td><? echo $sql_result_comment['author'] ?></td>
            <td><? echo substr($sql_result_comment['text'], 0, 15) . '...' ?></td>
            <td><? echo $sql_result_comment['pubdate'] ?></td>
            <td><a href="post.php?id_article=<?= $sql_result_comment['article_id'] ?>"><?= $title[title] ?></a></td>
            <td>actions</td>
        </tr>
        <?
        $id++;
                }
            }
        ?>
    </tbody>
    </table>
</div>
<?
}
?>