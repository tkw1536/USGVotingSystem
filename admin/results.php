<?php
	include dirname(__FILE__) . "/../inc/auth.php"; 
	include dirname(__FILE__) . "/../inc/vote.php"; 

	$user = @$_POST["user"]; 
	$pass = @$_POST["pass"]; 

	$user_info = getUserData($user, $pass); 

	if($user_info == False){
		redirect_to("../admin/?fail=authfail"); 
	}

  if(!is_admin($user)){
    redirect_to("../admin/01/?fail=noadmin");
  }

  $voters = get_voted_users(); 
  $results = get_results(); 

  include dirname(__FILE__) . "/../inc/head.php";
?>
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
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>