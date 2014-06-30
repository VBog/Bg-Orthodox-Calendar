var events;
var curMD = [];
var curYear;

function OldStyle(od, nd){
var Y, dd;

	Y=nd.getFullYear();
	dd = (Y-Y%100)/100 - (Y-Y%400)/400 - 2;
	od.setTime(nd.getTime());
	od.setDate(nd.getDate()-dd);

}
	
function easter(ed, Y) {
// Определяем день Пасхи на заданный год
var a, b, dd, D, M;
	
	a = ((19*(Y %19) + 15) % 30);
	b = ((2*(Y %4) + 4*(Y %7) + 6*a + 6) % 7);
	if (a+b>9) {
		D=a+b-9; M=3;
	}
	else {
		D=22+a+b; M=2;
	}
	ed.setFullYear(Y,M,D);
	dd = (Y-Y%100)/100 - (Y-Y%400)/400 - 2;
	ed.setDate(ed.getDate()+dd);
}

function MemoryDay () {
	this.start = new Date(0);
	this.finish = new Date(0);
	this.name = "";
	this.type = -999;
	this.setMemoryDay = function (s, f, n, t){
		this.start.setTime(s.getTime());
		this.finish.setTime(f.getTime());
		this.name = n;
		this.type = t;
	}
}

// Возвращает название Седмицы (Недели) по годичному кругу богослужений.
function Sedmica (d) {											// d - текущая дата
	var nw;
	var one_day=1000*60*60*24;									//1 день в милисекундах
	var wd = d.getDay();
	var easter_day = new Date(0);
	year = d.getFullYear();
	easter(easter_day, year);
	var cd = Math.floor(d.getTime()/one_day);
	var ed = Math.floor(easter_day.getTime()/one_day)-1;
	if (cd < ed-70) {				// До Недели о мытаре и фарисее идут седмицы по Пятидесятнице прошлого года
		easter(easter_day, (year-1));
		ed = Math.floor(easter_day.getTime()/one_day)-1;
		nw = Math.ceil((cd - (ed+49))/7);
		if (wd == 0) return "Неделя "+nw+"-я по Пятидесятнице";
		else return "Седмица "+nw+"-я по Пятидесятнице";
	}
	else if (cd == ed-70) return "Неделя о мытаре и фарисее";	// Седмицы подготовительные
	else if (cd < ed-63) return "Седмица о мытаре и фарисее";	
	else if (cd == ed-63) return "Неделя о блудном сыне";	
	else if (cd < ed-56) return "Седмица о блудном сыне";	
	else if (cd == ed-56) return "Неделя мясопустная, о Страшнем суде";	
	else if (cd < ed-49) return "Сырная седмица (масленица)";						
	else if (cd == ed-49) return "Неделя сыропустная. Воспоминание Адамова изгнания. Прощеное воскресенье";						
	else if (cd < ed-13) {										// Седмицы Великого поста
		nw = Math.ceil((cd - (ed-49))/7);
		if (wd == 0) return "Неделя "+nw+"-я Великого поста";
		else return "Седмица "+nw+"-я Великого поста";
	}
	else if (cd < ed-7) return "Седмица 6-я Великого поста (седмица ваий)";
	else if (cd == ed-7) return "Неделя 6-я Великого поста ваий (цветоносная, Вербное воскресенье)";
	else if (cd < ed) return "Страстная седмица";
	else if (cd == ed) return "";
	else if (cd < ed+7) return "Пасхальная (Светлая) седмица";
	else if (cd < ed+50) {										// Седмицы по Пасхе
		nw = Math.ceil((cd - ed)/7);
		if (wd == 0) return "Неделя "+(nw+1)+"-я по Пасхе";
		else return "Седмица "+nw+"-я по Пасхе";
	}
	else  {														// Седмицы по Пятидесятнице
		nw = Math.ceil((cd - (ed+49))/7);
		if (wd == 0) return "Неделя "+nw+"-я по Пятидесятнице";
		else return "Седмица "+nw+"-я по Пятидесятнице";
	}

	return "";
}

function ArrayMemoryDay (k) {
	
	for (i=1; i<k; i++) {
		(this)[i] = new MemoryDay ();
	}
	this.length=k;
}

