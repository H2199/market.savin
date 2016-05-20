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

function show_upload_form(){	
	echo '
			<form action="#" method="post" enctype="multipart/form-data">
				Ник:<input name="nik" type="text" size="25"><br>
				<input type="file" id="file" name="files[]" multiple="multiple" accept="image/*" /><br>
				<input type="submit" value="Upload multiple files!" />
			</form>
			<br><br><a href="login.php">Жмякни меня и полетишь на админскую менюшку</a>
		';
}
?>

<html lang="ru">
	<head>
	</head>
	<body>
	<?php
		if(isset($_FILES['files'])){
				$valid_formats = array("jpg", "png", "gif");
				$max_file_size = 5*1024*1024; //1mb
				$path = "../images/"; // Upload directory
				$small_img_path = "../small_images/";
				$count = 0;
				// Loop $_FILES to exeicute all files
				foreach ($_FILES['files']['name'] as $f => $name) {     
					if ($_FILES['files']['error'][$f] == 4) {
						continue; // Skip file if any error found
					}
					if ($_FILES['files']['error'][$f] == 0) {	 
						$file_exstension = pathinfo($name, PATHINFO_EXTENSION);
						if ($_FILES['files']['size'][$f] > $max_file_size) {
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
							if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path.$filename)){
								
								
								$count++; // Number of successfully uploaded file
								date_default_timezone_set('Europe/Moscow');
								$date = date("H:i:s,m.d.y");
								$ip = $_SERVER['REMOTE_ADDR'];
								$nik = $_POST['nik'];
								$tablename = 'images';
								$ins = "INSERT INTO `$tablename` (`file`, `ip`, `nik`) VALUES ( '$filename ',  '$ip',  '$nik')";
								mysql_query($ins)or die(mysql_error());
								list($width_i, $height_i, $type) = getimagesize($path.$filename);
								//resize to smaller pic
								if($height_i>=1000 || $width_i>=1000){
									if($width_i >= $height_i){
										resize($path.$filename, $path.$filename, 1000, 0);
									}else{
											resize($path.$filename, $path.$filename, 0, 1000);
										}
								}
								//Make preview for tag_search.php
								if($width_i>$height_i){
									//image is horizontal
									$margin = ($width_i-$height_i)/2;
									crop($path.$filename, $small_img_path.$filename, array($margin, 0, $height_i, $height_i),false); //square; each side equal to height size
								}elseif($width_i<$height_i){
										//image is vertical
										$margin = ($height_i-$width_i)/2;
										crop($path.$filename, $small_img_path.$filename, array(0, $margin, $width_i, $width_i),false);
									}
								if($height_i>=200 && $width_i>=200){
									resize($small_img_path.$filename, $small_img_path.$filename, 200, 200);
								}
							}
						}
					}
				}
				echo $count.' фото загружено, красавчик ;) <a href="upload.php">Обновим страничку</a>';
		}
		else{
				show_upload_form();
			}
	?>
	</body>
</html>