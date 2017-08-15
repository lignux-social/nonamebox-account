<?php
include_once "includes/functions.php";

if(empty($_SESSION["error"]) && empty($_SESSION["last_page"]))
  header('Location: home.php');


if (empty($_SESSION["error"]))
  $_SESSION["error"] = "Ooops! Something wierd just happened, this is embarrassing :/";

if (empty($_SESSION["last_page"]))
  $_SESSION["last_page"] = "home.php";


?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Account Management | NoNameBox</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/main.css" type="text/css" rel="stylesheet" />
    <link href="assets/big-picture/css/nnb_custom.css" type="text/css" rel="stylesheet" />
    <link href="assets/css/custom.css" type="text/css" rel="stylesheet" />

  </head>
  <body>
    <header id="header">
      <h1 id="logo"><a href="/"> <img src="assets/img/gus_nnblogo.png" alt="No Name Box" class="header-logo"></a></h1>
      <nav id="nav">
          <ul>
              <li><a href="http://nonamebox.org/">Get back to the site</a></li>
          </ul>
      </nav>
    </header>

    <div class="container">
      <div class="row">
          <div class="col-md-12">
            <h1>Account Manager</h1>
            <h2><span class="error">An error occurred</span></h2>
            <div class="row">
              <p><?=$_SESSION["error"]?></p>
              <p><a href="<?=$_SESSION["last_page"]?>" title="Get back.">Get me back to the last page I was visiting.</a></p>
            </div>
          </div>
      </div>
    </div>

    <footer id="footer">
      <ul class="actions">
          <li><a href="https://t.me/NoNameBox" class="icon fa-telegram"></a></li>
          <li><a href="mailto:info@nonamebox.org" class="icon fa-envelope"></a></li>
      </ul>
    </footer>

    <script src="assets/jquery/jquery-3.2.1.min.js" type="text/javascript"></script>
  </body>
</html>

<?php
unset($_SESSION["last_page"]);
unset($_SESSION["error"]);
 ?>
