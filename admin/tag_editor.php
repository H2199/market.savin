<?php
//DB connect +PWDs
require '../privat_info.php';
mysql_con();
$adm_pwd;
$adm_login;
require '../functions.php';
/**
clean_var($value)
random_pic_id()
show_tag_list($img)
tag_checkbox_select($img)
**/
session_start();
if($_SESSION['paswd']!=$adm_pwd || $_SESSION['login']!=$adm_login){
	header("location:login.php");
}

if(isset($_GET['action'])){
	switch($_GET['action']){
		case 'add_tag':
			$tablename="tags";
			$new_ru_tag = $_POST['new_ru_tag'];
			$new_en_tag = $_POST['new_en_tag'];
			$ins = "INSERT 
			INTO `$tablename` (`ru`, `en`) VALUES ('$new_ru_tag', '$new_en_tag')";
			mysql_query($ins)or die(mysql_error());	
			header("location:tag_editor.php");
		break;
		case 'delete_tag':
			$tablename="tags";
			foreach($_POST['tags'] as $tag){
				$del = "DELETE FROM `$tablename` WHERE id = '$tag'"; 
				mysql_query($del)or die(mysql_error());	
				header("location:tag_editor.php");
			}
		break;
	}
}
echo'
	<html>
		<head>
		</head>
			<body>	
				<form action="tag_editor.php?action=add_tag" method="post" >
				New RU tag:<input name="new_ru_tag" type="text" size="45";><br>
				New EN tag:<input name="new_en_tag" type="text" size="45";><br>
				Лучше заполнять и английскую тоже, в базе чет русский фигово отображается.<br>
				<input type="submit" value="Отправить"><br>

				</form>	
				<form	action="tag_editor.php?action=delete_tag" method="post" >
';
					echo tag_checkbox_select(0);
echo'
					<input type="submit" value="Удалить тэг"><br>
				</form>	
				<br><br><a href="login.php">LOGIN.PHP</a>				
			</body>
	</html>
';					
?>
