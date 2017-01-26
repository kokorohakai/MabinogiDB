<?php
class API_Action extends API_Action_Base{
	public function getBlank(){
		$this->output["records"] = 0;
		$this->output["total"] = 1;
		$this->output["page"] = $_REQUEST['page'];
		$this->output["rows"] = array();
	}
	public function getAll(){
		if (isset($_REQUEST["race"])){
			$raceID = intval($_REQUEST["race"]);
			$limit = intval($_REQUEST['rows']);
			$startat = (intval($_REQUEST['page']) - 1) * $limit;
			$sort = "DESC";
			if ($_REQUEST['sord']=="asc"){
				$sort = "ASC";
			}		
			$orderBy = "name";
			if (isset($_REQUEST['sidx']) && $_REQUEST['sidx']!=""){
				$orderBy = sanitize($_REQUEST['sidx']);
			}		

			//get the data
			$this->model = new Model("Skills");
			$data = $this->model->select("RaceID", $raceID, $orderBy, $sort, $limit, $startat );

			//prepare the output
			$this->output["records"] = $this->model->count();
			$this->output["total"] = floor($this->output["records"] / $limit) + 1;
			$this->output["page"] = $_REQUEST['page'];
			

			//the data mapping for the grid.
			$this->output["rows"] = array();
			foreach ( $data as $row ){
				$cell = array(
					"id" => $row["ID"],
					"cell" => array(
						$row["name"],
						$row["ID"],
						$row["ID"],
					)
				);
				$this->output["rows"][] = $cell;
			}
		} else {
			$this->output["errors"][] = "Race is Required.";
		}
	}

	public function update(){
		global $user;
		if ($user->loggedIn()){
			if (isset($_REQUEST['id']) && isset($_REQUEST["race"])){
				if (is_numeric($_REQUEST['id'])){
					$id = intval($_REQUEST['id']);
					//set the values
					$values = array();
					if (isset($_REQUEST['name'])){
						$values["name"] = $_REQUEST['name'];
					}
					//use the data model.
					$this->model = new Model("Skills","ID");
					$this->output = $this->model->update($id,$values);
				} else {
					$this->output["errors"][] = "Primary key must be a valid integer.";
				}
			} else {
				$this->output["errors"][] = "No primary key specfied.";
			}
		} else {
			$this->output["errors"][] = "Action not allowed.";
		}
	}

	public function insert(){
		global $user;
		if ($user->loggedIn()){
			if (isset($_REQUEST["race"])){
				$this->model = new Model("Skills","ID");
				$values = [
					"name" => "(New Skill)"
				];
				$data = $this->model->insert($values);
				$this->output = $data;
			} else {
				$this->output["errors"][] = "Race is required.";
			}
		} else {
			$this->output["errors"][] = "Action not allowed.";
		}
	}

	public function delete(){
		global $user;
		if ($user->loggedIn()){
			if (isset($_REQUEST['id'])){
				if (is_numeric($_REQUEST['id'])){
					$id = intval($_REQUEST['id']);
					$this->model = new Model("Skills","ID");
					$this->output = $this->model->delete("ID",$id);
				} else {
					$this->output["errors"][] = "Primary key must be a valid integer.";
				}
			} else {
				$this->output["errors"][] = "No primary key specfied.";
			}
		} else {
			$this->output["errors"][] = "Action not allowed.";
		}
	}
}