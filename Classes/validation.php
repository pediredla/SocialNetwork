<?php
class validation{
	private $_passed=false,
			$_errors=array(),
			$_database=null;
	
	public function __construct(){
		$this->_database=database::getInstance();
	}
	
	public function check($source,$items=array()){
		foreach ($items as $item=>$rules){
			foreach ($rules as $rule=>$rule_values){
				//echo "{$item} {$rule} must be {$rule_values}<br>";
				
				$values=trim($source[$item]);
				$item=escape($item);
				//echo $values;
				
				if($rule=='required'&&empty($values)){
					$this->addError("{$item} is required");
				} else if(!empty($values)){
					switch ($rule){
						case 'min':
							if(strlen($values)<$rule_values){
								$this->addError("{$item} must be minimum of {$rule_values}");
							}
							break;
							
						case 'max':
							if(strlen($values)>$rule_values){
								$this->addError("{$item} cannot exceed {$rule_values}");
							}
							break;
							
						case 'unique':
							$check=$this->_database->get('Users', array($item,'=',$values));
							//$data=array($check);
							//var_dump($data);
							if($check->counts()){
								$this->addError("{$item} already exists");
							}
							break;
						/*case 'Male':
							$check=$this->_database->insert('Users',array('Gender','=','M'));
							break;	
						case 'Female':
							$check=$this->_database->insert('Users',array('Gender','=','F'));
							break;*/
						case 'matches':	
							if($values!=$source[$rule_values]){
								$this->addError("{$rule_values} must match {$item}");
							}
							break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed=true;			
		}
		return $this;
	}
	
	private function addError($error){
		$this->_errors[]=$error;
	}
	
	public function errors(){
		return $this->_errors;
	}
	
	public function passed(){
		return $this->_passed;
	}
	
}	
?>