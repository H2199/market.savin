function setWidth(){
	var main_img = document.getElementById('main_img').getElementsByTagName('img');
	for (var i=0; i<main_img.length; i++){
		if (main_img[i].width > 650){
			main_img[i].height = main_img[i].height*(650/main_img[i].width);
			main_img[i].width = 650;
		}else if(main_img[i].height < 590){
				main_img[i].width = main_img[i].width*(590/main_img[i].height);
				main_img[i].height = 590;
			}else if(main_img[i].height > main_img[i].width){
				main_img[i].width = main_img[i].width*(590/main_img[i].height);
				main_img[i].height = 590;
				} else if(main_img[i].height < main_img[i].width){
						main_img[i].height = main_img[i].height*(650/main_img[i].width);
						main_img[i].width = 650;
					} else{
						}
	}

}
onload = setWidth;