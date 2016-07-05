<?php
/* 
    Plugin Name: Bg Orthodox Calendar 
    Plugin URI: http://bogaiskov.ru/plugin-orthodox-calendar/
    Description: Плагин выводит на экран православный календарь: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, дни почитания икон, посты и сплошные седмицы. 
    Author: VBog
    Version: 0.10.5-RC
    Author URI: http://bogaiskov.ru 
	License:     GPL2
*/

/*  Copyright 2015  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*****************************************************************************************
	Блок загрузки плагина
	
******************************************************************************************/

// Запрет прямого запуска скрипта
if ( !defined('ABSPATH') ) {
	die( 'Sorry, you are not allowed to access this page directly.' ); 
}

define('BG_ORTCAL_VERSION', '0.10.5-RC');

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/days.php');

// Динамическая Таблица стилей для плагина
function bg_ortcal_frontend_styles () {
	wp_enqueue_style( "bg_ortcal_colors", plugins_url( '/css/colors.php', plugin_basename(__FILE__) ), array() , BG_ORTCAL_VERSION  );
}
add_action( 'wp_enqueue_scripts' , 'bg_ortcal_frontend_styles' );

// Таблица стилей для админки плагина
function bg_ortcal_admin_styles () {
	wp_enqueue_style( "bg_ortcal_styles", plugins_url( '/css/styles.css', plugin_basename(__FILE__) ), array() , BG_ORTCAL_VERSION  );
}
add_action( 'admin_enqueue_scripts' , 'bg_ortcal_admin_styles' );

