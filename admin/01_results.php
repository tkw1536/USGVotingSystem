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
  $unvoters = get_not_voted_users(); 
  $results = get_results(); 

  include dirname(__FILE__) . "/../inc/head.php";
?>

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
            <li class="active">
                <form>
                  <button type="submit" class="btn btn-default disabled navbar-btn">Results</button>
                </form>
            </li>
            <li>
              <div class="navbar-form">
                <form action="02_voteconfig.php" method="POST">
                  <input type="hidden" name="user" value="<?php echo $user; ?>">
                  <input type="hidden" name="pass" value="<?php echo htmlentities($pass); ?>">
                  <button type="submit" class="btn btn-default">Vote settings</button>
                </form>
              </div>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <br />

    <div class="container">
        <?php
          if(@$_POST["do"] == "export_res"){
            $fn = vote_export($_POST["filename"]); 
            ?>
      <div class="row">
          <div class="alert alert-success">
          <b>Results exported:</b> 
            <a href="<?php echo $fn; ?>" target="_blank">View exported results</a>
          </div>
        </div>
            <?php
          }
        ?>
        <div class="row">
          <div class="alert alert-warning">
          <b>Warning:</b> 
            Percentages and total count of eligible voters
            may not be accurate if people attending the university have changed or voting permissions have been changed. <br>
             <form action="03_updatecount.php" method="POST">
              <input type="hidden" name="user" value="<?php echo $user; ?>">
              <input type="hidden" name="pass" value="<?php echo htmlentities($pass); ?>">
              <button type="submit" class="btn btn-default">Update people count</button> (This may take a minute or two. )
            </form>
          </div>
        </div>

        <h2>Results: </h2>
        <?php echo ptable("resprint", $results, 0); ?>

        <div class="row">
          <form action="01_results.php" method="POST"  class="form-inline" role="form">
              <input type="hidden" name="user" value="<?php echo $user; ?>">
              <input type="hidden" name="pass" value="<?php echo htmlentities($pass); ?>">
              <input type="hidden" name="do" value="export_res">
              <div class="form-group">
              <input type="text" name="filename" placeholder="results" class="form-control">
            </div>
              
              <button type="submit" class="btn btn-default">Export results</button>
            </form>
        </div>

        <h2>Voter status</h2>
        <?php echo ptable("voteprint", array("voted" => count($voters), "not voted" => count($unvoters)), array("green", "red")); ?>

        <h2>Voters so far: </h2>
        <div class="row">
          <?php if(count($voters) !== 0){ ?>
          <ul><?php
            //print the people that have voted
            echo "          <li>". join("</li>          <li>", $voters) . "</li>\n";  
          ?></ul>
          <?php echo "Total: ". count($voters) ?>
          <?php echo "<!-- ".json_encode($voters)." -->"; ?>
        </div>
        <?php
        } ?>

        <h2>People that have not yet voted: </h2>
        <div class="row">
          <?php if(count($unvoters) !== 0){ ?>
          <ul><?php
            //print the people that have voted
            echo "          <li>". join("</li>          <li>", $unvoters) . "</li>\n";  
          ?></ul>
          <?php echo "Total: ". count($unvoters) ?>
          <?php echo "<!-- ".json_encode($unvoters)." -->"; ?>
        </div>
        <?php
        } ?>
        
    </form>
  </div>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>