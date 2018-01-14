<?php
/* 
    Plugin Name: Bg Orthodox Calendar 
    Plugin URI: http://bogaiskov.ru/plugin-orthodox-calendar/
    Description: Плагин выводит на экран православный календарь: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, дни почитания икон, посты и сплошные седмицы. 
    Author: VBog
    Version: 0.13.7
    Author URI: http://bogaiskov.ru 
	License:     GPL2
	Text Domain: bg_ortcal
	Domain Path: /languages
*/

/*  Copyright 2017  Vadim Bogaiskov  (email: vadim.bogaiskov@gmail.com)

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

define('BG_ORTCAL_VERSION', '0.13.7');

// Загрузка интернационализации
add_action( 'plugins_loaded', 'bg_ortcal_load_textdomain' );
function bg_ortcal_load_textdomain() {
  load_plugin_textdomain( 'bg_ortcal', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

// Подключаем дополнительные модули
include_once('includes/settings.php');
include_once('includes/days.php');

// Если плагин обновился, сбросить весь внутренний кеш и создать новый
if (BG_ORTCAL_VERSION != get_option( "bg_ortcal_version" )) {
	bg_ortcal_delete_transients();
	wp_schedule_single_event( time() + 60, 'bg_ortcal_create_year' );	// Запустить выполнение через 1 минуту
	update_option( "bg_ortcal_version", BG_ORTCAL_VERSION );
}
// Создадим кеш календаря на ближайшие 366 дней в фоновом режиме
function bg_ortcal_create_year_now() {
	$m = date('n');
	$y = date('Y');
	for ($d=1; $d<=366; $d++) {
		$month =  date( 'n', mktime ( 0, 0, 0, $m, $d, $y ));
		$day =  date( 'j', mktime ( 0, 0, 0, $m, $d, $y ));
		$year =  date( 'Y', mktime ( 0, 0, 0, $m, $d, $y ));
		bg_ortcal_dayEvents($month, $day, $year);
	}
}
add_action('bg_ortcal_create_year','bg_ortcal_create_year_now' );

// Каждый месяц создаем кеш на такой же месяц следующего года
if (intval (date('Ym')) > intval (get_option( "bg_ortcal_last_month" ))) {
	wp_schedule_single_event( time() + 300, 'bg_ortcal_create_month' );	// Запустить выполнение через 5 минут
	update_option( "bg_ortcal_last_month", date('Ym') );
}
// Создадим кеш календаря через год на 31 день в фоновом режиме
function bg_ortcal_create_month_now() {
	$m = date('n');
	$y = date('Y')+1;
	for ($d=1; $d<=31; $d++) {
		$month =  date( 'n', mktime ( 0, 0, 0, $m, $d, $y ));
		$day =  date( 'j', mktime ( 0, 0, 0, $m, $d, $y ));
		$year =  date( 'Y', mktime ( 0, 0, 0, $m, $d, $y ));
		bg_ortcal_dayEvents($month, $day, $year);
	}
}
add_action('bg_ortcal_create_month','bg_ortcal_create_month_now' );


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
	$ajaxurl = admin_url('admin-ajax.php');
	wp_localize_script( 'bg_ortcal_init', 'bg_ortcal', array( 'ajaxurl' => $ajaxurl ) );
	wp_localize_script( 'bg_ortcal_year', 'bg_ortcal_lang', 
		array( 
			'mns' => array(__("Январь", 'bg_ortcal'),__("Февраль", 'bg_ortcal'),__("Март", 'bg_ortcal'),__("Апрель", 'bg_ortcal'),__("Май", 'bg_ortcal'),__("Июнь", 'bg_ortcal'),__("Июль", 'bg_ortcal'),__("Август", 'bg_ortcal'),__("Сентябрь", 'bg_ortcal'),__("Октябрь", 'bg_ortcal'),__("Ноябрь", 'bg_ortcal'),__("Декабрь", 'bg_ortcal')), 
			'mnr' => array(__("января", 'bg_ortcal'),__("февраля", 'bg_ortcal'),__("марта", 'bg_ortcal'),__("апреля", 'bg_ortcal'),__("мая", 'bg_ortcal'),__("июня", 'bg_ortcal'),__("июля", 'bg_ortcal'),__("августа", 'bg_ortcal'),__("сентября", 'bg_ortcal'),__("октября", 'bg_ortcal'),__("ноября", 'bg_ortcal'),__("декабря", 'bg_ortcal')),
			'cwd' => array(__("Воскресение", 'bg_ortcal'),__("Понедельник", 'bg_ortcal'),__("Вторник", 'bg_ortcal'),__("Среда", 'bg_ortcal'),__("Четверг", 'bg_ortcal'),__("Пятница", 'bg_ortcal'),__("Суббота", 'bg_ortcal')),
			'wds' => array(__("Пн", 'bg_ortcal'),__("Вт", 'bg_ortcal'),__("Ср", 'bg_ortcal'),__("Чт", 'bg_ortcal'),__("Пт", 'bg_ortcal'),__("Сб", 'bg_ortcal'),__("Вс", 'bg_ortcal')),
			'typicon' => array(__("Светлое Христово Воскресение", 'bg_ortcal'),__("Двунадесятый праздник", 'bg_ortcal'),__("Великий праздник", 'bg_ortcal'),__("Средний бденный праздник", 'bg_ortcal'),__("Средний полиелейный праздник", 'bg_ortcal'),__("Малый славословный праздник", 'bg_ortcal'),__("Малый шестиричный праздник", 'bg_ortcal'),__("Вседневный праздник", 'bg_ortcal'),__("Памятная дата", 'bg_ortcal'),__("День особого поминовения усопших", 'bg_ortcal')),

			'post' => __("Постный день", 'bg_ortcal'),
			'nowedding' => __("Браковенчание не совершается", 'bg_ortcal'),
			'wedding' => __("Показать дни браковенчаний", 'bg_ortcal'),
			'help' => __("Если навести мышку на какую-нибудь дату высвечиваются: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, посты и сплошные седмицы.\nЕсли нажать на кнопку мыши на одном из дней текущего года, открывается дополнительное меню.", 'bg_ortcal'),
			'title' => __("Выберите дату", 'bg_ortcal'),
			'close' => __("Закрыть", 'bg_ortcal'),
			'prev' => __("предыдущий год", 'bg_ortcal'),
			'next' => __("следующий год", 'bg_ortcal'),
			'yesterday' => __("вчера", 'bg_ortcal'),
			'today' => __("сегодня", 'bg_ortcal'),
			'tomorrow' => __("завтра", 'bg_ortcal'),
			'oldstyle' => __("ст.ст.", 'bg_ortcal'),

			'the_title1' => __("Наречение имени по месяцеслову", 'bg_ortcal'),
			'the_title2' => __("Православный календарь", 'bg_ortcal'),
			'title1' => __("День рождения", 'bg_ortcal'),
			'title2' => __("Наречение имени на 8-й день от рождения", 'bg_ortcal'),
			'title3' => __("Таинство крещения на", 'bg_ortcal'),
			'title3a' => __("-й день от рождения", 'bg_ortcal'),
			'prev_day' => __("Предыдущий день", 'bg_ortcal'),
			'next_day' => __("Следующий день", 'bg_ortcal'),
			
			'nedela' => __("Неделя", 'bg_ortcal'),
			'sedmica' => __("Седмица", 'bg_ortcal'),
			'po50' => __("-я по Пятидесятнице", 'bg_ortcal'),
			'posta' => __("-я Великого поста", 'bg_ortcal'),
			'popasche' => __("-я по Пасхе", 'bg_ortcal'),
			'n1pred' => __("Неделя о мытаре и фарисее", 'bg_ortcal'),
			's1pred' => __("Седмица о мытаре и фарисее", 'bg_ortcal'),
			'n2pred' => __("Неделя о блудном сыне", 'bg_ortcal'),
			's2pred' => __("Седмица о блудном сыне", 'bg_ortcal'),
			'n3pred' => __("Неделя мясопустная, о Страшнем суде", 'bg_ortcal'),	
			's3pred' => __("Сырная седмица (масленица)", 'bg_ortcal'),
			'n4pred' => __("Неделя сыропустная. Воспоминание Адамова изгнания. Прощеное воскресенье", 'bg_ortcal'),
			's6post' => __("Седмица 6-я Великого поста (седмица ваий)", 'bg_ortcal'),
			'n6post' => __("Неделя 6-я Великого поста ваий (цветоносная, Вербное воскресенье)", 'bg_ortcal'),
			's7post' => __("Страстная седмица", 'bg_ortcal'),
			'spascha' => __("Пасхальная (Светлая) седмица", 'bg_ortcal'),
			's1po50' => __("Седмица 1-я по Пятидесятнице (Троицкая)", 'bg_ortcal'),
			'bc' => __('в Год Рождества Христова', 'bg_ortcal'),
			'b_bc' => __('г. до РХ', 'bg_ortcal'),
			'a_bc' => __('г. от РХ', 'bg_ortcal'),
			't07' => __('Праздники:', 'bg_ortcal'),
			't16' => __('Соборы святых:', 'bg_ortcal'),
			't18' => __('День памяти святых:', 'bg_ortcal'),
			't19' => __('День памяти исповедников и новомучеников Церкви Русской:', 'bg_ortcal'),
			't17' => __('День почитания икон Божией Матери:', 'bg_ortcal'),
		) 
	);
}
function bg_ortcal_js_options () { 
	global $events;
		
	$customXML_val=get_option( "bg_ortcal_customXML" );
    $popmenu1_val = get_option( "bg_ortcal_popmenu1" );
    $popmenu2_val = get_option( "bg_ortcal_popmenu2" );
    $popmenu3_val = get_option( "bg_ortcal_popmenu3" );
    $popmenu4_val = get_option( "bg_ortcal_popmenu4" );
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
		if ($popmenu4_val) {echo 'bg_ortcal_popmenu['.$i.']={name: "'.$popmenu4_val.'", type: 4};';$i++;}
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
// Устанавливаем часовой пояс
if (get_option( "bg_ortcal_timezone", "WP" ) == "WP") 								
	date_default_timezone_set(wp_get_timezone_string());
else 
	date_default_timezone_set(get_option( "bg_ortcal_timezone", "WP" )); 

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
	add_shortcode( 'ortcal_year', 'bg_ortcal_year' );
	add_shortcode( 'DayInfo', 'bg_ortcal_DayInfo' );		// Для совместимости с версией 0.4
	add_shortcode( 'OldStyle', 'bg_ortcal_OldStyle' );		// Для совместимости с версией 0.4
	add_shortcode( 'Sedmica', 'bg_ortcal_Sedmica' );		// Для совместимости с версией 0.4
	add_shortcode( 'dayinfo', 'bg_ortcal_DayInfo' );
	add_shortcode( 'next_day', 'bg_ortcal_nextday' );
	add_shortcode( 'prev_day', 'bg_ortcal_prevday' );
	add_shortcode( 'ortcal', 'bg_ortcal_setDate' );
	add_shortcode( 'monthinfo', 'bg_ortcal_MonthInfo' );
	add_shortcode( 'oldstyle', 'bg_ortcal_OldStyle' );
	add_shortcode( 'sedmica', 'bg_ortcal_Sedmica' );
	add_shortcode( 'readings', 'bg_ortcal_Readings' );
	add_shortcode( 'dayinfo_all', 'bg_ortcal_DayInfo_all' ); 
	add_shortcode( 'upcoming_events', 'bg_ortcal_UpcomingEvents' ); 
	add_shortcode( 'schedule', 'bg_ortcal_schedule' ); 
	
}
// Функция действия перед крючком добавления меню
function bg_ortcal_add_pages() {
    // Добавим новое подменю в раздел Параметры 
    add_options_page( __('Православный календарь', 'bg_ortcal'), __('Православный календарь', 'bg_ortcal'), 'manage_options', __FILE__, 'bg_ortcal_options_page');
}
/*****************************************************************************************
	Функции запуска плагина
	
******************************************************************************************/

