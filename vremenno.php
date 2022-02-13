вырезка из frame.php из функции hor_coor:
<?

	/*
	$ratw = 6+$delta/3600+$time;
	
	$rate = $ratw+12;
	if($ratw>24) $ratw-=24;
	if($rate>24) $rate-=24;
	$raq = $ra/3600+24;
	$ratw+=24;
	$rate+=24;
	
	if($azim<0) $azim+=360;
	if($azim>=360) $azim-=360;
	if($height>=90) $height-=90;
	$azim = 360 - $azim;
	
	if($raq<$rate && $raq>$ratw) {
		if($azim<90) $azim = 180 - $azim;
		if($azim > 270) $azim = 270 - 360 + $azim;
	}
	
	if($ratw>$rate) {$rate+=24; if($ra/3600<$ratw) $raa = $ra/3600 + 24; else $raa = $ra/3600;}
	else $raa = $ra/3600;
	if($raa<$rate && $raa>$ratw)
	if($dec/3600>$shirota*sin(deg2rad(($raa-$ratw)*15))){
		$rr = $ratw+6;
		if($raa <= $rr) 
		$azim = 180-$azim;
		if($raa > $rr) 
		$azim = $azim-180;
	}
	
	$ratn = $ratw-6;
	if($ratn<0) $ratn+=24;
	if($ra/3600<$ratn && $ra/3600>$ratn-1) $azim = 360-$azim;
	
	if ($tugol>=0 && $tugol<90) $azim+=180;
	elseif ($tugol >=90 && $tugol < 180) $azim = abs($azim-360);
	elseif ($tugol < 0 && $tugol > -90) $azim = abs($azim+180);
	elseif ($tugol <= -90 && $tugol > -180) $azim = abs($azim);
	
	if($azim<0) $azim+=360;
	if($azim>=360) $azim-=360;
	
	$ratn = $ratw-6;
	if($ratn<0) $ratn+=24;
	if($ra/3600<$ratn+0.15 && $ra/3600>$ratn) $azim = 360-$azim;
	
	if($ra/3600<24 && $ra/3600>$ratn) $azim = 360 - $azim;
	*/
	

	?>
