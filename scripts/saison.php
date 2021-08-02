<?php
	$maintenant = date("d/m/yy"); 
	$today = date("m"); 
	if ($today<7){
		$annee=intval(date("yy"))-1;
	}else{
		$annee=intval(date("yy"));}
	$anneesuivante=$annee+1;
	$saison=$annee.'-'.$anneesuivante;