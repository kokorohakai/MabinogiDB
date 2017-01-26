<?
require_once("credentials.php");
require_once("model.php");
require_once("apiAction.php");
class API{
	private $action = null;
	function __construct(){
		if (isset($_REQUEST['m'])){
			$action = cleanFilename($_REQUEST['m']);
			$file = "api/".$action.".php";
			if (file_exists($file)){
				require_once($file);
				if (class_exists("API_Action")){
					$action = new API_Action();
				}
			}
		}
	}
}