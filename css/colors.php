<?php
	header("Content-type: text/css; charset: UTF-8");
	$absolute_path = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
	$wp_load = $absolute_path[0] . 'wp-load.php';
	require_once($wp_load);
  if (function_exists ( 'get_option' )){
    // Читаем существующие значения опций из базы данных
	$mainColor 		= get_option( "bg_ortcal_mainColor" );
	$mainBgColor 	= get_option( "bg_ortcal_mainBgColor" );
	$titleColor 	= get_option( "bg_ortcal_titleColor" );
	$otherColor 	= get_option( "bg_ortcal_otherColor" );
	$otherBgColor 	= get_option( "bg_ortcal_otherBgColor" );
	$overColor 		= get_option( "bg_ortcal_overColor" );
	$overBgColor 	= get_option( "bg_ortcal_overBgColor" );
	$todayColor 	= get_option( "bg_ortcal_todayColor" );
	$todayBgColor 	= get_option( "bg_ortcal_todayBgColor" );
	$weddingColor 	= get_option( "bg_ortcal_weddingColor" );
	$Zindex 		= get_option( "bg_ortcal_Zindex" );;
   }
?>
/* Окно календаря и меню													*/
#bscal, .bg_moncal, #v-menu {					
	background-color: <?php echo $mainBgColor; ?>;	/* Цвет основного фона	*/
	color: <?php echo $mainColor; ?>;				/* Цвет основного текста*/
}
/* Поля перехода назад-вперед и название месяца								*/
#bscal td.arrow, #bscal .month, .bg_moncal td.arrow, .bg_moncal .month{ 
	color: <?php echo $titleColor; ?>;				/* Цвет текста			*/
}
/* Заголовок и подвал окна, Дни недели, Выделение в меню  					*/
#bscal .top td, #bscal td.bot, #bscal .week, #v-menu li:hover, #v-menu li a:hover,
.bg_moncal .top td, .bg_moncal td.bot, .bg_moncal .week{ 
	background-color: <?php echo $otherBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $otherColor; ?>;				/* Цвет текста			*/
}
/* Дата под курсором														*/	
#bscal .over, .bg_moncal .over {			
	background-color: <?php echo $overBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $overColor; ?>;				/* Цвет текста			*/
}
/* Сегодня																	*/
#bscal .today, .bg_moncal .today{			
	background-color: <?php echo $todayBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $todayColor; ?>;				/* Цвет текста			*/
}
/* День бракосочетаний	(вкл.)												*/
#bscal .wedding_on, .bg_moncal .wedding_on{		
	outline-color: <?php echo $weddingColor; ?>;	/* Цвет рамки			*/
}
/* Окно описания дня 														*/
#snames {					
	background-color: <?php echo $otherBgColor; ?>;	/* Цвет основного фона	*/
	color: <?php echo $otherColor; ?>;				/* Цвет основного текста*/
}
/* Описание дня, Разделители низ											*/
#snames .day, #snames .separator2, #snames .bottom2 {	
	background-color: <?php echo $mainBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $mainColor; ?>;				/* Цвет текста			*/
}
/* Z-index для всплывающих окон */
#bscal {					
	z-index: <?php echo $Zindex;  ?>;				/* Z-index основного окна */
}
#v-menu {					
	z-index: <?php echo ($Zindex+1);  ?>;			/* Z-index окна меню */
}
#snames {					
	z-index: <?php echo ($Zindex+2);  ?>;			/* Z-index окна описания дня */
}
