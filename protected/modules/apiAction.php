<?php
class API_Action_Base{
	public $model = null;
	public $action = "";
	public $output = array();

	function __construct(){
		if (isset($_REQUEST['a'])){
			$this->action = $_REQUEST['a'];
			
			if ( method_exists($this,$this->action) ){
				$temp = $this->action;
				$this->$temp();
			} else {
				$this->output["errors"][] = "Action does not exist.";
			}
		} else {
			$this->output["errors"][] = "No action specified.";
		}
		echo json_encode($this->output);
	}
}