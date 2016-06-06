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
		 Text about who am I and how is going now.
		</title>
		<link type="text/css" rel="stylesheet" media="all" href="css/style.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<script src="js/index_form.js" type="text/javascript"></script>
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
	</head>
	<body>
		<?php if($auth == false){include 'yandex_metrika.php';}?>
		<div id="order_answer" style="display:none;">Thank you for the order placed!<br>I will contact you as soon as possible.</div>
		<div id="order_error" style="display:none;"><?php if(isset($_GET['text'])){echo $_GET['text'];}?></div>
		<div id="order_form_bg" style="display:none;" onclick="javascript:hideOrderForm();"></div>
		<div id="order_form" style="display:none;">
			<form onsubmit="return makeOrder();" action="add.php?action=1" method="post">
				<div id="close_order_form" onclick="javascript:hideOrderForm();" > close </div>
				<p>Name:</p>
				<input id="form_name" type="text" name="name">
				<p>Email:</p>
				<input id="form_email" type="text" name="email">
				<p>
					Place here link to the work that you are interested in<br>
					(f.e.http://market.savin.fi/gallery.php?image=2)
				</p> 
				<input id="form_link" type="text" name="link" value="<?php if(isset($_GET['link'])){echo $_GET['link'];}?>">  
				<p>What ever you want to say to me you can place here:</p>
				<textarea id="form_about" name="message"></textarea>
				<input id="order_form_sumbit_button" type="submit" value="SEND" />
			</form>
		</div>
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
		<div id="share_42_author_page">
				<div class="share42init" onclick="javascript:yaCounter37608890.reachGoal('index_share');"></div>
				<script type="text/javascript" src="http://so-funny.ru/js/share42/share42.js"></script>
				<script type="text/javascript">share42('http://so-funny.ru/js/share42/')</script>
			</div>
		<div id="center">
			<div id="author_page">
				<div id="about_author">
					<h1>My handmade wooden figures, frames, clocks, bears and many others</h1>
					<img src="img/author.jpg">
					<p>Time is going and I've decided to make my own online gallery for representing my wood carving works that I've done so far.<br><br>
					I'll try to update this photo gallery as often as possible!</p>
					<p></p>
					<p>By the way if you found some of my works nice and you wish to have similar item at your home you are free to push button downstairs for placing an order. If you wish to know something speicific you may send me email. I'll be happy to hear any feedback. :)<br>
					Shipping could be made worldwide as Finland has pretty delivery system but let us see for each order more detailed.</p>
					<button onclick="javascript:showOrderForm(); yaCounter37608890.reachGoal('open_author_page_form');">place an order</button>
						
				</div>
			</div>
			<div id="index_previews">
				<?php
							$previews_id = array('31','32','79','41','40');
							echo show_exact_previews($previews_id, true);
				?>
			</div>
			<!--
				<div class="author_page_links"> 
					<ul>
						<p>Gallery type:</p>
						<li><a href="gallery.php">Show one by one</a></li>
						<li><a href="images_for_tag.php">Show all</a></li>
					</ul>
				</div>
			-->
		</div>
		<div id="footer">
			<div id="footer-text">
					<a href="http://www.savin.fi">www.savin.fi</a>
			</div>
		</div>
	</body>
</html>