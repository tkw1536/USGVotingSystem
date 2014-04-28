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
		if($user_info["is_ug"] and (!cfg_contains("deny.txt", $user))){
			return True; 
		}

		//otherwise we can only vote if we're specifially allowed
		return cfg_contains("allow.txt", $user); 
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
?>