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

    $popmenu1_name = "bg_ortcal_popmenu1";						// Официальный календарь РПЦ
    $popmenu2_name = "bg_ortcal_popmenu2";						// Календарь на Православие.Ru
    $popmenu3_name = "bg_ortcal_popmenu3";						// Богослужебные указания
    $popmenu101_name = "bg_ortcal_popmenu101";					// Этот день в календаре
    $popmenu1001_name = "bg_ortcal_popmenu1001";				// Текущий день
    $popmenu1002_name = "bg_ortcal_popmenu1002";				// Выбор имени по Месяцеслову
    $dblClick_name = "bg_ortcal_dblClick";						// Функция при двойном щелчке по дате в календаре
    $customXML_name = "bg_ortcal_customXML";					// Имя пользовательского xml-файла

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
	
    $popmenu1_val = get_option( "bg_ortcal_popmenu1" );
    $popmenu2_val = get_option( "bg_ortcal_popmenu2" );
    $popmenu3_val = get_option( "bg_ortcal_popmenu3" );
    $popmenu101_val = get_option( "bg_ortcal_popmenu101" );
    $popmenu1001_val = get_option( "bg_ortcal_popmenu1001" );
    $popmenu1002_val = get_option( "bg_ortcal_popmenu1002" );
    $dblClick_val = get_option( "bg_ortcal_dblClick" );
	
    $customXML_val = get_option( "bg_ortcal_customXML" );
	
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

		$popmenu1_val = ( isset( $_POST[$popmenu1_name] ) && $_POST[$popmenu1_name] ) ? $_POST[$popmenu1_name] : '' ;
		update_option( $popmenu1_name, $popmenu1_val );
		$popmenu2_val = ( isset( $_POST[$popmenu2_name] ) && $_POST[$popmenu2_name] ) ? $_POST[$popmenu2_name] : '' ;
		update_option( $popmenu2_name, $popmenu2_val );
		$popmenu3_val = ( isset( $_POST[$popmenu3_name] ) && $_POST[$popmenu3_name] ) ? $_POST[$popmenu3_name] : '' ;
		update_option( $popmenu3_name, $popmenu3_val );
		$popmenu101_val = ( isset( $_POST[$popmenu101_name] ) && $_POST[$popmenu101_name] ) ? $_POST[$popmenu101_name] : '' ;
		update_option( $popmenu101_name, $popmenu101_val );
		$popmenu1001_val = ( isset( $_POST[$popmenu1001_name] ) && $_POST[$popmenu1001_name] ) ? $_POST[$popmenu1001_name] : '' ;
		update_option( $popmenu1001_name, $popmenu1001_val );
		$popmenu1002_val = ( isset( $_POST[$popmenu1002_name] ) && $_POST[$popmenu1002_name] ) ? $_POST[$popmenu1002_name] : '' ;
		update_option( $popmenu1002_name, $popmenu1002_val );

		$dblClick_val = ( isset( $_POST[$dblClick_name] ) && $_POST[$dblClick_name] ) ? $_POST[$dblClick_name] : '' ;
		update_option( $dblClick_name, $dblClick_val );
		
		$customXML_val = ( isset( $_POST[$customXML_name] ) && $_POST[$customXML_name] ) ? $_POST[$customXML_name] : '' ;
		update_option( $customXML_name, $customXML_val );

		$bg_fgc_val = ( isset( $_POST[$bg_fgc_name] ) && $_POST[$bg_fgc_name] ) ? $_POST[$bg_fgc_name] : '' ;
		update_option( $bg_fgc_name, $bg_fgc_val );

		$bg_fopen_val = ( isset( $_POST[$bg_fopen_name] ) && $_POST[$bg_fopen_name] ) ? $_POST[$bg_fopen_name] : '' ;
		update_option( $bg_fopen_name, $bg_fopen_val );

		$bg_curl_val = ( isset( $_POST[$bg_curl_name] ) && $_POST[$bg_curl_name] ) ? $_POST[$bg_curl_name] : '' ;
		update_option( $bg_curl_name, $bg_curl_val );

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
<th scope="row">Элементы всплывающего меню календаря</th>
<td>
<p id="popmenu" onclick='show_popmenu_items();' style="cursor:pointer">&#9660; отобразить  &#9660;<br />
</td></tr>
<tr valign="top" id="popmenu1" style="display:none">
<th scope="row">&#9654; Ссылка на сайт официального календаря РПЦ</th>
<td>
<input type="text" id="popmenu1_name" name="<?php echo $popmenu1_name ?>" size="60" value="<?php echo $popmenu1_val ?>"><br />
</td></tr>
<tr valign="top" id="popmenu2" style="display:none">
<th scope="row">&#9654; Ссылка на сайт календаря на Православие.Ru</th>
<td>
<input type="text" id="popmenu2_name" name="<?php echo $popmenu2_name ?>" size="60" value="<?php echo $popmenu2_val ?>"><br />
</td></tr>
<tr valign="top" id="popmenu3" style="display:none">
<th scope="row">&#9654; Ссылка на сайт богослужебных указаний</th>
<td>
<input type="text" id="popmenu3_name" name="<?php echo $popmenu3_name ?>" size="60" value="<?php echo $popmenu3_val ?>"><br />
</td></tr>
<tr valign="top" id="popmenu101" style="display:none">
<th scope="row">&#9654; Этот день в календаре (страница сайта)</th>
<td>
<input type="text" id="popmenu101_name" name="<?php echo $popmenu101_name ?>" size="60" value="<?php echo $popmenu101_val ?>"><br />
</td></tr>
<tr valign="top" id="popmenu1001" style="display:none">
<th scope="row">&#9654; Этот день в календаре (всплывающее окно)</th>
<td>
<input type="text" id="popmenu1001_name" name="<?php echo $popmenu1001_name ?>" size="60" value="<?php echo $popmenu1001_val ?>"><br />
</td></tr>
<tr valign="top" id="popmenu1002" style="display:none">
<th scope="row">&#9654; Выбор имени по Месяцеслову (всплывающее окно)</th>
<td>
<input type="text" id="popmenu1002_name" name="<?php echo $popmenu1002_name ?>" size="60" value="<?php echo $popmenu1002_val ?>"><br />
</td></tr>
<script>
function show_popmenu_items() {
	if (document.getElementById('popmenu1').style.display  == 'none') {
		document.getElementById('popmenu').innerHTML = ' &#9650; скрыть  &#9650;';
		document.getElementById('popmenu1').style.display = '';
		document.getElementById('popmenu2').style.display = '';
		document.getElementById('popmenu3').style.display = '';
		document.getElementById('popmenu101').style.display = '';
		document.getElementById('popmenu1001').style.display = '';
		document.getElementById('popmenu1002').style.display = '';
	} else {
		document.getElementById('popmenu').innerHTML = '&#9660; отобразить  &#9660;';
		document.getElementById('popmenu1').style.display = 'none';
		document.getElementById('popmenu2').style.display = 'none';
		document.getElementById('popmenu3').style.display = 'none';
		document.getElementById('popmenu101').style.display = 'none';
		document.getElementById('popmenu1001').style.display = 'none';
		document.getElementById('popmenu1002').style.display = 'none';
	}
}
reading_off_checked();
</script>
<tr valign="top">
<th scope="row">Функция при двойном щелчке по дате</th>
<td>
<select name="<?php echo $dblClick_name ?>">
<option value=1 <?php if($dblClick_val==1) echo 'selected="selected"'; ?> >Официальный календарь РПЦ</option>
<option value=2 <?php if($dblClick_val==2) echo 'selected="selected"'; ?> >Календарь на Православие.Ru</option>
<option value=3 <?php if($dblClick_val==3) echo 'selected="selected"'; ?> >Богослужебные указания</option>
<option value=101 <?php if($dblClick_val==101) echo 'selected="selected"'; ?> >Этот день в календаре (страница)</option>
<option value=1001 <?php if($dblClick_val==1001) echo 'selected="selected"'; ?> >Этот день в календаре (окно)</option>
<option value=1002 <?php if($dblClick_val==1002) echo 'selected="selected"'; ?> >Выбор имени по Месяцеслову</option>
</select>
</td></tr>

