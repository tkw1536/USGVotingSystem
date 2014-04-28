<?php
  //third stage - store the vote

	include dirname(__FILE__) . "/../inc/auth.php"; 
	include dirname(__FILE__) . "/../inc/vote.php"; 

  //read user and password from POST
	$user = @$_POST["user"]; 
	$pass = @$_POST["pass"]; 

  //get some user data
	$user_info = getUserData($user, $pass); 

	if($user_info == False){
    //can we login?
    redirect_to("../01/?fail=authfail"); 
  }

  if(!can_vote($user_info)){
    //is the user allowed to vote?
    redirect_to("../01/?fail=cantvote"); 
  }

  if(has_voted($user)){
    //did the user vote already?
    redirect_to("../01/?fail=hasvoted"); 
  }

  //check what the user voted for
  $vote = @$_POST["vote"]; 

  //does the given vote exist?
  if(!array_key_exists($vote, get_voting_options())){
    redirect_to("../01/?fail=illegalvote"); 
  }

  //store the vote and check if we suceeded
  if(!vote($user, $vote)){
    redirect_to("../01/?fail=votefail");
  }

 include dirname(__FILE__) . "/../inc/head.php";
?>
      <form class="form-signin">
        <h2 class="form-signin-heading">Finished. </h2>
        <div class="alert alert-success">
        Congrats, <strong><?php echo $user_info["username"]; ?></strong>. You have successfully voted. <br />
        You may now return to your academic work. 
        </div>
      </form>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>