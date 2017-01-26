<?
	class User {
		function __construct(){
			$this->checkLogout();
		}
		public function loggedIn(){
			return $_SESSION['logged'];
		}
		private function checkLogout(){
			if ($_REQUEST['logout']){
				$this->logout();
			}
		}
		public function logOut(){
			unset( $_SESSION['username'] );
			unset( $_SESSION['logged'] );			
		}
		public function logIn($username){
			$_SESSION['username'] = $username;
			$_SESSION['logged'] = true;
		}
	}