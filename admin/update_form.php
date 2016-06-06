<?php
echo '
<div id = "update_form">
	<div class="update_form">
		<form action="admin/update.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="N" value='.$img_info["N"].'>
			<input type="hidden" name="action" value="1">
			<a href="admin/exit.php">exit</a>
			<a href="admin/login.php">login.php</a>
			<p>Файл:</p>
			<input type="file" name="upload">
			<input type="submit" value="Загрузить новую картинку">
		</form>
	</div>
	<div class="update_form">
		<form action="admin/update.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="N" value='.$img_info["N"].'>
			<input type="hidden" name="action" value="2">
			<p>Описание под картинкой:</p>
			<textarea name="about">'.$img_info["about"].'</textarea>
			<p>description:</p>
			<textarea name="description">'.$img_info["description"].'</textarea>
			<p>Title+Alt:</p>
			<textarea name="title">'.$img_info["title"].'</textarea>
				<p>made_of:</p>
				<input type="text" name="made_of" value="'.$img_info["made_of"].'">
				<p>size:</p>
				<input type="text" name="size" value="'.$img_info["size"].'">
				<p>price:</p>
				<input type="text" name="price" value="'.$img_info["price"].'">
				<p>in_stock:</p>
				<input type="text" name="in_stock" value="'.$img_info["in_stock"].'">
				<p>time_for_production:</p>
				<input type="text" name="time_for_production" value="'.$img_info["time_for_production"].'">
				<p>made_in:</p>
				<input type="text" name="made_in" value="'.$img_info["made_in"].'">
				<p>made_by:</p>
				<input type="text" name="made_by" value="'.$img_info["made_by"].'">

';	
				echo tag_checkbox_select($img_info["N"]);
echo' 
			<div class="checkboxes">
				<input type="submit" value="Отправить новое описание">
			</div>
		</form>
		<form action="admin/update.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="N" value='.$img_info["N"].'>
			<input type="hidden" name="prev" value='.$prev_img_id.'>
			<input type="hidden" name="action" value="3">
			<input type="submit" value="Убрать фото">
		</form>
	</div>
</div>	
';
?>