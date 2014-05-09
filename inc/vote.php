<?php
	//Voting logic

	include dirname(__FILE__) .  "/config.php"; 

	function has_voted($user){
		//check if the user has voted
		return in_array($user, get_voted_users()); 
	}

	function can_vote($user_info){
		//check if a user is allowed to vote in general

		//get the username
		$user = $user_info["username"]; 

		//we can vote if we are not excluded and an undergrad
		if($user_info["is_ug"] and (!cfg_contains("deny.txt", $user)) and !cfg_contains("deny.txt", "*")){
			return True; 
		}

		//otherwise we can only vote if we're specifially allowed
		return cfg_contains("allow.txt", $user); 
	}

	function vote_locked(){
		//check if the vote is locked
		return file_exists(dirname(__FILE__) .  "/../config/lock.txt");
	}

	function lock_vote(){
		if(!vote_locked()){
			touch(dirname(__FILE__) .  "/../config/lock.txt");
		}
	}

	function unlock_vote(){
		if(vote_locked()){
			unlink(dirname(__FILE__) .  "/../config/lock.txt");
		}
	}

	function is_admin($user){
		//check if a person is in the admins
		return cfg_contains("admins.txt", $user);
	}


	function vote($user, $voteId){
		//perform a vote

		if(has_voted($user)){
			//did the user already vote?
			return false; 
		} else {
			
			//file to store voters in
			$votefile = dirname(__FILE__) . "/../voting/results/voters.txt"; 

			//create it if it doesnt exist yet
			touch($votefile); 

			//add a new line with the given user
			file_put_contents($votefile, file_get_contents($votefile) . "\n". $user); 

			
			//directory to store votes in 
			$vote_dir = dirname(__FILE__) . "/../voting/results/"; 

			//get voting options
			$options = get_voting_options(); 

			//touch all of the files with votes in them to make it harder
			foreach ($options as $id => $option){
				touch($vote_dir  . $id . ".txt"); 
			}

			//add a new line for the voting file
			file_put_contents($vote_dir  . $voteId . ".txt", "Someone voted for this one!\n", FILE_APPEND);

			//touch them again
			foreach ($options as $id => $option){
				touch($vote_dir  . $id . ".txt"); 
			}

			return true; 
		}
	}

	function get_voted_users(){
		//get an array of people that have voted
		return read_cfgFile("/../voting/results/voters.txt"); 
	}

	function get_not_voted_users(){
		//everyone who has not yet voted
		$all = get_eligible_voters(); 
		$voted = get_voted_users(); 
		$others = array(); 

		foreach($all as $person){
			if(!in_array($person, get_voted_users())){
				array_push($others, $person); 
			}
		}

		return $others; 
	}

	function get_eligible_voters(){
		//get all the users that can vote.
		try
		{
			return read_cfgFile("/../voting/tmp_voters.txt");
		} catch (Exception $e)
		{
		 return array(); 
		}
	}

	function get_results(){
		//get people hat have voted

		$results = array(); 

		//get the voting options
		$options = get_voting_options(); 

		//iterate over the options and count the lines in the appropriate config file
		foreach ($options as $id => $option){
			
			$files = read_cfgFile("/../voting/results/" . ((string)$id) . ".txt" ); 
			$results[$option] = count($files); 
		}

		return $results; 
	}

	function get_voting_options(){
		//get the available options for voting
		return read_cfgFile("votingoptions.txt");
	}

	function vote_export($filename){
		$dest = dirname(__FILE__) . "/../public/" . $filename . ".html"; 

		$vote_res = get_results(); 

		$voters = count(get_voted_users()); 
		$unvoters = count(get_not_voted_users()); 

		$head = file_get_contents(dirname(__FILE__) . "/head.php");
		$foot =  file_get_contents(dirname(__FILE__) . "/foot.php");



		$body = '
			<div class="container">
			<h1>Final results for vote</h1>

			<div class="row">
				<div class="col-md-12">
				'.join("\n", read_cfgFile("info.txt")).'
				</div>
			</div>

			<h2>Vote results</h2>
				' . ptable("results", get_results(), 0) . '

			<h2>Voters</h2>
			' . ptable("voteprint", array("voted" => $voters, "not voted" => $unvoters), array("green", "red")) . '
			
			</div>
		'; 

		file_put_contents($dest, $head . $body . $foot); 

		return "../public/" . $filename . ".html";
	}
?>