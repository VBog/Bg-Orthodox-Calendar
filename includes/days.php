<?php
/*******************************************************************************
// Функция определяет является ли указанный год високосным
*******************************************************************************/  
function ortcal_isLeap($year) {
	return (!($year % 4) && ($year % 100) || !($year % 400));
}
/*******************************************************************************
// Функция определяет количество дней в месяце
*******************************************************************************/  
function ortcal_numDays ($month, $year) {
	$dim = array(31,28,31,30,31,30,31,31,30,31,30,31);
	return ($month == 2 && ortcal_isLeap($year)) ? 29 : $dim[(int)$month-1];
}
/*******************************************************************************
// Функция возвращает количество дней между датами по новому и старому стилю
*******************************************************************************/  
function ortcal_dd($year) {
	return ($year-$year%100)/100 - ($year-$year%400)/400 - 2;
}
/*******************************************************************************
// Функция добавляет заданное количество дней к дате, заданной в виде int
*******************************************************************************/  
function ortcal_add_days($date, $days) {
	return date( 'U', mktime ( 0, 0, 0, date("n", $date), date("j", $date)+$days, date("Y", $date) ) );
}
/*******************************************************************************
// Функция возвращает дату по старому стилю
*******************************************************************************/  
function ortcal_oldStyle($format, $month, $day, $year){
	$replace = array ();
	$week = array(__('Воскресенье', 'bg_ortcal'), __('Понедельник', 'bg_ortcal'), __('Вторник', 'bg_ortcal'), __('Среда', 'bg_ortcal'), __('Четверг', 'bg_ortcal'), __('Пятница', 'bg_ortcal'), __('Суббота', 'bg_ortcal'));
	$wk = array(__("Вс", 'bg_ortcal'),__("Пн", 'bg_ortcal'),__("Вт", 'bg_ortcal'),__("Ср", 'bg_ortcal'),__("Чт", 'bg_ortcal'),__("Пт", 'bg_ortcal'),__("Сб", 'bg_ortcal'));
	$dd = ortcal_dd($year);
	for ($i=0; $i<7; $i++) {
		$di = ($i+$dd)%7;								// Смещение дня недели
		$replace[$week[$i]] = $week[$di];
		$replace[$wk[$i]] = $wk[$di];
	}
	return  strtr ( ortcal_dateRU ( date( $format, mktime ( 0, 0, 0, $month, $day-$dd, $year ) ) ), $replace );
}
/*******************************************************************************
// Перевод названия месяца на русский язык (именительный падеж, заглавная буква)
*******************************************************************************/  
function ortcal_monthRU ( $str ) {
	$replace = array (	'January' => __('Январь', 'bg_ortcal'),
						'February' => __('Февраль', 'bg_ortcal'),
						'March' => __('Март', 'bg_ortcal'),
						'April' => __('Апрель', 'bg_ortcal'),
						'May' => __('Май', 'bg_ortcal'),
						'June' => __('Июнь', 'bg_ortcal'),
						'July' => __('Июль', 'bg_ortcal'),
						'August' => __('Август', 'bg_ortcal'),
						'September' => __('Сентябрь', 'bg_ortcal'),
						'October' => __('Октябрь', 'bg_ortcal'),
						'November' => __('Ноябрь', 'bg_ortcal'),
						'December' => __('Декабрь', 'bg_ortcal'));
	return strtr ( $str, $replace );
}
/*******************************************************************************
// Перевод даты на русский язык (родительный падеж, строчная буква)
*******************************************************************************/  
function ortcal_dateRU ( $str ) {
	$replace = array (	'January' => __('января', 'bg_ortcal'),
						'February' => __('февраля', 'bg_ortcal'),
						'March' => __('марта', 'bg_ortcal'),
						'April' => __('апреля', 'bg_ortcal'),
						'May' => __('мая', 'bg_ortcal'),
						'June' => __('июня', 'bg_ortcal'),
						'July' => __('июля', 'bg_ortcal'),
						'August' => __('августа', 'bg_ortcal'),
						'September' => __('сентября', 'bg_ortcal'),
						'October' => __('октября', 'bg_ortcal'),
						'November' => __('ноября', 'bg_ortcal'),
						'December' => __('декабря', 'bg_ortcal'),
						'Jan' => __('янв', 'bg_ortcal'),
						'Feb' => __('фев', 'bg_ortcal'),
						'Mar' => __('мар', 'bg_ortcal'),
						'Apr' => __('апр', 'bg_ortcal'),
						'Jun' => __('июн', 'bg_ortcal'),
						'Jul' => __('июл', 'bg_ortcal'),
						'Aug' => __('авг', 'bg_ortcal'),
						'Sep' => __('сен', 'bg_ortcal'),
						'Oct' => __('окт', 'bg_ortcal'),
						'Nov' => __('ноя', 'bg_ortcal'),
						'Dec' => __('дек', 'bg_ortcal'),
						'Monday' => __('Понедельник', 'bg_ortcal'),
						'Tuesday' => __('Вторник', 'bg_ortcal'),
						'Wednesday' => __('Среда', 'bg_ortcal'),
						'Thursday' => __('Четверг', 'bg_ortcal'),
						'Friday' => __('Пятница', 'bg_ortcal'),
						'Saturday' => __('Суббота', 'bg_ortcal'),
						'Sunday' => __('Воскресенье', 'bg_ortcal'),
						'Mon' => __('Пн', 'bg_ortcal'),
						'Tue' => __('Вт', 'bg_ortcal'),
						'Wed' => __('Ср', 'bg_ortcal'),
						'Thu' => __('Чт', 'bg_ortcal'),
						'Fri' => __('Пт', 'bg_ortcal'),
						'Sat' => __('Сб', 'bg_ortcal'),
						'Sun' => __('Вс', 'bg_ortcal')
					);
	return strtr ( $str, $replace );
}
/*******************************************************************************
// Функция определяет день Пасхи на заданный год
*******************************************************************************/

