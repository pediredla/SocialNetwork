<?php
class hash{
	public static function make($string){
		return hash('sha256',$string);
	}
	
	/*public static function salt($length){
		return mcrypt_create_iv($length);
	}*/
	
	public static function unique(){
		return self::make(uniqid());
	}
}
?>