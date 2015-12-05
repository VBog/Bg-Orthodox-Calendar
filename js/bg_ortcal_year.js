var bg_ortcal_bscal = {
    left : 5,
    top  : 10,
    width: 0,
    height: 0,

    wds  : new Array("Пн","Вт","Ср","Чт","Пт","Сб","Вс"),
    mns  : new Array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"),
    mnr  : new Array(" января"," февраля"," марта"," апреля"," мая"," июня"," июля"," августа"," сентября"," октября"," ноября"," декабря"),
    dim  : new Array(31,28,31,30,31,30,31,31,30,31,30,31),

    nowD : new Date().getDate(),
    nowM : new Date().getMonth()+1,
    nowY : new Date().getFullYear(),

	curD : null,
    curM : null,
    curY : null,

//	minY : new Date().getFullYear() - 20,
	minY : 0,
//    maxY : new Date().getFullYear() + 20,
    maxY : 48099,

    css  : document.createElement("link"),
    div  : document.createElement("div"),
    vmn  : document.createElement("div"),
	
    ysel : null,
    hover: null,
	
	sDate: new Date(),
	
	init : function()
	{
		bg_ortcal_bscal.css.rel = "stylesheet";
		bg_ortcal_bscal.css.href= bg_ortcal_baseUrl+"css/bs_calendar.css";
		document.body.appendChild(bg_ortcal_bscal.css);

		bg_ortcal_bscal.div.style.left = "0px";
		bg_ortcal_bscal.div.style.top  = "0px";
		bg_ortcal_bscal.div.style.width  = "690px";
        bg_ortcal_bscal.div.id = 'bg_ortcal_bscal';
        bg_ortcal_bscal.div.innerHTML = bg_ortcal_bscal.html();
        bg_ortcal_bscal.div.style.display = "none";
		document.body.appendChild(bg_ortcal_bscal.div);

		bg_ortcal_bscal.vmn.style.left = "0px";
		bg_ortcal_bscal.vmn.style.top  = "0px";
        bg_ortcal_bscal.vmn.id = 'bg_ortcal_v-menu';
        bg_ortcal_bscal.vmn.name = 'bg_ortcal_v-menu';
        bg_ortcal_bscal.vmn.innerHTML = bg_ortcal_bscal.htmlMenu();
        bg_ortcal_bscal.vmn.style.display = "none";
        document.body.appendChild(bg_ortcal_bscal.vmn);
		document.body.onclick = function() {
			t=event.target||event.srcElement; 
			if (t.className!='bg_ortcal_over' && t.className!='bg_ortcal_bot') bg_ortcal_bscal.hideMenu();
		}

        bg_ortcal_bscal.ysel = document.getElementById("bs_year");
        bg_ortcal_bscal.ysel.style.width = "90px";

	},
	draw : function()
	{
		bg_ortcal_bscal.hideMenu();
		md = bg_ortcal_memory_days(bg_ortcal_bscal.curY);
		var beginY=new Date(0);
		beginY.setFullYear(bg_ortcal_bscal.curY-1, 11, 31);
		var one_day=1000*60*60*24;	//1 день в милисекундах
		var num_day=0;
		var m=0;
		var sd, fd;
		
		//очищаем дни
		for (var j=1; j<=4; j++)
		for (var i=1; i<=3; i++)
		{
		m++;
    	for (var y=1;y<=6;y++)
			for (var x=1;x<=7;x++){
				var el = document.getElementById("cell_"+m+"_"+y+"_"+x);
				el.className = "bg_ortcal_day";
				el.style.cursor = 'default';
				el.innerHTML   = "&nbsp;";
			}
		}
        m=0;
		for (var j=1; j<=4; j++)
		for (var i=1; i<=3; i++)
		{
		m++;
    	all_days = (m == 2 && bg_ortcal_bscal.isLeap(bg_ortcal_bscal.curY)) ? 29 : bg_ortcal_bscal.dim[m-1];
    	var beginM = new Date(0);
		beginM.setFullYear(bg_ortcal_bscal.curY,m-1,1);
		begin = beginM.getDay();
		
	    //заполняем месяц
         y=1; x=begin!=0 ? begin:7;
         for (c=1;c<=all_days;c++)
         {
			var el = document.getElementById("cell_"+m+"_"+y+"_"+x);
			num_day++;

			if (x > 6) {el.className = "bg_ortcal_weekend";}				// Воскресение
			if (x == 3 || x == 5) {el.className = "bg_ortcal_post";}		// Пост по средам и пятницам

// Проверяем и отмечаем памятные дни
		
			el.title = "";
			for (k = 0; k < md.length; k++) {
				if (md[k].name == "") continue;	
				if (md[k].type == 9) {
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {						// Дни особого поминовения усопших. (Тип 9)
						if(el.className == "bg_ortcal_post") {el.className = "bg_ortcal_post_memory";}						
						else {el.className = "bg_ortcal_memory";}
						el.title = md[k].name;
					} 
				}
			}
			for (k = 0; k < md.length; k++) {
				if (md[k].name == "") continue;	
				if (md[k].type == 8) {																		// Памятные дни (Типы 8)
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {
						el.title = (el.title == "")? md[k].name : md[k].name+',\n'+el.title;
					} 
				}
				else if (md[k].type == 1 || md[k].type == 2) {												// ДВУНАДЕСЯТЫЕ И ВЕЛИКИЕ ПРАЗДНИКИ (Тип 1 и 2)
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {
						if(el.className == "bg_ortcal_post") {el.className = "bg_ortcal_post_holidays";}						
						else {el.className = "bg_ortcal_holidays";}
						el.title = (el.title == "")? md[k].name : md[k].name+',\n'+el.title;
					} 
				}
				else if (md[k].type >= 3 && md[k].type <= 7) {												// СРЕДНИЕ, МАЛЫЕ и другие ПРАЗДНИКИ (Типы 3-7)
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {
						el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
					} 
				}
				else if (md[k].type == 17) {																//  Дни почитания икон (Тип 17)
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {
						el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
					} 
				}
			}
			for (k = 0; k < md.length; k++) {
				if (md[k].name == "") continue;	
				if (md[k].type == 0) {
					if (md[k].start.getDate() == c && md[k].start.getMonth() == m-1) {						// Светлое Христово Воскресение. Пасха. (Тип 0)
						el.className = "bg_ortcal_easter";
						el.title = (el.title == "")? md[k].name : md[k].name+',\n'+el.title;
					} 	
				}
			}
			for (k = 0; k < md.length; k++) {
				if (md[k].name == "") continue;	
				if (md[k].type == 100) {
					sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));
					fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));
					
					if (num_day >= sd && num_day <= fd) {										// Сплошные седмицы. (Тип 100)
						if (el.className == "bg_ortcal_post_memory") {
							el.className = "bg_ortcal_memory";
							if (md[k].name != "") el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else if (el.className == "bg_ortcal_post_holidays") {
							el.className = "bg_ortcal_holidays";
							if (md[k].name != "") el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else if (el.className == "bg_ortcal_day" || el.className == "bg_ortcal_post") {
							el.className = "bg_ortcal_day";
							if (md[k].name != "") el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else  {
							if (md[k].name != ""&& md[k].finish > md[k].start) el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
					} 
				}
			}
			for (k = 0; k < md.length; k++) {
				if (md[k].name == "") continue;	
				if (md[k].type == 10) {
					sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));
					fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));
					
					if ((num_day >= sd && num_day <= fd)) {						// Многодневные и однодневные посты.  (Тип 10) && Не забываем про окончание Филлипова поста в начале года
						if (el.className == "bg_ortcal_holidays") {
							el.className = "bg_ortcal_post_holidays";
							el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else if (el.className == "bg_ortcal_memory") {
							el.className = "bg_ortcal_post_memory";
							el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else if (el.className == "bg_ortcal_weekend") {
							el.className = "bg_ortcal_post_weekend";
							el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
						else if (el.className == "bg_ortcal_day" || el.className == "bg_ortcal_post") {
							el.className = "bg_ortcal_post";
							el.title = (el.title == "")? md[k].name : el.title+',\n'+md[k].name;
						}
					} 
				}
			}
// конец проверки памятных дней

			if (bg_ortcal_bscal.istoday(m, c)){el.className="bg_ortcal_today";}
			el.innerHTML   = c;
			el.style.cursor = 'pointer';

// Браковенчание не совершается
			el.className = el.className + " bg_ortcal_wedding_off";
			var nowedding = "Браковенчание не совершается";
			if (x == 2 || x == 4 || x == 6) {								// Не совершается браковенчание накануне среды и пятницы всего года (вторник и четверг), и воскресных дней (суббота)
				el.title = (el.title == "")? nowedding : el.title+',\n'+nowedding;
				el.className = el.className.replace(" bg_ortcal_wedding_off", " bg_ortcal_nowedding_off");
			}
			else {
				for (k = 0; k < md.length; k++) {																// А также не совершается браковенчание накануне двунадесятых, храмовых и великих праздников
					if (md[k].name == "") continue;																// в продолжение постов Великого, Петрова, Успенского и Рождественского; 
					if (md[k].type == 20) {																		// в продолжение Святок с 7 января (25 декабря) по 19 (6) января; сырной седмицы (масленицы),
						sd = Math.round((md[k].start.getTime()-beginY.getTime())/(one_day));					// начиная с Недели мясопустной и в Неделю сыропустную; в течение Пасхальной (Светлой) седмицы;
						fd = Math.round((md[k].finish.getTime()-beginY.getTime())/(one_day));					// в дни (и накануне) Усекновения главы Иоана Предтечи - 11 сентября (29 августа) и 
																												// Воздвижения Креста Господня - 27 (12) сентября.
						if (num_day >= sd && num_day <= fd) {													// Тип 20 && Не забываем про окончание Филлипова поста в начале года
							el.title = (el.title == "")? nowedding : el.title+',\n'+nowedding;
							el.className = el.className.replace(" bg_ortcal_wedding_off", " bg_ortcal_nowedding_off");
							break;
						}
					}
				}
			}
// Седмица
			var d = new Date (bg_ortcal_bscal.curY, m-1, c);
			var t = bg_ortcal_Sedmica(d);
			if (t !="") el.title = t + ".,\n" + el.title;

// Дата по старому стилю			
			var od = new Date(0);
			d.setFullYear(bg_ortcal_bscal.curY);
			bg_ortcal_OldStyle(od, d);
			el.title = d.getDate()+bg_ortcal_bscal.mnr[d.getMonth()]+" ("+od.getDate()+bg_ortcal_bscal.mnr[od.getMonth()]+" ст.ст.),\n" + el.title;
			
			x++; if (x>7){x=1;y++;}
         }
		}
		if (document.getElementById('weddingID').checked == true) bg_ortcal_bscal.changeWedding();
	},
	showList : function(){
		var link = bg_ortcal_baseUrl+'index.html';
	//	if (bg_ortcal_page) link = bg_ortcal_page;
		var y = document.getElementById('bs_year').value;
		if (y != "") link += '?date='+y;
		window.open(link);
		bg_ortcal_bscal.hide();
	},
	retD : function(r_month, r_day){
        if (!r_day || r_day=="&nbsp;") return false;
		var d = new Date(0);
		d.setFullYear(bg_ortcal_bscal.curY, r_month-1, r_day);
		bg_ortcal_bscal.link(d, bg_ortcal_dblClick);
	},
	retMenu : function(r_month, r_day, obj){
        if (!r_day || r_day=="&nbsp;") return false;
		if (!bg_ortcal_popmenu.length) return false;
		bg_ortcal_bscal.sDate.setFullYear(bg_ortcal_bscal.curY, r_month-1, r_day);
		if (bg_ortcal_popmenu.length == 1) bg_ortcal_bscal.link(bg_ortcal_bscal.sDate, bg_ortcal_popmenu[0].type);
		else bg_ortcal_bscal.showMenu(obj);
	},
	retOffset : function(r_month, r_day){
		if (!r_day || r_day=="&nbsp;") return false;
		
		var msec=24*60*60*1000;
        var d = new Date(0);
		d.setFullYear(bg_ortcal_bscal.curY, r_month-1, r_day);
		d_today = new Date(0);
		d.setHours(0, 0, 0, 0);
		d_today.setHours(0, 0, 0, 0);
		offset = Math.round((d.getTime() - d_today.getTime())/msec);
	    currentday(offset);
	    bg_ortcal_bscal.hide();
		location.reload();
	},
	istoday : function(month, day){
		return (bg_ortcal_bscal.nowD==day && bg_ortcal_bscal.curM==month && bg_ortcal_bscal.curY == bg_ortcal_bscal.nowY) ? true : false;
	},

    dover : function(el){
		if (el.innerHTML=='&nbsp;') return false;
		bg_ortcal_bscal.hover = el.className;
		el.className = 'bg_ortcal_over';
    },
    dout  : function(el){
		if (el.innerHTML=='&nbsp;') return false;
		el.className = bg_ortcal_bscal.hover;
		bg_ortcal_bscal.hover = null;
    },
	today : function(){
    	bg_ortcal_bscal.curD = bg_ortcal_bscal.nowD;
    	bg_ortcal_bscal.curM = bg_ortcal_bscal.nowM;
    	bg_ortcal_bscal.curY = bg_ortcal_bscal.nowY;
        bg_ortcal_bscal.scroll_Y(0);
	},
    change_Y : function (dir){
		bg_ortcal_bscal.curY = dir*1;
		bg_ortcal_bscal.scroll_Y(0);
    },
	scroll_Y : function (dir){
    	bg_ortcal_bscal.curY+= dir;
    	if (bg_ortcal_bscal.curY < bg_ortcal_bscal.minY) bg_ortcal_bscal.curY = bg_ortcal_bscal.minY;
    	if (bg_ortcal_bscal.curY > bg_ortcal_bscal.maxY) bg_ortcal_bscal.curY = bg_ortcal_bscal.maxY;
		document.getElementById('bs_year').value = bg_ortcal_bscal.curY;
		bg_ortcal_bscal.draw();
	},

    isLeap : function (year) {
		return (((year % 4)==0) && ((year % 100)!=0) || ((year % 400)==0)) ? true : false },

	html : function()
	{
 	    var res  = "";
		var m=0;

	    res += "<table width=100% unselectable=on>\n";
	    res += "<tr class='bg_ortcal_top'><td class=bg_ortcal_top-left title = 'Если навести мышку на какую-нибудь дату высвечиваются: дата по старому стилю, праздники по типикону (от двунадесятых до вседневных), памятные даты, дни поминовения усопших, посты и сплошные седмицы.\nЕсли нажать на кнопку мыши на одном из дней текущего года, открывается дополнительное меню.'>Выберите дату</td><td colspan='3'> </td><td class=bg_ortcal_top-right> <span title='Закрыть' onclick='bg_ortcal_bscal.hide();' style='cursor:pointer;'>x</span> </td></tr>\n";
		res += "<tr unselectable=on>"+
	           "<td class='bg_ortcal_arrow' onClick=bg_ortcal_bscal.scroll_Y(-1);><< предыдущий год</td>"+
				"<td unselectable=on></td>"+
				"<td unselectable=on><input id='bs_year' type='text' style='width: 90px' onchange=bg_ortcal_bscal.change_Y(this.value); onkeyup='return onlyDigits(this);'></input> <button onClick='bg_ortcal_bscal.change_Y(bs_year.value);'>&nbsp;Ok&nbsp;</button></td>"+
				"<td unselectable=on></td>"+
			   "<td class='bg_ortcal_arrow' onClick=bg_ortcal_bscal.scroll_Y(1);>следующий год >></td>\n"+
				"</tr>\n";
		for (var j=1; j<=4; j++)
		{
			res += "<tr unselectable=on>\n";
			for (var i=1; i<=3; i++)
			{
				m++;
				res += "<td id='month_"+m+"'>";
		
				res += "<table width=100% unselectable=on>\n";
				res += "<tr unselectable=on>"+
						"<td class='bg_ortcal_month' colspan=7 unselectable=on>"+bg_ortcal_bscal.mns[m-1]+"</td>"+
						"</tr>\n";
				res += "<tr unselectable=on align=center>\n";
				for (var x=0;x<7;x++)
					res += "<td class='bg_ortcal_week' unselectable=on>"+bg_ortcal_bscal.wds[x]+"</td>\n";
				res += "</tr>";
				for (var y=1;y<=6;y++)
				{
					res += "<tr align=center unselectable=on>\n";
					for (var x=1;x<=7;x++){
						res += "<td id='cell_"+m+"_"+y+"_"+x+"' onmouseover=\"bg_ortcal_bscal.dover(this);\" onmouseout=\"bg_ortcal_bscal.dout(this);\" onclick=\"bg_ortcal_bscal.retMenu("+m+", this.innerHTML, this);\" ondblclick=\"bg_ortcal_bscal.retD("+m+", this.innerHTML);\" unselectable=on>"+m+"_"+y+"_"+x+"</td>\n";
					}
					res += "</tr>\n";
				}
				res += "</table>";
		
				res += "</td>\n";
				if (i<=2) res += "<td>&nbsp;</td>\n";
			}
			res += "</tr>\n";
			if (j<=3) res += "<tr><td colspan=5></td></tr>\n";
		}
		res += "<tr class='bg_ortcal_top' align=center>\n"+
				"<td colspan=1 class=bg_ortcal_bot onClick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retMenu("+bg_ortcal_bscal.nowM+", "+(bg_ortcal_bscal.nowD-1)+", this);\" ondblclick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retD("+bg_ortcal_bscal.nowM+", "+(bg_ortcal_bscal.nowD-1)+");\" >вчера</td>\n"+
				"<td class=bg_ortcal_bot>/</td>"+
				"<td colspan=1 class=bg_ortcal_bot onClick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retMenu("+bg_ortcal_bscal.nowM+", "+bg_ortcal_bscal.nowD+", this);\" ondblclick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retD("+bg_ortcal_bscal.nowM+", "+bg_ortcal_bscal.nowD+");\" >сегодня</td>\n"+
				"<td class=bg_ortcal_bot>/</td>"+
				"<td colspan=1 class=bg_ortcal_bot onClick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retMenu("+bg_ortcal_bscal.nowM+", "+(bg_ortcal_bscal.nowD+1)+", this);\" ondblclick=\"bg_ortcal_bscal.today();bg_ortcal_bscal.retD("+bg_ortcal_bscal.nowM+", "+(bg_ortcal_bscal.nowD+1)+");\" >завтра</td>\n"+
				"</tr>\n";
		res += "</table>";
		res += "<span style='margin-left:1em;'><input id='weddingID' type='checkbox' onchange='bg_ortcal_bscal.changeWedding();'> Показать дни браковенчаний</span><br>";
		res += "<p style='margin-left:1em; font-size:80%'><a href='http://hpf.ru.com/'><b>Храм святых благоверных князей Петра и Февронии Муромских в Марьино г. Москвы</b></a>.<br /> © 2014 Все права защищены.</p>";

	return res;
	},
	htmlMenu : function() {
// А теперь добавим всплывающее меню
		var hr = false;
		var hr1 = false;
		var res  = "<ul>";
		res += "<div id='onlyThisYear'>";
		for (var i=0; i<bg_ortcal_popmenu.length; i++) {
			if (bg_ortcal_popmenu[i].type < 100) {			// Только текущий год
				if(bg_ortcal_popmenu[i].name) {
					res += "<li onclick='bg_ortcal_bscal.link(bg_ortcal_bscal.sDate, "+bg_ortcal_popmenu[i].type+")'>"+bg_ortcal_popmenu[i].name+"</li>";
					hr = true;
				}
			}
		}
		var res1 = "";
		for (var i=0; i<bg_ortcal_popmenu.length; i++) {
			if (bg_ortcal_popmenu[i].type >= 100) {
				if(bg_ortcal_popmenu[i].name) {
					res1 += "<li onclick='bg_ortcal_bscal.link(bg_ortcal_bscal.sDate, "+bg_ortcal_popmenu[i].type+")'>"+bg_ortcal_popmenu[i].name+"</li>";
					hr1 = true;
				}
			}
		}
		if (hr && hr1) res += "<hr>";
		res += res1+"</div>";
		res += "</ul>";
		return res;
	},
	changeWedding: function () {
        var m=0;
		for (var j=1; j<=4; j++)
		for (var i=1; i<=3; i++)
		{
			m++;
			for (var y=1;y<=6;y++)
			for (var x=1;x<=7;x++){
				var el = document.getElementById("cell_"+m+"_"+y+"_"+x);
				if (el.className.indexOf(" bg_ortcal_wedding_off") > -1) el.className = el.className.replace(" bg_ortcal_wedding_off", " bg_ortcal_wedding_on");
				else if (el.className.indexOf(" bg_ortcal_wedding_on") > -1) {el.className = el.className.replace(" bg_ortcal_wedding_on", " bg_ortcal_wedding_off"); }
			}
		}
	},

	show : function(year) {
    	if (bg_ortcal_bscal.div.style.display == "block"){
			bg_ortcal_bscal.hide(); return false;
    	}
    	bg_ortcal_bscal.curD = bg_ortcal_bscal.nowD;
    	bg_ortcal_bscal.curM = bg_ortcal_bscal.nowM;
		if (year === undefined) bg_ortcal_bscal.curY = bg_ortcal_bscal.nowY;
		else bg_ortcal_bscal.curY = year*1;
        bg_ortcal_bscal.scroll_Y(0);

		bg_ortcal_bscal.div.style.display = "block";

		bg_ortcal_bscal.width  = bg_ortcal_bscal.div.offsetWidth;
		bg_ortcal_bscal.height = bg_ortcal_bscal.div.offsetHeight;	
		p_left = window.pageXOffset +(parseInt(document.documentElement.clientWidth)-parseInt(bg_ortcal_bscal.div.clientWidth))/2;
		if (p_left < 24)p_left = 24;
		bg_ortcal_bscal.div.style.left = p_left+"px";
		p_top = window.pageYOffset+(parseInt(document.documentElement.clientHeight)-parseInt(bg_ortcal_bscal.div.clientHeight))/2;
		if (p_top < 24) p_top = 24;
		bg_ortcal_bscal.div.style.top = p_top+"px";
	},
	hide : function() {
		bg_ortcal_bscal.div.style.display = "none";
		bg_ortcal_bscal.hideMenu();
	},
    pos  : function (el) {
        var r = { x: el.offsetLeft, y: el.offsetTop };
        if (el.offsetParent) {
                var tmp = bg_ortcal_bscal.pos(el.offsetParent);
                r.x += tmp.x;
                r.y += tmp.y;
        }
	return r;
	},
	showMenu: function (obj){
		var pos = bg_ortcal_bscal.pos(obj);
		bg_ortcal_bscal.vmn.style.display="block";

		pos.y += obj.offsetHeight;
		if (pos.x > bg_ortcal_bscal.div.offsetLeft+bg_ortcal_bscal.div.offsetWidth-bg_ortcal_bscal.vmn.offsetWidth) {
			pos.x += obj.offsetWidth - bg_ortcal_bscal.vmn.offsetWidth;
		}
		bg_ortcal_bscal.vmn.style.left = pos.x+"px";
		bg_ortcal_bscal.vmn.style.top  = pos.y+"px";
		
	// Отображать пункты меню предназначенные только для текущего года
		var now = new Date();
		var el = document.getElementById("onlyThisYear");
		if (bg_ortcal_bscal.curY==now.getFullYear()) {
			el.style.display="block";
		} else {
			el.style.display="none";
		}
	},
	hideMenu : function() {
		if (bg_ortcal_bscal.vmn.style.display=="block") bg_ortcal_bscal.vmn.style.display="none";
	},
	link: function (d, type) {
		bg_ortcal_bscal.hideMenu();
		switch(type) {
		case 1001:
			bg_ortcal_today.show(d, 'bg_ortcal_bscal');
			break;
		case 1002:
			bg_ortcal_naming.show(d, 'bg_ortcal_bscal');
			break;
		default:
			var l = bg_ortcal_getLink(d, type);
			if (l) {
				window.open(l);
				bg_ortcal_bscal.hide();
			}
			break;
		}
	}
 };

 // Проверка ввода только цифр
function onlyDigits(input) {
    input.value = input.value.replace(/[^\d]/g, '');
};
// Синоним функции обработки нажатия кнопки календаря
// <button onclick='ortcal_button();'> Календарь на год </button>
function ortcal_button() {	
	bg_ortcal_bscal.show();	
}

// Перемещение окна календаря по экрану
bg_ortcal_bscal.div.onmousedown = function(e) { // отследить нажатие
	bg_ortcal_bscal.hideMenu();
	cursorX = parseInt(bg_ortcal_bscal.div.style.left)-e.pageX;
	cursorY = parseInt(bg_ortcal_bscal.div.style.top)-e.pageY;

	//  отследить движение 
	document.onmousemove = function(e) {
		bg_ortcal_bscal.div.style.left = e.pageX + cursorX + 'px';
		bg_ortcal_bscal.div.style.top = e.pageY + cursorY + 'px';
	}

	//  отследить окончание переноса
	bg_ortcal_bscal.div.onmouseup = function() {
		document.onmousemove = null;
		bg_ortcal_bscal.div.onmouseup = null;
	}
}
// Запрет встроенного  Drag'n'Drop 
bg_ortcal_bscal.div.ondragstart = function() {
  return false;
};