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
		<link href="css/lightbox.css" = type="text/css" rel="stylesheet" media="all" />
		<link href="css/style.css" = type="text/css" rel="stylesheet" media="all" />
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<script src="/js/resize.js" type="text/javascript" ></script>
	</head>
	<body>
		<?php if($auth == false){include 'yandex_metrika.php';}?>
		<div id="menu_button">
			<div class="line"></div>
			<div class="line"></div>
			<div class="line"></div>
			<div id="menu">
				<ul>
					<a href="images_for_tag.php" ><li>Gallery: show all</li></a>
					<a href="gallery.php" ><li>Gallery: one by one</li></a>
					<a href="images_for_tag.php?tag=32" ><li>Exhibition participation</li></a>
					<a href="index.php" ><li>About author</li></a>
				</ul>
			</div>
		</div>
		<div id="current_tag">
			<p>We are looking at:</p>
				<?php
					$tag_info; // id ru en
					$find_tags_info = mysql_query("SELECT * FROM tags")or die(mysql_error());
					$tag_list = '';
					if($tag == '') {$tag_list = '<li>All photos</li>'.$tag_list;}
					while($tags = mysql_fetch_array($find_tags_info)){
						$first_img_tag_id = take_pic_id('first',0,$tags['id']);
						if($tags['id'] == $tag){
							$tag_list = '<li>'.$tags['en'].'</li>'.$tag_list;
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
				}else{echo show_previews(0, 100, true);}
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
		<script src="/js/lightbox.js"type="text/javascript" ></script>
	</body>
</html>