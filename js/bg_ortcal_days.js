var bg_ortcal_events;
var bg_ortcal_curMD = [];
var bg_ortcal_curYear;

function bg_ortcal_OldStyle(od, nd){
var Y, dd;

	Y=nd.getFullYear();
	dd = (Y-Y%100)/100 - (Y-Y%400)/400 - 2;
	od.setTime(nd.getTime());
	od.setDate(nd.getDate()-dd);

}
	
function bg_ortcal_easter(ed, Y) {
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

function bg_ortcal_MemoryDay () {
	this.start = new Date(0);
	this.finish = new Date(0);
	this.name = "";
	this.link = "";
	this.type = -999;
	this.discription = "";
	this.setMemoryDay = function (s, f, n, l, t, d){
		this.start.setTime(s.getTime());
		this.finish.setTime(f.getTime());
		this.name = n;
		this.link = l;
		this.type = t;
		this.discription = d;
	}
}

// Возвращает название Седмицы (Недели) по годичному кругу богослужений.
function bg_ortcal_Sedmica (d) {											// d - текущая дата
	var nw;
	var one_day=1000*60*60*24;									//1 день в милисекундах
	var wd = d.getDay();
	var easter_day = new Date(0);
	year = d.getFullYear();
	bg_ortcal_easter(easter_day, year);
	var cd = Math.floor(d.getTime()/one_day);
	var ed = Math.floor(easter_day.getTime()/one_day)-1;
	if (cd < ed-70) {				// До Недели о мытаре и фарисее идут седмицы по Пятидесятнице прошлого года
		bg_ortcal_easter(easter_day, (year-1));
		ed = Math.floor(easter_day.getTime()/one_day)-1;
		nw = Math.ceil((cd - (ed+49))/7);
		if (wd == 0) return bg_ortcal_lang.nedela+" "+nw+bg_ortcal_lang.po50;
		else return bg_ortcal_lang.sedmica+" "+nw+bg_ortcal_lang.po50;
	}
	else if (cd == ed-70) return bg_ortcal_lang.n1pred;	// Седмицы подготовительные
	else if (cd < ed-63) return bg_ortcal_lang.s1pred;	
	else if (cd == ed-63) return bg_ortcal_lang.n2pred;	
	else if (cd < ed-56) return bg_ortcal_lang.s2pred;	
	else if (cd == ed-56) return bg_ortcal_lang.n3pred;	
	else if (cd < ed-49) return bg_ortcal_lang.s3pred;						
	else if (cd == ed-49) return bg_ortcal_lang.n4pred;						
	else if (cd < ed-13) {										// Седмицы Великого поста
		nw = Math.ceil((cd - (ed-49))/7);
		if (wd == 0) return bg_ortcal_lang.nedela+" "+nw+bg_ortcal_lang.posta;
		else return bg_ortcal_lang.sedmica+" "+nw+bg_ortcal_lang.posta;
	}
	else if (cd < ed-7) return bg_ortcal_lang.s6post;
	else if (cd == ed-7) return bg_ortcal_lang.n6post;
	else if (cd < ed) return bg_ortcal_lang.s7post;
	else if (cd == ed) return "";
	else if (cd < ed+7) return bg_ortcal_lang.spascha;
	else if (cd < ed+50) {										// Седмицы по Пасхе
		nw = Math.ceil((cd - ed)/7);
		if (wd == 0) return bg_ortcal_lang.nedela+" "+(nw+1)+bg_ortcal_lang.popasche;
		else return bg_ortcal_lang.sedmica+" "+nw+bg_ortcal_lang.popasche;
	}
	else  {														// Седмицы по Пятидесятнице
		nw = Math.ceil((cd - (ed+49))/7);
		if (wd == 0) return bg_ortcal_lang.nedela+" "+nw+bg_ortcal_lang.po50;
		else {
			if (nw==1) return bg_ortcal_lang.s1po50;
			else return bg_ortcal_lang.sedmica+" "+nw+bg_ortcal_lang.po50;
		}
	}

	return "";
}
function bg_ortcal_getLink(d, type) {
	var omonth, odate, l = "";
	var od= new Date();
	var now = new Date();
	var mon = new Array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");

	bg_ortcal_OldStyle(od, d);
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
		l = "http://days.pravoslavie.ru/Days/"+od.getFullYear()+""+omonth+""+odate+".html";
		break;
	case 3: 								// Богослужебные указания
		if (d.getFullYear()!=now.getFullYear()) break;	// Если не текущий год, то на выход
		omonth=d.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=d.getDate();
		if (odate<10) {odate="0"+odate}
		l = "http://www.patriarchia.ru/bu/"+od.getFullYear()+"-"+omonth+"-"+odate+"/print.html";
		break;
	case 4: 								// Страница дня на Azbyka.Ru
		if (d.getFullYear()!=now.getFullYear()) break;	// Если не текущий год, то на выход
		omonth=d.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=d.getDate();
		if (odate<10) {odate="0"+odate}
		l = "https://azbyka.ru/days/"+od.getFullYear()+"-"+omonth+"-"+odate;
		break;
	case 101: 								// Страница на сайте
		omonth=d.getMonth()+1;
		if (omonth<10) {omonth="0"+omonth}
		odate=d.getDate();
		if (odate<10) {odate="0"+odate}
		if (bg_ortcal_page) l = bg_ortcal_page;
		else l = bg_ortcal_baseUrl;
		l += "?date="+d.getFullYear()+"-"+omonth+"-"+odate;
		break;
	}

	return l;
}

