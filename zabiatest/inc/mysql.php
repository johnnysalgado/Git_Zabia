<?php
/* ----------------------------------- */ 
/* Class: MySQL						   */
/* ----------------------------------- */
class MySQL {    
	var $host	= "";
	var $user	= "";
	var $pass	= "";
	var $base	= "";
	var $conn	= NULL;
	var $debug	= true;

	function __construct ($host=DB_HOST, $base=DB_NAME, $user=DB_USER, $pass=DB_PASS) {
		$this->host = $host;
		$this->base = $base;
		$this->user = $user;
		$this->pass = $pass;
		
/*
		echo "host: ".$host."<br>";
		echo "base: ".$base."<br>";
		echo "user: ".$user."<br>";
		echo "pass: ".$pass."<br>";
*/		
		return $this->open();
	}

	function error($message = "") {
		if ($this->debug) {
			if ($message != "") {
				die($message);
			} else {
				die(mysqli_error($this->conn));
			}
		} else {
			die("");
		}
	}

	function open() {
		if (!$this->conn) {
			try {
				$this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->base);
				if (!$this->conn) {
				    echo "Error: Unable to connect to MySQL." . PHP_EOL;
				    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
				    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
				    exit;
				} 
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
			if ($this->conn) {
				if (!mysqli_select_db($this->conn, $this->base)) {
					$this->error();
				} else {
					mysqli_query($this->conn, "SET NAMES utf8");
				}
			} else {
				echo "conexión errada";
				$this->error();
			}
		}
		return $this;
	}

	function close() {
		if ($this->conn) {
			mysqli_close($this->conn);
			$this->conn = NULL;	
		}
		return $this;
	}

	function status() {
		return ( $this->conn ? 'open': 'close' );
	}

	function insertArray($table = NULL, $data = NULL){
		$sField = '';
		$sValue = '';
		$bFlag = false;
		if ( $table == '' ) return NULL;
		if ( ! is_array($data) ) return NULL;
		foreach($data as $key => $value ) {
			if ( $bFlag ) {
				$sField .= ',';
				$sValue .= ',';
			}
			$bFlag = true;
			$sField .= $key;
			$sValue .= $this->param($value, 'text');
		}
		mysqli_query($this->conn, "INSERT INTO ".$table." (".$sField.") VALUES (".$sValue.")") or $this->error();
		return mysqli_insert_id($this->conn);
	}

	function updateArray($table = NULL, $data = NULL, $filtro = NULL){
		$sField = '';
		$bFlag = false;
		if ($table == '') return NULL;
		if (!is_array($data)) return NULL;
		if ($filtro == NULL) return NULL;
		foreach($data as $key => $value ) {
			if ( $bFlag ) 
				$sField .= ', ';
			$bFlag = true;
			$sField .= $key."=". $this->param($value, 'text');
		}
		mysqli_query($this->conn, "UPDATE ".$table." SET ".$sField." WHERE ".$filtro) or $this->error();
		return true;
	}

	function insert($sql = "") {
		if ($sql != "") {
			mysqli_query($this->conn, $sql) or $this->error();
			return mysqli_insert_id($this->conn);
		} else {
			return $this->error('Empty query');
		}
	}

	function execute($sql = "") {
		if ($sql != "") {
			return mysqli_query($this->conn, $sql) or $this->error();
		} else {
			return $this->error('Empty query');
		}
	}

	function query($sql = "") {
		if ($sql != "") {
			if ($this->conn) {
//				echo "entra a ver el smysql<br>";
				return new MySQLData($sql, $this->conn);
			}
		} else {
			$this->error('Empty query');
		}
	}

	function param($theValue, $theType = "text", $theDefinedValue = "", $theNotDefinedValue = "") {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		$theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($theValue) : mysqli_escape_string($theValue);
		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . trim($theValue) . "'" : "NULL";
				break;
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date":
				$theValue = ($theValue != "") ? "'" . trim($theValue) . "'" : "NULL";
				break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
	  return $theValue;
	}
	
}
/* ----------------------------------- */ 
/* Class: MySQLData					   */
/* ----------------------------------- */
class MySQLData {		
	var $conn = NULL;
	var $data = NULL;
	var $item = NULL;
	var $rows = 0;
	var $curr = 0;
	var $size = 0;
	
	var $query = "";
	var $debug = true;
	
	function __construct($query, $conn) {
		$this->query = $query;
		$this->conn = $conn;
	}
	function paginate($curr, $size) {
		$this->curr = $curr;
		$this->size = $size;
	}

	function read() {
		if ($this->curr != 0) {
//			echo "<br>entra a read antes de query";
			$this->data = mysqli_query($this->conn, "SELECT count(*) as total FROM (".$this->query.") as total") or $this->error();
			$this->next();
			$this->rows = $this->field('total');
			$this->data = mysqli_query($this->conn, $this->query." LIMIT ".(($this->curr-1)*$this->size).", ".$this->size) or $this->error();
		} else {
			//echo "<br>entra a read antes de query: ".$this->query;
			$this->data = mysqli_query($this->conn, $this->query) or $this->error();
			$this->rows = mysqli_num_rows($this->data);
		}
	}

	function error($message = "") {
		if ($this->debug) {
			if ($message != "") {
				die($message);
			} else {
				die(mysqli_error($this->conn));
			}
		} else {
			die("");
		}
	}

	function total() {
		return $this->rows;	
	}

	function page() {
		return $this->curr;	
	}

	function pages() {
		$tpages = ceil($this->rows/$this->size);
		return $tpages;
	}

	function count() {
		if ($this->data) {
			return mysqli_num_rows($this->data);
		} else {
			$this->error('No Data');	
		}
	}

	function next() {
		if ($this->data) {
			$this->item = mysqli_fetch_assoc($this->data);
			return $this->item;
		} else {
			$this->error('No Data');	
		}
	}

	function field($field) {
		return $this->item[$field];	
	}
	
	function seek($num) {
		if ($this->data) {
	    	mysqli_data_seek($this->data, $num);
			return $this->read();
		} else {
			$this->error('No Data');
		}
	}

	function first() {
		return $this->seek(0);	
	}
}
?>