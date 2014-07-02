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

    $customXML_name = "bg_ortcal_customXML";					// Имя пользовательского xml-файла
	
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
	
    $customXML_val = get_option( "bg_ortcal_customXML" );
	
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

		$customXML_val = ( isset( $_POST[$customXML_name] ) && $_POST[$customXML_name] ) ? $_POST[$customXML_name] : '' ;
		update_option( $customXML_name, $customXML_val );

        // Вывести сообщение об обновлении параметров на экран
		echo '<div class="updated"><p><strong>Параметры сохранены.</strong></p></div>';
    }
?>
<!--  форма опций -->
    
<table width="100%">
<tr><td valign="top">
<!--  Теперь отобразим опции на экране редактирования -->
<div class="wrap">
<!--  Заголовок -->
<h2>Параметры плагина "Православный календарь"</h2>
<p><?php printf( 'Версия <b>'.bg_ortcal_get_plugin_version().'</b>' ); ?></p>

<!-- Форма настроек -->
<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

<!--  Основные параметры -->
<table class="form-table">

<tr valign="top">
<th scope="row">Основной цвет текста:</th>
<td>
<input type="hidden" name="<?php echo $bg_ortcal_hidden_field_name; ?>" value="Y">
<input type="color" id="mainColor_name" name="<?php echo $mainColor_name ?>" value="<?php echo $mainColor ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Основной цвет фона:</th>
<td>
<input type="color" id="mainBgColor_name" name="<?php echo $mainBgColor_name ?>" value="<?php echo $mainBgColor ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет заголовков:</th>
<td>
<input type="color" id="titleColor_name" name="<?php echo $titleColor_name ?>" value="<?php echo $titleColor ?>"> (стрелки перехода на другой год, названия месяцев)<br />
</td></tr>
<tr valign="top">
<th scope="row">Дополнительный цвет текста:</th>
<td>
<input type="color" id="otherColor_name" name="<?php echo $otherColor_name ?>" value="<?php echo $otherColor ?>"> (заголовок, подвал, дни недели)<br />
</td></tr>
<tr valign="top">
<th scope="row">Дополнительный цвет фона:</th>
<td>
<input type="color" id="otherBgColor_name" name="<?php echo $otherBgColor_name ?>" value="<?php echo $otherBgColor ?>"> (заголовок, подвал, дни недели)<br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет текста выбранного дня:</th>
<td>
<input type="color" id="overColor_name" name="<?php echo $overColor_name ?>" value="<?php echo $overColor ?>"> (день, на который указывает курсор)<br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет фона выбранного дня:</th>
<td>
<input type="color" id="overBgColor_name" name="<?php echo $overBgColor_name ?>" value="<?php echo $overBgColor ?>"> (день, на который указывает курсор)<br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет текста сегодня:</th>
<td>
<input type="color" id="todayColor_name" name="<?php echo $todayColor_name ?>" value="<?php echo $todayColor ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет фона сегодня:</th>
<td>
<input type="color" id="todayBgColor_name" name="<?php echo $todayBgColor_name ?>" value="<?php echo $todayBgColor ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Цвет рамки дней браковенчаний:</th>
<td>
<input type="color" id="weddingColor_name" name="<?php echo $weddingColor_name ?>" value="<?php echo $weddingColor ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Пользовательский XML-файл данных</th>
<td>
<input type="text" id="customXML_name" name="<?php echo $customXML_name ?>" size="60" value="<?php echo $customXML_val ?>"><br />
</td></tr></table>

<p class="submit">
<input type="submit" name="Submit" value="Сохранить настройки" />
</p>

</form>
</div>
</td>

<!-- Информация о плагине -->
<td valign="top" align="left" width="45em">

<div class="bg_bibfers_info_box">

	<h3>Спасибо, что используете Православный календарь!</h3>
	<p class="bg_bibfers_gravatar"><a href="http://bogaiskov.ru" target="_blank"><?php echo get_avatar("vadim.bogaiskov@gmail.com", '64'); ?></a></p>
	<p>Дорогие братия и сестры!<br />Спасибо, что используете мой плагин!<br />Я надеюсь, что он оказался полезен для вашего сайта.</p>
	<p class="bg_bibfers_author"><a href="http://bogaiskov.ru" target="_blank">Вадим Богайсков</a></p>

	<h3>Мне нравится этот плагин. Как я могу Вас отблагодарить?</h3>
	<p>Есть несколько путей сделать это:</p>
	<ul>
		<li><a href="http://hpf.ru.com/" target="_blank">Сделать пожертвование</a> на храм свв. Петра и Февронии в Марьино.</li>
		<li><a href="http://wordpress.org/support/view/plugin-reviews/bg-orthodox-calendar" target="_blank">Присвоить 5 звезд</a> в каталоге плагинов WordPress.</li>
		<li>Распространить информацию или написать заметку в свой блог о плагине.</li>
	</ul>
	<div class="share42init" align="center" data-url="http://bogaiskov.ru/plagin-orthodox-calendar/" data-title="Bg Orthodox Calendar - отличный плагин для православных сайтов на  WordPress"></div>
	<script type="text/javascript" src="<?php printf( plugins_url( 'share42/share42.js' , dirname(__FILE__) ) ) ?>"></script>

	<h3>Поддержка</h3>
	<p>См. <a href="http://wordpress.org/support/plugin/bg-orthodox-calendar" target="_blank">форум поддержки</a> или мой <a href="http://bogaiskov.ru/plagin-orthodox-calendar/" target="_blank">персональный сайт</a> для справки.</p>
	
	<p class="bg_bibfers_close">Храни вас Господи!</p>
</div>
</td></tr></table>
<?php 

} 

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
	add_option('bg_ortcal_customXML', "");
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
	delete_option('bg_ortcal_customXML');

	delete_option('bg_ortcal_submit_hidden');
}