function getLink(d, type) {
	var omonth, odate, l = "";
	var od= new Date();
	var now = new Date();
	var mon = new Array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");

	OldStyle(od, d);
	switch (type) {
	case 1: 								// Официальный календарь РПЦ
		if (d.getFullYear()!=now.getFullYear()) break;	// Если не текущий год, то на выход
		odate=od.getDate();
		if (odate<10) {odate="0"+odate}
		if (od.getFullYear() == d.getFullYear()) {
			l = "http://calendar.rop.ru/mes1/"+mon[od.getMonth()]+""+odate+".html";
		} else if (od.getFullYear() == d.getFullYear()-1) {
			l = "http://calendar.rop.ru/mes1/"+"dde"+odate+".html";		
		}
		break;
	case 2: 								// Страница дня на Православие.Ru
		if (d.getFullYear()!=now.getFullYear()) break;	// Если не текущий год, то на выход
		omonth=od.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=od.getDate();
		if (odate<10) {odate="0"+odate}
		l = "http://days.pravoslavie.ru/Days/"+od.getFullYear()+""+omonth+""+odate+".htm";
		break;
	case 3: 								// Богослужебные указания
		if (d.getFullYear()!=now.getFullYear()) break;	// Если не текущий год, то на выход
		omonth=d.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=d.getDate();
		if (odate<10) {odate="0"+odate}
		l = "http://www.patriarchia.ru/bu/"+od.getFullYear()+"-"+omonth+"-"+odate+"/print.html";
		break;
	case 101: 								// Богослужебные указания
		omonth=d.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=d.getDate();
		if (odate<10) {odate="0"+odate}
		l = baseUrl+"?year="+d.getFullYear()+"&month="+omonth+"&date="+odate;
		break;
	}

	return l;
}

function memory_days(year) {
	if (year == curYear) return curMD;
	
//	var md = [];

	var easter_day= new Date(0);
	var d= new Date(0);
	var sd= new Date(0);
	var fd= new Date(0);
	var bufer = new MemoryDay();
	var iw, im, ddd, wd;
	var dd; 
//	var daysY = isLeap(year) ? 366 : 365;
	var one_day=1000*60*60*24;	//1 день в милисекундах
					
//	Светлое Христово Воскресение. Пасха. 
	easter(easter_day, year);

	var j=0;
	if(events) {
		for(var i=0; i<events.length; i++)
		{
				
			name=getXMLvalue(events[i],"name");
			type=getXMLvalue(events[i],"type");
						
					
			s_date=parseInt(getXMLvalue(events[i],"s_date"));
			s_month=parseInt(getXMLvalue(events[i],"s_month"));
			f_date=parseInt(getXMLvalue(events[i],"f_date"));
			f_month=parseInt(getXMLvalue(events[i],"f_month"));

			
			for (var y = year-1; y <= year+1; y++) { 
				dd = (y-y%100)/100 - (y-y%400)/400 - 2; 
			//	Светлое Христово Воскресение. Пасха. 
				easter(easter_day, y);
			
			
				// Если не висакосный год, то праздники, приходящиеся на 29 февраля, отмечаются 28 февраля
				if (!isLeap(y)) {
					if (s_month == 2 && s_date == 29) s_date = 28;
					if (f_month == 2 && f_date == 29) f_date = 28;
				}	
				if (s_month < 0) {					
					sd.setFullYear(y, f_month-1, f_date+dd);		// Сб./Вс. перед/после праздника = -1, Праздник в Сб./Вс. перед/после даты = -2 Праздник в указанный день недели = -3
					we = sd.getDay();
					wd = s_date-we;
					if (s_month == -1 && wd == 0) continue;			// Если праздник приходится на Сб./Вс. перед/после праздника = -1, то игнорируем событие
					if (s_month == -3 && we != s_date) continue;	// Если праздник не совпадает с указанным днем недели, то не отмечается
					sd.setDate(sd.getDate()+wd);
					fd.setTime(sd.getTime());
				}
				else {
					if (s_month == 0) {						// Переходящие даты
						sd.setTime(easter_day.getTime());
						sd.setDate(sd.getDate()+s_date);
					}
					else {
						sd.setFullYear(y, s_month-1, s_date);
						sd.setDate(sd.getDate()+dd);
					}

					if (f_month == 0) {						// Переходящие даты
						fd.setTime(easter_day.getTime());
						fd.setDate(fd.getDate()+f_date);
					}
					else {
						if (s_month > f_month) fd.setFullYear(y+1, f_month-1, f_date);		// Cедмицы на рубеже года(напр., Святки)
						else fd.setFullYear(y, f_month-1, f_date);
						fd.setDate(fd.getDate()+dd);
					}
				}
								
				if (name == "") continue;													// Если наименование события не задано, то игнорируем это событие
				if ((sd.getFullYear() != year) && (fd.getFullYear() != year))  continue; 	// Если начальная и конечная даты события не в текущем году, то игнорируем событие
		// Сохраняем это событие в памяти 
				curMD[j]=new MemoryDay();
				curMD[j].setMemoryDay (sd, fd, name, type);
								
				j++;
			}
		}
	}
//	else alert ("Нет XML-данных.");
	
// Если Начало великого поста совпадает со Сретением, Праздник переносится на ближайшее воскесение.???
// Согласно греческого типикона празднование Благовещения, если оно приходится на Великую пятницу или субботу, переносится на первый день Пасхи.

// Сортируем массив памятных дат	
	t = true;
	while (t == true) {
	t = false;
		for (i=0; i<curMD.length-1; i++) {
			if (curMD[i].start.getTime() > curMD[i+1].start.getTime()){
				bufer.setMemoryDay(curMD[i].start, curMD[i].finish, curMD[i].name,  curMD[i].type);
				curMD[i].setMemoryDay (curMD[i+1].start, curMD[i+1].finish, curMD[i+1].name,  curMD[i+1].type);
				curMD[i+1].setMemoryDay (bufer.start, bufer.finish, bufer.name,  bufer.type);
				t = true;
			}
		}
	}
	curYear = year;
	return curMD;
}

