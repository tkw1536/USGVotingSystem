<?php
  include dirname(__FILE__) . "/../inc/auth.php"; 
  include dirname(__FILE__) . "/../inc/vote.php"; 

  //read user and password from POST
  $user = @$_POST["user"]; 
  $pass = @$_POST["pass"]; 

  //get some user data
  $user_info = getUserData($user, $pass); 

  if($user_info == False){
    //can we login?
    redirect_to("../admin/?fail=authfail");
  }

  if(!is_admin($user)){
    //are we admin?
    redirect_to("../admin/?fail=notadmin");
  }

  //get the results
  $voters = get_voted_users(); 
  $results = get_results(); 

  include dirname(__FILE__) . "/../inc/head.php";
?>
      <form class="form-signin" role="form" action="/03/"  method="POST">
        <h2 class="form-signin-heading">Voters so far: </h2>
        <ul><?php
          //print the people that have voted
          echo "          <li>". join("</li>          <li>", $voters) . "</li>\n";  
        ?></ul>
        <?php echo "Total: ". count($voters);  ?>
        <h2 class="form-signin-heading">Results: </h2>
        <ul><?php
          //print the votes for each of the options. 
          //also count the entire votes again

          $total_votes = 0; 
          foreach ($results as $vote => $count){
            $total_votes += $count; 
            echo "          <li>". $vote . ": " . ((string)$count) . " Votes</li>"; 
          }
        ?></ul>
        <?php echo "Total: ". ((string)$total_votes);  ?>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>