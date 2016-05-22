<?php
function clean_var($value){
    $newVal = trim($value);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}
function random_pic_id(){
	//$query = mysql_query("SELECT N FROM `images` WHERE moderatin = 1 AND`N` >= RAND()*(SELECT MAX(N) FROM `images`)"); //FOR FAST NOT VERY RANDOM
	$query = mysql_query("SELECT N FROM `images` WHERE moderation = 1 ORDER BY RAND()"); // MORE RANDOM
	$rand_N  = mysql_fetch_array($query);
	return $rand_N[0];
}
function show_tag_list($img, $all){
	if($img == 'all'){
		$q = "SELECT en, id FROM tags";
	}else{
			$q ="SELECT tags.en, tags.id FROM tags, tag_relation WHERE tag_relation.image_id = '$img' AND tag_relation.tag_id = tags.id";
		}
	$query = mysql_query("$q")or die(mysql_error());
	$list ='<ul>';
	while ($f = mysql_fetch_array($query)){
		$list .='<li><a href="images_for_tag.php?tag='.$f[1].'">'.$f[0].'</a></li>';
	}
	if($all == true){$list .='<li><a href="images_for_tag.php">All</a></li>';}
	$list .='</ul>';
	return $list;
}
function tag_checkbox_select($img){//$img = 0; show all
	$q = mysql_query("SELECT * FROM tags") //id ru en
		or die("Invalid query: " . mysql_error());
	$tag_select = '';
	while ($f = mysql_fetch_array($q)){
		$query3 = 'SELECT * FROM tag_relation WHERE image_id= "'.$img.'" AND tag_id="'.$f['id'].'"';
		$q3 = mysql_query($query3)or die(mysql_error());
		$count = mysql_num_rows($q3);
		if($count > 0){
			$tag_select .= '<div class="checkbox"><input type="checkbox" checked name ="tags[]" value="'.$f['id'].'">'.$f['ru'].'</div>';
		}else{
				$tag_select .= '<div class="checkbox"><input type="checkbox" name ="tags[]" value="'.$f['id'].'">'.$f['ru'].'</div>';
			}
	}
	return $tag_select;
}
?>