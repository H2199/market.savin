<?php
//DB connect +PWDs
require '../privat_info.php';
mysql_con();
$adm_pwd;
$adm_login;
require 'img_handlers.php';

session_start();
if($_SESSION['paswd']!=$adm_pwd || $_SESSION['login']!=$adm_login){
	header("location:login.php");
}

switch($_POST["action"]){
	case 1:
		if(isset($_FILES['upload'])){
			$img_id = isset($_POST["N"])?'?image='.$_POST["N"]:'';
			$valid_formats = array("jpg", "png", "gif");
			$max_file_size = 10*1024*1024; //1mb
			$path = "../images/"; // Upload directory
			$small_img_path = "../small_images/";
			$N=$_POST["N"];
			$redirect = "location:../gallery.php$img_id";
			

			// upload only 1 file
			$name = $_FILES['upload']['name'];
			if ($_FILES['upload']['error'] == 4) {
				continue; // Skip file if any error found
			}
			if ($_FILES['upload']['error'] == 0) {
				$file_exstension = pathinfo($name, PATHINFO_EXTENSION);
				if ($_FILES['upload']['size'] > $max_file_size) {
					$message[] = "$name is too large!.";
					continue; // Skip large files
				}
				elseif(!in_array($file_exstension, $valid_formats) ){
					$message[] = "$name is not a valid format";
					continue; // Skip invalid file formats
				}
				else{ // No error found! Move uploaded files 					
					while (true) {
						$filename = 'image'.uniqid(rand(), true).'.'.$file_exstension;
						if (!file_exists($path.$filename)) break;
					}
					if(move_uploaded_file($_FILES["upload"]["tmp_name"], $path.$filename)){
						date_default_timezone_set('Europe/Moscow');
						$date = date("H:i:s,m.d.y");
						$ip = $_SERVER['REMOTE_ADDR'];
						$tablename='images';
						$up = "UPDATE `$tablename`  SET `file` = '$filename' WHERE `N` = '$N'"; 
						mysql_query($up)or die(mysql_error());
						
						list($width_i, $height_i, $type) = getimagesize($path.$filename);
						//resize to smaller pic
						if($height_i>=1000 || $width_i>=1000){
							if($width_i >= $height_i){
								resize($path.$filename, $path.$filename, 1000, 0, false);
							}else{
									resize($path.$filename, $path.$filename, 0, 1000, false);
								}
						}
						list($width_i, $height_i, $type) = getimagesize($path.$filename);// take changed size
						//Make preview for tag_search.php
						if($width_i>$height_i){
							//image is horizontal
							$margin = round(($width_i-$height_i)/2);
							crop($path.$filename, $small_img_path.$filename, array($margin, 0, $height_i, $height_i),false); //square; each side equal to height size
						}elseif($width_i<$height_i){
								//image is vertical
								$margin = round(($height_i-$width_i)/2);
								crop($path.$filename, $small_img_path.$filename, array(0, $margin, $width_i, $width_i),false);
							}else{
									resize($path.$filename, $small_img_path.$filename, 200, 200, false);
								}
						if($height_i>=200 && $width_i>=200){
							resize($small_img_path.$filename, $small_img_path.$filename, 200, 200, false);
						}
						
						header("$redirect");
					}
				}
			}
			
			header("$redirect");
		}
		header("$redirect");
	break;
	case 2:
		if(isset($_POST["about"])||isset($_POST["title"])||isset($_POST["N"])||isset($_POST["tags"])){
			$img_id = isset($_POST["N"])?'?image='.$_POST["N"]:'';
			$tablename="images";
			$title=$_POST["title"];
			$description=$_POST["description"];
			$about=$_POST["about"];
			$N=$_POST["N"];
			$redirect = "location:../gallery.php$img_id";
			$up = "UPDATE `$tablename`  SET `title` = '$title', `about` = '$about', `description` = '$description' WHERE `N` = '$N'";
			mysql_query($up)or die(mysql_error());

			$query=mysql_query("SELECT * FROM tags")or die(mysql_error());
			while($info = mysql_fetch_array($query)){
				if(in_array($info['id'], $_POST['tags'])){
					//should be connected
					$query2 = 'SELECT * FROM tag_relation WHERE `image_id`= "'.$N.'" AND `tag_id`="'.$info['id'].'"';
					$q2 = mysql_query($query2)or die(mysql_error());
					$count = mysql_num_rows($q2);
					if($count == 0){
						$ins = 'INSERT INTO `tag_relation` (`image_id`,`tag_id`) VALUES ("'.$N.'","'.$info['id'].'")';
						mysql_query($ins)or die(mysql_error());
					}//die(''.$img_id);
					
					
				}else{
						//shouldn't be connected
						$query3 = 'SELECT * FROM tag_relation WHERE `image_id`= "'.$N.'" AND `tag_id`="'.$info['id'].'"';
						$q3 = mysql_query($query3)or die(mysql_error());
						$count = mysql_num_rows($q3);
						if($count > 0){
							$del = 'DELETE FROM `tag_relation` WHERE `image_id`="'.$N.'" AND `tag_id`="'.$info['id'].'"';
							mysql_query($del)or die(mysql_error());
						}
					}
			}
			header("$redirect");
		}else{header("$redirect");}
	break;
	case 3:
		if (isset($_POST["N"]) && !empty($_POST["N"])){
			$tablename="images";
			$N=$_POST["N"];
			$up = "UPDATE `images`  SET  `moderation` = '2' WHERE `N` = '$N'";
			mysql_query($up)or die(mysql_error());
			header("location:../images_for_tag.php");
		}
	break;
	case 4:
		if(isset($_POST["title"])||isset($_POST["description"])||isset($_POST["text"])||isset($_POST["tag_id"])){
			$title = $_POST["title"];
			$description = $_POST["description"];
			$text = $_POST["text"];
			$tag_id = $_POST["tag_id"];
			$up = "UPDATE `tags`  SET  `en_title` = '$title', `en_description` = '$description', `en_text` = '$text' WHERE `id` = '$tag_id'";
			mysql_query($up)or die(mysql_error());
			header("location:../images_for_tag.php?tag=$tag_id");
		}else{header("location:../image_for_tag.php?tag=$tag_id");}
	break;
}
?>