// JS скрипты 
function bg_ortcal_frontend_scripts () {
   	wp_enqueue_script( 'bg_ortcal_days', plugins_url( 'js/bg_ortcal_days.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_names', plugins_url( 'js/bg_ortcal_names.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_year', plugins_url( 'js/bg_ortcal_year.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_init', plugins_url( 'js/bg_ortcal_init.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
}
function bg_ortcal_js_options () { 
	global $events;
		
	$customXML_val=get_option( "bg_ortcal_customXML" );
    $popmenu1_val = get_option( "bg_ortcal_popmenu1" );
    $popmenu2_val = get_option( "bg_ortcal_popmenu2" );
    $popmenu3_val = get_option( "bg_ortcal_popmenu3" );
    $popmenu101_val = get_option( "bg_ortcal_popmenu101" );
    $popmenu1001_val = get_option( "bg_ortcal_popmenu1001" );
    $popmenu1002_val = get_option( "bg_ortcal_popmenu1002" );
    $dblClick_val = get_option( "bg_ortcal_dblClick" );
    $bg_ortcal_page_val = get_option( "bg_ortcal_page" );							// Постоянная ссылка на страницу с календарем
?>
	<script>
		var bg_ortcal_baseUrl =  "<?php echo plugins_url( '/' , __FILE__ ); ?>";
		var bg_ortcal_customXML =  "<?php if (is_file(ABSPATH."/".$customXML_val)) echo site_url()."/".$customXML_val; ?>";
		var bg_ortcal_popmenu =[];									
<?php 
		$i=0;
		if ($popmenu1_val) {echo 'bg_ortcal_popmenu['.$i.']={name:"'.$popmenu1_val.'", type: 1};'; $i++;}
		if ($popmenu2_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu2_val.'", type: 2};';$i++;}
		if ($popmenu3_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu3_val.'", type: 3};';$i++;}
		if ($popmenu101_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu101_val.'", type: 101};';$i++;}
		if ($popmenu1001_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu1001_val.'", type: 1001};';$i++;}
		if ($popmenu1002_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu1002_val.'", type: 1002};';$i++;}
 ?>

		var bg_ortcal_dblClick = <?php echo $dblClick_val; ?>;							// Пункт меню при двойном щелчке по дате (варианты см. выше)										
		var bg_ortcal_page = <?php echo '"'.$bg_ortcal_page_val. '"'; ?>;				// Постоянная ссылка на страницу с календарем
		var bg_ortcal_events = "";
	</script>
<?php
}
if ( !is_admin() ) {
	bg_ortcal_options_ini (); 			// Параметры по умолчанию
	add_action( 'wp_enqueue_scripts' , 'bg_ortcal_frontend_scripts' ); 
	add_action( 'wp_head' , 'bg_ortcal_js_options' ); 
}

if ( defined('ABSPATH') && defined('WPINC') ) {
// Регистрируем крючок для добавления меню администратора
	add_action('admin_menu', 'bg_ortcal_add_pages');
// Регистрируем крючок на удаление плагина
	if (function_exists('register_uninstall_hook')) {
		register_uninstall_hook(__FILE__, 'bg_ortcal_deinstall');
	}
/*****************************************************************************************
	Генератор ответа AJAX
	
******************************************************************************************/
add_action ('wp_ajax_bg_ortcal', 'bg_ortcal_callback');
add_action ('wp_ajax_nopriv_bg_ortcal', 'bg_ortcal_callback');
 
function bg_ortcal_callback() {
	global $events;
	
	
	if (isset($_GET['load'])) $load = $_GET["load"];
	else $load = "";
	if (isset($_GET['year'])) $y = $_GET["year"];
	else $y = "";
	if (isset($_GET['month'])) $m = $_GET["month"];
	else $m = "";
	if ($load == 'Y') {
		if (!$events) $events = bg_ortcal_load_xml();
		echo json_encode($events); 
	}
	else if ($y && $m) {
		echo ort_calendar($y, $m); 
	}

   die();
}
	
// Регистрируем шорт-код ortcal_button
	add_shortcode( 'ortcal_button', 'bg_ortcal_button' );
	add_shortcode( 'DayInfo', 'bg_ortcal_DayInfo' );		// Для совместимости с версией 0.4
	add_shortcode( 'OldStyle', 'bg_ortcal_OldStyle' );		// Для совместимости с версией 0.4
	add_shortcode( 'Sedmica', 'bg_ortcal_Sedmica' );		// Для совместимости с версией 0.4
	add_shortcode( 'dayinfo', 'bg_ortcal_DayInfo' );
	add_shortcode( 'ortcal', 'bg_ortcal_setDate' );
	add_shortcode( 'monthinfo', 'bg_ortcal_MonthInfo' );
	add_shortcode( 'oldstyle', 'bg_ortcal_OldStyle' );
	add_shortcode( 'sedmica', 'bg_ortcal_Sedmica' );
	add_shortcode( 'readings', 'bg_ortcal_Readings' );
	add_shortcode( 'dayinfo_all', 'bg_ortcal_DayInfo_all' ); 
	add_shortcode( 'upcoming_events', 'bg_ortcal_UpcomingEvents' ); 
}
// Функция действия перед крючком добавления меню
function bg_ortcal_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( 'Православный календарь', 'Православный календарь', 'manage_options', __FILE__, 'bg_ortcal_options_page');
}
/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/

// Функция обработки шорт-кода ortcal_button
function bg_ortcal_button($atts) {
	extract( shortcode_atts( array(
		'val' => ' Календарь на год '
	), $atts ) );

	$quote = "<button onClick='bg_ortcal_bscal.show();'>".$val."</button>";
	return "{$quote}";
}
// Функция обработки шорт-кода DayInfo
function bg_ortcal_DayInfo($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
		'date' => 'l, j F Y г. ',			// Формат даты по нов. стилю
		'old' => '(j F ст.ст.)',			// Формат даты по ст. стилю
		'sedmica' => 'on',					// Седмица
		'memory' => 'on',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 7,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'saints' => 'off',					// Святые
		'martyrs' => 'off',					// Новомученники и исповедники российские
		'icons' => 'off',					// Дни почитания икон Богоматери
		'posts' => 'off',					// Постные дни
		'noglans' => 'off',					// Дни, в которые браковенчание не совершается
		'readings' => 'off',				// Чтения Апостола и Евангелие
		'links' => 'on',					// Ссылки и цитаты
		'custom' => 'off',					// Пользовательские ссылки
	), $atts ) );

// Если $day задано значение "get", то получаем $day, $month и $year из ссылки	
	if ($day == "get") {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($year,$month, $day) = explode("-",$dd);
	}
// ===========================================================================
	return bg_ortcal_showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom );
}
// Функция обработки шорт-кода ortcal
function bg_ortcal_setDate($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
	), $atts ) );

	$input = '<div class="bg_moncal">'.ort_calendar($year, $month).'</div>'; 
	return "{$input}"; 
}

