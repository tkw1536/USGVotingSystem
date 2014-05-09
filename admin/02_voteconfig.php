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
  <div class="container">
  <div class="navbar navbar-fixed-top navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">USG Voting System - Admin</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li>
                <div class="navbar-form">
                  <form action="01_results.php" method="POST">
                    <input type="hidden" name="user" value="<?php echo $user; ?>">
                    <input type="hidden" name="pass" value="<?php echo htmlentities($pass); ?>">
                    <button type="submit" class="btn btn-default">Results</button>
                  </form>
              </div>
            </li>
            <li class="active">
              <div class="navbar-form">
                <form>
                  <button type="submit" class="btn btn-default disabled">Vote settings</button>
                </form>
              </div>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <br />

    this is not yet supported, sorry. 
  </div>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>