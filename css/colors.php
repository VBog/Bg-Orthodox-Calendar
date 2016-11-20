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
#bg_ortcal_bscal, .bg_moncal, #bg_ortcal_v-menu {					
	background-color: <?php echo $mainBgColor; ?>;	/* Цвет основного фона	*/
	color: <?php echo $mainColor; ?>;				/* Цвет основного текста*/
}
/* Поля перехода назад-вперед и название месяца								*/
#bg_ortcal_bscal td.bg_ortcal_arrow, #bg_ortcal_bscal .bg_ortcal_month, .bg_moncal td.bg_ortcal_arrow, .bg_moncal .bg_ortcal_month { 
	color: <?php echo $titleColor; ?>;				/* Цвет текста			*/
}
/* Заголовок и подвал окна, Дни недели, Выделение в меню  					*/
#bg_ortcal_bscal .bg_ortcal_top td, #bg_ortcal_bscal td.bg_ortcal_bot, #bg_ortcal_bscal .bg_ortcal_week, #bg_ortcal_v-menu li:hover, #bg_ortcal_v-menu li a:hover,
.bg_moncal .bg_ortcal_top td, .bg_moncal td.bg_ortcal_bot, .bg_moncal .bg_ortcal_week{ 
	background-color: <?php echo $otherBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $otherColor; ?>;				/* Цвет текста			*/
}
/* Дата под курсором														*/	
#bg_ortcal_bscal .bg_ortcal_over, .bg_moncal .bg_ortcal_over {			
	background-color: <?php echo $overBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $overColor; ?>;				/* Цвет текста			*/
}
/* Сегодня																	*/
#bg_ortcal_bscal .bg_ortcal_today, .bg_moncal .bg_ortcal_today{			
	background-color: <?php echo $todayBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $todayColor; ?>;				/* Цвет текста			*/
}
/* День бракосочетаний	(вкл.)												*/
#bg_ortcal_bscal .bg_ortcal_wedding_on{		
	outline-color: <?php echo $weddingColor; ?>;	/* Цвет рамки			*/
}
/* Окно описания дня 														*/
#bg_ortcal_snames {					
	background-color: <?php echo $otherBgColor; ?>;	/* Цвет основного фона	*/
	color: <?php echo $otherColor; ?>;				/* Цвет основного текста*/
}
/* Описание дня, Разделители низ											*/
#bg_ortcal_snames .bg_ortcal_day, #bg_ortcal_snames .bg_ortcal_separator2, #bg_ortcal_snames .bg_ortcal_bottom2 {	
	background-color: <?php echo $mainBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $mainColor; ?>;				/* Цвет текста			*/
}
/* Z-index для всплывающих окон */
#bg_ortcal_bscal {					
	z-index: <?php echo $Zindex;  ?>;				/* Z-index основного окна */
}
#bg_ortcal_v-menu {					
	z-index: <?php echo ($Zindex+1);  ?>;			/* Z-index окна меню */
}
#bg_ortcal_snames {					
	z-index: <?php echo ($Zindex+2);  ?>;			/* Z-index окна описания дня */
}
.bg_ortcal_schedule td.bg_ortcal_day {				/* Нижняя граница описания дня	*/
    border-top: 2px solid <?php echo $otherBgColor; ?>;
    border-bottom: 1px dotted <?php echo $otherBgColor; ?>;
}
.bg_ortcal_schedule table {							/* Нижняя граница таблицы расписания	*/
    border-bottom: 2px solid <?php echo $otherBgColor; ?>;
}
.bg_ortcal_schedule .bg_ortcal_week{ 
	background-color: <?php echo $otherBgColor; ?>;	/* Цвет фона			*/
	color: <?php echo $otherColor; ?>;				/* Цвет текста			*/
}