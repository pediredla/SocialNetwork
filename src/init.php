<?php
session_start();

/*
 * Initializing the parameters for establishing connection
 */
$GLOBALS['config']=array(
		'mysql'=>array(
				'host'=>'127.0.0.1',
				'username'=>'anil',
				'password'=>'Qazxsw23$',
				'db'=>'SocialNetwork'
		),
		'remember'=>array(
				'cookie_name'=>'hash',
				'cookie_expiry'=>604800
		),
		'session'=>array(
				'session_name'=>'user',
				'token_name'=>'token'
		)
);
/*
 * the below inbuild function with anonymous function will dynamicall calls the classes
 * based on its requirement.
 */
spl_autoload_register(function($class){
	require_once '/opt/lampp/htdocs/MySpace/Classes/'.$class.'.php';
});
require_once '/opt/lampp/htdocs/MySpace/Functions/sanitize.php';

if(cookie::exists(config::get('remember/cookie_name')) 
		&& !session::exists(config::get('session/session_name'))){
	$hash=cookie::get(config::get('remember/cookie_name'));
	$hashCheck=database::getInstance()->get('User_session', array('hash','=',$hash));
	
	if($hashCheck->counts()){
		$user=new user($hashCheck->first()->UserID);
		$user->login();
	}
	
}

?>