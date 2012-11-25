<?php
require_once('../inc/header.php');

if (isset($_POST['user']) && isset($_POST['pass'])){
	$user = $_POST['user'];
	$pass = $_POST['pass'];

	if(!empty($user) && !empty($pass)){
		require_once('../sqldb/connect.php');
		$sql = db_connect('ppl', 'main');
		
		$user = $sql->real_escape_string($user);
		$pass = $sql->real_escape_string($pass);
		
		if($users = $sql->query("SELECT uid, user, name FROM `user` WHERE user='$user' AND pass=MD5('$pass') LIMIT 1")){
			//var_dump($users);
			if($result = $users->fetch_row()){
				//var_dump($result);
				$_SESSION['uid'] = $result[0];
				if($result[2]){
					$_SESSION['name'] = $result[2];	// custom name
				}else{
					$_SESSION['name'] = $result[1];	// maintain original username case
				}
				session_write_close();
				header('Location: /');
			}else{
				echo $sql->error;
				echo 'login failed!';
			}
		}else{
			echo $sql->error;
		}
	}else{
		if(empty($user))
			$emptyUser = '<span style="color: red">* field cannot be empty</span>';
		if(empty($pass)) 
			$emptyPass = '* field cannot be empty';
	}
}

if (!isset($_SESSION['uid'])):?>
	<h3>Sign in<noscript> (javascript required)</noscript></h3><hr />
	<form action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='POST'>
		<label>Username:</label><input type='text' name='user'><?php if(isset($emptyUser)) echo$emptyUser?><br />
		<label>Password:</label><input type='password' name='pass'><?php if(isset($emptyPass)) echo$emptyPass?><br />
		<input type='submit' value='post' />
		<a href="/user/register.php">Sign Up</a>
	</form>
<?php else:?>
	You are signed in!<br />
	Did you mean: <a href="/user/logout.php?s=<?= rand(10000, 10000000) ?>">Logout</a>?
<?php endif;

include_once('../inc/footer.php');
?>