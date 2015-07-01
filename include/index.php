<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

// echo config::get('mysql/username');
/*
 * $users=database::getInstance()->query('SELECT FirstName,LastName FROM User');
 *
 * if($user->counts()){
 * foreach ($users as $user){
 * echo $user->FirstName.' '.$user->LastName;
 * }
 * }
 */
/*
 * //database::getInstance();
 * $user = database::getInstance()->query("SELECT FirstName,LastName from Users WHERE UserID=?",array(
 * 'pediredla'
 * ));
 */
// database::getInstance()->query("SELECT FirstName,LastName from Users");

// checking is made easy by using the get method defined in database class
// format get(tablename, conditions as array); for selecting user
// $user=database::getInstance()->get('Users',array('FirstName','=','Anil'));
// $user=database::getInstance()->query("SELECT * FROM Users;");
/*
 * if(!$user->counts()){
 * echo 'User not found!';
 * }else {
 * echo $user->first()->LastName;/*
 * foreach ($user->results() as $user){
 * echo $user->LastName.'<br>';
 * }'/
 * }
 */
// below is for insert/*
/*
 * $usersInsert=database::getInstance()->insert('Users',array(
 * 'FirstName'=>'Polo',
 * 'LastName'=>'Laddu',
 * 'password'=>'bandalanjakoduku'
 * ));
 * if($usersInsert){
 * echo 'Successfully added';
 * }
 */
/*
 * below is for update user's information. I can only use userID as reference to update
 */
/*
 * $userUpdate=database::getInstance()->update('Users','Sreeks',array(
 * 'Password'=>'newpassword',
 * 'CurrentTown'=>'Arlington'
 * ));
 * if($userUpdate){
 * echo 'Updated';
 * }else{
 * echo 'error';
 * }
 */
/*
 * if(session::exists('Sucess!')){
 * echo session::flash('Sucess!');
 * }
 */

if (session::exists ( 'home' )) {
	echo '<p>' . session::flash ( 'home' ) . '</p>';
}

