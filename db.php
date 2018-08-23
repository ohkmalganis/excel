<?php

// Powered by Lenin Aparicio

// Definiendo el Host
define("DB_HOST", "localhost");
// Usuario
define("DB_USER", "root");
// Password
define("DB_PASS", "0hkp455");
// Nombre de la base de datos
define("DB_NAME", "sarha32");


class DB extends mysqli {

    function __construct(){
        parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }
 
    public function execute($query) {
    	$result = $this->query($query);
    	if(!$result){
        	printf("Error: %s\n", $this->error);
        }else{
        	return $result;
        }
    }
    
    public function getobject($query){
		return $this->fetch_object($query);
    }
 
    public function getRow($query) {
        $result = $this->query($query);
        if (!$result) return null;
        return $result->fetch_assoc();
    }    
 
    public function getOne($query) {
        $result = $this->query($query);
        if (!$result) return null;
        $row = $result->fetch_row();
        return is_array($row) ? reset($row) : false;
    }

    public function getAll($query) {
        $result = $this->query($query);
        $ret = array();
        if (!$result) return null;
        while ($row = $result->fetch_assoc()) {
            $ret[] = $row;
        }
        return $ret;
    }
 
    public function getAssoc($query) {
        $result = $this->query($query);
        $ret = array();
 
        if (!$result) return null;
 
        while ($row = $result->fetch_assoc()) {
            $values = array_values($row);
	    $ret[$values[0]] = $values[1];
        }
 
        return $ret;
    }
 
    public function qstr($str) {
        if (is_array($str)) {
            return $this->qstrArr($str);
        }
 
        return "'{$this->real_escape_string($str)}'";
    }
 
    public function qstrArr($arr) {
        foreach ($arr as $key => $value) {
            $arr[$key] = $this->qstr($value);
        }
 
        return $arr;
    }
 
    public function getInsertSql($table_name, $fields) {
        $keys = array_keys($fields);
        $count = count($keys);
 
        for ($i = 0; $i < $count; $i++) {
            $keys[$i] = "`" . $keys[$i] . "`";
        }
 
        $keys = implode(", ", $keys);
        $values = implode(", ", $fields);
 
        return "INSERT INTO `$table_name` ($keys) VALUES ($values)";
    }
 
    public function getUpdateSql($table_name, $fields, $key_name, $key_value) {
        $keys = array_keys($fields);
 
        $update = array();
        foreach ($fields as $key => $value) {
            $update[] = "`$key`=$value";
        }
 
        $update = implode(", ", $update);
 
        return "UPDATE `$table_name` SET $update WHERE `$key_name`=$key_value";
    }
 
    public function regexpPrepare($str) {
        $length = mb_strlen($str);
        $buffer = '';
 
        for ($i = 0; $i < $length; $i++) {
            $char = mb_substr($str, $i, 1);
            if ('UTF-8' == mb_detect_encoding($char, 'auto')) {
                $buffer .= '('.mb_strtoupper($char).'|'.mb_strtolower($char).')';
            } else {
                $buffer .= $char;
            }
        }
 
        return $buffer;
    }
 
    public function getInsertId() {
        return $this->insert_id;
    }
    
    public function get_tables(){
		return $this->getAll("SHOW TABLES");
    }
 
    public function trim($table, $params) {
        $columns = $this->getAll("DESCRIBE `$table`");
        $data = array();
 
        foreach ($columns as $col) {
            if (!isset($params[$col['Field']]) || (strlen($params[$col['Field']]) == 0)) {
                if ($col['Null'] == 'YES') {
                    $data[$col['Field']] = 'NULL';
                } else if ($col['Default'] == NULL) {
                    $data[$col['Field']] = "''";
                }
            } else {
                $data[$col['Field']] = $this->qstr($params[$col['Field']]);
            }
        }
 
        return $data;
    }
    
    public function _get_tables(){
		return "SHOW TABLES";
    }
    
    public function _get_columns($table=FALSE){
		if($table){
		    return "SHOW COLUMNS FROM ".$table;
		}else{
		    echo "Error al crear la consulta.";
		    return FALSE;
		}
    }
 
}
?>
