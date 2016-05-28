<?php
		$q2 = mysql_query("SELECT * FROM tags WHERE id = '$tag'")
		or die("Invalid query: " . mysql_error());
		$tag_info=mysql_fetch_array($q2);//id ru en en title en_description en_text 
		echo '
			<div id = "update_form">
				<div class="update_form">
					<form action="admin/update.php" method="post" enctype="multipart/form-data">
						<a href="admin/exit.php">exit</a>
						<a href="admin/login.php">login.php</a>
						<p>Title:</p>
						<textarea name="title">'.$tag_info["en_title"].'</textarea>
						<p>Description:</p>
						<textarea name="description">'.$tag_info["en_description"].'</textarea>
						<p>Text:</p>
						<textarea name="text">'.$tag_info["en_text"].'</textarea>
						<input type="hidden" name="tag_id" value='.$tag_info["id"].'>
						<input type="hidden" name="action" value="4">
						<input type="submit" value="Отправить новое описание">
					</form>
				</div>
			</div>
		';

?>