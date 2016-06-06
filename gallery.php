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
	$find_image = mysql_query("SELECT N FROM images WHERE moderation = '1' AND N = '$img_N' ")or die(mysql_error());
	$count = mysql_num_rows($find_image);
	if($count==0){ header('Location: gallery.php');}
}else{
		$img_N = $first_img_id;
	}

	
//next and prev link generator
switch (true){ 
	case ($img_N == $first_img_id && $img_N == $last_img_id): //Only one image is available
		$prev_img_id = $img_N;
		$next_img_id = $img_N;
	break;
	case ($img_N == $first_img_id): // First image case
		$next_img_id = take_pic_id('next',$img_N,$tag);
		$prev_img_id = $last_img_id;
	break;
	case ($img_N == $last_img_id): // Last image case
		$prev_img_id = take_pic_id('prev',$img_N,$tag);
		$next_img_id = $first_img_id;
	break;
	case ($img_N<$last_img_id and $img_N>$first_img_id): // Somewhere in between
		$next_img_id = take_pic_id('next',$img_N,$tag);
		$prev_img_id = take_pic_id('prev',$img_N,$tag);
	break;
}
make_next_prev_link($prev_img_id, $next_img_id, $tag);

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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<!--<script type="text/javascript" src="/js/resize.js"></script>-->
		<link href="css/lightbox.css" = type="text/css" rel="stylesheet" media="all" />
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'>
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
						if($tag == '') {$tag_list = '<li>All photos</li>'.$tag_list;}
						while($tags = mysql_fetch_array($find_tags_info)){
							//$first_img_tag_id = take_pic_id('first',0,$tags['id']);
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
			<div id="content">
				<div id="main_img">
					<a data-lightbox="full" href="images/<?php echo $img_info["file"]; ?>">
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
				if($img_info["made_of"]!=''){
					echo'
						<table>
							<tbody>
								<tr><td><b>Made of:</b></td><td>'.$img_info["made_of"].'</td></tr>
								<tr><td><b>Size:</b></td><td>'.$img_info["size"].'</td></tr>
								<tr><td><b>Price:</b></td><td>'.$img_info["price"].' euro</td></tr>
								<tr><td><b>In stock:</b></td><td>'.$img_info["in_stock"].'</td></tr>
								
					';
					if($img_info["in_stock"] !="YES"){echo'<tr><td><b>Time spent:</b></td><td>'.$img_info["time_for_production"].'</td></tr>';}
					echo'
								<tr><td><b>Made in:</b></td><td>'.$img_info["made_in"].'</td></tr>
								<tr><td><b>Made by:</b></td><td>'.$img_info["made_by"].'</td></tr>
							</tbody>
						</table>
					';
				}
			?>
			<?php echo $img_info["about"]; ?>
		</div>
		<div id="footer">
			<a href="index.php">ABOUT AUTHOR</a>
		</div>
		<script src="/js/lightbox.js"type="text/javascript" ></script>
	</body>
</html>

