<?php
echo "
\t   _____                      ______           
\t  / ____|                    |  ____|          
\t | |     __ _ _ __ ___  _ __ | |__   __ _  ___ 
\t | |    / _` | '_ ` _ \| '_ \|  __| / _` |/ _ \
\t | |___| (_| | | | | | | |_) | |___| (_| |  __/
\t  \_____\__,_|_| |_| |_| .__/|______\__, |\___|
\t                       | |           __/ |     
\t                       |_|          |___/     

";

$str = "\t\e[31mGithub /furk4nyildiz - /yunemse48 - /burakyusuf\e[39m\n\n";

$dizi = explode(" ", $str);
foreach ($dizi as $d) {
	$d = $d . " ";
	for ($i=0; $i < strlen($d) ; $i++) { 
		echo $d[$i];
		usleep(10000);
	}
}

if(strlen(array_search("-h", $argv)) != null){
$str1 = "
Example : php detect.php -s -d google.com
Example : php detect.php -m -d google.com,yandex.com,bing.com,example.com
Example : php detect.php -fr path/file.txt
\t-s \t single search
\t-m \t multi search
\t-h \t help
\t-fr \t file read
\t-d \t domain\n";

$dizi1 = explode(" ", $str1);
foreach ($dizi1 as $d) {
	$d = $d . " ";
	for ($i=0; $i < strlen($d) ; $i++) { 
		echo $d[$i];
		usleep(10000);
	}
}
exit;
}

include 'op.php';
if(trim($argv[1]) == "-fr"){
	$file = fopen($argv[2], "r");
	while(!feof($file)){
		$line = trim(fgets($file));
		if(stristr($line,"http://") != null ){
			detect(trim(substr($line,"7")));
			continue;
		}
		if(stristr($line,"https://") != null ){
			detect(trim(substr($line,"8")));
			continue;
		}
		detect(trim($line));
		} 
	}
	fclose($file);

if(trim($argv[1]) == "-s"){
	echo "Single Starting... \n";
	if(trim($argv[2]) =="-d"){
		if(trim($argv[3] != null)){
			detect($argv[3]);
		}
	}
}else if(trim($argv[1]) == "-m"){
	echo "Multi Starting... \n";
	if(trim($argv[2]) =="-d"){
		if(trim($argv[3] != null)){
			$split = explode(",", $argv[3]);
			for ($i=0; $i < count($split) ; $i++) { 
				detect($split[$i]);
			}
		}
	}
}
echo "\e[39m";
?>
