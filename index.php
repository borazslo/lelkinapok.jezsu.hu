<?php

define("TMPFOLDER", sys_get_temp_dir ( ).'/'); // chown www-data

$menu = [	
	'bevezeto' => [
		'Bevezető',
		'menu' => [
			'cimlap' => 'Címlap',
			'copyrightoldal' => 'Szerzők és adatok',
			'tartalomjegyzek' => 'Tartalomjegyzék'
		]
	],
	'alapgondolatok' => [
		'Alapgondolatok',
		'menu' => [
			'alapgondolatok#tortenetunk' => 'Iskolai lelkigyakorlatos kultúránk története',
			'alapgondolatok#a_siker' => 'A siker',
			'alapgondolatok#emberkepunk' => 'Emberképünk',
			'alapgondolatok#egyuttmukodes' => 'Együttműködés',
			'alapgondolatok#csend' => 'Csend',
			'alapgondolatok#alkotas' => 'Alkotás és a szimbólumok',
			'alapgondolatok#kortas_vezetes' => 'Kortárs vezetés és kiscsoport',
			'alapgondolatok#megosztas' => 'Megosztás'
		] 
	],
	'lelkinap_leirasok' => [
		'Lelkinap leírások',
		'menu' => [
			'lelkinap_az_utrol' => 'Lelkinap az útról',
			'lelkinap_a_halarol' => 'Lelkinap a háláról',
			'lelkinap_a_miatyankrol' => 'Lelkinap a Miatyánkról',
			'betlehemes_lelkinap' => 'Betlehmes lelkinap',
			'lelkinap_a_megtisztulasarol' => 'Lelkinap a megtisztulásról',
			'lelkinap_a_nevadas_erejerol' => 'Lelkinap a névadás erejéről',
		]
	],
	'tippek_es_trukkok' => 'Tippek és Trükkök',
	'mellekletek' => [
		'Mellékletek',
		'menu' => [
			'hogyan_vezess' => 'Hogyan vezess kiscsoportos?',
			'jatekok' => 'Játékok'
		]
	]
];


if(isset($_REQUEST['q'])) {	
	if(file_exists($_REQUEST['q'].".html")) {
		$q = $_REQUEST['q'];		
	} elseif($_REQUEST['q'] == 'tippek_es_trukkok') {
		$q = 'lelkinap_az_utrol';
	} elseif ($_REQUEST['q'] == '') {
		$q = 'cimlap';
	} else {
		$q = '404';
	}
} else {
	$q = 'cimlap';
}

function return_output($file){
    ob_start();
    include $file;
    return ob_get_clean();
}
$content = return_output($q.".html");

if(preg_match('/<title>(.*?)<\/title>/s',$content,$matches))
	$title = $matches[1];

$content = preg_replace('/<head>.*<\/head>/si','',$content);
$content = preg_replace('/<(\/|)(html|body)>/i','',$content);

$content = '<p class="display-1">'.$title.'</p>'.$content;



if($q == 'jatekok') {
	$content = preg_replace('/<cím>(.*?)<\/cím>/si','<h2>$1</h2><div>',$content);
	$content = preg_replace('/<helyszin>(.*?)<\/helyszin>/si','',$content);
	$content = preg_replace('/<tipus>(.*?)<\/tipus>/si','<p><i>$1</i></p>',$content);
	$content = preg_replace('/<egymondat>(.*?)<\/egymondat>/si','',$content);
	$content = preg_replace('/<forrás>(.*?)<\/forrás>/i','<footer class="blockquote-footer">$1</footer>',$content);
	
	$content = preg_replace_callback('/<leiras>(.*?)<\/leiras>/si', function($match) {
			$paragraphs = explode("\n",$match[1]);
			$return = '';
			foreach($paragraphs as $par) {
				$return .= "<p>".$par."</p>\n";
			}			
			return $return;
			} , $content);

	
	
	
	$content = preg_replace('/<\/jatek>/si','</div></jatek>',$content);
}




/* Játékok betétele */
//Könyvkiadás miatt most az egysoros változatokkal //
$jatekok = getJatekok();
$content = preg_replace_callback('/<jatek id=(.*?)\/>/i', 'insertJatek', $content);


