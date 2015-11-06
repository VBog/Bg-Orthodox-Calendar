<?php
/* 
    Plugin Name: Bg Orthodox Calendar 
    Plugin URI: http://bogaiskov.ru/plugin-orthodox-calendar/
    Description: Плагин выводит на экран православный календарь: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, дни почитания икон, посты и сплошные седмицы. 
    Author: VBog
    Version: 0.9.2
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

define('BG_ORTCAL_VERSION', '0.9.2');

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/days.php');

// Динамическая Таблица стилей для плагина
function bg_ortcal_frontend_styles () {
	wp_enqueue_style( "bg_ortcal_colors", plugins_url( '/css/colors.php', plugin_basename(__FILE__) ), array() , BG_ORTCAL_VERSION  );
}
add_action( 'wp_enqueue_scripts' , 'bg_ortcal_frontend_styles' );
add_action( 'admin_enqueue_scripts' , 'bg_ortcal_frontend_styles' );

// JS скрипты 
function bg_ortcal_frontend_scripts () {
   	wp_enqueue_script( 'bg_ortcal_days', plugins_url( 'js/bg_ortcal_days.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_names', plugins_url( 'js/bg_ortcal_names.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_year', plugins_url( 'js/bg_ortcal_year.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
	wp_enqueue_script( 'bg_ortcal_init', plugins_url( 'js/bg_ortcal_init.js' , __FILE__ ), false, BG_ORTCAL_VERSION, true );
}
function bg_ortcal_js_options () { 
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
		var baseUrl =  "<?php echo plugins_url( '/' , __FILE__ ); ?>";
		var bg_ortcal_customXML =  "<?php if (is_file(ABSPATH."/".$customXML_val)) echo site_url()."/".$customXML_val; ?>";
		var popmenu =[];									
<?php 
		$i=0;
		if ($popmenu1_val) {echo 'popmenu['.$i.']={name:"'.$popmenu1_val.'", type: 1};'; $i++;}
		if ($popmenu2_val) {echo 'popmenu['.$i.']={name: "'.$popmenu2_val.'", type: 2};';$i++;}
		if ($popmenu3_val) {echo 'popmenu['.$i.']={name: "'.$popmenu3_val.'", type: 3};';$i++;}
		if ($popmenu101_val) {echo 'popmenu['.$i.']={name: "'.$popmenu101_val.'", type: 101};';$i++;}
		if ($popmenu1001_val) {echo 'popmenu['.$i.']={name: "'.$popmenu1001_val.'", type: 1001};';$i++;}
		if ($popmenu1002_val) {echo 'popmenu['.$i.']={name: "'.$popmenu1002_val.'", type: 1002};';$i++;}
 ?>
					
		var dblClick = <?php echo $dblClick_val; ?>;							// Пункт меню при двойном щелчке по дате (варианты см. выше)										
		var bg_ortcal_page = <?php echo '"'.$bg_ortcal_page_val. '"'; ?>;				// Постоянная ссылка на страницу с календарем
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

	bg_ortcal_load_xml();
	global $events;
	static $is_loaded = false;
	$quote = "<button onClick='bscal.show();'>".$val."</button>";
	if (!$is_loaded) {
		$quote = "<script>events=".json_encode($events)."</script>".$quote;
		$is_loaded = true;
	}
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
	), $atts ) );

// Если $day задано значение "get", то получаем $day, $month и $year из ссылки	
	if ($day == "get") {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($year,$month, $day) = explode("-",$dd);
	}
// ===========================================================================
	return showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links );
}
// Функция обработки шорт-кода ortcal
function bg_ortcal_setDate($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
	), $atts ) );

	$input = ort_calendar($year, $month); 
	
	return "{$input}"; 
}

function ort_calendar($y=null, $m=null) { 
    $bg_ortcal_page = get_option( "bg_ortcal_page" );
	if (!$bg_ortcal_page) $bg_ortcal_page = plugins_url( '/' , __FILE__ );
	$month_names=array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"); 
	$month_names2=array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря"); 

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
	$prev_month_stamp=mktime(0,0,0,$m-1,1,$y);
	$prev_day_count=date("t",$prev_month_stamp);
	$prev=date('?\d\a\t\e=Y-m-d',mktime (0,0,0,$m-1,$prev_day_count,$y));  
	$next=date('?\d\a\t\e=Y-m-d',mktime (0,0,0,$m+1,1,$y));
	$input = '
		<div class="bg_moncal">
		<table> 
		 <tr>
		  <td colspan=7 align="center"> 
		   <table width="100%" border=0 cellspacing=0 cellpadding=0> 
			<tr> 
			 <td align="left" class="arrow"><a href="'. $bg_ortcal_page . $prev .'" title="'.$prev_day_count.' '.$month_names2[$m-2].' '.$y.'">&lt;&lt;</a></td> 
			 <td align="center" class="month" style="font-size: 12px;">'. $month_names[$m-1] .' '. $y .'</td> 
			 <td align="right" class="arrow"><a href="'. $bg_ortcal_page . $next .'" title="1 '.$month_names2[$m].' '.$y.'">&gt;&gt;</a></td> 
			</tr> 
		   </table> 
		  </td> 
		 </tr> 
		 <tr><td class="week">Пн</td><td class="week">Вт</td><td class="week">Ср</td><td class="week">Чт</td><td class="week">Пт</td><td class="week">Сб</td><td class="week">Вс</td><tr>
	';
	$i=0;
	for($d=$start;$d<=$end;$d++) { 
		if (!($i++ % 7)) $input .= " <tr>\n";
		if ($d < 1 OR $d > $day_count) {
			$input .= '  <td align="center" class="day">&nbsp;';
		} else {
			$info = showDayInfo ( $d, $m, $y, 'l, j F Y г. ', '(j F ст.ст.)', 'on', 'on', 'on', 7, 'on', 'off', 'off', 'off', 'on', 'off', 'off', 'off' );
			$info = str_replace ( "<br>", "\n", $info );
			$prop = dayProperties ($m, $d, $y);
			$cur="$y-$m-".sprintf("%02d",$d);
			$selected=date('?\d\a\t\e=Y-m-d',mktime (0,0,0,$m,$d,$y));
			if ($cur == $today) $input .= ' <td align="center" class="today">'; 
			else {
				switch ($prop) {
				case 0:
					$input .= '  <td align="center" class="easter">';
					break;
				case 1:
				case 2:
					$input .= '  <td align="center" class="holidays">';
					break;
				case 9:
					$input .= '  <td align="center" class="memory">';
					break;
				case 11:
				case 12:
					$input .= '  <td align="center" class="post_holidays">';
					break;
				case 19:
					$input .= '  <td align="center" class="post_memory">';
					break;
				default:
					if ($prop > 10) {
						if (!($i % 7)) $input .= '  <td align="center" class="post_weekend">';
						else $input .= '  <td align="center" class="post">';
					}
					else {
						if (!($i % 7)) $input .= '  <td align="center" class="weekend">';
						else $input .= '  <td align="center" class="day">';
					}
					break;
				}
			}

			$input .= "<a href='". $bg_ortcal_page . $selected. "' title='". strip_tags($info). "'>".$d."</a>"; 
		}
		$input .= "</td>\n";
		if (!($i % 7))  $input .= " </tr>\n";
	} 
	$input .= '</table></div>';
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
	$days = numDays ($month, $year);
	for ( $day = 1;  $day <= $days; $day++) {
		$quote .= showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links )."<hr>";
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
	$days = numDays ($month, $year);
	if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
	if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
	
	$quote = '<span class="bg_ortcal_OldStyle_old'.(($wd==0)?' bg_ortcal_OldStyle_sunday':'').'">'.oldStyle ($old,  $month, $day, $year).'</span>';
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
	$days = numDays ($month, $year);
	if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
	if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
	
	$quote = '<span class="bg_ortcal_Sedmica_sedmica'.(($wd==0)?' bg_ortcal_Sedmica_sunday':'').'">'.sedmica ($month, $day, $year).'</span>';
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

	return showDayInfo ( $day, $month, $year, 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', $readings, $links );
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
	return showDayInfo ( $day, $month, $year, 'l, j F Y г. ', '(j F ст.ст.)', 'on', 'on', 'on', 'on', 'on', '<b>День памяти святых:</b><br>', '<b>День памяти исповедников и новомучеников российских:</b><br>', '<b>День почитания икон Божией Матери:</b><br>', '<hr />', 'on', '<hr /><b>Чтения дня:</b><br>', 'on' );
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
			$tt = showDayInfo($d, $month, $year, "", "", $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links);
			if ($tt) $t .= showDayInfo($d, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links);
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
		// Загружаем в память базу данных событий из XML
		$plugins_dir = dirname(__FILE__) . '/MemoryDays.xml';
		$xml = getXML($plugins_dir);
		if ($xml) $events = $xml["event"];
		else $events = false;
		if ($events) {
			$customXML_val = get_option("bg_ortcal_customXML");

			if (is_file(ABSPATH . $customXML_val)) {
				$custom_xml = getXML(ABSPATH . $customXML_val);
				if ($custom_xml) {
					$custom_events = $custom_xml["event"];
					if ($custom_events) {
						$events = array_merge($custom_events, $events);
					}
				}
			}
		}
		wp_cache_set('bg-orthodox-calendar-events',$events,'bg-ortho-cal',3600);
	}
	return $events;
}