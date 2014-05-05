var naming = {
    left : 0,
    top  : 0,
    width: 0,
    height: 0,
	css : document.createElement("link"),
    div : document.createElement("div"),
	bdD : null,
    bdM : null,
    bdY : null,
	
	baptism : 40,

	init: function () {
		naming.css.rel = "stylesheet";
		naming.css.href= baseUrl+"css/st_names.css";
		document.body.appendChild(naming.css);
		
		naming.div.id = 'snames';
		naming.div.innerHTML = naming.html();
		document.body.appendChild(naming.div);
		naming.div.style.display = "none";
	},
	html: function() {
	var t = '<table width="100%" class="header"><tr><td></td><td width="20px" style="cursor: pointer;" onclick="naming.hide();" title="Закрыть"><b>х</b></td></tr>';
		t += '<tr><td colspan="2"><p style="margin:12px 0px; font-size: 24px;">Наречение имени по месяцеслову</p></td></tr></table>';
		t += '<div class="separator1"></div>';
		t += '<div class="separator2"></div>';
		t += '<div id="day1" class="day"></div>';
		t += '<table width="100%" class="footer"><tr><td width="10%"></td>';
		t += '<td width="80%">День рождения</td>';
		t += '<td width="10%"></td></tr></table>';
		t += '<div class="separator1"></div>';
		t += '<div class="separator2"></div>';
		t += '<div id="day8" class="day"></div>';
		t += '<table width="100%" class="footer"><tr><td width="10%"></td>';
		t += '<td width="80%">Наречение имени на 8-й день от рождения</td>';
		t += '<td width="10%"></td></tr></table>';
		t += '<div class="separator1"></div>';
		t += '<div class="separator2"></div>';
		t += '<div id="day40" class="day"></div>';
		t += '<table width="100%" class="footer"><tr><td id="prevDay" width="10%" style="cursor: pointer;" onclick="naming.prevDay();" title="Предыдущий день"><b><<</b></td>';
		t += '<td id="bapID" width="80%">Таинство крещения на '+naming.baptism+'-й день от рождения</td>';
		t += '<td id="nextDay" width="10%" style="cursor: pointer;" onclick="naming.nextDay();" title="Следующий день"><b>>></b></td></tr></table>';
		t += '<div class="bottom1"></div>';
		t += '<div class="bottom2"><p style="margin-left:1em; font-size:80%"><a href="http://hpf.ru.com/"><b>Храм святых благоверных князей Петра и Февронии Муромских в Марьино г. Москвы</b></a>.<br /> © 2014 Все права защищены.</p></div>';
	return t;
	},
	show : function(d, obj) {
		naming.bdD = d.getDate();
		naming.bdM = d.getMonth()+1;
		naming.bdY = d.getFullYear();
		naming.draw ();
		naming.div.style.display = "block";
		
		naming.obj = document.getElementById(obj);
    	var pos = naming.pos(naming.obj);

		pos.x += naming.obj.offsetWidth - naming.div.offsetWidth + naming.left;
   		pos.y += naming.top;
		if (pos.y < 0) pos.y = 0;
		if (pos.x < 0) pos.x -= naming.obj.offsetWidth-naming.div.offsetWidth;
		naming.width  = naming.div.offsetWidth;
		naming.height = naming.div.offsetHeight;	
		naming.div.style.left = pos.x+"px";
		naming.div.style.top = pos.y+"px";
		bscal.hide();
	},
	draw : function() {
		var el = document.getElementById("day1");
		el.innerHTML = naming.dayInfo(1);
		el = document.getElementById("day8");
		el.innerHTML = naming.dayInfo(8);
		el = document.getElementById("day40");
		el.innerHTML = naming.dayInfo(40);		
	},
	hide : function() {
		naming.div.style.display = "none";
	},
    pos  : function (el) {
        var r = { x: el.offsetLeft, y: el.offsetTop };
        if (el.offsetParent) {
                var tmp = naming.pos(el.offsetParent);
                r.x += tmp.x;
                r.y += tmp.y;
        }
	return r;
	},
	prevDay : function () {
		if (naming.baptism > 2) {
			naming.baptism--;
			var el = document.getElementById("bapID");
			el.innerHTML = 'Таинство крещения на '+naming.baptism+'-й день от рождения';		
			el = document.getElementById("day40");
			el.innerHTML = naming.dayInfo(naming.baptism);	
		}
	},
	nextDay : function () {
		if (naming.baptism < 365) {
			naming.baptism++;
			var el = document.getElementById("bapID");
			el.innerHTML = 'Таинство крещения на '+naming.baptism+'-й день от рождения';		
			el = document.getElementById("day40");
			el.innerHTML = naming.dayInfo(naming.baptism);	
		}
	},
	dayInfo : function(n) {
		var d = new Date(0);
		d.setFullYear(naming.bdY, naming.bdM-1, naming.bdD+n-1);
		return getDayInfo(d);
	}
};	

