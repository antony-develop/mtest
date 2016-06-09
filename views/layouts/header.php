<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Test</title>

  <!-- Bootstrap -->
  <link href="../../template/css/bootstrap.min.css" rel="stylesheet">
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.12.2.min.js"></script>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
    
<header class="navbar  navbar-static-top bs-docs-nav">
    <div class="container">
        <nav id="bs-navbar" class="">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <?php if (Admin::isAdmin()): ?>
                        <a href="/logout">Выйти</a>
                    <?php else: ?>
                        <a href="/login">Войти</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </div>    
</header>
<div class="container bs-docs-container">
    <div class="row">     
