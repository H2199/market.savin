<?php
//DB connect +PWDs
require '../privat_info.php';
mysql_con();
$adm_pwd;
$adm_login;

session_start();
if($_SESSION['paswd']!=$adm_pwd || $_SESSION['login']!=$adm_login){
	header("location:login.php");
}

if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'send':
			$tablename="images";
			$N = $_POST['N'];
			$description = $_POST['description'];
			$about = $_POST['about'];
			$encore = $_POST['encore'];
			$up = "UPDATE `$tablename`  SET `description` = '$description', `about` = '$about', `encore` = '$encore', `moderation` = '1' WHERE `N` = '$N'";
			mysql_query($up)or die(mysql_error());		
			header("location:moderation.php");
		break;
		case 'delete':
			$tablename="images";
			$N=$_POST['N'];
			if(strlen($description)>150||strlen($about)>150||strlen($encore)>25){
				echo'Данные были введены неправильно <a href="moderation.php">назад</a>';	
			}
			else{
				$up = "UPDATE `$tablename`  SET `moderation` = '2' WHERE `N` = '$N'"; 
				mysql_query($up)or die(mysql_error());	
				header("location:moderation.php");
			}
		break;
	}
}

$tablename="images";
$q = mysql_query("SELECT * FROM $tablename where moderation = 0 LIMIT 0, 1", $lnk) //N, file, description, about, encore, ?links, date, moderation, ip, nik
	or die("Invalid query: " . mysql_error());
$f = mysql_fetch_array($q);
echo'
	<html>
		<head>
		</head>
			<body>	
				<img src="../images/'.$f['file'].'">
				Опубликовано:<br>
				<ul>
					<li>N: '.$f['N'].'</li>
					<li>ip: '.$f['ip'].'<br></li>
					<li>nik: '.$f['nik'].'<br></li>
					<li>date: '.$f['date'].'</li>
				</ul>
				<form action="moderation.php?action=send" method="post" >

				Описание под картинкой:<br><textarea rows="10" cols="45" name="about");>'.$f['about'].'</textarea><br>
				Title:<br><textarea rows="10" cols="45" name="description");>'.$f['description'].'</textarea><br>
				Encore:<input name="encore" type="text" size="45";><br>
				<input name="N" type="hidden" value="'.$f['N'].'" >
				<input name="action" type="hidden" value="send" >
				<input type="submit" value="Отправить"><br>

				</form>	
				<form	action="moderation.php?action=delete" method="post" >
					<input name="N" type="hidden" value="'.$f['N'].'" >
					<input name="action" type="hidden" value="delete" >
					<input type="submit" value="Удалить"><br>
				</form>
				<br><a href="login.php">Тут тоже жмякай для попадания на админское меню</a>
			</body>
	</html>
';					
?>