$user = new user ();
if ($user->isLoggedIn ()) {
	
	?>
<link href="<?php echo 'style.css';?>" rel='stylesheet' type='text/css'>
<p>
	Hello <a
		href="profile.php?User=<?php echo escape($user->data()->UserID);?>"><?php echo escape($user->data()->FirstName);?></a>!
</p>
<ul>
	<li><a href="update.php">Update</a></li>
	<li><a href="logout.php">Logout</a></li>
	<li><a href="changepassword.php">Settings</a></li>
	<li><a href="friends.php">Friends directory</a></li>
	<!-- <li><a href="login2.php">Pending Requests</a> -->
</ul>
<form name="form" method="post" action="">
	<input type="text" name="FirstName" placeholder="Search">
<?php //<input type="text" name="LastName" placeholder="LastName">?>
<input type="submit" name="Search" value="Search">
</form>
<br>
<?php
	if (input::exists ()) {
		// echo 'in';
		$tem = input::get ( 'FirstName' );
		if ($user->search ( $tem )) {
			echo 'Users found are:'.'<br>';
			foreach ( $user->lists () as $one ) {
				
				//echo $one->UserID;  ?>
				<a href="profile.php?User=<?php echo escape($one->UserID);?>"><?php echo escape($one->UserID);?></a>
			<?php 
			}
			//session::flash ( 'home', 'Users found!' );
			//redirect::to ( 'index.php' );
		} else {
			echo 'User not found!!';
		}
	}
	
	if ($user->ShowRequests ( $user->data ()->UserID )) {
		// do something
		// print_r($user->requests());
		foreach ( $user->requests () as $request ) {
			
			?>
<div>
	<a href="profile.php?User=<?php echo escape($request->UserID);?>"><?php echo escape($request->UserID);?></a>
</div>
<Form name="form1" Method="Post" ACTION="">
	<Input type='Submit'
		Name='decision<?php echo escape($request->UserID)?>' value='Accept'><Input
		type='Submit' Name='decision<?php echo escape($request->UserID)?>'
		value='Reject'>
	<p>

</FORM>

<?php
			// echo $request->UserID . '<br>';
			if (input::exists ()) {
				$decision = input::get ( 'decision' . $request->UserID );
				if ($decision == 'Accept') {
					if ($user->AddFriendsTable ( $user->data ()->UserID, $request->UserID )) {
						session::flash ( 'home', 'Friend request Accepted!' );
						redirect::to ( 'index.php' );
					}
				} else if ($decision == 'Reject') {
					if ($user->RequestRejected ( $user->data ()->UserID, $request->UserID )) {
						session::flash ( 'home', 'Friend request Rejected!' );
						redirect::to ( 'index.php' );
					}
				}
			}
		}
	}
	if ($user->ShowPosts ( $user->data ()->UserID )) {
		
		// do something
		// echo "in";
		foreach ( $user->posts () as $post ) {
			
			// echo '<p class="post">' . $post->Text . '<br>' . 'posted by ' . '<a href="profile.php?User=' . $post->PostedBy . '"/>' . $post->PostedBy . '</a></p>';
			?>
<p class="post"><?php echo escape($post->Text);?></p>
posted by
<a href="profile.php?User=<?php echo escape($post->PostedBy);?>"><?php echo escape($post->PostedBy)?></a>


<form method="post">
	<div>
		<textarea rows="1" cols="30"
			name='text<?php echo escape($post->PostID);?>'></textarea>
		<br> <input type="submit" name='like<?php echo escape($post->PostID);?>' value="Like" /> <input
			type="submit" name="comment<?php echo escape($post->PostID);?>"
			value="Comment" />
	</div>
</form>

<?php
			if ($user->ShowComments ( $post->PostID )) {
				foreach ( $user->comments () as $comment ) {
					if ($comment [0] == $post->PostID) {
						// echo '<br>' . $comment [1]->Text . '<br>' . 'commented by ' . '<a href="profile.php?User=' . $comment [1]->CommentedBy . '"/>' . $comment [1]->CommentedBy . '</a>';
						?>
<p class="comment"><?php echo escape($comment[1]->Text)?></p>
commented by
<a
	href="profile.php?User=<?php echo escape($comment [1]->CommentedBy);?>"><?php echo escape($comment[1]->CommentedBy);?></a>

<?php
					}
				}
			}
			if (input::exists ()) {
				$comment = input::get ( 'text' . $post->PostID );
				if ($comment != null) {
					if ($user->WrtieComment ( $user->data ()->UserID, $post->PostID, $comment )) 

					{
						session::flash ( 'home', 'Comment added' );
						redirect::to ( 'index.php' );
					}
				}
				$like=input::get('like'.$post->PostID);
				if($user->Like($user->data()->UserID,$post->PostID)){
					//session::flash ( 'home', 'Post liked!' );
					redirect::to ( 'index.php' );
				}
			}
		}
		?>
<p>Special Query to see list of comments you've commented</p>
<?php
		if ($user->SpecialQuery ( $user->data ()->UserID )) {
			foreach ( $user->special1 () as $special ) {
				echo '<br>' . $special->Text . ' on post ID:' . $special->PostID;
			}
		}
		?>
<p>Special query2 to see posts before a date</p>
<form name="form2" method="post" action="">
	<div>
		<input type="text" name="collect" placeholder="Date"> <input
			type="submit" name="send" value="Search">
	</div>
</form>
<?php
		if (input::exists ()) {
			$val = input::get ( 'collect' );
			if ($user->SpecialQuery2 ( $user->data ()->UserID, $val )) {
				echo '<br>'.'posts are:'.'<br>';
				foreach ( $user->lists () as $list ) {
					echo $list->Text . ' posted on ' . $list->RecievedBy;
				}
				//session::flash ( 'home', 'Posts are:' );
				//redirect::to ( 'index.php' );
			}
		}
		?>

<?php
	}
	// $Lname=input::get('LastName');
	// echo $Fname." ".$Lname ;
	if ($user->hasPermission ( 'admin' )) {
		echo 'You are an admin';
	}
} else {
	echo '<p>' . 'You need to <a href="login.php"> login</a> or <a href="register.php">register</a>' . '</p>';
}

?>