function ortcal_easter($format, $year, $old=false) {
	$a=((19*($year%19)+15)%30);
	$b=((2*($year%4)+4*($year%7)+6*$a+6)%7);
	if ($a+$b>9) {
		$day=$a+$b-9;
		$month=4;
	} else {
		$day=22+$a+$b;
		$month=3;
	}
	if ($old) $dd = 0;
	else $dd = ortcal_dd($year);
	$res=ortcal_dateRU ( date( $format, mktime ( 0, 0, 0, $month, $day+$dd, $year ) ) );
	return $res;
}
/*******************************************************************************
// Функция определяет название Седмицы (Недели) по годичному кругу богослужений
*******************************************************************************/  
function ortcal_sedmica ($month, $day, $year) {
	$cd = date( 'z', mktime ( 0, 0, 0, $month, $day, $year )); 	// Порядковый номер дня в году
	$ed = ortcal_easter('z', $year);									// Порядковый номер дня пасхи в году
	$wd = date( 'w', mktime ( 0, 0, 0, $month, $day, $year )); 	// Порядковый номер дня недели от 0 (воскресенье) до 6 (суббота)
	
	if ($cd < $ed-70) {				// До Недели о мытаре и фарисее идут седмицы по Пятидесятнице прошлого года
		$ed = ortcal_easter('z', $year-1);								// Порядковый номер дня пасхи в предыдущем году
		$nw = (int)(($cd+(ortcal_isLeap($year-1)?366:365)-($ed+49))/7)+1;
		if ($wd == 0) return __("Неделя", 'bg_ortcal')." ".($nw-1).__("-я по Пятидесятнице", 'bg_ortcal');
		else return __("Седмица", 'bg_ortcal')." ".$nw.__("-я по Пятидесятнице", 'bg_ortcal');
	}
	else if ($cd == $ed-70) return __("Неделя о мытаре и фарисее", 'bg_ortcal');	// Седмицы подготовительные
	else if ($cd < $ed-63) return __("Седмица о мытаре и фарисее", 'bg_ortcal');	
	else if ($cd == $ed-63) return __("Неделя о блудном сыне", 'bg_ortcal');	
	else if ($cd < $ed-56) return __("Седмица о блудном сыне", 'bg_ortcal');	
	else if ($cd == $ed-56) return __("Неделя мясопустная, о Страшнем суде", 'bg_ortcal');	
	else if ($cd < $ed-49) return __("Сырная седмица (масленица)", 'bg_ortcal');						
	else if ($cd == $ed-49) return __("Неделя сыропустная. Воспоминание Адамова изгнания. Прощеное воскресенье", 'bg_ortcal');						
	else if ($cd < $ed-13) {									// Седмицы Великого поста
		$nw = (int)(($cd - ($ed-49))/7)+1;
		if ($wd == 0) return __("Неделя", 'bg_ortcal')." ".($nw-1).__("-я Великого поста", 'bg_ortcal');
		else return __("Седмица", 'bg_ortcal')." ".$nw.__("-я Великого поста", 'bg_ortcal');
	}
	else if ($cd < $ed-7) return __("Седмица 6-я Великого поста (седмица ваий)", 'bg_ortcal');
	else if ($cd == $ed-7) return __("Неделя 6-я Великого поста ваий (цветоносная, Вербное воскресенье)", 'bg_ortcal');
	else if ($cd < $ed) return __("Страстная седмица", 'bg_ortcal');
	else if ($cd == $ed) return "";
	else if ($cd < $ed+7) return __("Пасхальная (Светлая) седмица", 'bg_ortcal');
	else if ($cd < $ed+50) {									// Седмицы по Пасхе
		$nw = (int)(($cd - $ed)/7)+1;
		if ($wd == 0) return __("Неделя", 'bg_ortcal')." ".$nw.__("-я по Пасхе", 'bg_ortcal');
		else return __("Седмица", 'bg_ortcal')." ".$nw.__("-я по Пасхе", 'bg_ortcal');
	}
	else  {														// Седмицы по Пятидесятнице
		$nw = (int)(($cd - ($ed+49))/7)+1;
		if ($wd == 0) return __("Неделя", 'bg_ortcal')." ".($nw-1).__("-я по Пятидесятнице", 'bg_ortcal');
		else {
			if ($nw==1) return __("Седмица 1-я по Пятидесятнице (Троицкая)", 'bg_ortcal');
			else return __("Седмица", 'bg_ortcal')." ".$nw.__("-я по Пятидесятнице", 'bg_ortcal');
		}
	}

	return "";
}
/*******************************************************************************
// Функция формирует массив данных, полученных из XML файлов
*******************************************************************************/
function bg_ortcal_load_xml() {
	if (false === ($events = get_transient('bg_ortcal_xml'))) {
		$events = false;
		$only_customXML = get_option( "bg_ortcal_only_customXML" );
	// Загружаем в память базу данных событий из XML
		if ($only_customXML != "on") {
			$locale = get_locale();
			$plugins_dir = dirname(dirname(__FILE__)) . '/MemoryDays-'.$locale.'.xml';
			if (!file_exists($plugins_dir))
				$plugins_dir = dirname(dirname(__FILE__)) . '/MemoryDays.xml';
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
		set_transient( 'bg_ortcal_xml', $events, YEAR_IN_SECONDS );
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

/*******************************************************************************
// Функция получает данные из XML файла	
*******************************************************************************/
function ortcal_getXML ($url) {
		
	$bg_curl_val = get_option( 'bg_ortcal_curl' );
	$bg_fgc_val = get_option( 'bg_ortcal_fgc' );
	$bg_fopen_val = get_option( 'bg_ortcal_fopen' );

	$code = false;

	if ($bg_fgc_val == 'on' && !$code) {									// Попытка 1. Попробуем применить file_get_contents()
		$code = file_get_contents($url);
	}

	if ($bg_fopen_val == 'on' && !$code) {									// Попытка 2. Если данные опять не получены попробуем применить fopen()
		$ch=fopen($url, "r" );													// Открываем файл для чтения
		if($ch)	{
			while (!feof($ch))	{$code .= fread($ch, 2097152);}					// загрузка текста (не более 2097152 байт)
			fclose($ch);														// Закрываем файл
		}
	}

	if ($bg_curl_val == 'on' && function_exists('curl_init') && !$code) {	// Попытка 3. Если данные не получены и установлен cURL
		$url = substr ($url, strlen(ABSPATH)-1);								// Путь из корневого каталога сайта
		$url = site_url( $url );												// URL файла
		$ch = curl_init($url);													// создание нового ресурса cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);							// возврат результата передачи в качестве строки из curl_exec() вместо прямого вывода в браузер
		$code = curl_exec($ch);													// загрузка текста
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($httpCode != '200') $code = false;									// Проверка на код http 200
		curl_close($ch);														// завершение сеанса и освобождение ресурсов
	}
	if (!$code) return false;												// Увы. Паранойя хостера достигла апогея. Файл не прочитан или ошибка
	$result = xml_array($code);

	return $result;																// Возвращаем PHP массив
}
/*******************************************************************************
// Функция для преобразования XML в PHP Array
*******************************************************************************/  
function xml_array($code){
	$xml = new SimpleXMLElement($code);
	$result = json_decode(json_encode($xml),true);
	return $result;
}

/*******************************************************************************
// Функция подготовки данных о событиях дня
*******************************************************************************/  
function bg_ortcal_dayEvents($month, $day, $year){
//$start_time = microtime(true);
	$key = 'bg_ortcal_dayEvents-'.intval($year).'-'.sprintf("%02d",intval($month)).'-'.sprintf("%02d",intval($day));
	if(false === ($result = get_transient($key))) {

		$events = bg_ortcal_load_xml();

		$date = ortcal_oldStyle('U', $month, $day, $year);				// Дата по старому стилю
		$os_year = date ('Y', $date);									// Год по старому стилю
		$dd = ortcal_dd($os_year);										// Отклонение григорианского календаря от юлианского в днях
		$leap = ortcal_isLeap(ortcal_oldStyle('Y', $month, $day, $year));		// true - если високосный год по старому стилю
		$ny =  date( 'U', mktime ( 0, 0, 0, 1, 1, $year ));				// Новый год по григорианскому календарю
		$easter = ortcal_easter('U', $year, true);						// Пасха в текущем году
		$easter_prev = ortcal_easter('U', $year-1, true);				// Пасха в предыдущем году
		$f_e = (date( 'U', mktime ( 0, 0, 0,12, 25, $year-1 ))-$easter_prev)/DAY_IN_SECONDS-date( 'w', mktime ( 0, 0, 0, 12, 25+ortcal_dd($year-1), $year-1 )); // Кол-во дней до Недели св.отцов от Пасхи
		$ep_e = (date( 'U', mktime ( 0, 0, 0, 1, 6, $year ))-$easter_prev)/DAY_IN_SECONDS-date( 'w', mktime ( 0, 0, 0, 1, 6+ortcal_dd($year), $year )); 		// Кол-во дней до Недели перед Богоявлением от Пасхи
		$wd = date( 'w', mktime ( 0, 0, 0, $month, $day, $year ));
		if ($wd == 3 || $wd == 5) $post = __("Постный день", 'bg_ortcal');				// Пост по средам и пятницам
		else $post = "";
		if ($wd == 2 || $wd == 4 || $wd == 6) $noglans = __("Браковенчание не совершается", 'bg_ortcal');	// Браковенчание не совершается накануне среды и пятницы всего года (вторник и четверг), и воскресных дней (суббота)
		else $noglans = "";

		$result = array();
		$cnt = count($events);
		$y = 0;
		if ($cnt) {
			for ($i=0; $i < $cnt; $i++) {
				$event = $events[$i];
				$s_date = (int) $event["s_date"];
				$s_month = (int) $event["s_month"];
				$f_date = (int) $event["f_date"];
				$f_month = (int) $event["f_month"];
				$name = $event["name"];
				$link = $event["link"];
				$type = (int) $event["type"];
				$discription = $event["discription"];

				// Если невисокосный год, то события которые приходятся на 29 февраля празднуются 28 февраля
				if ( ! $leap ) {
					if ( $s_month == 2 && $s_date == 29 ) {
						$s_date == 28;
					}
					if ( $f_month == 2 && $f_date == 29 ) {
						$f_date == 28;
					}
				}

				if ( $s_month < 0 ) {        //  Сб./Вс. перед/после праздника или Праздник в Сб./Вс. перед/после даты
					// Если неделя Богоотцов совпадает с Неделей перед Богоявлением, то чтения Недели перед Богоявлением переносятся на 1 января ст.ст.
					if ( ( $f_e + 7 == $ep_e ) && ( $s_month == - 1 && $s_date == 0 && $f_month == 1 && $f_date == 6 && ( $type == 204 || $type == 207 ) ) ) {
						$finish = date( 'U', mktime( 0, 0, 0, 1, 1, $os_year ) );
						$start  = $finish;
					} else {
						if ($s_month == -4) $f_date = $f_date - 3;
						$we     = date( 'w', mktime( 0, 0, 0, $f_month, $f_date+$dd, $os_year + $y ) );				// День недели
						$finish = date( 'U', mktime( 0, 0, 0, $f_month, $f_date+($s_date-$we), $os_year + $y ) );	// Смещение относительно даты на $s_date-$we дней
						$start  = $finish;

						if ( $s_month == - 1 && $we == $s_date ) {		// Если Сб./Вс. приходится на самый день праздника, то не отмечается
							$name = "";
						}                                                
						if ( $s_month == - 3 && $we != $s_date ) {		// Если праздник не совпадает с указанным днем недели, то не отмечается
							$name = "";
						}                                                
						if ( $y == 0 ) {								// Проверяем дважды: для текущего и следующего года
							$i --;
							$y = 1;
						}                                                                    
						else {
							$y = 0;
						}
					}
				} else {
					if ( $s_month > 0 ) {						// Неподвижные события - начало периода
						$start = date( 'U', mktime( 0, 0, 0, $s_month, $s_date, $os_year ) );
					}            
					else if ( $s_month == 0 ) {					// Переходящие события - начало периода
						if ( $type == 202 ) {						// Чтения на утрени
							$start = ortcal_shift202( $s_date, $date, $os_year );
						}										
						else if ( $type == 204 ) {					// Апостол на Литургии
							$start = ortcal_shift204( $s_date, $date, $os_year );
						}                                    
						else if ( $type == 207 ) {					// Евангелие на Литургии
							$start = ortcal_shift207( $s_date, $date, $os_year );
						}                                    
						else if ( $type >= 301 && $type <= 309 ) {	// Псалтирь
							$start = ortcal_shift300( $s_date, $date, $os_year );
						}                    
						else {										// Все остальные события
							if ( $date >= $ny ) {						// После Нового года - отсчет от текущей Пасхи
								$start = ortcal_add_days($easter, $s_date);
							}                                                
							else {										// До Нового года - отсчет от предыдущей Пасхи
								$start = ortcal_add_days($easter_prev, $s_date);
							}                                                        
						}
					}

					if ( $f_month > 0 ) {						// Неподвижные события - конец периода
						$finish = date( 'U', mktime( 0, 0, 0, $f_month, $f_date, $os_year ) );
					}            
					else if ( $f_month == 0 ) {						// Переходящие события - конец периода
						if ( $type == 202 ) {						// Чтения на утрени
							$finish = ortcal_shift202( $s_date, $date, $os_year );
						}                                        
						else if ( $type == 204 ) {					// Апостол на Литургии
							$finish = ortcal_shift204( $s_date, $date, $os_year );
						}                                
						else if ( $type == 207 ) {					// Евангелие на Литургии
							$finish = ortcal_shift207( $s_date, $date, $os_year );
						}                                
						else if ( $type >= 301 && $type <= 309 ) {	// Псалтирь
							$finish = ortcal_shift300( $s_date, $date, $os_year );
						}                    
						else {										// Все остальные события
							if ( $date >= $ny ) {						// После Нового года - отсчет от текущей Пасхи
								$finish = ortcal_add_days($easter, $f_date);
							}                                            
							else {										// До Нового года - отсчет от предыдущей Пасхи
								$finish = ortcal_add_days($easter_prev, $f_date);
							}                                                    
						}
					}
				}

				// Обрабатываем коллизию, связанную со сменой года
				if ($start > $finish) {
					if ($start > $date) $start = ortcal_add_days($start, ($leap?(-366):(-365)));	// Начало в прошлом году
					else $finish = ortcal_add_days($finish, ($leap?(366):(365)));					// Окончание в следующем году
				}

				if ($start && $finish) {
					// Событие относится к данному дню, если
					// - его наименование не пустое,
					// - день попадает в интервал между начальной и конечной датой события
					if ($name != "" && $date >= $start && $date <= $finish) {
						$s = ortcal_add_days($start, $dd);
						$f = ortcal_add_days($finish, $dd);
						$result[] = array (	
							"s_date" => date ("d", $s),
							"s_month" => date ("m", $s),
							"s_year" => date ("Y", $s),
							"f_date" => date ("d", $f),
							"f_month" => date ("m", $f),
							"f_year" => date ("Y", $f),
							"name" => $name,
							"type" => $event["type"],
							"link" => $event["link"],
							"discription" => $event["discription"]);
						if ($event["type"] == '10' || $event["type"] == '100') $post = "";	// Уже установлен пост по другой причине или сплошная седмица
						if ($event["type"] == '20') $noglans = "";							// Уже запрещено браковенчание по другой причине
					}
				}
			}
		}
		if ($post != "") {													// Пост по средам и пятницам
			$result[] = array (	"s_date" => $day,
				"s_month" => $month,
				"s_year" => $year,
				"f_date" => $day,
				"f_month" => $month,
				"f_year" => $year,
				"name" => $post,
				"type" => "10",
				"link" => "",
				"discription" => "");
		}
		if ($noglans != "") {												// Браковенчание не совершается по вторникам, четвергам и субботам
			$result[] = array (	"s_date" => $day,
				"s_month" => $month,
				"s_year" => $year,
				"f_date" => $day,
				"f_month" => $month,
				"f_year" => $year,
				"name" => $noglans,
				"type" => "20",
				"link" => "",
				"discription" => "");
		}

		set_transient( $key, $result, YEAR_IN_SECONDS );
//error_log(''.(microtime(true)-$start_time).' сек.(!!! '.$key.')'.PHP_EOL, 3, dirname(__FILE__)."/bg_error.log" );
	}
//error_log(''.(microtime(true)-$start_time).' сек.('.$key.')'.PHP_EOL, 3, dirname(__FILE__)."/bg_error.log" );
	return $result;
}
/*******************************************************************************
// Функция возвращает свойства дня
*******************************************************************************/  
function bg_ortcal_dayProperties ($month, $day, $year){
	$res = 7;							// Самый обычный день
	$post = 0;							// Нет поста
	$e = bg_ortcal_dayEvents($month, $day, $year);
	$cnt = count($e);
	for ($i=0; $i < $cnt; $i++) {
		if (($e[$i]['type'] <= 2 OR $e[$i]['type'] == 9)  AND $e[$i]['type'] < $res) $res = $e[$i]['type'];	
		if ($e[$i]['type'] == 10) $post = 10;
	}
	return $res+$post;
}
/*******************************************************************************
// Смещение даты для Чтений на Утрене
*******************************************************************************/  
function ortcal_shift202 ($d, $date, $year) {				
	$easter=ortcal_easter('U', $year, true);			// Пасха в текущем году
	$easter_prev=ortcal_easter('U', $year-1, true);
	if ($date < ortcal_add_days($easter, (-7))) {		// До Вербного Воскресенья - отсчет от предыдущей Пасхи
		$easter = $easter_prev;
		$easter_prev=ortcal_easter('U', $year-2, true);		// Пасха в предыдущем году
	}
	$e_e = ($easter - $easter_prev)/DAY_IN_SECONDS;		// Количество дней от Пасхи до Пасхи
	if ($d >= $e_e-7) return false;						// Отбрасываем лишние дни в конце цикла
	$res=ortcal_add_days($easter, $d);
	return $res;
}

/*******************************************************************************
// Смещение даты для Апостола на Литургии
*******************************************************************************/  
function ortcal_shift204 ($d, $date, $year) {				
	$easter = ortcal_easter('U', $year, true);				// Пасха в текущем году
	$dd = ortcal_dd($year);									// Отклонение григорианского календаря от юлианского в днях
	$er_e = (date('U', mktime(0, 0, 0, 9, 14, $year)) - $easter)/DAY_IN_SECONDS + 
		(7 - date('w', mktime(0, 0, 0, 9, 14+$dd, $year))); // Кол-во дней до Недели по воздвижении от Пасхи
	$easter_prev = ortcal_easter('U', $year-1, true);		// Пасха в предыдущем году
	$dd_prev = ortcal_dd( $year - 1 );					// Отклонение григорианского календаря от юлианского в днях в предыдущем году
	$er_e_prev = (date('U',mktime(0, 0, 0, 9, 14, $year-1))-$easter_prev)/DAY_IN_SECONDS + 
		(7 - date('w', mktime(0, 0, 0, 9, 14+$dd_prev, $year-1)));	// Кол-во дней до Недели по воздвижении от Пасхи в предыдущем году
	$end = ortcal_add_days($easter_prev, 280+($er_e_prev-168));		// День конца годичного цикла
	$ec_e = ($easter - $end)/DAY_IN_SECONDS;				// Количество дней от конца годичного цикла до Пасхи

	if ($ec_e <= 70) {											// Если внутрь-Пасха
		if ($d > 210+$ec_e) return false; 							// Отбрасываем лишние дни в конце цикла
		if ($d < -70) return false;									// Отсчет нового цикла с Недели о мытаре и фарисее
	} else {													// Если вне-Пасха
		if ($ec_e > 98) {											// Если недостает более 4 седмиц, вставляем 17-ю седмицу между 31-й и 32-й
			if ($d < -97) $d = $d + 14;								// 17-я седмица хранится в начале базы данных с датами от -104 до -98
			else if ($d >= -97 && $d <= -84) $d = $d - 7;
		}else
		if ($d < -$ec_e) return false;								// Отсчет нового цикла начинаем за $ec_e дней до Пасхи
	}
	// Евангельское зачало Недели 28-й и апостольское зачало Недели 29-й читаются в Неделю святых праотец,
	// поэтому они меняются местами с соответствующими рядовым чтениями той Недели,
	// на которую придется в данном году Неделя свв. праотец.
	$ff_e = (date('U', mktime(0, 0, 0, 12, 25, $year))-$easter)/(DAY_IN_SECONDS)-
		(7 + date( 'w', mktime (0, 0, 0, 12, 25+$dd, $year)));	// Кол-во дней до Недели Праотцов от Пасхи
	if ($d == 252) $d = $ff_e;									// Неделя 29-я
	else if ($d == $ff_e) $d = 252;								// Неделя Праотцов
	
	if ( $date <= $end ) {										// До конца годичного цикла - отсчет от предыдущей Пасхи
		$easter = $easter_prev;										// Пасха в указанном году
		$er_e   = $er_e_prev;										// Кол-во дней до Недели по воздвижении от Пасхи
	}
	if ($date > ortcal_add_days($easter, 280) ) {				//Если в конце периода не хватает седмиц,
		if ( $d > 280 - ( $er_e - 168 ) && $d <= 280 ) {			// и если была воздвиженская отступка, 
			$d = $d + ( $er_e - 168 );								// дублируем чтения предыдущих(ей) седмиц(ы)
		}                
	}
	$res=ortcal_add_days($easter, $d);
	return $res;
}

/*******************************************************************************
// Смещение даты для Евангелие на Литургии
*******************************************************************************/  
function ortcal_shift207 ($d, $date, $year) {						
	$easter = ortcal_easter( 'U', $year, true );			// Пасха в текущем году
	$dd = ortcal_dd( $year );								// Отклонение григорианского календаря от юлианского в днях
	$er_e = (date('U', mktime(0, 0, 0, 9, 14, $year)) - $easter)/DAY_IN_SECONDS + 
		(7 - date('w', mktime( 0, 0, 0, 9, 14 + $dd, $year))); // Кол-во дней до Недели по воздвижении от Пасхи
	$easter_prev = ortcal_easter( 'U', $year - 1, true );	// Пасха в предыдущем году
	$dd_prev = ortcal_dd( $year - 1 );						// Отклонение григорианского календаря от юлианского в днях в предыдущем году
	$er_e_prev = (date('U', mktime(0, 0, 0, 9, 14, $year - 1)) - $easter_prev)/DAY_IN_SECONDS + 
		(7 - date('w', mktime( 0, 0, 0, 9, 14 + $dd_prev, $year - 1))); // Кол-во дней до Недели по воздвижении от Пасхи в предыдущем году

	$end  = ortcal_add_days($easter_prev, 280+($er_e_prev-168));	// День конца годичного цикла
	$ec_e = ($easter - $end)/DAY_IN_SECONDS;				// Количество дней от конца годичного цикла до Пасхи

	if ( $ec_e <= 70 ) {									// Если внутрь-Пасха
		if ( $d > 210 + $ec_e ) return false;					// Отбрасываем лишние дни в конце цикла
		if ( $d < - 70 ) return false;							// Отсчет нового цикла с Недели о мытаре и фарисее
	} else {												// Если вне-Пасха
		if ( $ec_e > 98 ) {										// Если недостает более 4 седмиц, вставляем 17-ю седмицу между 31-й и 32-й
			if ( $d < - 97 ) $d = $d + 14;						// 17-я седмица хранится в начале базы данных с датами от -104 до -98
			else if ( $d >= - 97 && $d <= - 84 ) $d = $d - 7;
		} else 
		if ( $d < - $ec_e ) return false;						// Отсчет нового цикла начинаем за $ec_e дней до Пасхи
	}

	// Евангельское зачало Недели 28-й и апостольское зачало Недели 29-й читаются в Неделю святых праотец,
	// поэтому они меняются местами с соответствующими рядовым чтениями той Недели,
	// на которую придется в данном году Неделя свв. праотец.
	$ff_e = (date('U', mktime(0, 0, 0, 12, 25, $year)) - $easter)/DAY_IN_SECONDS - 
		(7 + date('w', mktime(0, 0, 0, 12, 25 + $dd, $year))) - ($er_e - 168); // Кол-во дней до Недели Праотцов от Пасхи с учетом воздвиженской отступки
	if ( $d == 245 ) {										// Неделя 28-я
		$d = $ff_e;
	}                                    
	else if ( $d == $ff_e ) {
		$d = 245;
	}

	if ( $date <= $end ) {									// До конца годичного цикла - отсчет от предыдущей Пасхи
		$easter = $easter_prev;									// Пасха в указанном году
		$er_e   = $er_e_prev;									// Кол-во дней до Недели по воздвижении от Пасхи
	}
	// Воздвиженская отступка: начало Чтения Луки всегда в Пн. после Недели по Воздвижении
	if ( $d > 168 ) {										// Смещение на величину Воздвиженской отступки $er_e-168
		$d = $d + ( $er_e - 168 );
	}                            
	if ( $date > ortcal_add_days($easter, 168) 
		&& $date <= ortcal_add_days($easter, $er_e) ) {		// Если 18-я седмица случилась до Недели по Воздвижении,
		if ( $d > 168 - ( $er_e - 168 ) && $d <= 168 ) {		// то дублируем чтения предыдущих(ей) седмиц(ы)
			$d = $d + ( $er_e - 168 );
		}                
	}

	$res=ortcal_add_days($easter, $d);
	return $res;
}

/*******************************************************************************
// Смещение даты для Чтений Псалтири
*******************************************************************************/  
function ortcal_shift300 ($d, $date, $year) {				
	$easter = ortcal_easter( 'U', $year, true );			// Пасха в текущем году
	if ( $date < ortcal_add_days($easter, 50) ) {			// До субботы перед Неделей сыропустной - отсчет от предыдущей Пасхи
		$year -= 1;
	}    
	$easter = ortcal_easter( 'U', $year, true );			// Пасха в текущем году
	$d_e    = ( $date - $easter )/DAY_IN_SECONDS;			// Дней после Пасхи
	if ( $d_e > 12 ) {										// От субботы перед Антипасхой до субботы перед Неделей сыропустной
		$dw = ( $d_e % 7 < 6 ) ? ( $d_e % 7 + 7 ) : 6;			// По 2 кафизмы с недельным циклом
		// От отдания Воздвижения (2.09 ст.ст.) до предпразднество Рождества Христова (20.12 ст.ст.) 
		// и от предпразнества Богоявления (15.01 ст.ст.) до субботы перед Неделей о блудном сыне
		$easter_next = ortcal_easter( 'U', $year+1, true );			// Пасха в следующем году
		if ( ( $date > date( 'U', mktime( 0, 0, 0, 9, 21, $year ) ) && $date < date( 'U', mktime( 0, 0, 0, 12, 20, $year ) ) ) ||
			 ( $date > date( 'U', mktime( 0, 0, 0, 1, 15, $year + 1 ) ) && $date < ortcal_add_days($easter_next, 64) )
		) {													// По 3 кафизмы с недельным циклом
			$dw += 350;
		} 

		if ( $d == $dw ) {
			return $date;
		}
	}

	$res=ortcal_add_days($easter, $d);
	return $res;
}
/*******************************************************************************
// Функция выводит на экран информацию об указанном дне
*******************************************************************************/  
function bg_ortcal_showDayInfo ( 
					$day,					// День (по умолчанию - сегодня)
					$month,					// Месяц (по умолчанию - сегодня)
					$year,					// Год (по умолчанию - сегодня)
					$date,					// Формат даты по нов. стилю
					$old,					// Формат даты по ст. стилю
					$sedmica,				// Седмица
					$memory,				// Памятные дни
					$honor,					// Дни поминовения усопших
					$holiday,				// Праздники (уровень значимости)
					$img,					// Значок праздника по Типикону
					$hosts,					// Соборы святых
					$saints,				// Святые
					$martyrs,				// Новомученники и исповедники российские
					$icons,					// Дни почитания икон Богоматери
					$posts,					// Постные дни
					$noglans,				// Дни, в которые браковенчание не совершается
					$readings,				// Чтения Апостола и Евангелие
					$links,					// Ссылки и цитаты
					$custom)				// Пользовательские ссылки
{
//$start_time = microtime(true);
	if ($day == 'post') {							// Дата создания текущего поста
		$year = get_the_date('Y');
		$month = get_the_date('m');
		$day = get_the_date('d');
	}
	else if (function_exists ($day) ) {				// Пользовательская функция определения даты
		$year = call_user_func ($day, 'Y');
		$month = call_user_func ($day, 'm');
		$day = call_user_func ($day, 'd');
	}
	else if (substr ($day,0,1) == '-') {			// Смещение текущей даты назад
		$year = date('Y');
		$month = date('m');
		$day = date('d')-((int)(substr($day,1)));
	}
	else if (substr ($day,0,1) == '+') {			// Смещение текущей даты вперед 
		$year = date('Y');
		$month = date('m');
		$day = date('d')+((int)(substr($day,1)));
	}
	else {
		if (!is_numeric ($year)) $year = date('Y');
		if (!is_numeric ($month) || ($month < 1 || $month > 12)) $month = date('m');
		if (!is_numeric ($day)) $day = date('d');
		$days = ortcal_numDays ($month, $year);
		if ($day < 1) $day = 1;			// если день задан меньше единицы то первое число 
		if ($day > $days) $day = $days;	// а если дата больше количества дней в месяце, последний день месяца
	}
	// Нормализуем дату
	$mtime = mktime(0, 0, 0, $month, $day, $year);

	$day = date('d', $mtime);
	$month = date('m', $mtime);
	$year = date('Y', $mtime);
	$wd = date('w', $mtime);

	$qdate = "&date=".$year."-".$month."-".$day;


	if ($sedmica != 'on' && $sedmica != 'nedela') $sedmica = 'off';
	if ($memory != 'off') $memory = 'on';
	if ($honor != 'off') $honor = 'on';

	if (!is_numeric ( $holiday ) && $holiday != 'off') $holiday = 7;
	if ($holiday < 0) $holiday = 0;
	if ($holiday > 7) $holiday = 7;
	if ($img != 'off') $img = 'on';

	// Тип отображения ссылок и цитат
	if ($links == 'on') $links = '';		// on - отображать ссылки, off - ничего не отображать, verses, quotes, и т.д. - цитаты.

	$quote = '';
	if ($date != 'off' && $date != '') $quote .= '<span class="bg_ortcal_date'.(($wd==0)?' bg_ortcal_sunday':'').'">'.ortcal_dateRU (date($date, mktime(0, 0, 0, $month, $day, $year))).'</span>';
	if ($old != 'off' && $old != '') $quote .= '<span class="bg_ortcal_old'.(($wd==0)?' bg_ortcal_sunday':'').'">'.ortcal_oldStyle ($old,  $month, $day, $year).'</span><br>';
	if ($sedmica == 'on' || ($sedmica == 'nedela' && $wd==0)) $quote .= '<span class="bg_ortcal_sedmica'.(($wd==0)?' bg_ortcal_sunday':'').'">'.ortcal_sedmica ($month, $day, $year).'</span><br>';

	$e = bg_ortcal_dayEvents($month, $day, $year);
	$e1 = bg_ortcal_dayEvents($month, $day+1, $year);
	$cnt = count($e);
	$cnt1 = count($e1);
	if ($cnt) {
		// Памятные даты
		if ($memory != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 8) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= '<span class="bg_ortcal_memory">'.$q.'</span><br>';
		}
		// Дни поминовения усопших
		if ($honor != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 9) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= '<span class="bg_ortcal_honor">'.$q.'</span><br>';
		}
		// Праздники
		if ($holiday != 'off') {
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] <= $holiday) {
					if ($e[$i]['type'] <= 2) $quote .= '<span class="bg_ortcal_great">'.(($img=='off')?'':ortcal_imgTypicon($e[$i]['type'])).ortcal_eventLink ($e[$i], $qdate).'</span><br>';
					else if ($e[$i]['type'] <= 4) $quote .= '<span class="bg_ortcal_middle">'.(($img=='off')?'':ortcal_imgTypicon($e[$i]['type'])).ortcal_eventLink ($e[$i], $qdate).'</span><br>';
					else $quote .= '<span class="bg_ortcal_small">'.(($img=='off')?'':ortcal_imgTypicon($e[$i]['type'])).ortcal_eventLink ($e[$i], $qdate).'</span><br>';
				}
			}
		}
		// Соборы святых
		if ($hosts != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 16) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($hosts!='on')?htmlspecialchars_decode($hosts):'').'<span class="bg_ortcal_hosts">'.$q.'</span><br>';
		}
		// Дни почитания святых
		if ($saints != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 18) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($saints!='on')?htmlspecialchars_decode($saints):'').'<span class="bg_ortcal_saints">'.$q.'</span><br>';
		}
		// Дни почитания исповедников и новомучеников российских
		if ($martyrs != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 19) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($martyrs!='on')?htmlspecialchars_decode($martyrs):'').'<span class="bg_ortcal_martyrs">'.$q.'</span><br>';
		}
		// Дни почитания икон
		if ($icons != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 17) $q .= ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($icons!='on')?htmlspecialchars_decode($icons):'').'<span class="bg_ortcal_icons">'.$q.'</span><br>';
		}
		// Посты и светлые седмицы
		if ($posts != 'off') {
			$q ="";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 10) $q = ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 100) $q = ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($posts!='on')?htmlspecialchars_decode($posts):'').'<span class="bg_ortcal_posts">'.$q.'</span><br>';
		}
		// Дни, в которые браковенчание не проводится
		if ($noglans != 'off') {
			$q ="";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 20) $q = ortcal_eventLink ($e[$i], $qdate).'. ';
			}
			if ($q) $quote .= (($noglans!='on')?htmlspecialchars_decode($noglans):'').'<span class="bg_ortcal_noglans">'.$q.'</span><br>';
		}
		// Евангельские чтения
		if ($readings != 'off') {
			$q ="";
			$qtitle = '';
			$qq=array(); $qq1=array();
				for ($id=200; $id < 310; $id++) {
					$qq[$id] = ""; $qq1[$id] = "";
				}
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] >= 200 && $e[$i]['type'] <= 309) {
					$id = $e[$i]['type'];
					$qq[$id] .= (($qq[$id]!="")?('; '):('')).$e[$i]['name'];	// На сегодня
				}
			}
			for ($i=0; $i < $cnt1; $i++) {
				if ($e1[$i]['type'] >= 200 && $e1[$i]['type'] <= 309) {
					$id = $e1[$i]['type'];
					$qq1[$id] .= (($qq1[$id]!="")?('; '):('')).$e1[$i]['name'];	// На завтра
				}
			}

			if ($readings == 'M') {												// Только Утрени
				if ($qq[202] != "") $q .= $qq[202];
			}
			else if ($readings == 'A') {										// Только Апостол на Литургию
				if ($qq[204] != "") $q .= $qq[204];
			}
			else if ($readings == 'G') {										// Только Евангелие на Литургию
				if ($qq[207] != "") $q .= $qq[207];
			}
			else if ($readings == 'AG') {										// Только Апостол и Евангелие на Литургию
				if ($qq[204] != "") $q .= $qq[204];
				if ($qq[207] != "") $q .= (($q!="")?('; '):('')).$qq[207];
			}
			else if ($readings == 'MAG') {										// Только Утрени и Апостол и Евангелие на Литургию
				if ($qq[202] != "") $q .= $qq[202];
				if ($qq[204] != "") $q .= (($q!="")?('; '):('')).$qq[204];
				if ($qq[207] != "") $q .= (($q!="")?('; '):('')).$qq[207];
			}
			else if ($readings == 'E') {										// Только Вечерни
				if ($qq[208] != "") $q .= $qq[208];
			}
			else if ($readings == 'H') {										// Только Часы
				if ($qq[201] != "") $q .= $qq[201];
				if ($qq[203] != "") $q .= (($q!="")?('; '):('')).$qq[203];
				if ($qq[206] != "") $q .= (($q!="")?('; '):('')).$qq[206];
				if ($qq[209] != "") $q .= (($q!="")?('; '):('')).$qq[209];
			}
			else if ($readings == 'F') {										// Только Праздники
				for ($type = 10; $type < 70; $type +=10) {
					if ($qq[200+$type+2] != "") $q .= '<em> '.__('На утр.:', 'bg_ortcal').' - </em>'.$qq[200+$type+2]."<br>";			

					if (($qq[200+$type+4] != "") || ($qq[200+$type+7] != "")) {
						$q .= '<em> '.__('На лит.:', 'bg_ortcal').' - </em>';
						if ($qq[200+$type+4] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[200+$type+4];			
						if ($qq[200+$type+7] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[200+$type+7];
						$q .= "<br>";
					}
					if ($qq[200+$type+8] != "") $q .= '<em> '.__('На веч.:', 'bg_ortcal').' - </em>'.$qq[200+$type+8]."<br>";			
					if ($qq[200+$type+1] != "") $q .= '<em> '.__('На 1-м часе:', 'bg_ortcal').' - </em>'.$qq[200+$type+1]."<br>";			
					if ($qq[200+$type+3] != "") $q .= '<em> '.__('На 3-м часе:', 'bg_ortcal').' - </em>'.$qq[200+$type+3]."<br>";			
					if ($qq[200+$type+6] != "") $q .= '<em> '.__('На 6-м часе:', 'bg_ortcal').' - </em>'.$qq[200+$type+6]."<br>";			
					if ($qq[200+$type+9] != "") $q .= '<em> '.__('На 9-м часе:', 'bg_ortcal').' - </em>'.$qq[200+$type+9]."<br>";			
				}
			}
			else if ($readings == 'P') {										// Только Псалтирь
				if ($qq[302] != "") $q .= $qq[302];
				if ($qq[308] != "") $q .= (($q!="")?('; '):('')).$qq[308];
				if ($qq[301] != "") $q .= (($q!="")?('; '):('')).$qq[301];
				if ($qq[303] != "") $q .= (($q!="")?('; '):('')).$qq[303];
				if ($qq[306] != "") $q .= (($q!="")?('; '):('')).$qq[306];
				if ($qq[309] != "") $q .= (($q!="")?('; '):('')).$qq[309];
			}
			else {																// Все чтения
				$qz = "";
				$qw = ortcal_worshipReadins ($qq, 2);			// На Утрене
				if ($qw) $qz .= '<em> '.__('На утр.:', 'bg_ortcal').' - </em>'.$qw;
				
				$qw = ortcal_liturgyReadins ($qq, $qq1, $wd);	// На литургии
				if ($qw) $qz .= '<em> '.__('На лит.:', 'bg_ortcal').' - </em>'.$qw;

				$qw = ortcal_worshipReadins ($qq, 8);			// На Вечерне
				if ($qw) $qz .= '<em> '.__('На веч.:', 'bg_ortcal').' - </em>'.$qw;
				$qw = ortcal_worshipReadins ($qq, 1);			// На 1-м часе
				if ($qw) $qz .= '<em> '.__('На 1-м часе:', 'bg_ortcal').' - </em>'.$qw;
				$qw = ortcal_worshipReadins ($qq, 3);			// На 3-м часе
				if ($qw) $qz .= '<em> '.__('На 3-м часе:', 'bg_ortcal').' - </em>'.$qw;
				$qw = ortcal_worshipReadins ($qq, 6);			// На 6-м часе
				if ($qw) $qz .= '<em> '.__('На 6-м часе:', 'bg_ortcal').' - </em>'.$qw;
				$qw = ortcal_worshipReadins ($qq, 9);			// На 9-м часе
				if ($qw) $qz .= '<em> '.__('На 9-м часе:', 'bg_ortcal').' - </em>'.$qw;

				if ($qz != "") $qz = "<em><strong>".__('Евангелие и Апостол:', 'bg_ortcal')."</strong></em><br>".$qz;

				$qw = ortcal_psalmsReadins ($qq);							// Чтения Псалтири
				if ($readings != 'N' && $qw != "") $qz .= (($qz!="")?('<br>'):(''))."<em><strong>".__('Псалтирь:', 'bg_ortcal')."</strong></em><br>".$qw;
				$q .= $qz;
				// Если не $readings специальные символы, то выводим их на экран
				if ($readings!='on' && $readings != 'N') $qtitle = htmlspecialchars_decode($readings);
			}
// Если подключен плагин Bg Bible References, то допускается расширенное представление Чтений
			if (function_exists ('bg_bibrefs_convertTitles') && $links != 'off') { // Bg Bible References версии выше 3.11 
				$q = bg_bibrefs_convertTitles($q, $links); // Преобразуем заголовки и подсвечиваем ссылки или выводим на экран текст Священного Писания
			}
			else if (function_exists ('bg_bibfers_convertTitles') && $links != 'off') { // Bg Bible References до версии 3.10 
				$q = bg_bibfers_convertTitles($q, $links); // Преобразуем заголовки и подсвечиваем ссылки или выводим на экран текст Священного Писания
			}
			if ($q) $quote .= $qtitle.'<span class="bg_ortcal_readings">'.$q.'</span><br>';
		}
		// Пользовательские ссылки
		if ($custom != 'off') {
			$q = "";
			for ($i=0; $i < $cnt; $i++) {
				if ($e[$i]['type'] == 999) $q .= ortcal_eventLink ($e[$i], $qdate).'<br>';
			}
			if ($q) $quote .= (($custom!='on')?'<strong>'.htmlspecialchars_decode($custom).'</strong>':'').'<br><span class="bg_ortcal_custom">'.$q.'</span>';
		}
	}
	$res="{$quote}";

