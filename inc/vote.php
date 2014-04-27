<?php
	
	include dirname(__FILE__) .  "/config.php"; 

	function has_voted($user){
		//check if the user has voted
		return in_array($user, get_voted_users()); 
	}

	function can_vote($user_info){
		//check if a user is allowed to vote in general

		$user = $user_info["username"]; 

		//TODO: implement allow_vote logic here

		//otherwise add the override here
		return cfg_contains("allow.txt", $user); 
	}

	function is_admin($user){
		//check if a person is in the admins
		return cfg_contains("admins.txt", $user);
	}


	function vote($user, $voteId){
		if(has_voted($user)){
			return false; 
		} else {
			//actually perform the vote

			$votefile = dirname(__FILE__) . "/../voting/results/voters.txt"; 

			//add person to has_voted dir
			touch($votefile); 

			file_put_contents($votefile, file_get_contents($votefile) . "\n". $user); 

			//add a new file to the vote

			$vote_dir = dirname(__FILE__) . "/../voting/results/"; 

			$options = get_voting_options(); 

			file_put_contents($vote_dir  . $voteId . ".txt", "Someone voted for this one!\n", FILE_APPEND);

			//touch all of the files to make tracing it harder. 
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

		$options = get_voting_options(); 

		foreach ($options as $id => $option){
			//list the appropriate directory
			$files = read_cfgFile("/../voting/results/" . ((string)$id) . ".txt" ); 
			$results[$option] = count($files); 
		}

		return $results; 
	}

	function get_voting_options(){
		return read_cfgFile("votingoptions.txt");
	}
?>