function ort_calendar($y=null, $m=null) { 
    $bg_ortcal_page = get_option( "bg_ortcal_page" );
	if (!$bg_ortcal_page) $bg_ortcal_page = plugins_url( '/' , __FILE__ );
	$month_names=array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"); 
	$month_names2=array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
	$day_name=array("Пн","Вт","Ср","Чт","Пт","Сб","Вс");

	if (isset($_GET['date'])) {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($y,$m, $d) = explode("-",$dd);
	}
	if (!isset($y) OR !$y) $y=date("Y");
	if (!isset($m) OR $m < 1 OR $m > 12) $m=date("m");
	
	$month_stamp=mktime(0,0,0,$m,1,$y);
	$day_count=date("t",$month_stamp);
	$weekday=date("w",$month_stamp);
	if ($weekday==0) $weekday=7;
	$start=-($weekday-2);
	$end=$start+41;
	$today=date("Y-m-d");
	$prev_m=$m-1;
	if ($prev_m < 1) {$prev_m = 12; $prev_y = $y-1;}
	else $prev_y = $y;
	$next_m=$m+1;
	if ($next_m > 12) {$next_m = 1; $next_y = $y+1;}
	else $next_y = $y;
	
	$input = '
		<table> 
		 <tr>
		  <td colspan=7 align="center"> 
		   <table width="100%" border=0 cellspacing=0 cellpadding=0> 
			<tr> 
			 <td align="left" class="bg_ortcal_arrow" onclick="bg_ortcal_month(this,'.$prev_y.','.$prev_m.');" title="Предыдущий месяц">&lt;&lt;</td> 
			 <td align="center" class="bg_ortcal_month" style="font-size: 12px;"><span style="cursor: pointer;" onClick="bg_ortcal_bscal.show('. $y .');" title="Календарь на '. $y .' год">'. $month_names[$m-1] .' '. $y .'</span></td> 
			 <td align="right" class="bg_ortcal_arrow" onclick="bg_ortcal_month(this,'.$next_y.','.$next_m.');" title="Следующий месяц">&gt;&gt;</td> 
			</tr> 
		   </table> 
		  </td> 
		 </tr> 
		 <tr>
	';
	foreach ($day_name as $weekday) {
		$input .= '<td class="bg_ortcal_week">'.$weekday.'</td>';
	}
	$input .= '<tr>';
	$i=0;
	for($d=$start;$d<=$end;$d++) { 
		if (!($i++ % 7)) $input .= " <tr>\n";
		if ($d < 1 OR $d > $day_count) {
			$input .= '  <td align="center" class="bg_ortcal_day">&nbsp;';
		} else {
			$info = bg_ortcal_showDayInfo ( $d, $m, $y, 'l, j F Y г. ', '(j F ст.ст.)', 'on', 'on', 'on', 7, 'on', 'off', 'off', 'off', 'on', 'off', 'off', 'off', 'off' );
			$info = str_replace ( "<br>", "\n", $info );
			$prop = bg_ortcal_dayProperties ($m, $d, $y);
			$cur="$y-$m-".sprintf("%02d",$d);
			$selected=date('?\d\a\t\e=Y-m-d',mktime (0,0,0,$m,$d,$y));
			if ($cur == $today) $input .= ' <td align="center" class="bg_ortcal_today">'; 
			else {
				switch ($prop) {
				case 0:
					$input .= '  <td align="center" class="bg_ortcal_easter">';
					break;
				case 1:
				case 2:
					$input .= '  <td align="center" class="bg_ortcal_holidays">';
					break;
				case 9:
					$input .= '  <td align="center" class="bg_ortcal_memory">';
					break;
				case 11:
				case 12:
					$input .= '  <td align="center" class="bg_ortcal_post_holidays">';
					break;
				case 19:
					$input .= '  <td align="center" class="bg_ortcal_post_memory">';
					break;
				default:
					if ($prop > 10) {
						if (!($i % 7)) $input .= '  <td align="center" class="bg_ortcal_post_weekend">';
						else $input .= '  <td align="center" class="bg_ortcal_post">';
					}
					else {
						if (!($i % 7)) $input .= '  <td align="center" class="bg_ortcal_weekend">';
						else $input .= '  <td align="center" class="bg_ortcal_day">';
					}
					break;
				}
			}

			$input .= '<a href="'. $bg_ortcal_page . $selected. '" title="'. htmlspecialchars ( strip_tags($info), ENT_QUOTES ). '">'.$d.'</a>'; 
		}
		$input .= "</td>\n";
		if (!($i % 7))  $input .= " </tr>\n";
	} 
	$input .= '</table>';
	return $input; 
}


