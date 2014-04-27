<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>USG Voting Platform - Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/bootstrap/custom/custom.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <?php
    function getErrorMessage($err){
      if($err == "authfail"){
        return "Failed to login. Make sure to use the correct password and username and then try again. "; 
      }
      if($err == "nodamin"){
        return "You are not an admin. What are you doing? "; 
      }
      
      return "Unknown error. Please try again. "; 
    }
  ?>

  <body>

    <div class="container">

      <form class="form-signin" role="form" action="/admin/results.php" method="POST">
        <h2 class="form-signin-heading">USG Voting Platform</h2>
        <?php if(!empty(@$_GET["fail"])){ ?>
        <div class="alert alert-danger"><strong><?php echo getErrorMessage($_GET["fail"]); ?></strong></div>
          <?php } ?><input type="text" class="form-control" placeholder="Campusnet Username" required autofocus name="user">
        <input type="password" class="form-control" placeholder="Campusnet Password" required name="pass">
        <label>
          Please sign in using your campusnet credentials. 
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Admin login</button>
      </form>

    </div> <!-- /container -->
  </body>
</html> 
