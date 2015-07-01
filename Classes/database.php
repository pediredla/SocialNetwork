<?php

class database{
	private static $_instance=null;
	private $_pdo,
			$_query,
			$_error=false,
			$_results,
			$_count=0;
	//do not change it to public. It is giving multiple instances
	private function __construct(){
		//echo "I am in constructor";
		try{
			//echo 'step2';
			//$this->_pdo=new PDO('mysql dbname=SocialNetwork;host=localhost','anil','Qazxsw23$');
			$this->_pdo=new PDO('mysql:dbname='. config::get('mysql/db') .';host='. config::get('mysql/host'),config::get('mysql/username'),config::get('mysql/password'));
			//echo 'connected';	
		}catch (PDOException $e){
			die($e->getMessage());
		}
	}
	
	public static function getInstance(){
		//echo 'In getInstance';
		if(!isset(self::$_instance)){
			//echo "step1";
			self::$_instance=new database();
		}
		return self::$_instance;
	}
	
	public function query($sql,$parms=array()){
		$this->_error=false;
		if($this->_query=$this->_pdo->prepare($sql)){
			//echo 'working';
			$x=1;
			if(count($parms)){
				foreach ($parms as $param){
					$this->_query->bindValue($x, $param);
					$x++;
				}
			}
			if($this->_query->execute()){
				//echo 'working';
				$this->_results=$this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count=$this->_query->rowCount();
			}else{
			$this->_error=true;
			}
		}
		return $this;
	}
	//for protection do not use this outside this class
	public function action($action,$table,$where=array()){
		if(count($where)===3){
			$operators=array('=','>','<','>=','<=','IN','LIKE','');
			
			$field=$where[0];
			$operator=$where[1];
			$value=$where[2];
			
			if(in_array($operator, $operators)){
				//$sql="SELECT FROM U
				$sql="{$action} FROM {$table} WHERE {$field} {$operator} ?";
				if(!$this->query($sql,array($value))->error()){
					return $this;
				}
			}
		}
		return false;	
	}
	
	public function get($table,$where){
		return $this->action('SELECT *', $table, $where);
	}
	
	public function delete($table,$where){
		return $this->action('DELETE', $table, $where);
	}
	
	public function insert($table, $fields=array()){
		//if(count($fields)){
			$keys=array_keys($fields);
			$values=null;
			$x=1;
			
			foreach($fields as $field){
				$values.="?";
				if($x<count($fields)){
					$values.=',';
				}
				$x++;
			}
			//die($values);
			$sql="INSERT INTO {$table}(`".implode('`,`', $keys) ."`) VALUES({$values})";
			if(!$this->query($sql,$fields)->error()){
				return true;
			}
			//echo $sql;
	//	}
		return false;
	}
	
	public function update($table, $UserID, $fields){
		$set='';
		$x=1;
		foreach ($fields as $name=>$value){
			$set.="{$name}= ?";
			if($x<count($fields)){
				$set.=', ';
			}
			$x++;
		}
		
		$sql="UPDATE {$table} SET {$set} WHERE UserID='{$UserID}'";
		//echo $sql;
		if(!$this->query($sql,$fields)->error()){
			return true;
		}
		return false;
	}
	
	public function results(){
		return $this->_results;
	}
	
	public function first(){
		return $this->results()[0];
	}
	
	public function error(){
		return $this->_error;
	}
	
	public function counts(){
		return $this->_count;
	}
}

?>