// Функция обработки шорт-кода MonthInfo
function bg_ortcal_MonthInfo($atts) {
	extract( shortcode_atts( array(
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
		'date' => 'l, j F Y г. ',			// Формат даты по нов. стилю
		'old' => '(j F ст.ст.)',			// Формат даты по ст. стилю
		'sedmica' => 'on',					// Седмица
		'memory' => 'on',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 7,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'saints' => 'off',					// Святые
		'martyrs' => 'off',					// Новомученники и исповедники российские
		'icons' => 'off',					// Дни почитания икон Богоматери
		'posts' => 'off',					// Постные дни
		'noglans' => 'off',					// Дни, в которые браковенчание не совершается
		'readings' => 'off',				// Чтения Апостола и Евангелие
		'links' => 'on',					// Ссылки и цитаты
		'custom' => 'off',						// Пользовательские ссылки
	), $atts ) );
	
// Если $day задано значение "get", то получаем $month и $year из ссылки	
	if ($day == "get") {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($year,$month, $day) = explode("-",$dd);
	}
// ===========================================================================
	$quote = "";
	if ($year == '') $year = date('Y');
	if ($month == '' || ($month < 1 || $month > 12)) $month = date('m');
	$days = ortcal_numDays ($month, $year);
	for ( $day = 1;  $day <= $days; $day++) {
		$quote .= bg_ortcal_showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom )."<hr>";
	}
	return "{$quote}";
}
// Функция обработки шорт-кода OldStyle
function bg_ortcal_OldStyle($atts) {
	extract( shortcode_atts( array(
		'day' => '',
		'month' => '',
		'year' => '',
		'old' => 'l, j F Y г. ст.ст.',
	), $atts ) );
			
	if ($year == '') $year = date('Y');
	if ($month == '' || ($month < 1 || $month > 12)) $month = date('m');
	if ($day == '') $day = date('d');
	$days = ortcal_numDays ($month, $year);
	if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
	if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
	
	$quote = '<span class="bg_ortcal_OldStyle_old'.(($wd==0)?' bg_ortcal_OldStyle_sunday':'').'">'.ortcal_oldStyle ($old,  $month, $day, $year).'</span>';
	return "{$quote}";
}
// Функция обработки шорт-кода Sedmica
function bg_ortcal_Sedmica($atts) {
	extract( shortcode_atts( array(
		'day' => '',
		'month' => '',
		'year' => '',
	), $atts ) );
			
	if ($year == '') $year = date('Y');
	if ($month == '' || ($month < 1 || $month > 12)) $month = date('m');
	if ($day == '') $day = date('d');
	$days = ortcal_numDays ($month, $year);
	if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
	if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
	
	$quote = '<span class="bg_ortcal_Sedmica_sedmica'.(($wd==0)?' bg_ortcal_Sedmica_sunday':'').'">'.ortcal_sedmica ($month, $day, $year).'</span>';
	return "{$quote}";
}

// Функция обработки шорт-кода Readings
function bg_ortcal_Readings ($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
		'readings' => 'G',					// Чтения Апостола и Евангелие
		'links' => 't_verses',				// Ссылки и цитаты
	), $atts ) );

	return bg_ortcal_showDayInfo ( $day, $month, $year, 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', $readings, $links, 'off' );
}
// Функция обработки шорт-кода DayInfo_all
function bg_ortcal_DayInfo_all ($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
	), $atts ) );

