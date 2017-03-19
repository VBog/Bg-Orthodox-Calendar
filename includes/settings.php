<?php 
/******************************************************************************************
	Страница настроек
    отображает содержимое страницы для подменю Bible References
*******************************************************************************************/
function bg_ortcal_options_page() {

    // имена опций и полей
	$mainColor_name 	= "bg_ortcal_mainColor";
	$mainBgColor_name 	= "bg_ortcal_mainBgColor";
	$titleColor_name 	= "bg_ortcal_titleColor";
	$otherColor_name 	= "bg_ortcal_otherColor";
	$otherBgColor_name 	= "bg_ortcal_otherBgColor";
	$overColor_name 	= "bg_ortcal_overColor";
	$overBgColor_name 	= "bg_ortcal_overBgColor";
	$todayColor_name 	= "bg_ortcal_todayColor";
	$todayBgColor_name	= "bg_ortcal_todayBgColor"; 
	$weddingColor_name	= "bg_ortcal_weddingColor"; 
	$Zindex_name 		= "bg_ortcal_Zindex";

    $popmenu1_name = "bg_ortcal_popmenu1";						// Официальный календарь РПЦ
    $popmenu2_name = "bg_ortcal_popmenu2";						// Календарь на Православие.Ru
    $popmenu3_name = "bg_ortcal_popmenu3";						// Богослужебные указания
    $popmenu4_name = "bg_ortcal_popmenu4";						// Календарь на Azbyka.Ru
    $popmenu101_name = "bg_ortcal_popmenu101";					// Этот день в календаре
    $popmenu1001_name = "bg_ortcal_popmenu1001";				// Текущий день
    $popmenu1002_name = "bg_ortcal_popmenu1002";				// Выбор имени по Месяцеслову
    $dblClick_name = "bg_ortcal_dblClick";						// Функция при двойном щелчке по дате в календаре

    $bg_ortcal_page = "bg_ortcal_page";							// Постоянная ссылка на страницу с календарем
    $customXML_name = "bg_ortcal_customXML";					// Имя пользовательского xml-файла
    $only_customXML = "bg_ortcal_only_customXML";				// Только пользовательский xml-файл
	$bg_ortcal_linkImage = "bg_ortcal_linkImage";				// Изображение кнопки ссылки
	$bg_ortcal_timezone = "bg_ortcal_timezone";					// Временная зона для смены дат на сервере
	$bg_ortcal_addDate = "bg_ortcal_addDate";					// Добавлять дату к адресу ссылки
	$bg_fgc_name = 'bg_ortcal_fgc';								// Чтение XML-файлов с помощью file_get_contents()
	$bg_fopen_name = 'bg_ortcal_fopen';							// Чтение XML-файлов с помощью fopen()
	$bg_curl_name = 'bg_ortcal_curl';							// Чтение XML-файлов с помощью cURL
	
    $bg_ortcal_hidden_field_name = 'bg_ortcal_submit_hidden';	// Скрытое поле для проверки обновления информацции в форме
	
	bg_ortcal_options_ini (); 			// Параметры по умолчанию
	
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
	$Zindex 		= get_option( "bg_ortcal_Zindex" );
	
    $popmenu1_val = get_option( "bg_ortcal_popmenu1" );
    $popmenu2_val = get_option( "bg_ortcal_popmenu2" );
    $popmenu3_val = get_option( "bg_ortcal_popmenu3" );
    $popmenu4_val = get_option( "bg_ortcal_popmenu4" );
    $popmenu101_val = get_option( "bg_ortcal_popmenu101" );
    $popmenu1001_val = get_option( "bg_ortcal_popmenu1001" );
    $popmenu1002_val = get_option( "bg_ortcal_popmenu1002" );
    $dblClick_val = get_option( "bg_ortcal_dblClick" );

    $bg_ortcal_page_val = get_option( "bg_ortcal_page" );
    $customXML_val = get_option( "bg_ortcal_customXML" );
    $only_customXML_val = get_option( "bg_ortcal_only_customXML" );
	$bg_ortcal_linkImage_val =  get_option( "bg_ortcal_linkImage" );
	$bg_ortcal_timezone_val =  get_option( "bg_ortcal_timezone" );
	$bg_ortcal_addDate_val =  get_option( "bg_ortcal_addDate" );
    $bg_fgc_val = get_option( $bg_fgc_name );
    $bg_fopen_val = get_option( $bg_fopen_name );
    $bg_curl_val = get_option( $bg_curl_name );

// Проверяем, отправил ли пользователь нам некоторую информацию
// Если "Да", в это скрытое поле будет установлено значение 'Y'
    if( isset( $_POST[ $bg_ortcal_hidden_field_name ] ) && $_POST[ $bg_ortcal_hidden_field_name ] == 'Y' ) {

	// Сохраняем отправленное значение в БД
		$mainColor = ( isset( $_POST[$mainColor_name] ) && $_POST[$mainColor_name] ) ? $_POST[$mainColor_name] : '' ;
		update_option( $mainColor_name, $mainColor );

		$mainBgColor =( isset( $_POST[$mainBgColor_name] ) && $_POST[$mainBgColor_name] ) ? $_POST[$mainBgColor_name] : '' ;
		update_option( $mainBgColor_name, $mainBgColor );

		$titleColor = ( isset( $_POST[$titleColor_name] ) && $_POST[$titleColor_name] ) ? $_POST[$titleColor_name] : '' ;
		update_option( $titleColor_name, $titleColor );

		$otherColor = ( isset( $_POST[$otherColor_name] ) && $_POST[$otherColor_name] ) ? $_POST[$otherColor_name] : '' ;
		update_option( $otherColor_name, $otherColor );

		$otherBgColor = ( isset( $_POST[$otherBgColor_name] ) && $_POST[$otherBgColor_name] ) ? $_POST[$otherBgColor_name] : '' ;
		update_option( $otherBgColor_name, $otherBgColor );

		$overColor = ( isset( $_POST[$overColor_name] ) && $_POST[$overColor_name] ) ? $_POST[$overColor_name] : '' ;
		update_option( $overColor_name, $overColor );

		$overBgColor = ( isset( $_POST[$overBgColor_name] ) && $_POST[$overBgColor_name] ) ? $_POST[$overBgColor_name] : '' ;
		update_option( $overBgColor_name, $overBgColor );

		$todayColor = ( isset( $_POST[$todayColor_name] ) && $_POST[$todayColor_name] ) ? $_POST[$todayColor_name] : '' ;
		update_option( $todayColor_name, $todayColor );

		$todayBgColor = ( isset( $_POST[$todayBgColor_name] ) && $_POST[$todayBgColor_name] ) ? $_POST[$todayBgColor_name] : '' ;
		update_option( $todayBgColor_name, $todayBgColor );

		$weddingColor = ( isset( $_POST[$weddingColor_name] ) && $_POST[$weddingColor_name] ) ? $_POST[$weddingColor_name] : '' ;
		update_option( $weddingColor_name, $weddingColor );

		$Zindex = ( isset( $_POST[$Zindex_name] ) && $_POST[$Zindex_name] ) ? $_POST[$Zindex_name] : '' ;
		update_option( $Zindex_name, $Zindex );

		$popmenu1_val = ( isset( $_POST[$popmenu1_name] ) && $_POST[$popmenu1_name] ) ? $_POST[$popmenu1_name] : '' ;
		update_option( $popmenu1_name, $popmenu1_val );

		$popmenu2_val = ( isset( $_POST[$popmenu2_name] ) && $_POST[$popmenu2_name] ) ? $_POST[$popmenu2_name] : '' ;
		update_option( $popmenu2_name, $popmenu2_val );

		$popmenu3_val = ( isset( $_POST[$popmenu3_name] ) && $_POST[$popmenu3_name] ) ? $_POST[$popmenu3_name] : '' ;
		update_option( $popmenu3_name, $popmenu3_val );

		$popmenu4_val = ( isset( $_POST[$popmenu4_name] ) && $_POST[$popmenu4_name] ) ? $_POST[$popmenu4_name] : '' ;
		update_option( $popmenu4_name, $popmenu4_val );

		$popmenu101_val = ( isset( $_POST[$popmenu101_name] ) && $_POST[$popmenu101_name] ) ? $_POST[$popmenu101_name] : '' ;
		update_option( $popmenu101_name, $popmenu101_val );

		$popmenu1001_val = ( isset( $_POST[$popmenu1001_name] ) && $_POST[$popmenu1001_name] ) ? $_POST[$popmenu1001_name] : '' ;
		update_option( $popmenu1001_name, $popmenu1001_val );

		$popmenu1002_val = ( isset( $_POST[$popmenu1002_name] ) && $_POST[$popmenu1002_name] ) ? $_POST[$popmenu1002_name] : '' ;
		update_option( $popmenu1002_name, $popmenu1002_val );

		$dblClick_val = ( isset( $_POST[$dblClick_name] ) && $_POST[$dblClick_name] ) ? $_POST[$dblClick_name] : '' ;
		update_option( $dblClick_name, $dblClick_val );
		
		$bg_ortcal_page_val = ( isset( $_POST[$bg_ortcal_page] ) && $_POST[$bg_ortcal_page] ) ? $_POST[$bg_ortcal_page] : '' ;
		update_option( $bg_ortcal_page, $bg_ortcal_page_val );
		
		$customXML_val = ( isset( $_POST[$customXML_name] ) && $_POST[$customXML_name] ) ? $_POST[$customXML_name] : '' ;
		update_option( $customXML_name, $customXML_val );

		$only_customXML_val = ( isset( $_POST[$only_customXML] ) && $_POST[$only_customXML] ) ? $_POST[$only_customXML] : '' ;
		update_option( $only_customXML, $only_customXML_val );

		$bg_ortcal_linkImage_val = ( isset( $_POST[$bg_ortcal_linkImage] ) && $_POST[$bg_ortcal_linkImage] ) ? $_POST[$bg_ortcal_linkImage] : '' ;
		update_option( $bg_ortcal_linkImage, $bg_ortcal_linkImage_val );

		$bg_ortcal_timezone_val = ( isset( $_POST[$bg_ortcal_timezone] ) && $_POST[$bg_ortcal_timezone] ) ? $_POST[$bg_ortcal_timezone] : '' ;
		update_option( $bg_ortcal_timezone, $bg_ortcal_timezone_val );

		$bg_ortcal_addDate_val = ( isset( $_POST[$bg_ortcal_addDate] ) && $_POST[$bg_ortcal_addDate] ) ? $_POST[$bg_ortcal_addDate] : '' ;
		update_option( $bg_ortcal_addDate, $bg_ortcal_addDate_val );

		$bg_fgc_val = ( isset( $_POST[$bg_fgc_name] ) && $_POST[$bg_fgc_name] ) ? $_POST[$bg_fgc_name] : '' ;
		update_option( $bg_fgc_name, $bg_fgc_val );

		$bg_fopen_val = ( isset( $_POST[$bg_fopen_name] ) && $_POST[$bg_fopen_name] ) ? $_POST[$bg_fopen_name] : '' ;
		update_option( $bg_fopen_name, $bg_fopen_val );

		$bg_curl_val = ( isset( $_POST[$bg_curl_name] ) && $_POST[$bg_curl_name] ) ? $_POST[$bg_curl_name] : '' ;
		update_option( $bg_curl_name, $bg_curl_val );

        // Вывести сообщение об обновлении параметров на экран
		echo '<div class="updated"><p><strong>'.__('Параметры сохранены.', 'bg_ortcal').'</strong></p></div>';

        // Вывести сообщение об обновлении параметров на экран
		if ( $bg_deleted_transients = bg_ortcal_delete_transients() ){
			delete_option( "bg_ortcal_version" );
			delete_option( "bg_ortcal_last_month" );
			echo '<div class="updated"><p><strong>'.__('Внутренний кеш плагина сброшен. Удалено', 'bg_ortcal').' '.$bg_deleted_transients.' '.__('опций.', 'bg_ortcal').'</strong></p></div>';
		}
    }
?>
<!--  форма опций -->
<table width="100%">
<tr><td valign="top">
<!--  Теперь отобразим опции на экране редактирования -->
<div class="wrap">
<!--  Заголовок -->
<h2><?php _e('Параметры плагина "Православный календарь"', 'bg_ortcal') ?></h2>
<p><?php printf( __('Версия', 'bg_ortcal').' <b>'.bg_ortcal_get_plugin_version().'</b>' ); ?></p>

<!-- Форма настроек -->
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<!--  Основные параметры -->
<details>
<summary><strong><?php _e('Цвета формы календаря', 'bg_ortcal') ?></strong></summary>
<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Основной цвет текста:', 'bg_ortcal') ?></th>
	<td>
	<input type="hidden" name="<?php echo $bg_ortcal_hidden_field_name; ?>" value="Y">
	<input type="color" id="mainColor_name" name="<?php echo $mainColor_name ?>" value="<?php echo $mainColor ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Основной цвет фона:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="mainBgColor_name" name="<?php echo $mainBgColor_name ?>" value="<?php echo $mainBgColor ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет заголовков:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="titleColor_name" name="<?php echo $titleColor_name ?>" value="<?php echo $titleColor ?>"> <?php _e('(стрелки перехода на другой год, названия месяцев)', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Дополнительный цвет текста:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="otherColor_name" name="<?php echo $otherColor_name ?>" value="<?php echo $otherColor ?>"> <?php _e('(заголовок, подвал, дни недели)', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Дополнительный цвет фона:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="otherBgColor_name" name="<?php echo $otherBgColor_name ?>" value="<?php echo $otherBgColor ?>"> <?php _e('(заголовок, подвал, дни недели)', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет текста выбранного дня:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="overColor_name" name="<?php echo $overColor_name ?>" value="<?php echo $overColor ?>"> <?php _e('(день, на который указывает курсор)', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет фона выбранного дня:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="overBgColor_name" name="<?php echo $overBgColor_name ?>" value="<?php echo $overBgColor ?>"> <?php _e('(день, на который указывает курсор)', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет текста сегодня:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="todayColor_name" name="<?php echo $todayColor_name ?>" value="<?php echo $todayColor ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет фона сегодня:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="todayBgColor_name" name="<?php echo $todayBgColor_name ?>" value="<?php echo $todayBgColor ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Цвет рамки дней браковенчаний:', 'bg_ortcal') ?></th>
	<td>
	<input type="color" id="weddingColor_name" name="<?php echo $weddingColor_name ?>" value="<?php echo $weddingColor ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Z-index всплывающего окна календаря:', 'bg_ortcal') ?></th>
	<td>
	<input type="number" id="Zindex_name" name="<?php echo $Zindex_name ?>" value="<?php echo $Zindex ?>"><br />
	</td></tr>
</table>
<hr>
</details>
<br>
<details>
<summary><strong><?php _e('Элементы всплывающего меню календаря', 'bg_ortcal') ?></strong></summary>
<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Ссылка на сайт официального календаря РПЦ', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu1_name" name="<?php echo $popmenu1_name ?>" size="60" value="<?php echo $popmenu1_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Ссылка на сайт календаря на Православие.Ru', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu2_name" name="<?php echo $popmenu2_name ?>" size="60" value="<?php echo $popmenu2_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Ссылка на сайт богослужебных указаний', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu3_name" name="<?php echo $popmenu3_name ?>" size="60" value="<?php echo $popmenu3_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Ссылка на сайт календаря Азбука веры', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu4_name" name="<?php echo $popmenu4_name ?>" size="60" value="<?php echo $popmenu4_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Этот день в календаре (страница сайта)', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu101_name" name="<?php echo $popmenu101_name ?>" size="60" value="<?php echo $popmenu101_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Этот день в календаре (всплывающее окно)', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu1001_name" name="<?php echo $popmenu1001_name ?>" size="60" value="<?php echo $popmenu1001_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Выбор имени по Месяцеслову (всплывающее окно)', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="popmenu1002_name" name="<?php echo $popmenu1002_name ?>" size="60" value="<?php echo $popmenu1002_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Функция при двойном щелчке по дате', 'bg_ortcal') ?></th>
	<td>
	<select name="<?php echo $dblClick_name ?>">
	<option value=1 <?php if($dblClick_val==1) echo 'selected="selected"'; ?> ><?php _e('Официальный календарь РПЦ', 'bg_ortcal') ?></option>
	<option value=2 <?php if($dblClick_val==2) echo 'selected="selected"'; ?> ><?php _e('Календарь на Православие.Ru', 'bg_ortcal') ?></option>
	<option value=3 <?php if($dblClick_val==3) echo 'selected="selected"'; ?> ><?php _e('Богослужебные указания', 'bg_ortcal') ?></option>
	<option value=101 <?php if($dblClick_val==101) echo 'selected="selected"'; ?> ><?php _e('Этот день в календаре (страница)', 'bg_ortcal') ?></option>
	<option value=1001 <?php if($dblClick_val==1001) echo 'selected="selected"'; ?> ><?php _e('Этот день в календаре (окно)', 'bg_ortcal') ?></option>
	<option value=1002 <?php if($dblClick_val==1002) echo 'selected="selected"'; ?> ><?php _e('Выбор имени по Месяцеслову', 'bg_ortcal') ?></option>
	</select>
	</td></tr>
</table>
<hr>
</details>

<br>
<details>
<summary><strong><?php _e('Прочие параметры ...', 'bg_ortcal') ?></strong></summary>
<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Постоянная ссылка на страницу с календарем', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="bg_ortcal_page" name="<?php echo $bg_ortcal_page ?>" size="60" value="<?php echo $bg_ortcal_page_val ?>"><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Пользовательский XML-файл данных', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="customXML_name" name="<?php echo $customXML_name ?>" size="60" value="<?php echo $customXML_val ?>">
	<input type="checkbox" id="only_customXML" name="<?php echo $only_customXML ?>" <?php if($only_customXML_val=="on") echo "checked" ?> value="on"> <?php _e('Только пользовательский XML-файл', 'bg_ortcal') ?><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Изображение кнопки ссылки', 'bg_ortcal') ?></th>
	<td>
	<input type="text" id="bg_ortcal_linkImage" name="<?php echo $bg_ortcal_linkImage ?>" size="60" value="<?php echo $bg_ortcal_linkImage_val ?>"><br />
	<i><?php _e('(Если изображение не задано (указан просто текст), то отображается указанный здесь текст в виде текстовой ссылки.<br> Если ничего не указано (пустая строка), то отображается название события как простая текстовая ссылка.)', 'bg_ortcal') ?></i><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Часовой пояс', 'bg_ortcal') ?></th>
	<td>
	<select name="<?php echo $bg_ortcal_timezone ?>">
		<?php 
		$array = DateTimeZone::listIdentifiers();
		foreach($array as $value) {
			echo "<option value='".$value."'".(($bg_ortcal_timezone_val==$value)? " selected='selected'":"").">".__($value)."</option>";
		}
		echo "<option value='WP'".(($bg_ortcal_timezone_val=='WP')? " selected='selected'":"").">".__('Часовой пояс', 'bg_ortcal')." WP (".wp_get_timezone_string().")</option>";
		?>
	</select><br /><i><?php _e('(для определения даты на сервере)', 'bg_ortcal') ?></i><br /><?php _e('Местное время на сервере:', 'bg_ortcal') ?> <code><?php echo date('d-m-Y, H:i:s',time()); ?></code><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Добавлять дату к ссылке в событии календаря', 'bg_ortcal') ?></th>
	<td>
	<input type="checkbox" id="bg_addDate" name="<?php echo $bg_ortcal_addDate ?>" <?php if($bg_ortcal_addDate_val=="on") echo "checked" ?> value="on"><br><i><?php _e('Например', 'bg_ortcal') ?>, http:\\my-link.ru<b>?date=2015-07-08</b></i><br />
	</td></tr>
	<tr valign="top">
	<th scope="row"><?php _e('Метод чтения файлов', 'bg_ortcal') ?></th>
	<td>
	<input type="checkbox" id="bg_fgc" name="<?php echo $bg_fgc_name ?>" <?php if($bg_fgc_val=="on") echo "checked" ?>  value="on"> file_get_contents()<br />
	<input type="checkbox" id="bg_fopen" name="<?php echo $bg_fopen_name ?>" <?php if($bg_fopen_val=="on") echo "checked" ?>  value="on"> fopen() - fread() - fclose()<br />
	<input type="checkbox" id="bg_curl" name="<?php echo $bg_curl_name ?>" <?php if($bg_curl_val=="on") echo "checked" ?> value="on"> cURL<br />
	<i><?php _e('(Плагин пытается загружать XML-файлы данных отмеченными методами в указанном порядке.<br>Чтобы сделать загрузку более быстрой отключите лишние методы.', 'bg_ortcal') ?><br><u><?php _e('Внимание:', 'bg_ortcal') ?></u> <?php _e('Некоторые методы могут быть недоступны на Вашем сервере.)', 'bg_ortcal') ?></i><br />
	</td></tr>
</table>
</details>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Сохранить настройки', 'bg_ortcal') ?>" />
</p>

</form>
</div>
<div>
<img src="<?php echo plugins_url('/bg_pbu.jpg', plugin_basename(__FILE__) ); ?>" style="float:left;  margin: 2px 7px 0px 0px;" width="32" height="32" /><?php _e('Новый плагин', 'bg_ortcal') ?> <a href="https://wordpress.org/plugins/bg-patriarchia-bu/" target="_blank">Bg Patriarchia BU</a> <?php _e('копирует богослужебные указания с сайта Patriarchia.ru и вставляет их на страницу вашего сайта.', 'bg_ortcal') ?><br>
<?php _e('Плагин может работать как автономно, так и совместно с плагином "Православный календарь".', 'bg_ortcal') ?>
</div>
</td>

<!-- Информация о плагине -->
<td valign="top" align="left" width="45em">

<div class="bg_ortcal_info_box">

	<h3><?php _e('Спасибо, что используете Православный календарь!', 'bg_ortcal') ?></h3>
	<p class="bg_ortcal_gravatar"><a href="http://bogaiskov.ru" target="_blank"><?php echo get_avatar("vadim.bogaiskov@gmail.com", '64'); ?></a></p>
	<p><?php _e('Дорогие братия и сестры!<br />Спасибо, что используете мой плагин!<br />Я надеюсь, что он оказался полезен для вашего сайта.', 'bg_ortcal') ?></p>
	<p class="bg_ortcal_author"><a href="http://bogaiskov.ru" target="_blank">Вадим Богайсков</a></p>

	<h3><?php _e('Мне нравится этот плагин. Как я могу Вас отблагодарить?', 'bg_ortcal') ?></h3>
	<p><?php _e('Есть несколько путей сделать это:', 'bg_ortcal') ?></p>
	<ul>
		<li><?php _e('<a href="http://hpf.ru.com/" target="_blank">Сделать пожертвование</a> на храм свв. Петра и Февронии в Марьино.', 'bg_ortcal') ?></li>
		<li><?php _e('<a href="http://wordpress.org/support/view/plugin-reviews/bg-orthodox-calendar" target="_blank">Присвоить 5 звезд</a> в каталоге плагинов WordPress.', 'bg_ortcal') ?></li>
		<li><?php _e('Распространить информацию или написать заметку в свой блог о плагине.', 'bg_ortcal') ?></li>
	</ul>
	<div class="share42init" align="center" data-url="http://bogaiskov.ru/plagin-orthodox-calendar/" data-title="<?php _e('Bg Orthodox Calendar - отличный плагин для православных сайтов на  WordPress', 'bg_ortcal') ?>"></div>
	<script type="text/javascript" src="<?php printf( plugins_url( 'share42/share42.js' , dirname(__FILE__) ) ) ?>"></script>

	<h3><?php _e('Поддержка', 'bg_ortcal') ?></h3>
	<p><?php _e('См. <a href="http://wordpress.org/support/plugin/bg-orthodox-calendar" target="_blank">форум поддержки</a> или мой <a href="http://bogaiskov.ru/plagin-orthodox-calendar/" target="_blank">персональный сайт</a> для справки.', 'bg_ortcal') ?></p>
	
	<p class="bg_ortcal_close"><?php _e('Храни вас Господи!', 'bg_ortcal') ?></p>
</div>
</td></tr></table>
<?php 

} 
