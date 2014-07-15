<?php

class User {

	function __construct($username, $password, $userid, $groupid, $useridinfo, $homedirectory, $commandshell) {
       $this->username = $username;
       $this->password = $password;
       $this->userid = $userid;
       $this->groupid = $groupid;
       $this->useridinfo = $useridinfo;
       $this->homedirectory = $homedirectory;
       $this->commandshell = $commandshell;

   }

		public static function all(){
		$users=[];
		$output = array();
		exec('cat /etc/passwd', $output);

		foreach ($output as $key => $user) {
			$user = explode(":", $user);
			if (count($user) > 1)
				array_push($users, new user($user[0], $user[1], $user[2], $user[3], $user[4], $user[5], $user[6]));
		}
		
		return $users;
	}

	public static function findOrFail($username){
		$users = User::all();

		foreach ($users as $key => $user) {
			if ($user->username == $username) {
				return $user;
			}
		}

		return false;
	}

}