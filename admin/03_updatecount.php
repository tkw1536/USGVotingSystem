<?php
  //Sorry time limit
  set_time_limit(0);

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

	function curlGet($page) {
	//helper function to curl get something
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $page);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	  $result = curl_exec($ch);
	  curl_close($ch);
	  return $result;
	}

	function dataURL( $chr ){
		//data url for a specific person
	  	return "http://swebtst01.public.jacobs-university.de/jPeople/ldap/xml_people_search.php?limit=10000&search=".$chr."&filter=all";
	}

	function person_info($person){
		//gets info about aperson via the xml

		$vals = array();

		//what to store where
		$map = array(
			"samaccountname" => "username",
			"displayname" => "fullname",  
			"mail" => "mail", 
			"description" => "fullstate"
		); 

		//store xml values
		foreach( $map as $k => $v ){
			$vals[$v] = utf8_decode($person->getElementsByTagName($k)->item(0)->textContent);
		}

		//Check if the person is undergrad
		$is_ug = split(" ", $vals["fullstate"]) or array();

		if(count($is_ug) > 1){
			$is_ug = ($is_ug[1] == "ug"); 
		} else {
			$is_ug = False; 
		}

		//get the full name
		$name = $vals["fullname"]; 
		$name = explode(", ", $name); 
		$name = $name[1] . " " . $name[0]; 


		//store all the data
		$vals["fullname"] = $name; 
		$vals["is_ug"] = $is_ug; 

	  	return $vals;
	}

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
            <li>
                <form>
                  <button type="submit" class="btn btn-default disabled navbar-btn">Results</button>
                </form>
            </li>
            <li>
              <form>
                  <button type="submit" class="btn btn-default disabled navbar-btn">Vote settings</button>
                </form>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <br />

	<div class="container">
		<div class="row">
			<h1>Updating Voter Count</h1>
		</div>
		<div class="row">
	<?php

	//get everyone via curl requests
	$h = '';
	for($i=97; $i<=122; ++$i){
		$chr = urlencode(chr($i)."@");
		$href = dataURL( $chr );
		$h .= curlGet($href);
	}

	//read and parse the xml
	$DD = new DOMDocument('1.0', 'utf-8');
	$DD->loadXML( utf8_encode( '<jPeople>'.$h.'</jPeople>' ) );
	$persons = $DD->getElementsByTagName('person');
	$voters = array(); 

	//itertate over everyone and check if he / she can vote
	for( $i=0; $i<$persons->length; ++$i ){
	  $p = person_info($persons->item($i));
	  if(can_vote($p)){
	  	echo "<span class='label label-success'>" . $p["username"] . "</span>   ";
		array_push($voters, $p["username"]);  
	  } else {
	  	echo "<span class='label label-danger'>" . $p["username"] . "</span>   "; 
	  }
	}

	file_put_contents(
		dirname(__FILE__) . "/../voting/tmp_voters.txt"
		, 
		"# This file contains people eligible to vote. \n# It is updated automatically. \n" . join("\n", $voters)
	);

	$tot = $persons->length;
	$el = count($voters);
	$iel = $tot - $el;  
	?>
		</div>

		<?php echo ptable("holder", array("eligible" => $el, "ineligible" => $iel), array("#5cb85c", "#d9534f")); ?> 

		<div class="row">
			<form action="01_results.php" method="POST">
				<input type="hidden" name="user" value="<?php echo $user; ?>">
				<input type="hidden" name="pass" value="<?php echo htmlentities($pass); ?>">
				<button type="submit" class="btn btn-default">Return to results page</button>
			</form>
		</div>

	</div>
<?php include dirname(__FILE__) . "/../inc/foot.php"; ?>