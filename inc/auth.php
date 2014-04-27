<?php

	function check_ldap_pass($user, $pass) {
		//connect to ldap and check pass
		if($user == "" || $pass == ""){
			return false; 
		}
		$ldap_host = 'jacobs.jacobs-university.de';
		$ldap_port = 389;
		$ds = @ldap_connect($ldap_host,$ldap_port);
		$res = @ldap_bind($ds, $user . "@" . $ldap_host, $pass); 
		@ldap_unbind($ds); 
		return $res; 
	}

	function get_userinfo($user, $pass){
		//get some user info
		$ldap_host = 'jacobs.jacobs-university.de';
		$ldap_port = 389;
		$ds = @ldap_connect($ldap_host,$ldap_port);
		$res = @ldap_bind($ds, $user . "@" . $ldap_host, $pass);

		$dn = "OU=Users,OU=CampusNet,DC=jacobs,DC=jacobs-university,DC=de";
		$filter="(sAMAccountName=". $user .")";
		$justthese = array("displayName", "mail");

		$sr=ldap_search($ds, $dn, $filter, $justthese); //, justthese
		$info =ldap_get_entries($ds, $sr);

		$mail = $info[0]["mail"][0]; 
		$name = $info[0]["displayname"][0]; 

		$name = explode(", ", $name); 
		$name = $name[1] . " " . $name[0]; 

		ldap_unbind($ds); 

		$arr = array("fullname" => $name, "mail" => $mail, "username" => $user);
		return $arr; 
	}


	function getUserData($user, $pass){
		//get data about this user
		//return false if he/she cant login

		if($user == "" or $pass == ""){
			return False; 
		}

		$LOGIN_OK = check_ldap_pass($user, $pass); 

		if($LOGIN_OK){

			return get_userinfo($user, $pass); 
		} else {
			return False; 
		}
	}

?>