function setLink(t) {
	var d = new Date(0);
	d.setTime(t);
	var link = getLink(d, dayLink);
	if (link) {
		window.open(link);
	}
}

function getDayInfo(d) {

    var mnr = new Array(" января"," февраля"," марта"," апреля"," мая"," июня"," июля"," августа"," сентября"," октября"," ноября"," декабря");
	var cwd = new Array("Воскресение","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота");
	var typicon = new Array("Светлое Христово Воскресение","Двунадесятый праздник","Великий праздник","Средний бденный праздник","Средний полиелейный праздник","Малый славословный праздник","Малый шестиричный праздник","Вседневный праздник","Памятная дата","День особого поминовения усопших");

	var curD = d.getDate();
	var curM = d.getMonth()+1;
	var curY = d.getFullYear();
	var curW = d.getDay();
	var name;
	var yCh;
	
	var md = memory_days(curY);
	var beginY=new Date(0);
	var nowY=beginY.getFullYear();
	beginY.setFullYear(curY-1, 11, 31);
	var one_day=1000*60*60*24;	//1 день в милисекундах
	var sd, fd;
	var num_day = Math.round((d.getTime()-beginY.getTime())/(one_day));	

// Дата по новому и старому стилю, Седмица, Памятные дни
	var od = new Date(0);
	OldStyle(od, d);
	if (curY == 0) yCh = 'в Год Рождества Христова';
	else if (curY < 0) yCh = (-curY) + ' г. до РХ';
	else yCh = curY + ' г. от РХ';
		
	var t = curD+mnr[curM-1]+" "+yCh+" ("+od.getDate()+mnr[od.getMonth()]+" ст.ст.), "+cwd[curW];
	if (curY == nowY) t = "<span class='curDate' style='cursor:pointer' onclick=\'setLink("+d.getTime()+")\'>" + t + "</span><br>";
	else t = "<span class='curDate' style='cursor:auto' onclick=\'setLink("+d.getTime()+")\'>" + t + "</span><br>";		// Если не текущий год, то курсор обыкновенный
	var tt = Sedmica(d);																			// Седмица
	if (tt !="") t += "<b>" + tt + "</b><br>";
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 8 || md[k].type == 9) {													// Памятные дни. (Тип 8 и 9)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<b>"+md[k].name+"</b><br>";
			} 	
		}
	}
	if (tt !="") t += tt;
	t = "<div id='dateID' align='center'>"+t+"</div>";

	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 0) {																		// Светлое Христово Воскресение. Пасха. (Тип 0)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<img src='"+baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' /><span class='holiday'>"+md[k].name+"</span><br>";
			} 	
		}
	}
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 1 || md[k].type == 2) {													// ДВУНАДЕСЯТЫЕ И ВЕЛИКИЕ ПРАЗДНИКИ (Тип 1 и 2)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<img src='"+baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' /><span class='holiday'>"+md[k].name+"</span><br>";
			} 	
		}
	}
	if (tt !="" || curW == 0) t=t.replace("curDate","holDate");										// Если Великий праздник или воскресный день, меняем цвет даты на красный
		
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type >= 3 && md[k].type <= 7) {													// СРЕДНИЕ, МАЛЫЕ и другие ПРАЗДНИКИ (Типы 3-7)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<img src='"+baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' />"+md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>Праздники:</b><br>" + tt;
		
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 18) {																		// Святые. (Тип 18)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>День памяти святых:</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "" || curY < 2001) continue;	
		if (md[k].type == 19) {																		// Исповедники и новомученики российские. (Тип 19)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>День памяти исповедников и новомучеников российских:</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 17) {																		// Дни почитания местночтимых икон Божией Матери. (Тип 17)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>День почитания икон Божией Матери:</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {													// Сплошные седмицы. (Тип 100)												
		if (md[k].name == "") continue;												
		if (md[k].type == 100) {														
			sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));	
			fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));	
																						
			if (num_day >= sd && num_day <= fd) {									
				tt +=  "<i>"+md[k].name+"</i>";
				break;
			}
		}
	}
	for (k = 0; k < md.length; k++) {													// Многодневные и однодневные посты.  (Тип 10) && Не забываем про окончание Филлипова поста в начале года												
		if (md[k].name == "") continue;												
		if (md[k].type == 10) {														
			sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));	
			fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));	
																						
			if (num_day >= sd && num_day <= fd) {									
				if (tt !="") tt += ". ";
				tt +=  "<i>"+md[k].name+"</i>";
				break;
			}
		}
	}
	if ((tt =="") && (curW == 3 || curW == 5)) {tt +=  "<i>Постный день</i>";}			// Среда и пятница - постные дни	 						