// Функция обработки шорт-кода ortcal_button
function bg_ortcal_button($atts) {
	extract( shortcode_atts( array(
		'val' => __('Календарь на год', 'bg_ortcal')
	), $atts ) );

	$quote = "<button onClick='bg_ortcal_bscal.show();'>".$val."</button>";
	return "{$quote}";
}
// Функция обработки шорт-кода ortcal_year
function bg_ortcal_year($atts) {
	extract( shortcode_atts( array(
		'val' => __('Календарь на год', 'bg_ortcal')
	), $atts ) );

	$quote = "<div id='bg_ortcal_year' title='".$val."'><p id='bg_ortcal_loading'>".__('Загрузка календаря', 'bg_ortcal')." ...</p></div>";
	return "{$quote}";
}
// Функция обработки шорт-кода DayInfo
function bg_ortcal_DayInfo($atts) {
	extract( shortcode_atts( array(
		'day' => '',						// День (по умолчанию - сегодня)
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
		'date' => __('l, j F Y г. ', 'bg_ortcal'),			// Формат даты по нов. стилю
		'old' => __('(j F ст.ст.)', 'bg_ortcal'),			// Формат даты по ст. стилю
		'sedmica' => 'on',					// Седмица
		'memory' => 'on',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 7,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'hosts' => 'off',					// Соборы святых
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
		else $dd = "";
		list($year, $month, $day) = explode("-",$dd);
	}
// ===========================================================================
	return bg_ortcal_showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $hosts, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom );
}
// Функция обработки шорт-кода next_day
function bg_ortcal_nextday($atts) {
	extract( shortcode_atts( array(
		'title' => __('Следующий день', 'bg_ortcal')	// Подпись на кнопке
	), $atts ) );
	
	if (isset($_GET['date'])) {
		$dd = $_GET["date"];
		list($year, $month, $day) = explode("-",$dd);
		$d = date ("U", mktime ( 0, 0, 0, $month, $day, $year ));
	} else {
		$d = date ("U", mktime ( 0, 0, 0 ));
	}
	$selected = date('?\d\a\t\e=Y-m-d', ortcal_add_days($d, 1));
	$input = '<a class="bg_ortcal_nextday" href="'. get_page_link( ) . $selected. '" title="'.$title.'" rel="nofollow">'.$title.'</a>'; 
	return "{$input}"; 
	
}
// Функция обработки шорт-кода prev_day
function bg_ortcal_prevday($atts) {
	extract( shortcode_atts( array(
		'title' => __('Предыдущий день', 'bg_ortcal')	// Подпись на кнопке
	), $atts ) );
	
	if (isset($_GET['date'])) {
		$dd = $_GET["date"];
		list($year, $month, $day) = explode("-",$dd);
		$d = date ("U", mktime ( 0, 0, 0, $month, $day, $year ));
	} else {
		$d = date ("U", mktime ( 0, 0, 0 ));
	}
	$selected = date('?\d\a\t\e=Y-m-d', ortcal_add_days($d, (-1)));
	$input = '<a class="bg_ortcal_prevday" href="'. get_page_link( ) . $selected. '" title="'.$title.'" rel="nofollow">'.$title.'</a>'; 
	return "{$input}"; 
	
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
	$month_names=array(__("Январь", 'bg_ortcal'),__("Февраль", 'bg_ortcal'),__("Март", 'bg_ortcal'),__("Апрель", 'bg_ortcal'),__("Май", 'bg_ortcal'),__("Июнь", 'bg_ortcal'),__("Июль", 'bg_ortcal'),__("Август", 'bg_ortcal'),__("Сентябрь", 'bg_ortcal'),__("Октябрь", 'bg_ortcal'),__("Ноябрь", 'bg_ortcal'),__("Декабрь", 'bg_ortcal')); 
	$month_names2=array(__("января", 'bg_ortcal'),__("февраля", 'bg_ortcal'),__("марта", 'bg_ortcal'),__("апреля", 'bg_ortcal'),__("мая", 'bg_ortcal'),__("июня", 'bg_ortcal'),__("июля", 'bg_ortcal'),__("августа", 'bg_ortcal'),__("сентября", 'bg_ortcal'),__("октября", 'bg_ortcal'),__("ноября", 'bg_ortcal'),__("декабря", 'bg_ortcal'));
	$day_name=array(__("Пн", 'bg_ortcal'),__("Вт", 'bg_ortcal'),__("Ср", 'bg_ortcal'),__("Чт", 'bg_ortcal'),__("Пт", 'bg_ortcal'),__("Сб", 'bg_ortcal'),__("Вс", 'bg_ortcal'));

	if (isset($_GET['date'])) {
		if (isset($_GET['date'])) $dd = $_GET["date"];
		list($y,$m, $d) = explode("-",$dd);
	}
	if (!isset($y) OR !$y) $y=date("Y");
	if (!isset($m) OR $m < 1 OR $m > 12) $m=date("m");
	
	$today=date("Y-m-d");
	$month = intval($y).'-'.sprintf("%02d",intval($m));
	$key='bg_ortcal_calendar-'.$month;
	if (date("Y-m") == $month) $key=$key.'-'.date("d");
	if(false === ($input = get_transient($key))) {
		$month_stamp=mktime(0,0,0,$m,1,$y);
		$day_count=date("t",$month_stamp);
		$weekday=date("w",$month_stamp);
		if ($weekday==0) $weekday=7;
		$start=-($weekday-2);
		$end=$start+41;
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
				 <td align="left" class="bg_ortcal_arrow" onclick="bg_ortcal_month(this,'.$prev_y.','.$prev_m.');" title="'.__('Предыдущий месяц', 'bg_ortcal').'">&lt;&lt;</td> 
				 <td align="center" class="bg_ortcal_month" style="font-size: 12px;"><span style="cursor: pointer;" onClick="bg_ortcal_bscal.show('. $y .');" title="'.__('Календарь на', 'bg_ortcal').' '. $y .' '.__('год', 'bg_ortcal').'">'. $month_names[$m-1] .' '. $y .'</span></td> 
				 <td align="right" class="bg_ortcal_arrow" onclick="bg_ortcal_month(this,'.$next_y.','.$next_m.');" title="'.__('Следующий месяц', 'bg_ortcal').'">&gt;&gt;</td> 
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
				$info = bg_ortcal_showDayInfo ( $d, $m, $y, __('l, j F Y г. ', 'bg_ortcal'), __('(j F ст.ст.)', 'bg_ortcal'), 'on', 'on', 'on', 7, 'on', 'off', 'off', 'off', 'off', 'on', 'off', 'off', 'off', 'off' );
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
						$input .= '  <td align="center" class="bg_ortcal_commemoration">';
						break;
					case 11:
					case 12:
						$input .= '  <td align="center" class="bg_ortcal_post_holidays">';
						break;
					case 19:
						$input .= '  <td align="center" class="bg_ortcal_post_commemoration">';
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

		set_transient( $key, htmlspecialchars($input, ENT_QUOTES), DAY_IN_SECONDS );
	} else $input = htmlspecialchars_decode ($input, ENT_QUOTES);
	return $input; 
}


// Функция обработки шорт-кода MonthInfo
function bg_ortcal_MonthInfo($atts) {
	extract( shortcode_atts( array(
		'month' => '',						// Месяц (по умолчанию - сегодня)
		'year' => '',						// Год (по умолчанию - сегодня)
		'date' => __('l, j F Y г. ', 'bg_ortcal'),			// Формат даты по нов. стилю
		'old' => __('(j F ст.ст.)', 'bg_ortcal'),			// Формат даты по ст. стилю
		'sedmica' => 'on',					// Седмица
		'memory' => 'on',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 7,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'hosts' => 'off',					// Соборы святых
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
		$quote .= bg_ortcal_showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $hosts, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom )."<hr>";
	}
	return "{$quote}";
}
// Функция обработки шорт-кода OldStyle
function bg_ortcal_OldStyle($atts) {
	extract( shortcode_atts( array(
		'day' => '',
		'month' => '',
		'year' => '',
		'old' => __('l, j F Y г. ст.ст.', 'bg_ortcal')
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

	return bg_ortcal_showDayInfo ( $day, $month, $year, 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', 'off', $readings, $links, 'off' );
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
		if (isset($_GET['date'])) {
			$dd = $_GET["date"];
			list($year, $month, $day) = explode("-",$dd);
		}
	}
// ===========================================================================
	return bg_ortcal_showDayInfo ( $day, $month, $year, __('l, j F Y г. ', 'bg_ortcal'), __('(j F ст.ст.)', 'bg_ortcal'), 'on', 'on', 'on', 'on', 'on', '<b>Соборы святых:</b><br>', '<b>День памяти святых:</b><br>', '<b>День памяти исповедников и новомучеников Церкви Русской:</b><br>', '<b>День почитания икон Божией Матери:</b><br>', '<hr />', 'on', '<hr /><b>Чтения дня</b><br>', 'on', '<hr />См. также:' );
}

// Функция обработки шорт-кода UpcomingEvents
function bg_ortcal_UpcomingEvents($atts) {
	extract( shortcode_atts( array(
		'numdays' => 14,					// Количество дней поиска ближайших событий
		'date' => __('j F ', 'bg_ortcal'),					// Формат даты по нов. стилю
		'old' => __('(j F ст.ст.)', 'bg_ortcal'),			// Формат даты по ст. стилю
		'sedmica' => 'off',					// Седмица
		'memory' => 'off',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 2,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'hosts' => 'off',					// Соборы святых
		'saints' => 'off',					// Святые
		'martyrs' => 'off',					// Новомученники и исповедники российские
		'icons' => 'off',					// Дни почитания икон Богоматери
		'posts' => 'off',					// Постные дни
		'noglans' => 'off',					// Дни, в которые браковенчание не совершается
		'readings' => 'off',				// Чтения Апостола и Евангелие
		'links' => 'off',					// Ссылки и цитаты
		'custom' => 'off',					// Пользовательские ссылки
	), $atts ) );

	$day = '';                        // День (по умолчанию - сегодня)
	$month = '';                    // Месяц (по умолчанию - сегодня)
	$year = '';                        // Год (по умолчанию - сегодня)

	if ($numdays < 1) $numdays = 14;
	$t = "";
	for ($n = 0; $n < $numdays; $n++) {
		$d = "+" . ($n + 1);
		$tt = bg_ortcal_showDayInfo($d, $month, $year, "", "", $sedmica, $memory, $honor, $holiday, $img, $hosts, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom);
		if ($tt) $t .= bg_ortcal_showDayInfo($d, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $hosts, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom);
	}
	return $t;
}
// Функция обработки шорт-кода [schedule]
function bg_ortcal_schedule( $atts, $content=null ) {
	extract( shortcode_atts( array(
		'period' => 's',					// Период группировки дней: m - месяц, s - седмица
		'date' => __('l, j F Y г. ', 'bg_ortcal'),			// Формат даты по нов. стилю
		'old' => __('(j F ст.ст.)', 'bg_ortcal'),			// Формат даты по ст. стилю
		'sedmica' => 'nedela',				// Седмица
		'memory' => 'on',					// Памятные дни
		'honor' => 'on',					// Дни поминовения усопших
		'holiday' => 7,						// Праздники (уровень значимости)
		'img' => 'on',						// Значок праздника по Типикону
		'hosts' => 'off',					// Соборы святых
		'saints' => 'off',					// Святые
		'martyrs' => 'off',					// Новомученники и исповедники российские
		'icons' => 'off',					// Дни почитания икон Богоматери
		'posts' => 'off',					// Постные дни
		'noglans' => 'off',					// Дни, в которые браковенчание не совершается
		'readings' => 'off',				// Чтения Апостола и Евангелие
		'links' => 'on',					// Ссылки и цитаты
		'custom' => 'off',					// Пользовательские ссылки
	), $atts ) );
	$rus_month = array(__("Январь", 'bg_ortcal'),__("Февраль", 'bg_ortcal'),__("Март", 'bg_ortcal'),__("Апрель", 'bg_ortcal'),__("Май", 'bg_ortcal'),__("Июнь", 'bg_ortcal'),__("Июль", 'bg_ortcal'),__("Август", 'bg_ortcal'),__("Сентябрь", 'bg_ortcal'),__("Октябрь", 'bg_ortcal'),__("Ноябрь", 'bg_ortcal'),__("Декабрь", 'bg_ortcal')); 
	$template = '/(\d{1,2}[\/-]\d{1,2}[\/-]\d{2,4})\s+(\d{1,2}:\d{1,2})\s(.*)/ui';
	preg_match_all($template, $content, $matches, PREG_OFFSET_CAPTURE);
	$cnt = count($matches[0]);
	$prev_date = "";
	$prev_week = 0;
	$prev_month = 0;
	$content = "<div class='bg_ortcal_schedule'><table width='100%' cellpadding='0' cellspacing='0'>";
	for ($i = 0; $i < $cnt; $i++) {
	// Проверим по каждому паттерну. 
		preg_match($template, $matches[0][$i][0], $mt);
		
		$the_time = trim($mt[2]);
		if ($the_time == '88:88') $time = strtotime (trim(str_replace('/','-',$mt[1]))." 00:00");
		else $time = strtotime (trim(str_replace('/','-',$mt[1]))." ".$the_time);
		if ($time):
			$text = trim(str_replace('<p></p>','',$mt[3]));
			$the_month = (int) date("n", $time);
			$the_week = (int) date("W", $time);
			$the_date = date("Y-m-j", $time);
			$wd = (int) date("w", $time);
			list($year, $month, $day) = explode("-", $the_date);
			if ($period == 'm' && $prev_month != $the_month) {
				$content .= "<tr><td colspan='2' class='bg_ortcal_week'>".$rus_month [$month-1]." ".$year." ".__('г.', 'bg_ortcal')."</td></tr>";
				$prev_month = $the_month;
			}
			else if ($period == 's' && $prev_week != $the_week) {
				$content .= "<tr><td colspan='2' class='bg_ortcal_week'>".ortcal_sedmica ($month, $wd?$day:($day-6), $year)."</td></tr>";
				$prev_week = $the_week;
			}
			if ($prev_date != $the_date) {
				$content .= "<tr><td style='padding-left:4.5em;' colspan='2' class='bg_ortcal_day'>".bg_ortcal_showDayInfo ( $day, $month, $year, $date, $old, $sedmica, $memory, $honor, $holiday, $img, $hosts, $saints, $martyrs, $icons, $posts, $noglans, $readings, $links, $custom )."</td></tr>";
				$prev_date = $the_date;
			}
			$content .= "<tr><td width='4em' class='bg_ortcal_time'>".(($the_time=='88:88')?"":date("G:i ", $time))."</td><td style='padding-left:0.5em;' class='bg_ortcal_event'>".$text."</td></tr>";
		endif;
	}
	$content .= "</table></div>";
	return "{$content}";
}

// Определить версию плагина
function bg_ortcal_get_plugin_version() {
	$plugin_data = get_plugin_data( __FILE__  );
	return $plugin_data['Version'];
}

/**
 * Returns the timezone string for a site, even if it's set to a UTC offset
 *
 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
 *
 * @return string valid PHP timezone string
 */
function wp_get_timezone_string() {
 
    // if site timezone string exists, return it
    if ( $timezone = get_option( 'timezone_string' ) )
        return $timezone;
 
    // get UTC offset, if it isn't set then return UTC
    if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) )
        return 'UTC';
 
    // adjust UTC offset from hours to seconds
    $utc_offset *= HOUR_IN_SECONDS;
 
    // attempt to guess the timezone string from the UTC offset
    if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
        return $timezone;
    }
 
    // last try, guess timezone string manually
    $is_dst = date( 'I' );
 
    foreach ( timezone_abbreviations_list() as $abbr ) {
        foreach ( $abbr as $city ) {
            if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset )
                return $city['timezone_id'];
        }
    }
     
    // fallback to UTC
    return 'UTC';
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
	add_option('bg_ortcal_popmenu1', __("Официальный календарь РПЦ", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu2', __("Календарь на Православие.Ru", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu3', __("Богослужебные указания", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu4', __("Календарь на Азбука веры", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu101', __("Этот день в календаре (страница)", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu1001', __("Этот день в календаре (окно)", 'bg_ortcal'));
	add_option('bg_ortcal_popmenu1002', __("Выбор имени по Месяцеслову", 'bg_ortcal'));
	add_option('bg_ortcal_dblClick', "2");
	add_option('bg_ortcal_page', "");
	add_option('bg_ortcal_customXML', "");
	add_option('bg_ortcal_only_customXML');
	add_option('bg_ortcal_linkImage', "");
	add_option('bg_ortcal_timezone',"WP");
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
	delete_option('bg_ortcal_popmenu4');
	delete_option('bg_ortcal_popmenu101');
	delete_option('bg_ortcal_popmenu1001');
	delete_option('bg_ortcal_popmenu1002');
	delete_option('bg_ortcal_dblClick');
	delete_option('bg_ortcal_page');
	delete_option('bg_ortcal_customXML');
	delete_option('bg_ortcal_only_customXML');
	delete_option('bg_ortcal_linkImage');
	delete_option('bg_ortcal_timezone');
	delete_option('bg_ortcal_addDate');
	delete_option('bg_ortcal_fgc');
	delete_option('bg_ortcal_fopen');
	delete_option('bg_ortcal_curl');
	delete_option("bg_ortcal_version");
	delete_option("bg_ortcal_last_month");
	bg_ortcal_delete_transients ();

	delete_option('bg_ortcal_submit_hidden');
}

function bg_ortcal_delete_transients() {
	global $wpdb;
	return $wpdb->query("DELETE FROM `".$wpdb->prefix."options` WHERE  `option_name` LIKE  '_transient%bg_ortcal%'");
}