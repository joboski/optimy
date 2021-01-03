<?php

use optimy\app\core\Application;
use optimy\app\core\Helper;

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript -->
    

    <title>News</title>
  </head>
  <body>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><b>Optimy</b></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        
        <?php if (Application::isGuest()) : ?>

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="/foods">Foods</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/places">Places</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sports">Sports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/people">People</a>
              </li>
            </ul>
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
               <li class="nav-item">
                <a class="nav-link" href="/login"><b>Hi Guest</b> <small style="color:blue;">(Login)</small></a>
               </li>
            </ul>

        <?php else: ?>

          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class='btn btn-primary ?>' href="/blog">Create Blog</a>
              </li>         
              <li class="nav-item">
                <a class="nav-link" href="/foods">Foods</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/places">Places</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/sports">Sports</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/people">People</a>
              </li>
          </ul>
            <ul class="navbar-nav ml-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="/logout"><b>Hi <?php echo Application::$app->user->firstname;  ?></b><small style="color:red;"> (Logout) </small></a>
              </li>
            </ul>
        <?php endif; ?>

      </div>
    </div>
  </nav>
 
    <?php if (Application::$app->session->getFlash("success")): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <?php echo Application::$app->session->getFlash("success") ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php elseif (optimy\app\core\Application::$app->session->getFlash("fail")): ?>
     <div class="alert alert-warning alert-dismissible fade show">
      <?php echo optimy\app\core\Application::$app->session->getFlash("fail") ?>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <?php endif; ?>

  <div class="container">
    
    <div class="content">
       

       {{content}}
    

    </div>
  
  </div>

  <footer class="footer py-5 bg-dark mt-4">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; 2020</p>
    </div>
  </footer>

  <script src="../assets/js/jquery-3.5.0.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
  <script type="text/javascript" src="../assets/js/script.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
 -->
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
  </body>
</html>