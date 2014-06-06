<?php
  header("Content-type: text/css; charset: UTF-8");

  $mainColor = "#000000";
  $mainBgColor = "#EEEEEE";
?>
#bscal,  #v-menu {					
	background-color: <?php echo $mainBgColor; ?>;	/* ���� ��������� ����	*/
	color: <?php echo $mainColor; ?>;				/* ���� ��������� ������*/
}
/* ��������� � ������ ����, ��� ������, ��������� � ���� */
#bscal  td.top, td.bot, .week, #v-menu li:hover, li a:hover  { 
		background-color: #132E64; 		/* ���� ����	*/	
		color: white; 					/* ���� ������ 	*/	
}
/* ���� �������� �����-������ � �������� ������			*/
#bscal td.arrow, .month{ 
		color: #132E64; 				/* ���� ������ 	*/	
}
/* ���� ��� ��������									*/	
#bscal .over {			
		background-color: #406FCD; 		/* ���� ���� 	*/	
		color: white; 					/* ���� ������ 	*/	
}
/* �������												*/
#bscal .today {			
		background-color: #335AAB; 		/* ���� ���� 	*/	
		color: white; 					/* ���� ������ 	*/	
}