<tr valign="top">
<th scope="row">Пользовательский XML-файл данных</th>
<td>
<input type="text" id="customXML_name" name="<?php echo $customXML_name ?>" size="60" value="<?php echo $customXML_val ?>"><br />
</td></tr>
<tr valign="top">
<th scope="row">Метод чтения файлов</th>
<td>
<input type="checkbox" id="bg_fgc" name="<?php echo $bg_fgc_name ?>" <?php if($bg_fgc_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> file_get_contents()<br />
<input type="checkbox" id="bg_fopen" name="<?php echo $bg_fopen_name ?>" <?php if($bg_fopen_val=="on") echo "checked" ?>  value="on" onclick='reading_off_checked();'> fopen() - fread() - fclose()<br />
<input type="checkbox" id="bg_curl" name="<?php echo $bg_curl_name ?>" <?php if($bg_curl_val=="on") echo "checked" ?> value="on" onclick='reading_off_checked();'> cURL<br />
<i>(Плагин пытается загружать XML-файлы данных отмеченными методами в указанном порядке.<br>Чтобы сделать загрузку более быстрой отключите лишние методы.<br><u>Внимание:</u> Некоторые методы могут быть недоступны на Вашем сервере.)</i><br />
</td></tr></table>
<script>
function reading_off_checked() {
	if (document.getElementById('bg_curl').checked == true || document.getElementById('bg_fgc').checked == true || document.getElementById('bg_fopen').checked == true) {
		document.getElementById('bg_verses').disabled = false;
	} else {
		document.getElementById('bg_verses').disabled = true;
		document.getElementById('bg_verses').checked = false;
		document.getElementById('bg_preq').disabled = true;
		document.getElementById('bg_preq').checked = false;
	}
}
reading_off_checked();
</script>

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
	add_option('bg_ortcal_popmenu1', "Официальный календарь РПЦ");
	add_option('bg_ortcal_popmenu2', "Календарь на Православие.Ru");
	add_option('bg_ortcal_popmenu3', "Богослужебные указания");
	add_option('bg_ortcal_popmenu101', "Этот день в календаре (страница)");
	add_option('bg_ortcal_popmenu1001', "Этот день в календаре (окно)");
	add_option('bg_ortcal_popmenu1002', "Выбор имени по Месяцеслову");
	add_option('bg_ortcal_dblClick', "2");
	add_option('bg_ortcal_customXML', "");
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
	delete_option('bg_ortcal_popmenu1');
	delete_option('bg_ortcal_popmenu2');
	delete_option('bg_ortcal_popmenu3');
	delete_option('bg_ortcal_popmenu101');
	delete_option('bg_ortcal_popmenu1001');
	delete_option('bg_ortcal_popmenu1002');
	delete_option('bg_ortcal_dblClick');
	delete_option('bg_ortcal_customXML');
	delete_option('bg_ortcal_fgc');
	delete_option('bg_ortcal_fopen');
	delete_option('bg_ortcal_curl');

	delete_option('bg_ortcal_submit_hidden');
}
