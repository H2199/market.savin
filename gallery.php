﻿<?php
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

function make_next_prev_link($prev, $next, $tag_id){
	global $prev_link;
	global $next_link;
	if(!empty($tag_id)){$tag_lnk = '&tag='.$tag_id;}else{$tag_lnk='';}
	$prev_link='<a href="gallery.php?image='.$prev.$tag_lnk.'"><img src="img/arrow/al'.rand(1,8).'.png" alt="" border="0"></a>'; 
	$next_link ='<a href="gallery.php?image='.$next.$tag_lnk.'"><img src="img/arrow/ar'.rand(1,8).'.png" alt="" border="0"></a>';
}

if(isset($_GET['tag'])&&!empty($_GET['tag'])){
	$tag_id = clean_var($_GET['tag']);
	$find_tag_info = mysql_query("SELECT id FROM tags WHERE id = '$tag_id' ")or die(mysql_error());
	$count = mysql_num_rows($find_tag_info);
	if($count!=0){
		$tag = $_GET['tag'];
		$tag_lnk = '&tag='.$tag;
	}else{
			$tag = '';
			$tag_lnk='';
		}
}else{
		$tag = '';
		$tag_lnk='';
	}

$first_img_id = take_pic_id('first',0,$tag);//take first pic
$last_img_id = take_pic_id('last',0,$tag);//take last pic

if(isset($_GET['image'])&&!empty($_GET['image'])){
	$img_N = clean_var($_GET['image']);
	$find_image = mysql_query("SELECT COUNT(*) FROM images WHERE moderation = 1 AND N = '$img_N' ")or die(mysql_error());
	$count = mysql_num_rows($find_image);
	if($count==0){ header('Location: gallery.php'); }
}else{
		$img_N = $first_img_id;
	}

	
//next and prev link generator
switch (true){ 
	case ($img_N == $first_img_id && $img_N == $last_img_id): //Only one image is available
		make_next_prev_link($img_N, $img_N, $tag);
	break;
	
	case ($img_N == $first_img_id): // First image case
		$next_img_id = take_pic_id('next',$img_N,$tag);
		make_next_prev_link($last_img_id, $next_img_id, $tag);
	break;
	
	case ($img_N == $last_img_id): // Last image case
		$prev_img_id = take_pic_id('prev',$img_N,$tag);
		make_next_prev_link($prev_img_id, $first_img_id, $tag);
	break;
	
	case ($img_N<$last_img_id and $img_N>$first_img_id): // Somewhere in between
		$next_img_id = take_pic_id('next',$img_N,$tag);
		$prev_img_id = take_pic_id('prev',$img_N,$tag);
		make_next_prev_link($prev_img_id, $next_img_id, $tag);
	break;
}

//TAKE INFO ABOUT pic on the page
$q = mysql_query("SELECT * FROM images WHERE moderation = 1 AND N = '$img_N' ", $lnk)
	or die("Invalid query: " . mysql_error());
$img_info=mysql_fetch_array($q);
?>

<html lang="ru">
	<head>
		<?php if($auth==true){echo'<link type="text/css" rel="stylesheet" media="all" href="css/admin_style.css">';}?>
		<meta name="description" content="Photo №<?php echo $img.$img_info['description'];?> " >
		<title> <?php echo $img_info["title"]?> </title>
		<!--<script type="text/javascript" src="/js/resize.js"></script>-->
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<?php if($auth == false){include 'yandex_metrika.php';}?>
		<div id="header">
		</div>
			<div class="left">
				<?php echo $prev_link; ?>
			</div>
		<div class="right">
			<?php echo $next_link; ?>
		</div>
		
		<div id="center">
			<div id="current_tag">
				<p>We are looking at:</p>
					<?php
						$find_tags_info = mysql_query("SELECT * FROM tags")or die(mysql_error());
						$tag_list = '';
						if($tag == '') {$tag_list = '<li><a href="gallery.php">All photos</a></li>'.$tag_list;}
						while($tags = mysql_fetch_array($find_tags_info)){
							$first_img_tag_id = take_pic_id('first',0,$tags['id']);
							if($tags['id'] == $tag){
								$tag_list = '<li><a href="gallery.php?image='.$img_N.'&tag='.$tags['id'].'">'.$tags['en'].'</a></li>'.$tag_list;
							}else{
									$tag_list .= '<li><a href="gallery.php?image='.$first_img_tag_id.'&tag='.$tags['id'].'">'.$tags['en'].'</a></li>';
								}
						}
						if($tag != ''){$tag_list .= '<li><a href="gallery.php">All photos</a></li>';}
						$tag_list = '<ul>'.$tag_list.'</ul>';
						echo $tag_list;
					?>
			</div>
			<div id="content">
				<div id="main_img">
					<a href="gallery.php?image=<?php echo take_pic_id('next',$img_N,$tag); echo $tag_lnk;?>">
						<img src="images/<?php echo $img_info["file"]; ?>" alt="<?php echo $img_info["title"]; ?>">
					</a>
				</div>
				<?php if($auth==true){ include 'admin/update_form.php';} ?>
			</div>
			<!--
				<div id="random">
					 <a href="gallery.php?image=<?php echo random_pic_id();?>">random picture</a> 
					
				</div>
			-->
			<div id="order_button"><a onclick="javascript:yaCounter37608890.reachGoal('open_gallery_form');" target="_blank" href="index.php?buy&link=http://market.savin.fi/gallery.php?image=<?php echo $img_N; ?>"><button>i wish to have the same</button></a></div>
			<div id="tags">
				<?php echo show_tag_list($img_info["N"], false);?>
			</div>
			<div class="share42init" onclick="javascript:yaCounter37608890.reachGoal('gallery_share');"></div>
			<script type="text/javascript" src="js/share42/share42.js"></script>
			<script type="text/javascript">share42('js/share42/')</script>
		</div>
		<div id="imgtext">
			<?php
				echo $img_info["about"];
			?>
		</div>
		<div id="footer">
			<a href="index.php">ABOUT AUTHOR</a>
		</div>
	</body>
</html>
