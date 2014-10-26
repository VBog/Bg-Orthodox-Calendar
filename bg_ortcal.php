<?php
/* 
    Plugin Name: Bg Orthodox Calendar 
    Plugin URI: http://bogaiskov.ru/plugin-orthodox-calendar/
    Description: Плагин выводит на экран православный календарь на год: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, дни почитания икон, посты и сплошные седмицы. 
    Author: Vadim Bogaiskov
    Version: 0.7.1
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

define('BG_ORTCAL_VERSION', '0.7.1');

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
	$customXML_val=get_option( "bg_ortcal_customXML" );?>
	<script>
		var baseUrl =  "<?php echo plugins_url( '/' , __FILE__ ); ?>";
		var bg_ortcal_customXML =  "<?php if (is_file(ABSPATH."/".$customXML_val)) echo site_url()."/".$customXML_val; ?>";
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
	add_shortcode( 'monthinfo', 'bg_ortcal_MonthInfo' );
	add_shortcode( 'oldstyle', 'bg_ortcal_OldStyle' );
	add_shortcode( 'sedmica', 'bg_ortcal_Sedmica' );
	add_shortcode( 'readings', 'bg_ortcal_Readings' );
	add_shortcode( 'dayinfo_all', 'bg_ortcal_DayInfo_all' ); 
	add_shortcode( 'upcoming_events', 'bg_ortcal_UpcomingEvents' ); 
}
// Загружаем в память базу данных событий из XML
$url = plugins_url( '/MemoryDays.xml', __FILE__  );			// URL файла
$xml = getXML($url);
if ($xml) $events = $xml["event"];
else $events = false;
if ($events) {
	$customXML_val = get_option( "bg_ortcal_customXML" );

	$url = ABSPATH."/".$customXML_val;
	if (is_file($url)) {
		$custom_xml = getXML($url);
		if ($custom_xml) {
			$custom_events = $custom_xml["event"];
			if ($custom_events) {
				$events = array_merge ( $custom_events, $events );
			}
		}
	}
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

	$quote = "<button  onClick='bscal.show();'>".$val."</button>";
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

	return showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links );
}
// Функция обработки шорт-кода YearInfo
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

	return showDayInfo ( $day, $month, $year, 'l, j F Y г. ', '(j F ст.ст.)', 'on', 'on', 'on', 'on', 'on', '<b>День памяти святых:</b><br>', '<b>День памяти исповедников и новомучеников российских:</b><br>', '<b>День почитания икон Божией Матери:</b><br>', '<hr />', 'on', '<hr /><b>Чтения дня:</b><br>', 'on' );
	$quote .= showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links )."<hr>";
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

	$day = '';						// День (по умолчанию - сегодня)
	$month = '';					// Месяц (по умолчанию - сегодня)
	$year = '';						// Год (по умолчанию - сегодня)

	if ($numdays < 1) $numdays = 14;
	$t = "";
	for ($n = 0; $n < $numdays; $n++) {
		$d = "+".($n+1);
		$tt = showDayInfo ( $d, $month, $year, "", "", $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links );
		if ($tt) $t .= showDayInfo ( $d, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links );
	}
	return $t;
}
// Определить версию плагина
function bg_ortcal_get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}