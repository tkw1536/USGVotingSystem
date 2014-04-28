<?php
  //second stage - select vote

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



  include dirname(__FILE__) . "/../inc/head.php"; 
?>
      <form class="form-signin" role="form" action="/03/"  method="POST">
        <h2 class="form-signin-heading">Placing your vote</h2>
        <input type="hidden" name="user" value="<?php echo $user; ?>">
        <input type="hidden" name="pass" value="<?php echo $pass; ?>">
        Your name: <?php echo $user_info["fullname"]; ?><br />
        Your vote: <select name="vote">
        <option value='illegalvote'>Please choose an option below. </option>
        <?php
          //get the voting options
        	$options = get_voting_options(); 

          //put them in a select
    			foreach ($options as $id => $option){
    				echo "<option value='". ((string)$id) . "'>" . $option . "</option>"; 
    			}
        ?>
        </select>
        <label>
          <?php 
            //print some information about the vote
            echo join("\n", read_cfgFile("info.txt")); 
          ?>
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Vote</button>
      </form>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>