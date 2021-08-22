<?php
session_start();

require('./PDO/taskPDO.php');
require('./Function/function.php');
$pdo = new taskPDO();

//データのロード
if(isset($_GET['orderbylevel'])) {
    $data = $pdo -> getAllDatalevel($_SESSION["user"]);
} else {
    $data = $pdo -> getAllData($_SESSION["user"]);
}

//セッションの確認
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
}

?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.79.0">

        <link rel="canonical" href="https://getbootstrap.jp/docs/5.0/examples/navbar-fixed/">
        <!-- Bootstrap core CSS -->
        <link href=https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <!-- Favicons -->
        <link rel="apple-touch-icon" href="/docs/5.0/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
        <link rel="manifest" href="/docs/5.0/assets/img/favicons/manifest.json">
        <link rel="mask-icon" href="/docs/5.0/assets/img/favicons/safari-pinned-tab.svg" color="#7952b3">
        <link rel="icon" href="/docs/5.0/assets/img/favicons/favicon.ico">
        <meta name="theme-color" content="#7952b3">

        <!-- Custom styles for this template -->
        <link href="navbar-top-fixed.css" rel="stylesheet">
        <link href="./style.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">TaskBomber</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="index.php">日付順</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="index.php?orderbylevel">重要度順</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="./login.php">ログアウト</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="./exit.php">退会</a>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        <main class="container">
        <div class="bg-light p-5 rounded">
            <h1>This is your Tasks</h1>
            <p class="lead">これは<?php echo $_SESSION["user"] ?>さんのタスクです. <br>現実から目をそらさずにおとなしく課題を進めましょう<br><br>タスクの数: <?php echo count($data) ?></p>
            <div class="row mb-3">
                <?php foreach($data as $key=>$value): ?>
                    <hr>
                    <div class="col-md-5 themed-grid-col">
                        <h5><?php echo $value["name"] ?></h5>
                    </div>
                    <div class="col-md-3 themed-grid-col">
                        <?php $num=calculate_time($value["deadline"]) ?>
                        <?php if($num <= 3 or strtotime(date("Y/m/d")) > strtotime($value["deadline"])): ?>
                            <p style="color:#dc3545;"><?php echo $value["deadline"] ?></p>
                        <?php else: ?>
                            <p><?php echo $value["deadline"] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="col-md-2 themed-grid-col">
                        <?php for($i=1; $i<=$value["level"]; $i++): ?>
                            ★
                        <?php endfor ?>
                    </div>
                    <div class="col-md-2 themed-grid-col">
                        <form action="" method="post">
                            <button type="submit" name="delete" class="btn btn-danger" value="<?php echo $value["id"] ?>">削除</button>
                        </form>
                        <?php if(array_key_exists("delete", $_POST)): ?>
                            <?php $pdo->delData($_POST["delete"]) ?>
                            <script> location.replace("index.php"); </script>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
                <hr>
            </div>
            	
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw&text=私はタスクをため過ぎました...%0A次からは以下の点を気を付けます!%0A[URL]"  class="btn btn-lg btn-primary" data-show-count="false" role="button" data-url="http://www.example.com/" data-related="KKariya23">こんな現実があってはならない</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
            <br>
            <br>
            <br>
            <form action="" method="post">
                    <fieldset>
                        <legend>Adding a task</legend>
                        <div class="mb-3">
                            <label for="TextInput" class="form-label">Task Name</label>
                            <input type="text" id="disabledTextInput" name="name" class="form-control" placeholder="Enter Your Task">
                        </div>
                        <div class="mb-3">
                            <label for="TextInput" class="form-label">Level</label>
                            <select class="form-select" name="level" aria-label="Default select example">
                                <option selected value="1">ordinary</option>
                                <option value="2">important</option>
                                <option value="3">most important</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="Select" class="form-label">Deadline</label><br>
                            <input type="date" id="start" name="date" value="<?php date("Y/m/d") ?>" min="2020-01-01" max="2030-12-31">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <?php if(array_key_exists("name", $_POST) && array_key_exists("date", $_POST)): ?>
                            <?php echo $_POST["level"] ?>
                            <?php $pdo->mkData($_POST["name"], $_POST["date"], $_POST["level"], $_SESSION["user"]) ?>
                            <script> location.replace("index.php"); </script>
                        <?php endif ?>
                    </fieldset>
                </form>
        </div>
        <script src="/docs/5.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <body>
</html>

