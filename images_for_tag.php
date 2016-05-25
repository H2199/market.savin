<?php
require 'privat_info.php';
mysql_con();
$adm_pwd;
$adm_login;
session_start ();
$session_pwd   =  isset($_SESSION['paswd'])	? $_SESSION['paswd'] : '';
$session_login =  isset($_SESSION['login'])	? $_SESSION['login'] : '';
$auth = ($session_pwd==$adm_pwd && $session_login==$adm_login)? true : false;

require 'functions.php';
/**
clean_var($value)
random_pic_id()
show_tag_list($img)
tag_checkbox_select($img)
**/

function show_previews($tag_id, $amount, $all){
	$images = '';
	if($all == true){
		$query = "SELECT N, file FROM images WHERE moderation = 1 LIMIT 0, $amount ";
	}else{
			$query = "SELECT images.N, images.file FROM images, tag_relation WHERE tag_relation.image_id = images.N AND tag_relation.tag_id = '$tag_id'";
		}
	$q = mysql_query($query)or die(mysql_error());
	$count = mysql_num_rows($q);
	if($count==0){
		$images .='No images to show';
	}else{
			$n =1;
			while ($f = mysql_fetch_array($q)){
				$files[$n]['N'] = $f[0];
				$files[$n]['file'] = $f[1];
				$n++;
			}
			shuffle($files);
			$tag_id = $tag_id==0  ?  ''  :  '&tag='.$tag_id.'';
			for ($i = 0; $i <$count; $i++) {
				$images .= '<a href="index.php?image='.$files[$i]['N'].$tag_id.'"><img src="small_images/'.$files[$i]['file'].'"></a>';
			}
		}
	return $images;
}

if(isset($_GET['tag'])&&!empty($_GET['tag'])){
	$tag_id = clean_var($_GET['tag']);
	$find_tag_info = mysql_query("SELECT id FROM tags WHERE id = '$tag_id' ")or die(mysql_error());
	$count = mysql_num_rows($find_tag_info);
	if($count!=0){
		$tag = $_GET['tag'];
	}else{$tag = '';}
}else{$tag = '';}

?>
<html>
	<head>
		<title>
		 Картинка №1
		</title>
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css" />
		<script type="text/javascript" src="/js/resize.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
	</head>
	<body>
		<div id="current_tag">
			<p>We are looking at:</p>
				<?php
					$tag_info; // id ru en
					$find_tags_info = mysql_query("SELECT * FROM tags")or die(mysql_error());
					$tag_list = '';
					if($tag == '') {$tag_list = '<li><a href="images_for_tag.php">All photos</a></li>'.$tag_list;}
					while($tags = mysql_fetch_array($find_tags_info)){
						$first_img_tag_id = take_pic_id('first',0,$tags['id']);
						if($tags['id'] == $tag){
							$tag_list = '<li><a href="images_for_tag.php?tag='.$tags['id'].'">'.$tags['en'].'</a></li>'.$tag_list;
						}else{
								$tag_list .= '<li><a href="images_for_tag?tag='.$tags['id'].'">'.$tags['en'].'</a></li>';
							}
					}
					if($tag != ''){$tag_list .= '<li><a href="images_for_tag.php">All photos</a></li>';}
					$tag_list = '<ul>'.$tag_list.'</ul>';
					echo $tag_list;
				?>
		</div>
		<div id="previews">
			<?php
				if(isset($_GET['tag'])){
					$tag = clean_var($_GET['tag']);
					echo show_previews($tag, 100, false);
				}else{
						echo show_previews(0, 100, true);
					}
			?>
		</div>
		<div id="random">
			<a href="index.php?image=<?php echo random_pic_id();?>">random picture</a>
		</div>
		<div class="share42init"></div>
		<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
		<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
		<div id="footer">
			<a href="about_author.php">ABOUT AUTHOR</a>
		</div>
	</body>
</html>