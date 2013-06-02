<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Database project</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="js/jquery.js"></script>
    
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px;
        padding-bottom: 60px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>

    <!-- CodeMirror -->
    <script src="js/codemirror.js"></script>
    <link rel="stylesheet" href="css/codemirror.css">
    <script src="js/sql.js"></script>

  </head>
  <body>
    <?php
      if (isset($_POST['password']) && $_POST['password'] == "dias2013") {
        $_SESSION['login'] = "OK";
      } else if (!isset($_SESSION['login']) || $_SESSION['login'] != "OK") {
        ?>
        <style>
        .form-signin {
          max-width: 300px;
          padding: 19px 29px 29px;
          margin: 0 auto 20px;
          background-color: #fff;
          border: 1px solid #e5e5e5;
          -webkit-border-radius: 5px;
             -moz-border-radius: 5px;
                  border-radius: 5px;
          -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
             -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                  box-shadow: 0 1px 2px rgba(0,0,0,.05);
        }
        .form-signin .form-signin-heading,
        .form-signin .checkbox {
          margin-bottom: 10px;
        }
        .form-signin input[type="text"],
        .form-signin input[type="password"] {
          font-size: 16px;
          height: auto;
          margin-bottom: 15px;
          padding: 7px 9px;
        }
        </style>
        <div class="container">

          <form class="form-signin" action="" method="post">
            <h2 class="form-signin-heading">Please sign in</h2>
            <input type="password" name="password" class="input-block-level" placeholder="Password">
            <button class="btn btn-large btn-primary" type="submit">Sign in</button>
          </form>

        </div>
      </body>
      </html>
        <?php
        exit;
      }
    ?>
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="?p=home">Database project</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="?p=search">Search</a></li>
              <li><a href="?p=insert">Insert</a></li>
              <li><a href="?p=query">Execute SQL</a></li>
            </ul>
            <ul class="nav">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Deliverable 2
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="?p=query_2a">Query A</a></li>
                  <li><a href="?p=query_2b">Query B</a></li>
                  <li><a href="?p=query_2c">Query C</a></li>
                  <li><a href="?p=query_2d">Query D</a></li>
                  <li><a href="?p=query_2e">Query E</a></li>
                  <li><a href="?p=query_2f">Query F</a></li>
                  <li><a href="?p=query_2g">Query G</a></li>
                  <li><a href="?p=query_2h">Query H</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Deliverable 3
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="?p=query_3i">Query I</a></li>
                  <li><a href="?p=query_3j">Query J</a></li>
                  <li><a href="?p=query_3k">Query K</a></li>
                  <li><a href="?p=query_3l">Query L</a></li>
                  <li><a href="?p=query_3m">Query M</a></li>
                  <li><a href="?p=query_3n">Query N</a></li>
                  <li><a href="?p=query_3o">Query O</a></li>
                  <li><a href="?p=query_3p">Query P</a></li>
                  <li><a href="?p=query_3q">Query Q</a></li>
                  <li><a href="?p=query_3r">Query R</a></li>
                  <li><a href="?p=query_3s">Query S</a></li>
                  <li><a href="?p=query_3t">Query T</a></li>
                  <li><a href="?p=query_3u">Query U</a></li>
                  <li><a href="?p=query_3v">Query V</a></li>
                </ul>
              </li>
            </ul>
            <ul class="nav pull-right">
              <li><a href="logout.php">Log out</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container">

      <?php
      if (empty($_GET['p']) || !preg_match("#^[a-z0-9_-]+$#", $_GET['p']) || !file_exists('pages/' . $_GET['p'] . '.php')) {
        include 'pages/home.php';
      } else {
        include 'pages/' . $_GET['p'] . '.php';
      }
      ?>

    </div> <!-- /container -->
  </body>
</html>