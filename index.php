<?php

define("TMPFOLDER", sys_get_temp_dir ( ).'/'); // chown www-data

$lang = 'en';


if($lang == 'hu') {
	$homeTitle = 'Lelkinapok';
	$menu = [	
		'bevezeto' => [
			'Bevezető',
			'menu' => [
				'cimlap' => 'Címlap',
				'cimlap#video' => 'Köszöntő',
				'cimlap#bevezeto' => 'Bevezető',
				'cimlap#copyrightoldal' => 'Szerzők és adatok',
				'cimlap#tartalomjegyzek' => 'Tartalomjegyzék'
			]
		],
		'alapgondolatok' => [
			'Alapgondolatok',
			'menu' => [
				'alapgondolatok#cim_1-1' => 'Iskolai lelkigyakorlatos kultúránk története',
				'alapgondolatok#cim_1-2' => 'A siker',
				'alapgondolatok#cim_1-3' => 'Emberképünk',
				'alapgondolatok#cim_1-4' => 'Együttműködés',
				'alapgondolatok#cim_1-5' => 'Csend',
				'alapgondolatok#cim_1-6' => 'Alkotás és a szimbólumok',
				'alapgondolatok#cim_1-7' => 'Kortárs vezetés és kiscsoport',
				'alapgondolatok#cim_1-8' => 'Megosztás'
			] 
		],
		'lelkinap_leirasok' => [
			'Lelkinap leírások',
			'menu' => [
				'lelkinap_az_utrol' => 'Lelkinap az útról',
				'lelkinap_a_halarol' => 'Lelkinap a háláról',
				'lelkinap_a_miatyankrol' => 'Lelkinap a Miatyánkról',
				'betlehemes_lelkinap' => 'Betlehemes lelkinap',
				'lelkinap_a_megtisztulasrol' => 'Lelkinap a megtisztulásról',
				'lelkinap_a_nevadas_erejerol' => 'Lelkinap a névadás erejéről',
			]
		],
		'tippek_es_trukkok' => 'Tippek és Trükkök',
		'mellekletek' => [
			'Mellékletek',
			'menu' => [
				'hogyan_vezess' => 'Hogyan vezess kiscsoportot?',
				'jatekok' => 'Játékok'
			]
		]
	];


	if(isset($_REQUEST['q'])) {	
		if(file_exists($lang."/".$_REQUEST['q'].".html")) {
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

} elseif ($lang == 'en') {
	$homeTitle = 'Retreats';

	$menu = [	
		'bevezeto' => [
			'Introduction',
			'menu' => [
				'home' => 'Home',
				'home#introduction' => 'Introduction',
				'home#copyrightoldal' => 'Authors',
				'home#tartalomjegyzek' => 'Table of Contents'
			]
		],
		'alapgondolatok' => [
			'Basic principles',
			'menu' => [
				'alapgondolatok#cim_1-1' => 'The history of our school retreat culture',
				'alapgondolatok#cim_1-2' => 'A successful school retreat',
				'alapgondolatok#cim_1-3' => 'Our view of the human person',
				'alapgondolatok#cim_1-4' => 'Cooperation',
				'alapgondolatok#cim_1-5' => 'Silence',
				'alapgondolatok#cim_1-6' => 'Creativity and symbols',
				'alapgondolatok#cim_1-7' => 'Peer leaders and small groups',
				'alapgondolatok#cim_1-8' => 'Sharing'
			] 
		],
		'lelkinap_leirasok' => [
			'Retreats',
			'menu' => [
				'lelkinap_az_utrol' => 'The journey',
				'lelkinap_a_halarol' => 'Gratefulness',
				'lelkinap_a_miatyankrol' => 'Ther Lord\'s prayer',
				'betlehemes_lelkinap' => 'The nativity',
				'lelkinap_a_megtisztulasrol' => 'Becoming clean',
				'lelkinap_a_nevadas_erejerol' => 'The power in a name',
			]
		],
		'tippek_es_trukkok' => 'Tips and tricks',
		'mellekletek' => [
			'Appendices',
			'menu' => [
				'hogyan_vezess' => 'How to lead a group',
				'games' => 'Games'
			]
		]
	];


	if(isset($_REQUEST['q'])) {	
		if(file_exists($lang."/".$_REQUEST['q'].".html")) {
			$q = $_REQUEST['q'];		
		} elseif($_REQUEST['q'] == 'tippek_es_trukkok') {
			$q = 'lelkinap_az_utrol';
		} elseif ($_REQUEST['q'] == '') {
			$q = 'home';
		} else {
			
			$q = '404';
		}
	} else {
		$q = 'home';
	}
}


function return_output($file){
	if(!file_exists($file)) die('Hiányzó fájl: '.$file);
    ob_start();
    include $file;
    return ob_get_clean();
}

$jatekok = getJatekok();

$content = getContent($q, $lang);

if($q == 'cimlap') {
	$content .= getContent('video', 'hu');
	$content .= getContent('bevezeto', 'hu');
	$content .= getContent('copyrightoldal', 'hu');
	$content .= getContent('tartalomjegyzek', 'hu');
}

if($q == 'home') {
	$content .= getContent('introduction', 'en');
	$content .= getContent('tartalomjegyzek', 'hu');
}

function getContent($q, $lang = 'hu') {	
	global $title;
	$content = return_output($lang."/".$q.".html");
	
	if(preg_match('/<title>(.*?)<\/title>/s',$content,$matches))
		$title = $matches[1];
	else $title = false;

	$content = preg_replace('/<head>.*<\/head>/si','',$content);
	$content = preg_replace('/<(\/|)(html|body)>/i','',$content);


	if($q == 'lelkinap_az_utrol' AND $_REQUEST['q'] != 'tippek_es_trukkok') {
		$title = 'Lelkinap az Útról';
		$content = preg_replace('/<otlet>(.*?)<\/otlet>/si','',$content);
	}

	if ($title) $content = '<span class="chapter-title" id="'.$q.'">'.$title.'</span>'.$content;

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


	$content = preg_replace('/<div class="card">/i','<div class="card extratext">',$content);

	/* Játékok betétele */
	//Könyvkiadás miatt most az egysoros változatokkal //
	
	
	$content = preg_replace_callback('/<jatek id=("|)([^"]*)("| ).*?\/>/i', 'insertJatek', $content);

	
	$content = preg_replace('/<quote>/i','„',$content);
	$content = preg_replace('/<\/quote>/i','”',$content);


	$content = preg_replace('/<ido>(.*?)<\/ido>/i','<span class="ido text-muted"> | $1 </span>',$content);

	$content = preg_replace('/<cimkieg>(.*?)<\/cimkieg>/si',' <span class="cimkieg">‹ $1 ›</span>',$content);

	//Címekben kettőspontból nagykötőjel / gonodaltjel / desh
	for($i=1;$i<=5;$i++) {
		$content = preg_replace_callback('/<(h'.$i.')(.*?)>(.*?)<\/h'.$i.'>/si',
			function ($matches) {
				return "<".$matches[1]." ".$matches[2].">".preg_replace('/:/si',' — ',$matches[3])."</".$matches[1].">";
			},$content);
	}

	//tan átalakítása sorszámozással 
		$content = preg_replace_callback('/<(tanacs|otlet|colop)>(.*?)<\/(tanacs|otlet|colop)>/si',
			function ($matches) {  
				if($matches[1] == 'tanacs') $matches[3] = 'tanács'; 
				elseif($matches[1] == 'otlet') $matches[3] = 'ötlet';
				elseif($matches[1] == 'colop') $matches[3] = 'cölöpök';
			//echo "<pre>"; print_r($matches);
					return '<dl class="row">
					<dt class="col-sm-2">'.$matches[3].'</dt>
					<dd class="col-sm-10 '.$matches[1].'">'.$matches[2].'</dd>
					</dl>';
				
			}
		, $content);

	// Játékok beillesztése id alapján
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
	return "<section class='min-vh-100 ".$q."'><div class='container'>".$content."</div></section>";
}


include 'layout.html';



function getJatekok() {
	global $lang; 
	$file['content'] = return_output($lang."/jatekok.html");
	
	preg_match_all('/<jatek(.*?)<\/jatek>/si', $file['content'], $matches);
	

	$jatekok = [];
	foreach($matches[0] as $match) {
		$jatek = [];
		preg_match('/<jatek id=\"(.*?)\">/i',$match,$id);
		
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

	$jatek = $jatekok[trim($match[2])];
		
	
	if(!$jatek) {
		return "<div class='alert alert-danger'>Hiányzik egy játék! Nincs olyan, hogy: ".trim($match[2])."!</div>";
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



