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
		<div id="center">
			<div id="author_page">
				<div id="about_author">
					<h1>My handmade wooden figures, frames, clocks, bears and many others</h1>
					<img src="img/author.jpg">
					<p>Time is going and I've decided to make my own online gallery for representing my wood carving works that I've done so far.<br><br>
					I'll try to update this photo gallery as often as possible!</p>
					<p></p>
					<p>By the way if you found some of my works nice and you wish to have similar item at your home you are free to push button downstairs for placing an order.<br>
					Shipping could be made worldwide as Finland has pretty delivery system but let us see for each order more detailed.</p>
					<button>place an order</button>
					
					
				</div>
			</div>
			<div class="author_page_links">
				 
				<ul>
					<p>Gallery type:</p>
					<li><a href="index.php">Show one by one</a></li>
					<li><a href="images_for_tag.php?">Show all</a></li>
				</ul>
			</div>
			<div id="share_42_author_page">
				<div class="share42init"></div>
				<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
				<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
			</div>
		</div>
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