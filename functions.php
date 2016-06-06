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
function take_pic_id($pic_place, $current_pic, $tag){
	switch ($pic_place){ 
		case 'first':
			if(empty($tag)){$q = "SELECT MIN(N) FROM images WHERE moderation = 1";}
			else{$q = "SELECT MIN(images.N) FROM images, tag_relation WHERE images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = '1'";}
		break;
		case 'last':
			if(empty($tag)){$q = "SELECT MAX(N) FROM images WHERE moderation = 1";}
			else{$q = "SELECT MAX(images.N) FROM images, tag_relation WHERE images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = '1'";}
		break;
		case 'next':
			if(empty($tag)){$q = "SELECT MIN(N) FROM images WHERE N > '$current_pic' AND moderation = 1";}
			else{$q = "SELECT MIN(images.N) FROM images, tag_relation WHERE images.N > '$current_pic' AND images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = '1' ";}
		break;
		case 'prev':
			if(empty($tag)){$q = "SELECT MAX(N) FROM images WHERE N < '$current_pic' AND moderation = 1";}
			else{$q = "SELECT MAX(images.N) FROM images, tag_relation WHERE images.N < '$current_pic' AND images.N = tag_relation.image_id AND tag_relation.tag_id = '$tag' AND images.moderation = '1' ";}
		break;
	}
	$query = mysql_query("$q")or die(mysql_error());
	$values = mysql_fetch_array($query);
	return $values[0];
}
function show_previews($tag_id, $amount, $all){
	$images = '';
	if($all == true){
		$query = "SELECT N, file, price FROM images WHERE moderation = '1' LIMIT 0, $amount ";
	}else{
			$query = "SELECT images.N, images.file, images.price FROM images, tag_relation WHERE tag_relation.image_id = images.N AND tag_relation.tag_id = '$tag_id' AND images.moderation = '1'";
		}
	$q = mysql_query($query)or die(mysql_error());
	$count = mysql_num_rows($q);
	if($count==0){
		$images .='No images to show';
	}else{
			$n =1;
			while ($f = mysql_fetch_array($q)){
				$files[$n]['N'] = $f[0];
				$files[$n]['file'] = $f[1];
				$files[$n]['price'] = $f[2];
				$n++;
			}
			//shuffle($files); show previews in random order
			$tag_id = $tag_id==0  ?  ''  :  '&tag='.$tag_id.'';
			for ($i = 1; $i <=$count; $i++) {//start at 0 if random and 1 if not
				//$images .= '<a data-lightbox="previews" href="images/'.$files[$i]['file'].'"><img src="small_images/'.$files[$i]['file'].'"></a>';
				$images .= '<a href="gallery.php?image='.$files[$i]['N'].$tag_id.'"><img src="small_images/'.$files[$i]['file'].'">';
				if($files[$i]['price']!=''){$images .= '<div class="price">€'.$files[$i]['price'].'</div>';}
				$images .= '</a>';
			}
		}
	return $images;
}
function show_exact_previews ($id_array, $more){
	if(!is_array($id_array)){return 'whut';}
	$previews = '';
	$end_value = end($id_array);
	foreach ($id_array as $value){
		$query = "SELECT N, file, price FROM images WHERE N = '$value' AND images.moderation = '1'";
		$q = mysql_query($query)or die(mysql_error());
		$f = mysql_fetch_array($q);
		
		//LAST HREF LINK
		if($value === $end_value && $more){$previews .= '<a href="images_for_tag.php"><img src="small_images/'.$f['file'].'">';}
		else{$previews .= '<a href="gallery.php?image='.$f['N'].'"><img src="small_images/'.$f['file'].'">';}
		if($f['price']!=''){$previews .= '<div class="price">€'.$f['price'].'</div>';}
		//LAST GREY PIC
		if($value === $end_value && $more){$previews .= '<div class="more">More..</div>';}
		$previews .= '</a>';
	}
	return $previews;
}
function make_next_prev_link($prev, $next, $tag_id){
	global $prev_link;
	global $next_link;
	if(!empty($tag_id)){$tag_lnk = '&tag='.$tag_id;}else{$tag_lnk='';}
	$prev_link='<a href="gallery.php?image='.$prev.$tag_lnk.'"><img src="img/arrow/al'.rand(1,8).'.png" alt="" border="0"></a>'; 
	$next_link ='<a href="gallery.php?image='.$next.$tag_lnk.'"><img src="img/arrow/ar'.rand(1,8).'.png" alt="" border="0"></a>';
}
?>