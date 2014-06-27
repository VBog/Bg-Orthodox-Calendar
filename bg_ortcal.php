<?php
/* 
    Plugin Name: Bg Orthodox Calendar 
    Plugin URI: http://bogaiskov.ru/plugin-orthodox-calendar/
    Description: Плагин выводит на экран православный календарь на год: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, дни почитания икон, посты и сплошные седмицы. 
    Author: Vadim Bogaiskov
    Version: 0.4
    Author URI: http://bogaiskov.ru 
*/

/*  Copyright 2014  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

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

define('BG_ORTCAL_VERSION', '0.4');

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
function bg_ortcal_js_options () { ?>
	<script>
		var baseUrl =  "<?php echo plugins_url( '/' , __FILE__ );?>";
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
	add_shortcode( 'DayInfo', 'bg_ortcal_DayInfo' );
	add_shortcode( 'OldStyle', 'bg_ortcal_OldStyle' );
	add_shortcode( 'Sedmica', 'bg_ortcal_Sedmica' );
}
// Загружаем в память базу данных событий из XML
$xml = getXML('/MemoryDays.xml');
if ($xml) $events = $xml["event"];
else $events = false;
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

	$quote = "<button  onClick='bscal.show();'>".$val."</button>";
	return "{$quote}";
}
// Функция обработки шорт-кода DayInfo
function bg_ortcal_DayInfo($atts) {
	extract( shortcode_atts( array(
		'day' => '',
		'month' => '',
		'year' => '',
		'date' => 'l, j F Y г. ',
		'old' => '(j F ст.ст.)',
		'sedmica' => 'on',
		'memory' => 'on',
		'holiday' => 7,
		'img' => 'on',
		'saints' => 'off',
		'martyrs' => 'off',
		'icons' => 'off',
		'posts' => 'off',
		'noglans' => 'off',
	), $atts ) );
			
	if ($year == '') $year = date('Y');
	if ($month == '' || ($month < 1 || $month > 12)) $month = date('m');
	if ($day == '') $day = date('d');
	$days = numDays ($month, $year);
	if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
	if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
	
	if ($sedmica != 'off') $sedmica = 'on';
	if ($memory != 'off') $memory = 'on';
	
	if (!is_numeric ( $holiday ) && $holiday != 'off') $holiday = 7;
	if ($holiday < 0) $holiday = 0;
	if ($holiday > 7) $holiday = 7;
	if ($img != 'off') $img = 'on';
	
	$quote = '';
	if ($date != 'off' && $date != '') $quote .= '<span class="bg_ortcal_date'.(($wd==0)?' bg_ortcal_sunday':'').'">'.dateRU (date($date, mktime(0, 0, 0, $month, $day, $year))).'</span>';
	if ($old != 'off' && $old != '') $quote .= '<span class="bg_ortcal_old'.(($wd==0)?' bg_ortcal_sunday':'').'">'.oldStyle ($old,  $month, $day, $year).'</span><br>';
	if ($sedmica != 'off') $quote .= '<span class="bg_ortcal_sedmica'.(($wd==0)?' bg_ortcal_sunday':'').'">'.sedmica ($month, $day, $year).'</span><br>';

	$e = dayEvents($month, $day, $year);
	$cnt = count($e);
	if ($cnt) {
		// Памятные даты
		if ($memory != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 8) $q .= $e[$i]['name'].'. ';
			}
			if ($q) $quote .= '<span class="bg_ortcal_memory">'.$q.'</span><br>';
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 9) $q .= $e[$i]['name'].'. ';
			}
			if ($q) $quote .= '<span class="bg_ortcal_honor">'.$q.'</span><br>';
		}
		// Праздники
		if ($holiday != 'off') {
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] <= $holiday) {
					if ($e[$i]['type'] <= 2) $quote .= '<span class="bg_ortcal_great">'.(($img=='off')?'':imgTypicon($e[$i]['type'])).$e[$i]['name'].'</span><br>';
					else if ($e[$i]['type'] <= 4) $quote .= '<span class="bg_ortcal_middle">'.(($img=='off')?'':imgTypicon($e[$i]['type'])).$e[$i]['name'].'</span><br>';
					else $quote .= '<span class="bg_ortcal_small">'.(($img=='off')?'':imgTypicon($e[$i]['type'])).$e[$i]['name'].'</span><br>';
				}
			}
		}
		// Дни почитания святых
		if ($saints != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 18) $q .= $e[$i]['name'].'. ';
			}
			if ($q) $quote .= (($saints!='on')?htmlspecialchars_decode($saints):'').'<span class="bg_ortcal_saints">'.$q.'</span><br>';
		}
		// Дни почитания исповедников и новомучеников российских
		if ($martyrs != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 19) $q .= $e[$i]['name'].'. ';
			}
			if ($q) $quote .= (($martyrs!='on')?htmlspecialchars_decode($martyrs):'').'<span class="bg_ortcal_martyrs">'.$q.'</span><br>';
		}
		// Дни почитания икон
		if ($icons != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 17) $q .= $e[$i]['name'].'. ';
			}
			if ($q) $quote .= (($icons!='on')?htmlspecialchars_decode($icons):'').'<span class="bg_ortcal_icons">'.$q.'</span><br>';
		}
		// Посты и светлые седмицы
		if ($posts != 'off') {
			$q ="";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 10) $q = $e[$i]['name'].'. ';
			}
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 100) $q = $e[$i]['name'].'. ';
			}
			if ($q) $quote .= (($posts!='on')?htmlspecialchars_decode($posts):'').'<span class="bg_ortcal_posts">'.$q.'</span><br>';
		}
		// Дни, в которые браковенчание не проводится
		if ($noglans != 'off') {
			$q ="";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 20) $q = $e[$i]['name'].'. ';
			}
			if ($q) $quote .= (($noglans!='on')?htmlspecialchars_decode($noglans):'').'<span class="bg_ortcal_noglans">'.$q.'</span><br>';
		}
		
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
function imgTypicon ($lavel) {
	$title = array ('Светлое Христово Воскресение. Пасха', 'Двунадесятый праздник', 'Великий праздник', 'Средний бденный праздник', 'Средний полиелейный праздник', 'Малый славословный праздник', 'Малый шестиричный праздник', 'Вседневный праздник. Cовершается служба, не отмеченная в Типиконе никаким знаком');
	return '<img src="'.plugins_url( 'js/S'.$lavel.'.gif' , __FILE__ ).'" title="'.$title[$lavel].'" /> ';
}
// Определить версию плагина
function bg_ortcal_get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}