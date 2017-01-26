<?php
class Model{
    private $credentials = null;
    private $connection = null;

    private $sql		= array(
    						"table"=>"",
    						"joins"=>"",
    						"where"=>""
    				    );
    private $seq = "";
    private function connect(){
        $this->credentials = new Credentials();
    	try {
		    $this->connection = new PDO('pgsql:host='.$this->credentials->host.';dbname='.$this->credentials->database, $this->credentials->username, $this->credentials->password);
		} catch (PDOException $e) {
			http_response_code(503);
		    echo json_encode(array("errors"=>"Error!: " . $e->getMessage()));
		    exit(0);
		}
    	
    }

    private $pkey = "ID";

    private function exec( $sql ){
    	$data = array();
    	try{
            $rows = $this->connection->query($sql);
            if (!$rows){
                http_response_code(503);
                echo json_encode(array("errors"=>"Error!: " . $this->connection->errorInfo()[2] ));
                exit(0);
            }
            foreach( $rows as $row ){
	    		$data[] = $row;
	    	};
			return $data;
		} catch (PDOException $e) {
			http_response_code(503);
		    echo json_encode(array("errors"=>"Error!: " . $e->getMessage()));
		    exit(0);
		}
    }
    
   /********************************************************************************************************
    *   $table: The table of the database to select.
    *   $pkey:  The field of the table that acts as primary private key.
    *   $joins: an array of fields and they matching table/field. 
    *           The array should look like this:
    *           
    *           $joins = array( "permission_id" => array( "permission" => "id" ) );
    *               -or-
    *           $joins = ["field_name"] | ["table_name"] = "field_name"
    *
    ********************************************************************************************************/
    public function __construct($table, $pkey='ID', $joins=[], $seq = "" ){
        $this->connect();

        $joinStr = "";
        foreach( $joins as $field=>$join ) {
            foreach ($join as $target=>$target_field ) {
                $joinStr.='LEFT JOIN "'.$target.'" ON "'.$table.'"."'.$field.'" = "'.$target.'"."'.$target_field.'" ';
            }
        }

        $this->sql['table'] = $table;
        $this->sql['joins'] = $joinStr;
        $this->sql['where'] = 'WHERE "'.$table.'"."'.$pkey.'" = ';
        $this->pkey = $pkey;
        if (empty($seq)){
            $seq = $table."_".$pkey."_"."seq";
        }
        $this->seq = $seq;
    }

    /****************************************************************
        select: 
            $key = string of key to compare, or array composed like this array("table","field");
            $value = string of value to compare with keys
            $sortBy = which data element to sort the table by, by default is 1 for column 1.
            $sortOrd = Order of which sorted data is sorted, default is Descending
            $limit = maximum number of items to allow, default is 100
            $startat = which number of items to start the selection at, default is 0 the beginning.
    ****************************************************************/
    public function select( $key=null, $value=null, $sortBy="1", $sortOrd='DESC', $limit='100', $startat='0' ){
        if (!is_numeric($sortBy)){
            $sortBy = '"'.$this->sql['table'].'"."'.$sortBy.'"';
        } 
        $endSql = "ORDER BY ".$sortBy." ".$sortOrd." LIMIT ".$limit." OFFSET ".$startat;
        if (!is_null($value)){
            $value = str_replace("'","''",$value);
            if (is_null($key)){
                $key = $this->pkey;
            }
            if (!is_null($key) && !empty($key)){
                $sql = 'SELECT * FROM "'.$this->sql['table'].'" '.$this->sql['joins'];

                //where clause.
                $where = "";
                if (is_string($key)){
                    $where = ' WHERE "'.$this->sql['table'].'"."'.$key.'" = '."'".$value."' ".$endSql;
                }
                if (is_array($key)){
                    if ( sizeof($key) > 1 ){
                        $where = ' WHERE "'.$key[0].'"."'.$key[1].'" = '."'".$value."' ".$endSql;
                    }
                }
                $sql .= $where;
            } else {
                http_response_code(503);
                return array("error"=>"No key was specified for select.");
            }
        } else {
            $sql = 'SELECT * FROM "'.$this->sql['table'].'" '.$this->sql['joins'].' '.$endSql;
        }
        return $this->exec($sql);
    }
    /********************************************************************************************************
        update:
            $id = the primary key of the field.
            $values = an indexed array of values to update. 
                e.g. array("First Name"=>"Anna","Last Name"=>"Wall");
    ********************************************************************************************************/
    public function update( $id, $values ) {
        $id = str_replace("'","''",$id);
        $valueStr = "";
        foreach ( $values as $key=>$value ) {
            $valueStr.='"'.$key.'" = '."'".str_replace("'","''",$value)."', ";
        }
        $valueStr=substr($valueStr,0,-2);
        $sql = 'UPDATE "'.$this->sql['table'].'" SET '.$valueStr.' WHERE "'.$this->pkey.'"= '."'".$id."'";
        $this->exec($sql);
        return array("success"=>"It was a triumph, I'm making a note here, huge success.");
    }
    /********************************************************************************************************
        insert:
            $values = an indexed array of values to insert. 
                e.g. array("First Name"=>"Anna","Last Name"=>"Wall");
    ********************************************************************************************************/
    public function insert( $values ) {
        $sql = 'INSERT INTO "'.$this->sql['table'].'"';
        if (sizeof($values) > 0){
            $fieldStr = "(";
            $valueStr = "VALUES (";
            foreach ( $values as $key=>$value ) {
                $fieldStr.='"'.$key.'",';
                $valueStr.="'".str_replace("'","''",$value)."',";
            }
            $fieldStr=substr($fieldStr,0,-1).")";
            $valueStr=substr($valueStr,0,-1).")";

            $sql = 'INSERT INTO "'.$this->sql['table'].'" '.$fieldStr." ".$valueStr;
        } 
        $this->exec($sql);
        
        $sql="SELECT currval('\"".$this->seq."\"') as ID;";
        $data = $this->exec($sql);

        return $data;
    }
    /********************************************************************************************************
        delete:
            $key = the key at which to delete by
            $value = the value at which that key should be.
            Delete's a row, key and value are required to prevent deleting all rows. Single row deletion
            only allowed.
    ********************************************************************************************************/
    public function delete( $key,$value ){
        $sql = 'DELETE FROM "'.$this->sql['table'].'" WHERE "'.$key.'" ='." '".$value."'";
        $data = $this->exec($sql);
        return $data;
    }            
    /********************************************************************************************************
        count:
           Just returns a count of rows.
    ********************************************************************************************************/
    public function count() {
        $sql = 'SELECT count("'.$this->pkey.'") FROM "'.$this->sql['table'].'"';
        $data = $this->exec($sql);
        return intval($data[0]["count"]);
    }
}