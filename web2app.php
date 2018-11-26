<?php

require('config.php');
require('func.php');

$kategorie = get_categories();
$zvirata = array();

if(!is_dir(WWW)){
	mkdir(WWW, 0755, true);
}

foreach($kategorie as $url=>$nazev){
	$z = get_members($url.'?mode=100');
	foreach($z as $clanek=>$jmeno){
		$htmlfile = asciize($jmeno).'.html';
		savehtml(ROZHLAS.$clanek);
		$zvirata[$htmlfile] = array(
			'jmeno' => $jmeno,
			'info' => get_zvireinfo($clanek),
			'nahravky' => get_nahravkyinfo($clanek),
			'img' => array(),
			'mp3' => array(),
		);
		foreach(get_img(ROZHLAS.$clanek) as $id=>$img){
			$filename = TMP.'/'.$img['id'].'.jpeg';
			if(!is_file($filename)){
				savefile($img['orig'], $filename);
			}
			array_push($zvirata[$htmlfile]['img'], $img);
		}

		foreach(get_mp3(ROZHLAS.$clanek) as $mp3){
			$filename=TMP.'/'.$mp3['id'].'.mp3';
			if(!is_file($filename)){
				savefile($mp3['url'], $filename);
			}
			array_push($zvirata[$htmlfile]['mp3'], $mp3);
		}
	}
}

uasort($zvirata, 'sort_by_jmeno');

$cislo = 0;
$seznamzvirat = array();
foreach($zvirata as $htmlfile => $zvire){
	$seznamzvirat[$cislo] = array('file' => $htmlfile,
		'zvire' =>$zvire);
	$cislo++;
}

$cislo = 0;

foreach($zvirata as $htmlfile => $zvire){
	$smarty->assign('title', $zvire['jmeno']);
	$smarty->assign('zvire', $zvire);
	if($cislo == 0){
		$smarty->assign('prev', $seznamzvirat[count($seznamzvirat)-1]);
	}else{
		$smarty->assign('prev', $seznamzvirat[$cislo-1]);
	}


	if($cislo == count($seznamzvirat)-1){
		$smarty->assign('next', $seznamzvirat[0]);
	}else{
		$smarty->assign('next', $seznamzvirat[$cislo+1]);
	}
	$html = $smarty->fetch('hlavicka.tpl');
	$html .= $smarty->fetch('zvire.tpl');
	$html .= $smarty->fetch('paticka.tpl');
	file_put_contents(WWW.'/'.$htmlfile, $html);
	$cislo++;
}

$smarty->assign('title', 'Hlasy zvířat');
$smarty->assign('zvirata', $zvirata);
$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('index.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/index.html', $html);

$html = $smarty->fetch('hlavicka.tpl');
$html .= $smarty->fetch('about.tpl');
$html .= $smarty->fetch('paticka.tpl');
file_put_contents(WWW.'/about.html', $html);

copy('templates/z.css', WWW.'/z.css');
copy('templates/roboto-regular.ttf', WWW.'/roboto-regular.ttf');
copy('templates/jquery-1.12.4.min.js', WWW.'/jquery.js');
copy('templates/jquery.touchSwipe-1.6.18.min.js', WWW.'/ts.js');

copyToDir(TMP.'/*.jpeg', WWW);
copyToDir(TMP.'/*.mp3', WWW);