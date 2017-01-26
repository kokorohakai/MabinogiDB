<?php
class API_Action extends API_Action_Base{
	public function login(){
		global $user;
		if ( isset($_REQUEST['username']) && isset($_REQUEST['password']) ){
			$username = sanitize($_REQUEST['username']);
			$password = sanitize($_REQUEST['password']);

			$this->model = new Model("Users");
			$data = $this->model->select("username",$username,"username","DESC",1,0);

			if ( sizeof($data) > 0){
				if ($password == $data[0]['password']){
					$user->login($username);
					$this->output["success"] = true;
				} else {
					$this->output["errors"][] = "Incorrect password.";
				}
			} else {
				$this->output["errors"][] = "User does not exist.";
			}
		}
		if (!isset($_REQUEST['username'])){
			$this->output["errors"][] = "No user name specified.";
		}
		if (!isset($_REQUEST['password'])){
			$this->output["errors"][] = "No password specified.";
		}
	}

	public function logout(){
		global $user;
		$user->logout();
	}
}