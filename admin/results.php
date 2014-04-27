<?php
	include dirname(__FILE__) . "/../inc/auth.php"; 
	include dirname(__FILE__) . "/../inc/vote.php"; 

	$user = @$_POST["user"]; 
	$pass = @$_POST["pass"]; 

	$user_info = getUserData($user, $pass); 

	if($user_info == False){
		header("HTTP/1.1 303 See Other");
		header( 'Location: /admin/?fail=authfail' );
		die("Please direct your browser to /admin/?fail=authfail"); 
	}

  if(!is_admin($user)){
    header("HTTP/1.1 303 See Other");
    header( 'Location: /admin/?fail=noadmin' );
    die("Please direct your browser to /admin/?fail=noadmin"); 
  }

  $voters = get_voted_users(); 
  $results = get_results(); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>USG Voting Platform - Results</title>

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

  <body>

    <div class="container">

      <form class="form-signin" role="form" action="/03/"  method="POST">
        <h2 class="form-signin-heading">Voters so far: </h2>
        <ul><?php echo "<li>". join("</li><li>", $voters) . "</li>";  ?></ul>
        <?php echo "Total: ". count($voters);  ?>
        <h2 class="form-signin-heading">Results: </h2>
        <ul><?php
          $total_votes = 0; 
          foreach ($results as $vote => $count){
            $total_votes += $count; 
            echo "<li>". $vote . ": " . ((string)$count) . " Votes</li>"; 
          }
        ?></ul>
        <?php echo "Total: ". ((string)$total_votes);  ?>
    </div> <!-- /container -->
  </body>
</html> 