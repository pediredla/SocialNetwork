<?php
class user {
	private $_database, 
			$_data, 
			$_sessionName, 
			$_cookieName, 
			$_isLoggedIn, 
			$_requests = array (), 
			$_posts = array (), 
			$_comments = array (), 
			$_friends = array (), 
			$_mutual = array (), 
			$_special1 = array (), 
			$_special2 = array (),
			$_list=array();
	
	public function __construct($UserID = null) {
		$this->_database = database::getInstance ();
		$this->_sessionName = config::get ( 'session/session_name' );
		$this->_cookieName = config::get ( 'remember/cookie_name' );
		
		if (! $UserID) {
			if (session::exists ( $this->_sessionName )) {
				$UserID = session::get ( $this->_sessionName );
				if ($this->find ( $UserID )) {
					$this->_isLoggedIn = true;
				} else {
					// proceed to logout
					$this->logout ();
				}
			}
		} else {
			$this->find ( $UserID );
		}
	}
	
	public function data() {
		return $this->_data;
	}
	
	public function requests() {
		return $this->_requests;
	}
	
	public function posts() {
		return $this->_posts;
		$this->_posts=array();
	}
	
	public function comments() {
		return $this->_comments;
	}
	
	public function friends() {
		return $this->_friends;
	}
	
	public function mutual() {
		return $this->_mutual;
	}
	
	public function isLoggedIn() {
		return $this->_isLoggedIn;
	}
	
	public function special1() {
		return $this->_special1;
	}
	
	public function special2() {
		return $this->_special2;
	}
	
	public function lists(){
		return $this->_list;
	}
	
	public function create($fields) {
		if ($this->_database->insert ( 'Users', $fields )) {
			throw new Exception ( "Problem creating account" );
		}
	}
	
	public function find($UserID = null) {
		if ($UserID) {
			$field = (is_numeric ( $UserID )) ? 'node_id' : 'UserID';
			$data = $this->_database->get ( 'Users', array (
					$field,
					'=',
					$UserID 
			) );
			
			if ($data->counts ()) {
				$this->_data = $data->first ();
				return true;
			}
		}
		return false;
	}
	
	public function login($UserID = null, $Password = null, $remember = false) {
		// $user=$this->find($UserID);
		if (! $UserID && ! $Password && $this->exists ()) {
			session::put ( $this->_sessionName, $this->data () );
		} else {
			$user = $this->find ( $UserID );
			// print_r($user);
			// print_r($this->_data);
			if ($user) {
				if ($this->data ()->Password === hash::make ( $Password )) {
					echo 'ok!';
					// need to check the node_id/UserID
					session::put ( $this->_sessionName, $this->data ()->node_id );
					if ($remember) {
						$hash = hash::unique ();
						$hashCheck = $this->_database->get ( 'User_session', array (
								'UserID',
								'=',
								$this->data ()->node_id 
						) );
						
						if (! $hashCheck->counts ()) {
							$this->_database->insert ( 'User_session', array (
									'userID' => $this->data ()->SessionID,
									'Hash' => $hash 
							) );
						} else {
							$hash = $hashCheck->first ()->hash;
						}
						
						cookie::put ( $this->_cookieName, $hash, config::get ( 'remember/cookie_expiry' ) );
					}
					return true;
				}
			}
		}
		return false;
	}
	
	public function ShowMutualFriends($user1 = null, $user2 = null) {
		$mutual = $this->_database->query ( "SELECT V1.FriendsWith FROM (SELECT * FROM Friends WHERE UserID='$user1') AS V1 INNER JOIN (SELECT * FROM Friends WHERE UserID='$user2') AS V2 ON V1.FriendsWith=V2.FriendsWith" );
		if ($mutual->counts ()) {
			$results = $mutual->results ();
			foreach ( $results as $result ) {
				array_push ( $this->_mutual, $result );
			}
			return true;
		}
		return false;
	}
	
	public function SpecialQuery($user) {
		$query = $this->_database->query ("SELECT * FROM Post p JOIN Comment c ON p.PostID=c.PostID WHERE C.CommentedBy='$user'");
		if ($query->counts ()) {
			$results = $query->results ();
			foreach ( $results as $result ) {
				array_push ( $this->_special1, $result );
			}
			return true;
		}
		return false;
	}
	
	public function SpecialQuery2($user,$date){
		$query=$this->_database->query("SELECT p.Text, p.PostedBy, p.RecievedBy FROM Post p JOIN Users u on u.UserID=p.PostedBy WHERE p.DateCreated <='$date' AND u.UserID='$user'");
		if($query->counts()){
			$results=$query->results();
			foreach ($results as $result){
				array_push($this->_special2, $result);
				print_r($result);
			}
		}
		return false;
	}
	
	public function exists() {
		return (! empty ( $this->_data )) ? true : false;
	}
	
	public function logout() {
		$this->_database->delete ( 'User_session', array (
				'UserID',
				'=',
				$this->data ()->UserID 
		) );
		
		session::delete ( $this->_sessionName );
		cookie::delete ( $this->_cookieName );
	}
	
	public function update($fields = array(), $userID = null) {
		if (! $userID && $this->isLoggedIn ()) {
			$userID = $this->data ()->UserID;
		}
		if (! $this->_database->update ( 'Users', $userID, $fields )) {
			throw new Exception ( 'There is a problem in updating the information' );
		}
	}
	
