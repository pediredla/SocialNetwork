<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

$user=new user();
if($user->isLoggedIn()){
	if($user->ShowPosts($user->data()->UserID)){
		print_r($user->posts());
		foreach ($user->posts() as $post){
			echo $post->Text.'<br>';
			if($user->ShowComments($post->PostID)){
				foreach ($user->comments() as $comment){
					echo '<br>';
					
					//echo '<br>';
					if($comment[0]==$post->PostID){
						//print_r($comment);
						echo '<br>'.$comment[1]->Text.'<br>';
					}
				}
			}
		}
	}
}

?>


	