// Браковенчание не совершается
	var nowedding = "<i>Браковенчание не совершается</i>";
	if (curW == 2 || curW == 4 || curW == 6) {											// Не совершается браковенчание накануне среды и пятницы всего года (вторник и четверг), и воскресных дней (суббота)
		if (tt !="") tt += ". ";
		tt +=  nowedding;
	}	 						
	else {
		for (k = 0; k < md.length; k++) {												// А также не совершается браковенчание накануне двунадесятых, храмовых и великих праздников
			if (md[k].name == "") continue;												// в продолжение постов Великого, Петрова, Успенского и Рождественского; 
			if (md[k].type == 20) {														// в продолжение Святок с 7 января (25 декабря) по 19 (6) января; сырной седмицы (масленицы),
				sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));	// начиная с Недели мясопустной и в Неделю сыропустную; в течение Пасхальной (Светлой) седмицы;
				fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));	// в дни (и накануне) Усекновения главы Иоана Предтечи - 11 сентября (29 августа) и 
																						// Воздвижения Креста Господня - 27 (12) сентября.
				if (num_day >= sd && num_day <= fd) {									// Тип 20 && Не забываем про окончание Филлипова поста в начале года
					if (tt !="") tt += ". ";
					tt +=  nowedding;
					break;
				}
			}
		}
	}
	if (tt !="") t += "<hr>" + tt;
	
	return t;
}




function getXMLvalue(e,tag) {
	var s=e.getElementsByTagName(tag);
	if (s[0].textContent) return s[0].textContent;
	else return s[0].text;
}


function isLeap(year) {
		return (((year % 4)==0) && ((year % 100)!=0) || ((year % 400)==0)) ? true : false }

// Кроссбраузерное создание объекта запроса		
function getXmlHttp(){
  var xmlhttp;
  try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (e) {
    try {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e) {
      xmlhttp = false;
    }
  }
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  return xmlhttp;
}	

function loadXML() {
// 	Получаем данные о событиях из XML-файла
	var xmlhttp = getXmlHttp();
	xmlhttp.open("GET", baseUrl+"MemoryDays.xml",true);
    xmlhttp.onreadystatechange = function() {
		try { // Важно!
			// только при состоянии "complete"
			if (xmlhttp.readyState == 4) {
				// для статуса "OK"
				if (xmlhttp.status == 200) {
					// обработка ответа
					xml=xmlhttp.responseXML;
					events=xml.getElementsByTagName("event");
				}
				else {
					alert("Не удалось получить данные:\n" +	this.statusText);
				}
			}
		  }
		  catch( e ) {
				alert(e.name);
			  // alert('Ошибка: ' + e.description);
			  // В связи с багом XMLHttpRequest в Firefox приходится отлавливать ошибку
			  // Bugzilla Bug 238559 XMLHttpRequest needs a way to report networking errors
			  // https://bugzilla.mozilla.org/show_bug.cgi?id=238559
		  }
	};
	xmlhttp.send();
}

