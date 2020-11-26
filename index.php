<?php

define("TMPFOLDER", sys_get_temp_dir ( ).'/'); // chown www-data


$fejezetek = array(
	21 => array(
		'title' => '2.1 Számlás hálás',
		'id' => '1XjhFEOH_-KZVLQ0_hQr0qAqs0hin64mbkkHG9-G0zvM'
		),
	23 => array(
		'title' => '2.3 Miatyánk',
		'id' => '1EuRajjJ8HMJ77fTzFMqEW113hw0MlqYdHOzsmNSKSY4'
		),
	3 => array(		
		'title' => '3. Teljes preparátum',
		'id'=> '1ILMyp4xiu5UYemc8VAKSJ76BTmJbHHX58r78LxPU_TM' 
		)

	);

if(isset($_REQUEST['id']) AND in_array($_REQUEST['id'], array(3,21,23))) {
	$id = $_REQUEST['id'];	
} else {
	$id = 3;	
}
$fejezet = $fejezetek[$id];

define("SELF", $_SERVER['PHP_SELF']."?id=".$id);


function getGoogleFile($fileId) {
	
	$tmpfilename = TMPFOLDER.'lelkinap_'.$fileId.'.gdocs';
	if (file_exists($tmpfilename) AND filemtime($tmpfilename) > strtotime("-10 minutes") AND (!isset($_REQUEST['update']) OR $_REQUEST['update'] == false)) {
		//File is up to date!	
	} else {
		//echo "kell nekünk";
		//Download the file.
		$content = file_get_contents('https://docs.google.com/feeds/download/documents/export/Export?id='.$fileId.'&exportFormat=txt');
		file_put_contents($tmpfilename, $content);
		//echo "$tmpfilename has been downloaded ";
	}
	$return = file_get_contents($tmpfilename);	
	return array(
		'fileId' => $fileId,
		'content' => $return,
		'filemtime' => filemtime($tmpfilename)
	);
}


function getJatekok() {
	$file = getGoogleFile('1YHQV8XQchYpuVNEPgHawqB1Dmuk1P70nRWcRSDBneHE');
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

$file = getGoogleFile($fejezet['id']);
$content = $file['content'];


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

?>