// Если $day задано значение "get", то получаем $day, $month и $year из ссылки	
	if ($day == "get") {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($year,$month, $day) = explode("-",$dd);
	}
// ===========================================================================
	return bg_ortcal_showDayInfo ( $day, $month, $year, 'l, j F Y г. ', '(j F ст.ст.)', 'on', 'on', 'on', 'on', 'on', '<b>День памяти святых:</b><br>', '<b>День памяти исповедников и новомучеников российских:</b><br>', '<b>День почитания икон Божией Матери:</b><br>', '<hr />', 'on', '<hr /><b>Чтения дня:</b><br>', 'on', '<hr />См. также:' );
}

// Функция обработки шорт-кода UpcomingEvents
function bg_ortcal_UpcomingEvents($atts) {
	extract( shortcode_atts( array(
		'numdays' => 14,					// Количество дней поиска ближайших событий
		'date' => 'j F ',					// Формат даты по нов. стилю
		'old' => '(j F ст.ст.)',			// Формат даты по ст. стилю
		'sedmica' => 'off',					// Седмица
		'memory' => 'off',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 2,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'saints' => 'off',					// Святые
		'martyrs' => 'off',					// Новомученники и исповедники российские
		'icons' => 'off',					// Дни почитания икон Богоматери
		'posts' => 'off',					// Постные дни
		'noglans' => 'off',					// Дни, в которые браковенчание не совершается
		'readings' => 'off',				// Чтения Апостола и Евангелие
		'links' => 'off',					// Ссылки и цитаты
		'custom' => 'off',					// Пользовательские ссылки
	), $atts ) );

	$key='up_'.date("m.d.y").md5(json_encode($atts));
	if(false===($t=wp_cache_get($key,'bg-ortho-cal'))) {
		$day = '';                        // День (по умолчанию - сегодня)
		$month = '';                    // Месяц (по умолчанию - сегодня)
		$year = '';                        // Год (по умолчанию - сегодня)

		if ($numdays < 1) $numdays = 14;
		$t = "";
		for ($n = 0; $n < $numdays; $n++) {
			$d = "+" . ($n + 1);
			$tt = bg_ortcal_showDayInfo($d, $month, $year, "", "", $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom);
			if ($tt) $t .= bg_ortcal_showDayInfo($d, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom);
		}
		wp_cache_set($key,$t,'bg-ortho-cal',12*3600);
	}
	return $t;
}
// Определить версию плагина
function bg_ortcal_get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}
function bg_ortcal_load_xml() {
	if(false===($events=wp_cache_get('bg-orthodox-calendar-events','bg-ortho-cal'))) {
		
		$events = false;
		$only_customXML = get_option( "bg_ortcal_only_customXML" );
	// Загружаем в память базу данных событий из XML
		if ($only_customXML != "on") {	
			$plugins_dir = dirname(__FILE__) . '/MemoryDays.xml';
			$xml = ortcal_getXML($plugins_dir);
			if ($xml) $events = bg_ortcal_events_array($xml["event"]);
		}
			
		$customXML_val = get_option("bg_ortcal_customXML");
		if (is_file(ABSPATH . $customXML_val)) {
			$custom_xml = ortcal_getXML(ABSPATH . $customXML_val);
			if ($custom_xml) {
				if ($events) {
					$custom_events = bg_ortcal_events_array($custom_xml["event"]);
					if ($custom_events) $events = array_merge($custom_events, $events);
				}
				else $events = bg_ortcal_events_array($custom_xml["event"]);
			}
		}
		wp_cache_set('bg-orthodox-calendar-events',$events,'bg-ortho-cal',3600);
	}
	return $events;
}
// Дополняет пропущенные элементы массива
function bg_ortcal_events_array($event) {
	$cnt = count ($event);
	for ($i=0; $i < $cnt; $i++) {
		if (!array_key_exists ( "s_month" , $event[$i] )) $event[$i]["s_month"]=0;
		if (!array_key_exists ( "s_date" , $event[$i] )) $event[$i]["s_date"]=0;
		if (!array_key_exists ( "f_month" , $event[$i] )) $event[$i]["f_month"]=0;
		if (!array_key_exists ( "f_date" , $event[$i] )) $event[$i]["f_date"]=0;
		if (!array_key_exists ( "name" , $event[$i] )) $event[$i]["name"]="";
		if (!array_key_exists ( "type" , $event[$i] )) $event[$i]["type"]=0;
		if (!array_key_exists ( "link" , $event[$i] )) $event[$i]["link"]="";
		if (!array_key_exists ( "discription" , $event[$i] )) $event[$i]["discription"]="";
	}
	return $event;
}
/*****************************************************************************************
	Параметры плагина
	
******************************************************************************************/
// Задание параметров по умолчанию
function bg_ortcal_options_ini () {
	add_option('bg_ortcal_mainColor', "#000000");
	add_option('bg_ortcal_mainBgColor', "#EEEEEE");
	add_option('bg_ortcal_titleColor', "#132E64");
	add_option('bg_ortcal_otherColor', "#FFFFFF");
	add_option('bg_ortcal_otherBgColor', "#132E64");
	add_option('bg_ortcal_overColor', "#FFFFFF");
	add_option('bg_ortcal_overBgColor', "#406FCD");
	add_option('bg_ortcal_todayColor', "#FFFFFF");
	add_option('bg_ortcal_todayBgColor', "#335AAB");
	add_option('bg_ortcal_weddingColor', "#335AAB");
	add_option('bg_ortcal_Zindex', "10000");
	add_option('bg_ortcal_popmenu1', "Официальный календарь РПЦ");
	add_option('bg_ortcal_popmenu2', "Календарь на Православие.Ru");
	add_option('bg_ortcal_popmenu3', "Богослужебные указания");
	add_option('bg_ortcal_popmenu101', "Этот день в календаре (страница)");
	add_option('bg_ortcal_popmenu1001', "Этот день в календаре (окно)");
	add_option('bg_ortcal_popmenu1002', "Выбор имени по Месяцеслову");
	add_option('bg_ortcal_dblClick', "2");
	add_option('bg_ortcal_page', "");
	add_option('bg_ortcal_customXML', "");
	add_option('bg_ortcal_only_customXML');
	add_option('bg_ortcal_linkImage', "");
	add_option('bg_ortcal_addDate');
	add_option('bg_ortcal_fgc', "on");
	add_option('bg_ortcal_fopen', "on");
	add_option('bg_ortcal_curl', "on");
}

