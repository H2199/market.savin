<?php
echo '
<div id = "update_form">
	<div class="update_form">
		<form action="admin/update.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="N" value='.$img_info["N"].'>
			<input type="hidden" name="action" value="1">
			<input type="hidden" name="image" value='.$img_N.'>
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
			<input type="hidden" name="image" value='.$img_N.'>
			<p>Описание под картинкой:</p>
			<textarea name="about">'.$img_info["about"].'</textarea>
			<p>description:</p>
			<textarea name="about">'.$img_info["description"].'</textarea>
			<p>Title+Alt:</p>
			<textarea name="title">'.$img_info["title"].'</textarea>
			
';	
				echo tag_checkbox_select($img_info["N"]);
echo' 
			<div class="checkboxes">
				<input type="submit" value="Отправить новое описание">
			</div>
		</form>
	</div>
</div>	
';
?>