//error_log($qdate.' '.(microtime(true)-$start_time).' сек.(bg_ortcal_showDayInfo)'.PHP_EOL, 3, dirname(__FILE__)."/bg_error.log" );
	return $res;
}
/*******************************************************************************
// Функция формирует вывод на экран списка чтений на церковную службу (кроме Литургии)
*******************************************************************************/  
function ortcal_worshipReadins ($qq, $w) {
	$q = "";
	if ($qq[200+$w] != "") $q = $qq[200+$w];						// Рядовые чтения
	
	for ($type = 80; $type > 0; $type -=10) {
		if ($qq[200+$type+$w] != "") $q = $qq[200+$type+$w];			// Праздники в порядке возрастания значимости
	}
	if ($qq[290+$w] != "") $q .= (($q!="")?('; '):('')).$qq[290+$w];	// Чтения ВМЕСТЕ другими
	return $q;
}
/*******************************************************************************
// Функция формирует вывод на экран списка чтений на Литургии 
*******************************************************************************/  
function ortcal_liturgyReadins ($qq, $qq1, $wd) {
	$q = "";
			
// В церковном году есть воскресенья, когда, помимо рядовых чтений, положены еще и особые: чтения Недели святых праотец, святых отец пред Рождеством Христовым, по Рождестве Христове, пред Просвещением, по Просвещении.
// Устав предписывает рядовые чтения этих Недель или опускать совсем, как в Недели святых праотец и святых отец, или разрешает читать под зачало, то есть два подряд, в случае, «аще не будет отступки» (см. Типикон под 26 декабря, 9-е «зри»).
// В праздники Рождества Христова и Богоявления Господня, случившиеся в воскресенье, рядовое воскресное зачало не читается.
	if ($qq[284] != "" || $qq[287] != "") {					// Особые дни. Чтения ВМЕСТО рядовых
		$q .= "<em>".__('Праздник:', 'bg_ortcal')."</em> ";
		if ($qq[284] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[284];
		if ($qq[287] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[287];
	}
	else if($qq[294] != "" || $qq[297] != "") {				// Особые дни. Чтения ВМЕСТЕ с рядовыми
		$q .= "<em>".__('Праздник:', 'bg_ortcal')."</em> ";
		if ($qq[294] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[294];
		if ($qq[297] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[297];

		if ($qq[204] != "" || $qq[207] != "") $q .= "<em> ".__('Под зач.:', 'bg_ortcal')." </em>";	// Рядовые чтения "под зачало"
		if ($qq[204] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[204];
		if ($qq[207] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[207];
	}
// В великие праздники Господские, Богородичные и святых, которым положено бдение, рядовые Апостол и Евангелие не читаются, а только данному празднику или святому.
// Но если великий Богородичен праздник или святого с бдением случится в воскресный день тогда читается сначала воскресный рядовой Апостол и Евангелие, а потом уже праздника или святого.
	else if ($qq[234] != "" || $qq[237] != "") {			// Бденные праздники
		if (($qq[204] != "" || $qq[207] != "") && $wd == 0) {
			if ($qq[204] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[204];
			if ($qq[207] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[207];
			$q .= "<em> ".__('Под зач.:', 'bg_ortcal')." </em>";	// Чтения праздника "под зачало"
		}
		$q .= "<em><strong>".__('Праздник:', 'bg_ortcal')."</strong></em> ";
		if ($qq[234] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[234];
		if ($qq[237] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[237];
	}
	else if ($qq[224] != "" || $qq[227] != "") {			// Богородичные праздники
		if (($qq[204] != "" || $qq[207] != "") && $wd == 0) {
			if ($qq[204] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[204];
			if ($qq[207] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[207];
			$q .= "<em> ".__('Под зач.:', 'bg_ortcal')." </em>";	// Чтения праздника "под зачало"
		}
		$q .= "<em><strong>Праздник:', 'bg_ortcal').'</strong></em> ";
		if ($qq[224] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[224];
		if ($qq[227] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[227];
	}
	else if ($qq[214] != "" || $qq[217] != "") {			// Господские праздники
		$q .= "<em><strong>".__('Праздник:', 'bg_ortcal')."</strong></em> ";
		if ($qq[214] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[214];
		if ($qq[217] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[217];
	}
	else {													// Рядовые чтения
		if ($qq[204] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[204];
		if ($qq[207] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[207];	
	}
// Если завтра случится праздник Господский, или Богородичный и святого, которому положено бдение и при этом завтра не воскресенье (сегодня не суббота), то сегодня читаются рядовые Апостол и Евангелие завтрешнего дня "под зачало".
	if ((($qq1[214] != "" || $qq1[217] != "") || ($qq1[224] != "" || $qq1[227] != "") || ($qq1[234] != "" || $qq1[237] != "")) && $wd == 6) {	// Завтра Господский, Богородичен или бденный праздник и воскресенье
		if ($qq1[204] != "" || $qq1[207] != "") $q .= "<em> ".__('Под зач.:', 'bg_ortcal')." </em>";	// Рядовые чтения "под зачало"
		if ($qq1[204] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq1[204];
		if ($qq1[207] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq1[207];
	}
	return $q;
}


/*******************************************************************************
// Функция формирует вывод на экран списка чтений Псалтири
*******************************************************************************/  
function ortcal_psalmsReadins ($qq) {
	$q = "";
	if ($qq[302] != "") $q .= '<em> '.__('На утр.:', 'bg_ortcal').' - </em>'.$qq[302];
	if ($qq[304] != "" || $qq[307] != "") {
		$q .= '<em> '.__('На лит.:', 'bg_ortcal').' - </em>';
		if ($qq[304] != "") $q .= '<em> '.__('Ап.:', 'bg_ortcal').' </em>'.$qq[304];
		if ($qq[307] != "") $q .= '<em> '.__('Ев.:', 'bg_ortcal').' </em>'.$qq[307];
	}
	if ($qq[308] != "") $q .= '<em> '.__('На веч.:', 'bg_ortcal').' - </em>'.$qq[308];
	if ($qq[301] != "") $q .= '<em> '.__('На 1-м часе:', 'bg_ortcal').' - </em>'.$qq[301];
	if ($qq[303] != "") $q .= '<em> '.__('На 3-м часе:', 'bg_ortcal').' - </em>'.$qq[303];
	if ($qq[306] != "") $q .= '<em> '.__('На 6-м часе:', 'bg_ortcal').' - </em>'.$qq[306];
	if ($qq[309] != "") $q .= '<em> '.__('На 9-м часе:', 'bg_ortcal').' - </em>'.$qq[309];
	if ($qq[305] != "") $q .= ' '.$qq[305];

	return $q;
}

/*******************************************************************************
// Функция выводит на экран значок по Типикону
*******************************************************************************/  
function ortcal_imgTypicon ($lavel) {
	$title = array (__('Светлое Христово Воскресение. Пасха', 'bg_ortcal'), __('Двунадесятый праздник', 'bg_ortcal'), __('Великий праздник', 'bg_ortcal'), __('Средний бденный праздник', 'bg_ortcal'), __('Средний полиелейный праздник', 'bg_ortcal'), __('Малый славословный праздник', 'bg_ortcal'), __('Малый шестиричный праздник', 'bg_ortcal'), __('Вседневный праздник. Cовершается служба, не отмеченная в Типиконе никаким знаком', 'bg_ortcal'));
	return '<img src="'.plugins_url( 'js/S'.$lavel.'.gif' , dirname(__FILE__) ).'" title="'.$title[$lavel].'" /> ';
}
/*******************************************************************************
// Функция формирует гиперссылку события
*******************************************************************************/  
function ortcal_eventLink ($e, $date) {
	$bg_ortcal_addDate_val =  get_option( "bg_ortcal_addDate" );
	if ($bg_ortcal_addDate_val == 'on' && $e['link']) {
		if (strrpos ( $e['link'], "?" ) ) $link = $e['link']."&".$date;
		else $link = $e['link']."?".$date;
	}
	else $link = $e['link'];

	$bg_ortcal_linkImage_val =  get_option( "bg_ortcal_linkImage" );
	if ($bg_ortcal_linkImage_val) {
		if (is_file(ABSPATH . $bg_ortcal_linkImage_val)) $bg_ortcal_linkImage = '<img src="'.site_url( $bg_ortcal_linkImage_val ).'" style="border: 0px; padding: 0px; margin: 0px;">';
		else if (is_file_url($bg_ortcal_linkImage_val)) $bg_ortcal_linkImage =  '<img src="'.$bg_ortcal_linkImage_val.'" style="border: 0px; padding: 0px; margin: 0px;">';
		else $bg_ortcal_linkImage = $bg_ortcal_linkImage_val;

		if ($link) $res = $e['name'].' <a href="'.$link.'"  style="border: 0px; padding: 0px; margin: 0px;" title="'.$e['discription'].'">'.$bg_ortcal_linkImage.'</a> ';
		else if ($e['discription']) $res = $e['name'].' <span title="'.$e['discription'].'">'.$bg_ortcal_linkImage.'</span> ';
		else $res = $e['name'];
	}
	else {
		if ($link) $res = '<a href="'.$link.'" title="'.$e['discription'].'">'.$e['name'].'</a>';
		else if ($e['discription']) $res = '<span title="'.$e['discription'].'">'.$e['name'].'</span>';
		else $res = $e['name'];
	}
	return $res;
}

/*******************************************************************************
// Функция проверяет url файла на его наличие
*******************************************************************************/  
function is_file_url ($url){
	$file_headers = @get_headers($url);

	$file_exists = false;
	if (false !== strpos($file_headers[0], '200 OK')) {
	  // Проверка MIME-типа: [3] => Content-Type: image/png
	  $file_exists = true;
	}
	return $file_exists;
}