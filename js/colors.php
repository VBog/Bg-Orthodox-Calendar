<?php
  header("Content-type: text/css; charset: UTF-8");

  $mainColor = "#000000";
  $mainBgColor = "#EEEEEE";
?>
#bscal,  #v-menu {					
	background-color: <?php echo $mainBgColor; ?>;	/* Цвет основного фона	*/
	color: <?php echo $mainColor; ?>;				/* Цвет основного текста*/
}
/* Заголовок и подвал окна, Дни недели, Выделение в меню */
#bscal  td.top, td.bot, .week, #v-menu li:hover, li a:hover  { 
		background-color: #132E64; 		/* Цвет фона	*/	
		color: white; 					/* Цвет текста 	*/	
}
/* Поля перехода назад-вперед и название месяца			*/
#bscal td.arrow, .month{ 
		color: #132E64; 				/* Цвет текста 	*/	
}
/* Дата под курсором									*/	
#bscal .over {			
		background-color: #406FCD; 		/* Цвет фона 	*/	
		color: white; 					/* Цвет текста 	*/	
}
/* Сегодня												*/
#bscal .today {			
		background-color: #335AAB; 		/* Цвет фона 	*/	
		color: white; 					/* Цвет текста 	*/	
}