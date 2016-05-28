<?php
require '../privat_info.php';
$adm_pwd;
$adm_login;
session_start ();
$session_pwd = isset($_SESSION['paswd'])?$_SESSION['paswd']:'';
$session_login = isset($_SESSION['login'])?$_SESSION['login']:'';
$post_pwd = isset($_POST['paswd'])?$_POST['paswd']:'';
$post_login = isset($_POST['login'])?$_POST['login']:'';

if($session_pwd!=$adm_pwd || $session_login!=$adm_login){
	
	if($post_pwd!= $adm_pwd || $post_login!= $adm_login){ 
		?>
			<form method="POST" action="login.php">
				login	<input type="text" name="login" size ="15"><br>
				password<input type="password" maxlength="10" name="paswd"size ="15">
						<input type="submit" value="LOGin">
			</form>
		<?php
	}else{
			$_SESSION['paswd']=$_POST['paswd'];
			$_SESSION['login']=$_POST['login'];
			echo'<a href="../gallery.php">MAIN</a><br>';
			echo'<a href="moderation.php">Moderation</a><br>';
			echo '<a href="upload.php">Upload image</a><br>';
			echo '<a href="tag_editor.php">TAG editor</a><br>';
			echo '<a href="exit.php">exit</a>';
		}
}else{
		echo'<a href="../gallery.php">MAIN</a><br>';
		echo'<a href="moderation.php">Moderation</a><br>';
		echo '<a href="upload.php">Upload image</a><br>';
		echo '<a href="tag_editor.php">TAG editor</a><br>';
		echo '<a href="exit.php">exit</a>';
	}
?>
