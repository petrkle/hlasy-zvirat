<?php

define('ROZHLAS', 'https://www.rozhlas.cz');
define('TMP', 'tmp');
define('WWW', 'app/src/main/assets/www');
libxml_use_internal_errors(true);
setlocale(LC_CTYPE, 'cs_CZ.UTF-8', 'Czech');

require 'vendor/autoload.php';
use Smarty\Smarty;

$smarty = new Smarty();

$lat = array(
  'blatnice-skvrnita' => array('c' => 'Blatnice skvrnitá', 'l' => 'Pelobates fuscus'),
  'bobr-evropsky' => array('c' => 'Bobr evropský', 'l' => 'Castor fiber'),
  'cvrcek-polni' => array('c' => 'Cvrček polní', 'l' => 'Gryllus campestris'),
  'cvrcivec-revovy' => array('c' => 'Cvrčivec révový', 'l' => 'Oecanthus pellucens'),
  'danek-skvrnity' => array('c' => 'Daněk skvrnitý', 'l' => 'Dama dama'),
  'jelen-lesni' => array('c' => 'Jelen lesní', 'l' => 'Cervus elaphus'),
  'jezevec-lesni' => array('c' => 'Jezevec lesní', 'l' => 'Meles meles'),
  'jezek' => array('c' => 'Ježek', 'l' => 'Erinaceinae'),
  'kamzik-horsky' => array('c' => 'Kamzík horský', 'l' => 'Rupicapra rupicapra'),
  'kobylka-hneda' => array('c' => 'Kobylka hnědá', 'l' => 'Decticus verrucivorus'),
  'komar-pisklavy' => array('c' => 'Komár pisklavý', 'l' => 'Culex pipiens'),
  'krtonozka-obecna' => array('c' => 'Krtonožka obecná', 'l' => 'Gryllotalpa gryllotalpa'),
  'kuna-lesni' => array('c' => 'Kuna lesní', 'l' => 'Martes martes'),
  'kunka-obecna' => array('c' => 'Kuňka obecná', 'l' => 'Bombina bombina'),
  'kunka-zlutobricha' => array('c' => 'Kuňka žlutobřichá', 'l' => 'Bombina variegata'),
  'lasice-kolcava' => array('c' => 'Lasice kolčava', 'l' => 'Mustela nivalis'),
  'liska-obecna' => array('c' => 'Liška obecná', 'l' => 'Vulpes vulpes'),
  'los-evropsky' => array('c' => 'Los evropský', 'l' => 'Alces alces'),
  'medved-hnedy' => array('c' => 'Medvěd hnědý', 'l' => 'Ursus arctos'),
  'netopyri' => array('c' => 'Netopýři', 'l' => 'Microchiroptera'),
  'octomilka-obecna' => array('c' => 'Octomilka obecná', 'l' => 'Drosophila melanogaster'),
  'ondatra-pizmova' => array('c' => 'Ondatra pižmová', 'l' => 'Ondatra zibethicus'),
  'prase-divoke' => array('c' => 'Prase divoké', 'l' => 'Sus scrofa'),
  'ropucha-kratkonoha' => array('c' => 'Ropucha krátkonohá', 'l' => 'Epidalea calamita'),
  'ropucha-obecna' => array('c' => 'Ropucha obecná', 'l' => 'Bufo bufo'),
  'ropucha-zelena' => array('c' => 'Ropucha zelená', 'l' => 'Bufo viridis'),
  'rosnicka-zelena' => array('c' => 'Rosnička zelená', 'l' => 'Hyla arborea'),
  'rys-ostrovid' => array('c' => 'Rys ostrovid', 'l' => 'Lynx lynx'),
  'sarance-carkovana' => array('c' => 'Saranče čárkovaná', 'l' => 'Stenobothrus lineatus'),
  'sika' => array('c' => 'Sika', 'l' => 'Cervus nippon'),
  'skokan-hnedy' => array('c' => 'Skokan hnědý', 'l' => 'Rana temporaria'),
  'skokan-kratkonohy' => array('c' => 'Skokan krátkonohý', 'l' => 'Pelophylax lessonae'),
  'skokan-skrehotavy' => array('c' => 'Skokan skřehotavý', 'l' => 'Rana ridibunda'),
  'skokan-stihly' => array('c' => 'Skokan štíhlý', 'l' => 'Rana dalmatina'),
  'skokan-zeleny' => array('c' => 'Skokan zelený', 'l' => 'Pelophylax kl. esculentus'),
  'srnec-obecny' => array('c' => 'Srnec obecný', 'l' => 'Capreolus capreolus'),
  'tchor-tmavy' => array('c' => 'Tchoř tmavý', 'l' => 'Putorius putorius'),
  'veverka-obecna' => array('c' => 'Veverka obecná', 'l' => 'Sciurus vulgaris'),
  'vlk-eurasijsky' => array('c' => 'Vlk eurasijský', 'l' => 'Canis lupus lupus'),
  'vydra-ricni' => array('c' => 'Vydra říční', 'l' => 'Lutra lutra'),
  'zajic-polni' => array('c' => 'Zajíc polní', 'l' => 'Lepus europaeus'),
);
