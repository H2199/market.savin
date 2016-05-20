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

function make_next_prev_link($prev, $next, $tag_id){
	global $prev_link;
	global $next_link;
	if(!empty($tag_id)){$tag_lnk = '&tag='.$tag_id;}else{$tag_lnk='';}
	$prev_link='<a href="index.php?image='.$prev.$tag_lnk.'"><img src="img/arrow/arrow-left-'.rand(1,8).'.png" alt="" border="0"></a>'; 
	$next_link ='<a href="index.php?image='.$next.$tag_lnk.'"><img src="img/arrow/arrow-right-'.rand(1,8).'.png" alt="" border="0"></a>';
}
function take_pic_id($pic_place, $current_pic, $tag){
	switch ($pic_place){ 
		case 'first':
			if(empty($tag)){$q = "SELECT MIN(N) FROM images WHERE moderation = 1";}
			else{$q = "SELECT MIN(images.N) FROM images, tag_relation WHERE images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = 1";}
		break;
		case 'last':
			if(empty($tag)){$q = "SELECT MAX(N) FROM images WHERE moderation = 1";}
			else{$q = "SELECT MAX(images.N) FROM images, tag_relation WHERE images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = 1";}
		break;
		case 'next':
			if(empty($tag)){$q = "SELECT MIN(N) FROM images WHERE N > '$current_pic' AND moderation = 1";}
			else{$q = "SELECT MIN(images.N) FROM images, tag_relation WHERE images.N > '$current_pic' AND images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = 1 ";}
		break;
		case 'prev':
			if(empty($tag)){$q = "SELECT MAX(N) FROM images WHERE N < '$current_pic' AND moderation = 1";}
			else{$q = "SELECT MAX(images.N) FROM images, tag_relation WHERE images.N < '$current_pic' AND images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = 1 ";}
		break;
	}
	$query = mysql_query("$q")or die(mysql_error());
	$values = mysql_fetch_array($query);
	return $values[0];
}

if(isset($_GET['tag'])&&!empty($_GET['tag'])){
	$tag_id = clean_var($_GET['tag']);
	$find_tag_info = mysql_query("SELECT COUNT(*) FROM tags WHERE id = '$tag_id' ")or die(mysql_error());
	$count = mysql_num_rows($find_tag_info);
	if($count!=0){
		$tag = $_GET['tag'];
		$tag_info = mysql_fetch_array($find_tag_info);
	}else{$tag = '';}
}else{$tag = '';}

$first_img_id = take_pic_id('first',0,$tag);//take first pic
$last_img_id = take_pic_id('last',0,$tag);//take last pic

if(isset($_GET['image'])&&!empty($_GET['image'])){
	$img_N = clean_var($_GET['image']);
	$find_image = mysql_query("SELECT COUNT(*) FROM images WHERE moderation = 1 AND N = '$img_N' ")or die(mysql_error());
	$count = mysql_num_rows($find_image);
	if($count==0){ header('Location: http://so-funny.ru/index.php'); }
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
		<meta name="description" content="Картинка №<?php echo $img; ?> Смотрите все самые прикольные картинки у нас." >
		<title> <?php echo $img_info["description"]?> </title>
		<!--<script type="text/javascript" src="/js/resize.js"></script>-->
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
	</head>
	<body>
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
				<p>Сейчас смотрим:</p>
					<?php
						$tag_info; // id ru en
						$find_tags_info = mysql_query("SELECT * FROM tags")or die(mysql_error());
						$tag_list = '';
						if($tag == '') {$tag_list = '<li><a href="http://so-funny.ru">Все картиночки</a></li>'.$tag_list;}
						while($tags = mysql_fetch_array($find_tags_info)){
							$first_img_tag_id = take_pic_id('first',0,$tags['id']);
							if($tags['id'] == $tag){
								$tag_list = '<li><a href="index.php?image='.$img_N.'&tag='.$tags['id'].'">'.$tags['ru'].'</a></li>'.$tag_list;
							}else{
									$tag_list .= '<li><a href="index.php?image='.$first_img_tag_id.'&tag='.$tags['id'].'">'.$tags['ru'].'</a></li>';
								}
						}
						if($tag != ''){$tag_list .= '<li><a href="http://so-funny.ru">Все картиночки</a></li>';}
						$tag_list = '<ul>'.$tag_list.'</ul>';
						echo $tag_list;
					?>
			</div>
			<div id="content">
				<div id="main_img">
					<img src="images/<?php echo $img_info["file"]; ?>" alt="<?php echo $img_info["description"]; ?>">
				</div>
				<?php if($auth==true){ include 'admin/update_form.php';} ?>
			</div>
			<div id="random">
				<a href="index.php?image=<?php echo random_pic_id();?>">случайная картиночка</a>
			</div>
			<div id="tags">
				<?php echo show_tag_list($img_info["N"]);?>
			</div>
			<div class="share42init"></div>
			<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
			<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
		</div>
		<div id="imgtext">
			<div> 
				<?php
					echo $img_info["about"];
				?>
			</div>
		</div>
			<div id="footer">
				<a href="russian_bear.php">made by Russian bear</a>
			</div>
	</body>
</html>

