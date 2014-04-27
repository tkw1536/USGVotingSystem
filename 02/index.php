<?php
	include dirname(__FILE__) . "/../inc/auth.php"; 
	include dirname(__FILE__) . "/../inc/vote.php"; 

	$user = @$_POST["user"]; 
	$pass = @$_POST["pass"]; 

	$user_info = getUserData($user, $pass); 

	if($user_info == False){
		header("HTTP/1.1 303 See Other");
		header( 'Location: /01/?fail=authfail' );
		die("Please direct your browser to /01/?fail=authfail"); 
	}

	if(has_voted($user)){
		header("HTTP/1.1 303 See Other");
		header( 'Location: /01/?fail=hasvoted' );
		die("Please direct your browser to /01/?fail=hasvoted"); 
	}

	if(!can_vote($user_info)){
		header("HTTP/1.1 303 See Other");
		header( 'Location: /01/?fail=cantvote' );
		die("Please direct your browser to /01/?fail=hasvoted"); 
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>USG Voting Platform</title>

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
        <h2 class="form-signin-heading">Placing your vote</h2>
        <input type="hidden" name="user" value="<?php echo $user; ?>">
        <input type="hidden" name="pass" value="<?php echo $pass; ?>">
        Your name: <?php echo $user_info["fullname"]; ?><br />
        Your vote: <select name="vote">
        <option value='illegalvote'>Please choose an option below. </option>
        <?php
        	$options = get_voting_options(); 

			foreach ($options as $id => $option){
				echo "<option value='". ((string)$id) . "'>" . $option . "</option>"; 
			}
        ?>
        </select>
        <label>
          <?php echo join("\n", read_cfgFile("info.txt")); ?>
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Vote</button>
      </form>

    </div> <!-- /container -->
  </body>
</html> 