// Очистка таблицы параметров при удалении плагина
function bg_ortcal_deinstall() {
	delete_option('bg_ortcal_mainColor');
	delete_option('bg_ortcal_mainBgColor');
	delete_option('bg_ortcal_titleColor');
	delete_option('bg_ortcal_otherColor');
	delete_option('bg_ortcal_otherBgColor');
	delete_option('bg_ortcal_overColor');
	delete_option('bg_ortcal_overBgColor');
	delete_option('bg_ortcal_todayColor');
	delete_option('bg_ortcal_todayBgColor');
	delete_option('bg_ortcal_weddingColor');
	delete_option('bg_ortcal_Zindex');
	delete_option('bg_ortcal_popmenu1');
	delete_option('bg_ortcal_popmenu2');
	delete_option('bg_ortcal_popmenu3');
	delete_option('bg_ortcal_popmenu101');
	delete_option('bg_ortcal_popmenu1001');
	delete_option('bg_ortcal_popmenu1002');
	delete_option('bg_ortcal_dblClick');
	delete_option('bg_ortcal_page');
	delete_option('bg_ortcal_customXML');
	delete_option('bg_ortcal_only_customXML');
	delete_option('bg_ortcal_linkImage');
	delete_option('bg_ortcal_addDate');
	delete_option('bg_ortcal_fgc');
	delete_option('bg_ortcal_fopen');
	delete_option('bg_ortcal_curl');

	delete_option('bg_ortcal_submit_hidden');
}
