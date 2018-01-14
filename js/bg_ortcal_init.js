/***************************************************************
	Блок процедур, инициирующих календарь и формирующих
	кнопку вызова календаря
****************************************************************/
/*** Инициируем календарь                          ***/
function bg_ortcal_loadXML(){ 						
	if (!bg_ortcal_events) {						// Если данные для календаря не определены
		jQuery.ajax({
			type: 'GET',
			async: true,
			dataType: 'json',
			url: bg_ortcal.ajaxurl+'?load=Y',	// Запрос загрузки данных
			data: {
				action: 'bg_ortcal'
			},
			success: function (e) {
				bg_ortcal_events = e;				// Добавляем  данные для календаря
				bg_ortcal_show();
			}
		});
	}
}
function bg_ortcal_month(ee, y, m) { 						
	var e = jQuery(ee);
	var el = e.parents('div.bg_moncal');
	var cursor = e.css("cursor");
	e.css("cursor", "wait");
	jQuery.ajax({
		type: 'GET',
		async: true,
		dataType: 'html',
		url: bg_ortcal.ajaxurl+'?year='+y+'&month='+m,	// Запрос загрузки данных
		data: {
				action: 'bg_ortcal'
		},
		success: function (t) {
			el.html(t);							// Добавляем  данные для календаря
			e.css("cursor", cursor);
		}
	});
}
// Устанавливает объект посередине страницы
function setObj (obj) {
		p_left = window.pageXOffset +(parseInt(document.documentElement.clientWidth)-parseInt(obj.clientWidth))/2;
		if (p_left < 0)p_left = 0;
		obj.style.left = p_left+"px";
		p_top = window.pageYOffset+(parseInt(document.documentElement.clientHeight)-parseInt(obj.clientHeight))/2;
		if (p_top < 32) p_top = 32;
		obj.style.top = p_top+"px";		
}

// Установим обработчик события resize
jQuery(window).resize(function() {
	bg_ortcal_bscal.hideMenu();
 	setObj(bg_ortcal_bscal.div);
 	setObj(bg_ortcal_naming.div);
 	setObj(bg_ortcal_today.div);
});
// Установим обработчик события keydown
jQuery(window).keydown(function(e) {
	// Закрыть все окна по Esc
	if( e.keyCode === 27 ) {
		bg_ortcal_bscal.hideMenu();
		bg_ortcal_bscal.hide();
		bg_ortcal_naming.hide();
		bg_ortcal_today.hide();	
	}
});

function bg_ortcal_show() {
	
	jQuery(document).ready(function() {
		var el=document.getElementById('bg_ortcal_year');
		if (el) {
			el.innerHTML = "";
			bg_ortcal_bscal.show('', el);
			ocument.getElementById('bg_ortcal_close').style.display == "none";
		}
	});	
}

bg_ortcal_loadXML(); 								// Загрузка данных для календаря
bg_ortcal_bscal.init();							// Инициация картинки календаря
bg_ortcal_naming.init();						// Инициация месяцеслова
bg_ortcal_today.init();							// Инициация всплывающего календаря

