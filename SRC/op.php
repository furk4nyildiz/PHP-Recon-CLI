<?php
error_reporting(0);
function cdn($x){
	$search = $x;
	$ns1ip = !empty(gethostbynamel($search)[0]) ? gethostbynamel($search)[0] : false;
	$ns2ip = !empty(gethostbynamel($search)[1]) ? gethostbynamel($search)[1] : false;
	$ns3ip = !empty(gethostbynamel($search)[2]) ? gethostbynamel($search)[2] : false;
	$ns4ip = !empty(gethostbynamel($search)[3]) ? gethostbynamel($search)[3] : false;
	$resolve = array();
	$source = file_get_contents("https://viewdns.info/iphistory/?domain=$search");
	preg_match_all('#((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)#', $source,$print);
	if(!empty($print[0])){
		array_push($resolve,$print[0]);
	}else{
		echo "Null";
	}
	echo "\e[38;5;82mCDN Found!\e[37m\n" . "Possible IP List: \n\e[38;5;82m";
	foreach ($resolve as $res) {
		foreach ($res as $result) {
			if($result != $ns1ip && $result != $ns2ip && $result != $ns3ip && $result != $ns4ip){
				echo $result . "\n";	
			}
		}
	}
}

function detect($site){
	$search = get_headers("http://".$site);
	$serverinfo = array();
	$only = array();
	$pow = array();

	echo "\n\e[38;5;82m# # # # # # #\n\n\e[37m";
	if($search != false){
		foreach ($search as $searchsite) {
			$searchsite = strtolower($searchsite);
			$serv = stristr($searchsite, "server");
			$httponly = stristr($searchsite, "httponly");
			$powered = stristr($searchsite, "powered");
			if($serv){
				array_push($serverinfo,$searchsite);
			}
			if($httponly){
				array_push($only, "HttpOnly");
			}
			if($powered){
				array_push($pow,$searchsite);
			}
		}
		echo "Target: ". $site."\n";
		echo "IP: " . gethostbynamel(trim($site))[0] . "\n";
		echo "NS1 : " . dns_get_record(trim($site), DNS_NS)[0]["target"]."\n";
		echo "NS2 : " . dns_get_record(trim($site), DNS_NS)[1]["target"]."\n";
		if(count($serverinfo) > 0){
			$p = explode(":", $serverinfo[0]);
			echo "Server Info: " . trim($p[1]) . "\n";
		}else{
			echo "Server Info: Null\n";
		}
		if(count($only) > 0){
			echo "Secure: HttpOnly\n";
		}else{
			echo "Secure: Null\n";
		}
		if(count($pow) > 0){
			$p = explode(":", $pow[0]);
			echo "Soft. Info: " . $p[1]."\n";
		}else{
			echo "Soft. Info: Null\n";
		}
		echo "Get Parameters : ";
		$sourceget = file_get_contents("http://".$site);
		preg_match_all('#<a href="(.*?)">#', $sourceget,$pri);
		$link = array();
		$path = array();
		$norepeat = array();
		foreach ($pri as $ci) {
			foreach ($ci as $c){
				$b = explode('<a href="',$c);
				$n = explode('">', $b[1]);
				if(count($n) >= 2){
					array_push($link, $n);
				}
			}
		}

		foreach ($link as $lin) {
			foreach ($lin as $li) {
				if(!empty($li)){
					array_push($path, $li);
				}
			}
		}
		foreach ($path as $pa) {
			$divi = explode("?", $pa);
			$request = explode("=", $divi[1]);
			if(!empty($request[0])){
				if(!in_array($request[0],$norepeat)){
					array_push($norepeat, $request[0]);
				}
			}
		}
		foreach ($norepeat as $repeat) {
			echo "?" . $repeat . "=, ";
		}
		echo "\n";

		if(stristr(dns_get_record(trim($site), DNS_NS)[0]["target"],"cloud")){
			echo cdn($site);
		}else if(stristr(dns_get_record(trim($site), DNS_NS)[0]["target"],"aws")){
			echo cdn($site);
		}else if(stristr(dns_get_record(trim($site), DNS_NS)[0]["target"],"gws")){
			echo cdn($site);
		}else if(stristr(dns_get_record(trim($site), DNS_NS)[0]["target"],"sucuri")){
			echo cdn($site);
		}
	}
	else{
		echo "\e[31mTarget : " . $site . "\tUnreachable.\n\e[39m";
	}
echo "\e[39";
}

?>
