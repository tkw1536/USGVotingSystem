<?php
	//Authentication

	function check_ldap_pass($user, $pass) {
		//connect to ldap and check pass

		//empty pass wont work
		if($user == "" || $pass == ""){
			return false; 
		}

		//set ldap host
		$ldap_host = 'jacobs.jacobs-university.de';
		$ldap_port = 389;

		//connect and try to bind
		$ds = @ldap_connect($ldap_host,$ldap_port);
		$res = @ldap_bind($ds, $user . "@" . $ldap_host, $pass); 

		//unbind
		@ldap_unbind($ds); 
		
		return $res; 
	}

	function get_userinfo($user, $pass){
		//get some user info

		//set ldap host
		$ldap_host = 'jacobs.jacobs-university.de';
		$ldap_port = 389;

		//connect and try to bind
		$ds = @ldap_connect($ldap_host,$ldap_port);
		$res = @ldap_bind($ds, $user . "@" . $ldap_host, $pass);

		//firgure out what we are looking for and what info we want
		$dn = "OU=Users,OU=CampusNet,DC=jacobs,DC=jacobs-university,DC=de";
		$filter="(sAMAccountName=". $user .")";
		$justthese = array("displayName", "mail", "description");

		//perform the search
		$sr=ldap_search($ds, $dn, $filter, $justthese);
		$info =ldap_get_entries($ds, $sr);

		//figure out mail
		$mail = $info[0]["mail"][0]; 

		//figure out display name
		$name = $info[0]["displayname"][0]; 
		$name = explode(", ", $name); 
		$name = $name[1] . " " . $name[0]; 

		//load the description
		$is_ug = split(" ", $info[0]["description"][0]) or array();

		//check if we are undergrad
		if(count($is_ug) > 1){
			$is_ug = ($is_ug[1] == "ug"); 
		} else {
			$is_ug = False; 
		}

		//undbind from ldap
		ldap_unbind($ds); 

		//put it in an array and return it
		return array("fullname" => $name, "mail" => $mail, "username" => $user, "is_ug" => $is_ug);
	}


	function getUserData($user, $pass){
		//get data about this user

		if(check_ldap_pass($user, $pass)){
			
			//if we can login, return user data
			return get_userinfo($user, $pass); 
		} else {

			//otherwise return false
			return False; 
		}
	}

?>