	public function hasPermission($key) {
		$group = $this->_database->get ( 'DB_Group', array (
				'DBID',
				'=',
				$this->data ()->Group 
		) );
		
		if ($group->counts ()) {
			$permissions = json_decode ( $group->first ()->Permissions, true );
			if ($permissions [$key] == true) {
				return true;
			}
		}
		return false;
	}
	
	public function search($user = null) {
		$query=$this->_database->query("SELECT * FROM Users WHERE UserID LIKE '$user'");
		if($query->counts()){
			$results=$query->results();
			foreach ($results as $result){
				array_push($this->_list, $result);
				//echo $result->UserID;
			}
			return true;
		}
		return false;
	}
	
	public function isFriend($user1 = null, $user2 = null) {
		if ($user1 != $user2) {
			$friend = $this->_database->query ( "SELECT * FROM Friends WHERE UserID='$user1' AND FriendsWith='$user2'" );
			if ($friend->counts ()) {
				// $this->_data = $friend->first ();
				// echo 'Is friend';
				// do something
				return true;
			}
		} else {
			return true;
		}
		return false;
	}
	
	public function AddFriend($user1 = null, $user2 = null) {
		$sendRequest = $this->_database->insert ( 'Requests', array (
				'UserID' => $user1,
				'FriendsWith' => $user2,
				'Status' => 'pending' 
		) );
		
		if ($sendRequest) {
			return true;
		}
		return false;
	}
	
	public function ShowRequests($user1 = null) {
		$requests = $this->_database->query ( "SELECT * FROM Requests WHERE FriendsWith='$user1' AND Status='pending'" );
		if ($requests->counts ()) {
			$results = $requests->results ();
			foreach ( $results as $result ) {
				// echo $result->UserID.' has sent you a friend request<br>';
				array_push ( $this->_requests, $result );
			}
			return true;
		}
		return false;
	}
	
	public function ShowPosts($user = null) {
		$request = $this->_database->query ( "SELECT * FROM Post WHERE RecievedBy='$user'" );
		if ($request->counts ()) {
			$results = $request->results ();
			foreach ( $results as $result ) {
				// echo '<p>'.$result->Text.'<p>'.'posted by'.'<br>'.$result->PostedBy.'</p>'.'</p>';
				array_push ( $this->_posts, $result );
			}
			return true;
		}
		return false;
	}
	
	public function AddFriendsTable($user1 = null, $user2 = null) {
		$updateFriends = $this->_database->insert ( 'Friends', array (
				'UserID' => $user1,
				'FriendsWith' => $user2,
				'Date' => date ( "Y-m-d H:i:s" ) 
		) );
		$updateFriends2 = $this->_database->insert ( 'Friends', array (
				'UserID' => $user2,
				'FriendsWith' => $user1,
				'Date' => date ( "Y-m-d H:i:s" ) 
		) );
		if ($updateFriends && $updateFriends2) {
			$updateRequests = $this->_database->query ( "UPDATE Requests SET Status='Accepted' WHERE UserID='$user2' AND FriendsWith='$user1'" );
			if ($updateRequests->counts ()) {
				return true;
			}
		}
		return false;
	}
	
	public function RequestRejected($user1 = null, $user2 = null) {
		$update = $this->_database->query ( "UPDATE Requests SET Status='Rejected' WHERE UserID='$user2' AND FriendsWith='$user1'" );
		
		if ($update->counts ()) {
			return true;
		}
		return false;
	}
	
	public function WritePost($user1 = null, $user2 = null, $text = "") {
		if ($user1 != $user2) {
			if ($this->isFriend ( $user1, $user2 )) {
				$post = $this->_database->insert ( 'Post', array (
						'PostedBy' => $user1,
						'RecievedBy' => $user2,
						'Text' => $text,
						'DateCreated' => date ( "Y-m-d H:i:s" ) 
				) );
				if ($post) {
					return true;
				}
			}
		} else {
			$post = $this->_database->insert ( 'Post', array (
					'PostedBy' => $user1,
					'RecievedBy' => $user2,
					'Text' => $text,
					'DateCreated' => date ( "Y-m-d H:i:s" ) 
			) );
			if ($post) {
				return true;
			}
		}
		return false;
	}
	
	public function WrtieComment($user = null, $post = null, $text = "") {
		if ($post != null) {
			$comment = $this->_database->insert ( 'Comment', array (
					'PostID' => $post,
					'CommentedBy' => $user,
					'Text' => $text 
			) );
			if ($comment) {
				return true;
			}
		}
		
		return false;
	}
	
	public function ShowComments($post = null) {
		$request = $this->_database->query ( "SELECT * FROM Comment WHERE PostID='$post'" );
		if ($request->counts ()) {
			$results = $request->results ();
			foreach ( $results as $result ) {
				// echo '<p>'.$result->Text.'<p>'.'posted by'.'<br>'.$result->PostedBy.'</p>'.'</p>';
				array_push ( $this->_comments,array($post, $result ));
			}
			return true;
		}
		return false;
	}
	
	public function ShowFriends($user = null) {
		$friends = $this->_database->query ( "SELECT * FROM Friends WHERE UserID='$user'" );
		if ($friends->counts ()) {
			$results = $friends->results ();
			foreach ( $results as $result ) {
				array_push ( $this->_friends, $result );
			}
			return true;
		}
		return false;
	}
	
	public function Like($user = null, $post = null) {
		if ($post != null) {
			$like = $this->_database->insert ( 'Likes', array (
					'PostID' => $post,
					'LikedBy' => $user 
			) );
			if ($like) {
				return true;
			}
		}
		
		return false;
	}
}