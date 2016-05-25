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
		<script type="text/javascript">
			// показать форму заказа
			function showOrderForm() {
				calculate_sum();
				$('#popups').removeClass("m-hide");
				$('#order').removeClass("m-hide");
				$('#msg').addClass("m-hide");
				yaCounter31976076.reachGoal('open_form');
			}

			// показать форму оформления заказа
			function showOrderOkForm() {
				$('#popups').removeClass("m-hide");
				$('#order').addClass("m-hide");
				$('#msg').removeClass("m-hide");
			}

			// прячем формы
			function hidePopups() {
				$('#popups').addClass("m-hide");
				$('#order').addClass("m-hide");
				$('#msg').addClass("m-hide");
				var search_now = window.location.search;
				if(search_now == "?buy")
				{
					window.location.search = "";
				}
				
			}

			// оформляем заказ
			function makeOrder() {
				$('#popups').addClass("m-hide");
				$('#order').addClass("m-hide");
				$('#msg').addClass("m-hide");
			}

			// оформить заказа
			function makeOrder() {
			//customer's info
				if (document.getElementById("name").value == "")
				{
					alert("Укажите Ваше ФИО");
					return false;
				}
				if (document.getElementById("email").value == "")
				{
					alert("Укажите электронный адрес");
					return false;
				}
				if (document.getElementById("comment").value == "")
				{
					alert("Укажите электронный адрес");
					return false;
				}
				//customer's order
				var var1 = document.getElementById("var1").checked;
				var var2 = document.getElementById("var2").checked;
				var var3 = document.getElementById("var3").checked;
					
				if( !var1 && !var2 && !var3 )
				{
					alert("Уточните вид чая, что Вы хотите заказать.");
					return false;
				}
				if( var1 && $("#mvar1").val() == '')
				{
					alert("Мы не можем вам привезти 0г лотуса... Уточните, пожалуйста, сколько нужно.");
					return false;
				}
				if( var2 && $("#mvar2").val() == '')
				{
					alert("Уточните, пожалуйста, сколько вам необходимо чая.");
					return false;
				}
				if( var3 && $("#mvar3").val() == '')
				{
					alert("Уточните, пожалуйста, сколько вам необходимо чая.");
					return false;
				}
				//alert("ok");
				yaCounter31976076.reachGoal('order');
				return true;
			}
			function open_answer(){
				$("#answer").css({"display":"block"});
			}

			function close_answer(){
				$("#answer").css({"display":"none"});
				window.location.search = "";
			}
			  window.onload = function()
			{
				var search = window.location.search;
				if(search == "?buy")
				{
					showOrderForm();
				}
				if(search == "?sent=1")
				{
					setTimeout(open_answer, 500);
					setTimeout(close_answer, 5000);
				}
			}
		</script>
		<link href='http://fonts.googleapis.com/css?family=Neucha&subset=cyrillic,latin' rel='stylesheet' type='text/css'></link>
	</head>
	<body>
	<div id="order_answer" style="display:none;">Thank you for the order placed I will contact you as soon as possible.</div>
	<div id="order_form" style="display:none;">
		<form onsubmit="return makeOrder();">
			<p>Name:</p> <input type="text" name="name">
			<p>Email:</p> <input type="text" name="email">
			<p>Place here link to the work that you are interested in</p> <input type="text" name="link"> (f.e. http://market.savin.fi/index.php?image=2)
			<p>What ever you want to say to me you can place here:</p> <textarea name="about"></textarea>
		</form>
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
					<button onclick="javascript:showOrderForm();">place an order</button>
					
					
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