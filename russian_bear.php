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
		<div id="banner">
			<div>
			 тут может быть ваш баннер 600х90
			</div>
		</div>
		<div id="content">
			<div id="main_img">
				<img src="img/russian_bear.png" height="100%" width="100%" />
			</div>
		</div>
		<div id="random">
			<a href="index.php?image=<?php echo random_pic_id(); ?>"> случайная картиночка </a>
		</div>
		<div class="share42init"></div>
		<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
		<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
		<div id="footer">
			<div id="footer-text">
				<div>
					<a href="http://www.savin.fi">www.savin.fi</a>
						<!-- <div id="site">
							<a href=""></a>
						</div>-->
				</div>
			</div>
		</div>
	</body>
</html>