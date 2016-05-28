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
				$images .= '<a href="gallery.php?image='.$files[$i]['N'].$tag_id.'"><img src="small_images/'.$files[$i]['file'].'"></a>';
			}
		}
	return $images;
}

if(isset($_GET['tag'])&&!empty($_GET['tag'])){
	$tag_id = clean_var($_GET['tag']);
	$find_tag_info = mysql_query("SELECT * FROM tags WHERE id = '$tag_id' ")or die(mysql_error());
	$count = mysql_num_rows($find_tag_info);
	if($count!=0){
		$current_tag_info = mysql_fetch_array($find_tag_info);
		$tag = $_GET['tag'];
	}else{
			$tag = '';
		}
}else{
		$tag = '';
	}

?>
<html>
	<head>
		<title>
			<?php
				if($tag!=''){echo $current_tag_info['en_title'];}
				else{echo'';} 
			?>
		</title>
		<meta name="description" content="
			<?php
				if($tag!=''){echo $current_tag_info['en_description'];}
				else{echo'';}
			?>
		" >
		<?php if($auth==true){echo'<link type="text/css" rel="stylesheet" media="all" href="css/admin_style.css">';}?>
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css" />
		<script type="text/javascript" src="/js/resize.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
	</head>
	<body>
		<?php if($auth == false){include 'yandex_metrika.php';}?>
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
								$tag_list .= '<li><a href="images_for_tag.php?tag='.$tags['id'].'">'.$tags['en'].'</a></li>';
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
		<?php if($auth==true){ include 'admin/update_tag_form.php';} ?>
		<!--
			<div id="random">
				<a href="gallery.php?image=<?php echo random_pic_id();?>">random picture</a>
			</div>
		-->
		<div id="text_on_tags_place">
			<?php
				if($tag!=''){echo $current_tag_info['en_text'];}
				else{echo'';}
			?>
		</div>
		<div class="share42init" onclick="javascript:yaCounter37608890.reachGoal('tag_page_share');"></div>
		<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
		<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
		<div id="footer">
			<a href="index.php">ABOUT AUTHOR</a>
		</div>
	</body>
</html>