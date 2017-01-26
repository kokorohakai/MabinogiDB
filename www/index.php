<? 
session_start();
$section = "home";
if (!empty($_REQUEST['url'])){
	$section=preg_replace("/[- .\/\\\?]/","_",$_REQUEST['url']);
}
require("../protected/layout.php");	