$content = preg_replace('/<organizerTip>/i','<p class="organizerTip">',$content);
$content = preg_replace('/<\/organizerTip>/i','</p>',$content);
$content = preg_replace('/<szervezonek>(.*?)<\/szervezonek>/i','<p class="organizerTip">$1</p>',$content);

$content = preg_replace('/<quote>/i','<span class="quote">„',$content);
$content = preg_replace('/<\/quote>/i','”</span>',$content);


$content = preg_replace('/<duration>/i',' | ',$content);
$content = preg_replace('/<\/duration>/i','',$content);


$content = preg_replace('/<ido>(.*?)<\/ido>/i','<span class="ido"> | $1 </span>',$content);

$content = preg_replace('/<cimkieg>(.*?)<\/cimkieg>/i',' <small class="text-muted">» $1 «</small>',$content);


//Címekben kettőspontból nagykötőjel / gonodaltjel / desh
for($i=1;$i<=5;$i++)
	$content = preg_replace('/<(h'.$i.')(.*?)\:(.*?)<\/h'.$i.'>/i','<$1$2 — $3</$1>',$content);



$content = preg_replace('/<h1>/i','<h1 class="text-uppercase">',$content);

$content = preg_replace('/<csopvez>(.*?)<\/csopvez>/is','<p class="leaderTip">$1</p>',$content);

$gameID = 0;
$content = preg_replace_callback('/<games>(.*?)<\/games>/si',
		function ($matches) {
			$id = md5($matches[0]);
			$return = '<div class="accordion md-accordion" id="'.$id.'" role="tablist" aria-multiselectable="true">';
			
			$return .= preg_replace_callback('/<game>(.*?)<\/game>/si',
				function ($matches) { 
					global $gameID;
					$gameID++;
					$return = '<div class="card">';
					$matches[1] = preg_replace('/<type>(.*?)<\/type>/i',' <small class="text-muted">($1)</small>',$matches[1]);
			 				
					$return .= preg_replace(
						'/<title>(.*?)<\/title>(.*?)<body>(.*?)<\/body>/si',
						'<h5 class="card-header collapsed" data-toggle="collapse" href="#gameCollapse'.$gameID.'">$1</h5>$2<div id="gameCollapse'.$gameID.'" class="collapse card-body">$3</div>',
						$matches[1]);
				
					$return .= '</div>';
					return $return;		
				},
				$matches[1]);
					
			$return .= '</div>';
            return $return;
        },
		$content);



include 'layout.html';



function getJatekok() {
	$file['content'] = return_output("jatekok.html");
	
	preg_match_all('/<jatek(.*?)<\/jatek>/si', $file['content'], $matches);
	

	$jatekok = [];
	foreach($matches[0] as $match) {
		$jatek = [];
		preg_match('/<jatek id=(.*?)>/i',$match,$id);
		$jatek['id'] = trim($id[1]);
		

		foreach(array('cím','helyszin','tipus','leiras','forrás','egymondat') as $key ) {
			preg_match('/<'.$key.'>(.*?)<\/'.$key.'>/si', $match, $value);
			$jatek[$key] = trim($value[1]);

		}
		
		$jatekok[$jatek['id']] = $jatek;

	}
	return $jatekok;

}

function insertJatek($match) {
	global $jatekok;
	
	$jatek = $jatekok[trim($match[1])];
	
	if(!$jatek) {
		return "<div class='alert alert-danger'>Hiányzik egy játék! Nincs olyan, hogy: ".trim($match[1])."!</div>";
	}

	$return = '<div class="card">
		<h5 class="card-header collapsed" data-toggle="collapse" href="#gameCollapse_'.$jatek['id'].'">'.$jatek['cím'].'<cimkieg>'.$jatek['tipus'].'</cimkieg><br/>
		<small>'.$jatek['egymondat'].'</small></h5>
		<div id="gameCollapse_'.$jatek['id'].'" class="collapse card-body">      
			<p>'.$jatek['leiras'].'</p>     
			<footer class="blockquote-footer">'.$jatek['forrás'].'</footer>
		</div>
	 </div>';

	
	return $return;
	

}


?>



