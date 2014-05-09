<?php
  //admin interface - login

    function getErrorMessage($err){
      //different error messages

      if($err == "authfail"){
        return "Failed to login. Make sure to use the correct password and username and then try again. "; 
      }
      if($err == "notadmin"){
        return "You are not an admin. What do you think you are doing? "; 
      }
      
      return "Unknown error. Please try again. "; 
    }

     include dirname(__FILE__) . "/../inc/head.php";
?>
      <form class="form-signin" role="form" action="./01_results.php" method="POST">
        <h2 class="form-signin-heading">USG Voting Platform</h2>
        <?php 
          //print an error message if we have an error
          if(!empty($_GET["fail"])){ 
        ?>
          <div class="alert alert-danger"><strong><?php echo getErrorMessage($_GET["fail"]); ?></strong></div>
        <?php
          } 
        ?><input type="text" class="form-control" placeholder="Campusnet Username" required autofocus name="user">
        <input type="password" class="form-control" placeholder="Campusnet Password" required name="pass">
        <label>
          Please sign in using your campusnet credentials. 
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Admin login</button>
      </form>
    </div>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>