function bg_ortcal_memory_days(year) {
	if (year == bg_ortcal_curYear) return bg_ortcal_curMD;
	
//	var md = [];

	var easter_day= new Date(0);
	var d= new Date(0);
	var sd= new Date(0);
	var fd= new Date(0);
	var bufer = new bg_ortcal_MemoryDay();
	var iw, im, ddd, wd;
	var dd; 
//	var daysY = bg_ortcal_isLeap(year) ? 366 : 365;
	var one_day=1000*60*60*24;	//1 день в милисекундах
					
//	Светлое Христово Воскресение. Пасха. 
	bg_ortcal_easter(easter_day, year);

	var j=0;
	if(bg_ortcal_events) {
		for(var i=0; i<bg_ortcal_events.length; i++)
		{
			name=bg_ortcal_events[i].name;
			link=bg_ortcal_events[i].link;
			type=bg_ortcal_events[i].type;
			discription=bg_ortcal_events[i].discription;
					
			s_date=parseInt(bg_ortcal_events[i].s_date);
			s_month=parseInt(bg_ortcal_events[i].s_month);
			f_date=parseInt(bg_ortcal_events[i].f_date);
			f_month=parseInt(bg_ortcal_events[i].f_month);
			
			for (var y = year-1; y <= year+1; y++) { 
				dd = (y-y%100)/100 - (y-y%400)/400 - 2; 
			//	Светлое Христово Воскресение. Пасха. 
				bg_ortcal_easter(easter_day, y);
			
			
				// Если не високосный год, то праздники, приходящиеся на 29 февраля, отмечаются 28 февраля
				if (!bg_ortcal_isLeap(y)) {
					if (s_month == 2 && s_date == 29) s_date = 28;
					if (f_month == 2 && f_date == 29) f_date = 28;
				}	
				if (s_month < 0) {
					if (s_month == -4) f_date = f_date -3;
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
				bg_ortcal_curMD[j]=new bg_ortcal_MemoryDay();
				bg_ortcal_curMD[j].setMemoryDay (sd, fd, name, link, type, discription);
								
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
		for (i=0; i<bg_ortcal_curMD.length-1; i++) {
			if (bg_ortcal_curMD[i].start.getTime() > bg_ortcal_curMD[i+1].start.getTime()){
				bufer.setMemoryDay(bg_ortcal_curMD[i].start, bg_ortcal_curMD[i].finish, bg_ortcal_curMD[i].name, bg_ortcal_curMD[i].link,  bg_ortcal_curMD[i].type, bg_ortcal_curMD[i].discription);
				bg_ortcal_curMD[i].setMemoryDay (bg_ortcal_curMD[i+1].start, bg_ortcal_curMD[i+1].finish, bg_ortcal_curMD[i+1].name, bg_ortcal_curMD[i+1].link,  bg_ortcal_curMD[i+1].type, bg_ortcal_curMD[i+1].discription);
				bg_ortcal_curMD[i+1].setMemoryDay (bufer.start, bufer.finish, bufer.name, bufer.link,  bufer.type, bufer.discription);
				t = true;
			}
		}
	}
	bg_ortcal_curYear = year;
	return bg_ortcal_curMD;
}

function bg_ortcal_setLink(t) {
	var d = new Date(0);
	d.setTime(t);
	var link = bg_ortcal_getLink(d, dayLink);
	if (link) {
		window.open(link);
	}
}

function bg_ortcal_getDayInfo(d) {

    var mnr = bg_ortcal_lang.mnr;
	var cwd = bg_ortcal_lang.cwd;
	var typicon = bg_ortcal_lang.typicon;

	var curD = d.getDate();
	var curM = d.getMonth()+1;
	var curY = d.getFullYear();
	var curW = d.getDay();
	var name;
	var yCh;
	
	var md = bg_ortcal_memory_days(curY);
	var beginY=new Date(0);
	var nowY=beginY.getFullYear();
	beginY.setFullYear(curY-1, 11, 31);
	var one_day=1000*60*60*24;	//1 день в милисекундах
	var sd, fd;
	var num_day = Math.round((d.getTime()-beginY.getTime())/(one_day));	

// Дата по новому и старому стилю, Седмица, Памятные дни
	var od = new Date(0);
	bg_ortcal_OldStyle(od, d);
	if (curY == 0) yCh = bg_ortcal_lang.bc;
	else if (curY < 0) yCh = (-curY) + ' '+bg_ortcal_lang.b_bc;
	else yCh = curY + ' '+bg_ortcal_lang.a_bc;
		
	var t = curD+" "+mnr[curM-1]+" "+yCh+" ("+od.getDate()+" "+mnr[od.getMonth()]+" ст.ст.), "+cwd[curW];
	if (curY == nowY) t = "<span class='bg_ortcal_curDate' style='cursor:pointer' onclick=\'bg_ortcal_setLink("+d.getTime()+")\'>" + t + "</span><br>";
	else t = "<span class='bg_ortcal_curDate' style='cursor:auto' onclick=\'bg_ortcal_setLink("+d.getTime()+")\'>" + t + "</span><br>";		// Если не текущий год, то курсор обыкновенный
	var tt = bg_ortcal_Sedmica(d);																			// Седмица
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
				tt += "<img src='"+bg_ortcal_baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' /><span class='bg_ortcal_holiday'>"+md[k].name+"</span><br>";
			} 	
		}
	}
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 1 || md[k].type == 2) {													// ДВУНАДЕСЯТЫЕ И ВЕЛИКИЕ ПРАЗДНИКИ (Тип 1 и 2)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<img src='"+bg_ortcal_baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' /><span class='bg_ortcal_holiday'>"+md[k].name+"</span><br>";
			} 	
		}
	}
	if (tt !="" || curW == 0) t=t.replace("bg_ortcal_curDate","bg_ortcal_holDate");										// Если Великий праздник или воскресный день, меняем цвет даты на красный
		
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type >= 3 && md[k].type <= 7) {													// СРЕДНИЕ, МАЛЫЕ и другие ПРАЗДНИКИ (Типы 3-7)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += "<img src='"+bg_ortcal_baseUrl+"js/S"+md[k].type+".gif' title='"+typicon[md[k].type]+"' />"+md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>"+bg_ortcal_lang.t07+"</b><br>" + tt;
		
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 16) {																		// Соборы святых. (Тип 16)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>"+bg_ortcal_lang.t16+"</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 18) {																		// Святые. (Тип 18)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>"+bg_ortcal_lang.t18+"</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "" || curY < 2001) continue;	
		if (md[k].type == 19) {																		// Исповедники и новомученики российские. (Тип 19)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>"+bg_ortcal_lang.t19+"</b><br>" + tt;
		
	tt = "";
	for (k = 0; k < md.length; k++) {
		if (md[k].name == "") continue;	
		if (md[k].type == 17) {																		// Дни почитания местночтимых икон Божией Матери. (Тип 17)
			if (md[k].start.getDate() == curD && md[k].start.getMonth() == curM-1) {				
				tt += md[k].name+"<br>";
			} 	
		}
	}
	if (tt !="") t += "<b>"+bg_ortcal_lang.t17+"</b><br>" + tt;
		
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
	if ((tt =="") && (curW == 3 || curW == 5)) {tt +=  "<i>"+bg_ortcal_lang.post+"</i>";}			// Среда и пятница - постные дни	 						

// Браковенчание не совершается
	var nowedding = "<i>"+bg_ortcal_lang.nowedding+"</i>";
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

function bg_ortcal_isLeap(year) {
		return (((year % 4)==0) && ((year % 100)!=0) || ((year % 400)==0)) ? true : false }