var today = {
    left : 0,
    top  : 0,
    width: 0,
    height: 0,
	css : document.createElement("link"),
    div : document.createElement("div"),
	bdD : null,
    bdM : null,
    bdY : null,
	
	offset : 0,

	init: function () {
		today.css.rel = "stylesheet";
		today.css.href= baseUrl+"css/st_names.css";
		document.body.appendChild(today.css);
		
		today.div.id = 'snames';
		today.div.innerHTML = today.html();
		document.body.appendChild(today.div);
		today.div.style.display = "none";
	},
	html: function() {
	var t = '<table width="100%" class="header"><tr><td></td><td width="20px" style="cursor: pointer;" onclick="today.hide();" title="Закрыть"><b>х</b></td></tr>';
		t += '<tr><td colspan="2"><p style="margin:12px 0px; font-size: 24px;">Православный календарь</p></td></tr></table>';
		t += '<div class="separator"></div>';
		t += '<div id="thisDay" class="day"></div>';
		t += '<table width="100%" class="footer"><tr><td id="prevDay" width="30%" style="cursor: pointer;" onclick="today.prevDay();" title="Предыдущий день"><b><< Вчера</b></td>';
		t += '<td width="40%"> </td>';
		t += '<td id="nextDay" width="30%" style="cursor: pointer;" onclick="today.nextDay();" title="Следующий день"><b>Завтра >></b></td></tr></table>';
	return t;
	},
	show : function(d, obj) {
		today.bdD = d.getDate();
		today.bdM = d.getMonth()+1;
		today.bdY = d.getFullYear();
		var el = document.getElementById("thisDay");
		el.innerHTML = today.dayInfo(1);
		today.div.style.display = "block";
		
		today.obj = document.getElementById(obj);
    	var pos = today.pos(today.obj);

		pos.x += today.obj.offsetWidth - today.div.offsetWidth + today.left;
   		pos.y += today.top;
		if (pos.y < 0) pos.y = 0;
		if (pos.x < 0) pos.x -= today.obj.offsetWidth-today.div.offsetWidth;
		today.width  = today.div.offsetWidth;
		today.height = today.div.offsetHeight;	
		today.div.style.left = pos.x+"px";
		today.div.style.top = pos.y+"px";
		bscal.hide();
	},
	hide : function() {
		today.div.style.display = "none";
	},
    pos  : function (el) {
        var r = { x: el.offsetLeft, y: el.offsetTop };
        if (el.offsetParent) {
                var tmp = today.pos(el.offsetParent);
                r.x += tmp.x;
                r.y += tmp.y;
        }
	return r;
	},
	prevDay : function () {
			today.offset--;
			el = document.getElementById("thisDay");
			el.innerHTML = today.dayInfo(today.offset);	
	},
	nextDay : function () {
			today.offset++;
			el = document.getElementById("thisDay");
			el.innerHTML = today.dayInfo(today.offset);	
	},
	dayInfo : function(n) {
		var d = new Date(0);
		d.setFullYear(today.bdY, today.bdM-1, today.bdD+n-1);
		return getDayInfo(d);
	}
};	
