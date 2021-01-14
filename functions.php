<?php


  if(isset($_POST['btnReboot'])) {
      $message = "System wir neu gestartet";
      echo "<script type='text/javascript'>alert('$message');</script>";
      exec('sudo /sbin/reboot');
  }
  if(isset($_POST['btnShutdown'])) {
      $message = "System wird heruntergefahren";
      echo "<script type='text/javascript'>alert('$message');</script>";
      exec('sudo /sbin/shutdown -h now');
  }

  if(isset($_POST['btnScanWiFi'])) {
      //echo "Scanning";
      $networks = wifiscan();
      print_r($networks);
  }

  //////////////>>><<<hier in eine Funktion kapseln
  $stats = getSystemConfig();
  $dhcprangestart = $stats[0];
  $dhcprangeend = $stats[1];
  $dhcpleasetime = $stats[2];
  $ssidwlan0 = $stats[3];
  $pskwlan0 = $stats[4];
  $ipeth0 = $stats[5];
  $ipwlan0 = $stats[6];
  $ipwlan1 = $stats[7];
  $ssidwlan1 = $stats[8];
  $countrycodewlan0 = $stats[9];
  $channelwlan0 = $stats[10];
  $dhcpdns = $stats[11];
  $dhcprange = $stats[12];
  $wlan0dns = $stats[13];

  if(isset($_POST['btnHotspot'])) {
    $ssid = $_POST['ssid'];
    $password = $_POST['password'];
    $countrycode = $_POST['countrycode'];
    $frq24 = "2.4 GHz";
    $frq50 = "5.0 GHz";
    $tempvar = $_POST['hwmode'];
    if ($tempvar == $frq24){
      $hwmode = "g";
    }
    elseif ($tempvar == $frq50){
      $hwmode = "a";
    }
    $channel = $_POST['channel'];
    //echo "$ssid<br>";
    //echo "$password<br>";
    //echo "$countrycode<br>";
    //echo "$hwmode<br>";
    //echo "$channel<br><br>";
    writeHostAPDConf($ssid, $password, $countrycode, $hwmode, $channel);
    exec('sudo /bin/systemctl stop hostapd.service');
    exec('sudo /bin/cp /var/www/html/tmp/hostapd.conf /etc/hostapd/hostapd.conf');
    exec('sudo /bin/systemctl start hostapd.service');
    echo "hostapd Conf erstellt";
  }

  if(isset($_POST['btnNetwork'])) {
    $IPwlan0 = $_POST['staticIP'];
    $DNSwlan0 = $_POST['dnswlan0'];
    $DHCPStartIP = $_POST['rangeStart'];
    $DHCPEndIP = $_POST['rangeEnd'];
    $DNSClients = $_POST['dnsClients'];
    $leaseTime = $_POST['leaseTime'];
    //echo "$IPwlan0<br>";
    //echo "$DNSwlan0<br>";
    //echo "$DHCPStartIP<br>";
    //echo "$DHCPEndIP<br>";
    //echo "$DNSClients<br>";
    //echo "$leaseTime<br><br>";
    writeDHCPCDConf($IPwlan0, $DNSwlan0);
    writeDNSMasqConf($IPwlan0, $DHCPStartIP, $DHCPEndIP, $leaseTime, $DNSClients);
    exec('sudo /bin/systemctl stop hostapd.service');
    exec('sudo /bin/systemctl stop dnsmasq.service');
    exec('sudo /bin/systemctl stop dhcpcd.service');
    exec('sudo /bin/cp /var/www/html/tmp/090_raspap.conf /etc/dnsmasq.d/090_raspap.conf');
    exec('sudo /bin/cp /var/www/html/tmp/dhcpcd.conf /etc/dhcpcd.conf');
    exec('sudo /bin/systemctl start hostapd.service');
    exec('sudo /bin/systemctl start dhcpcd.service');
    exec('sudo /bin/systemctl start dnsmasq.service');
    echo "dnsmasq conf erstellt<br>";
    echo "dhcpcd conf erstellt";
  }


if(isset($_POST['btndevicetest'])){
  echo wifiscan();
}

if(isset($_POST['scanresults'])){
  echo wifiscanresults();
}

if(isset($_POST['writeWPAConf'])){
  $ssid = $_POST['ssid'];
  $password = $_POST['password'];
  if ($_POST['hidden'] == "true"){
    $hidden = "true";
    writeWPAConf($ssid, $password, $hidden);
  }
  else{
    $hidden = "false";
    writeWPAConf($ssid, $password, $hidden);
  }

  echo json_encode("wpa_supplicant Config erstellt");
}

function wifiscan(){
  //echo "wpa_cli scan ";
  $chkok = exec('sudo /sbin/wpa_cli -i wlan1 scan');
  if ($chkok === "OK") {
    echo "true";
  }
  else{
    echo "false";
  }
}

function wifiscanresults(){
    exec('sudo /sbin/wpa_cli -i wlan1 scan_results', $result);

    $networks = array();
    foreach ($result as $key => $value) {
      $row = explode("\t", $value);
      if (!isset($row[4])) {
        continue;
      }

      $row_assoc['bssid'] = $row[0];
      $row_assoc['frequency'] = $row[1];
      $row_assoc['signal level'] = $row[2];
      $row_assoc['flags'] = $row[3];
      $row_assoc['ssid'] = $row[4];

      $networks[] = $row_assoc;
    }
    //return $networks;
    echo json_encode($networks);
}

function getSystemConfig(){

	$filecontent = "";
        if ($file = fopen("/etc/hostapd/hostapd.conf", "r")) {
            while(!feof($file)) {
                $line = fgets($file);
                if(strpos($line,'ssid=') !== false){
                        if(strpos($line,'ignore_broadcast_ssid') !== false){
                                //do nothing
                        }
                        else{
                                $result = explode('=',$line);
                                $ssidwlan0 = $result[1];

                        }
                }
                if(strpos($line,'wpa_passphrase') !== false){
                        $result = explode('=',$line);
                        $pskwlan0 = $result[1];
                }
		if(strpos($line,'country_code=') !== false){
                        $result = explode('=',$line);
                        $countrycodewlan0 = $result[1];
                }
		if(strpos($line,'channel=') !== false){
                        $result = explode('=',$line);
                        $channelwlan0 = $result[1];
                }

            }
            fclose($file);
        }

        if ($file = fopen("/etc/dhcpcd.conf", "r")) {
	    $wlan0dns = "-not set-";
	    while(!feof($file)) {
                $line = fgets($file);
                if(strpos($line,'static domain_name_server=') !== false){
			if(strpos($line,'#') !== false){
				//do nothing
			}
			else{
				$result = explode('=',$line);
				$wlan0dns = $result[1];
			}
		}
	    }
	    fclose($file);
        }

        //if ($file = fopen("/etc/dnsmasq.conf", "r")) {
        if ($file = fopen("/etc/dnsmasq.d/090_raspap.conf", "r")) {
            $dhcprange = "-not set-";
            $dhcpdns = "-not set-";
            while(!feof($file)) {
                $line = fgets($file);
                if(strpos($line,'dhcp-range=') !== false){
                        if(strpos($line,'#') !== false){
                                //do nothing
                        }
                        else{
                                $result = explode('=',$line);
                                $dhcprange = $result[1];
                        }
                }
		if(strpos($line,'dhcp-option=option:dns-server,') !== false){
                        if(strpos($line,'#') !== false){
                                //do nothing
                        }
                        else{
                                $result = explode('dhcp-option=option:dns-server,',$line);
                                $dhcpdns = $result[1];
                        }
                }
            }
            fclose($file);
        }
        $tempvar = explode(',',$dhcprange);
        $dhcprangestart = $tempvar[0];
        $dhcprangeend = $tempvar[1];
        $dhcpleasetime = $tempvar[3];

        $dhcprangestart = explode('.',$dhcprangestart);
        $dhcprangestart = $dhcprangestart[3];
        $dhcprangeend = explode('.',$dhcprangeend);
        $dhcprangeend = $dhcprangeend[3];

        $ipeth0 = exec("ip -4 addr show eth0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}'");
        $ipwlan0 = exec("ip -4 addr show wlan0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}'");
        $ipwlan1 = exec("ip -4 addr show wlan1 | grep -oP '(?<=inet\s)\d+(\.\d+){3}'");

        $ssidwlan1 = exec("iwgetid -r");

	$tempvar = explode(',',$dhcprange);
	$tempvar = $tempvar[0];
	$tempvar = explode('.',$tempvar);
	$dhcprange = $tempvar[0] .".". $tempvar[1] .".". $tempvar[2] .".";

	return array($dhcprangestart, $dhcprangeend, $dhcpleasetime, $ssidwlan0, $pskwlan0, $ipeth0, $ipwlan0, $ipwlan1, $ssidwlan1, $countrycodewlan0, $channelwlan0, $dhcpdns, $dhcprange, $wlan0dns);

}

/*
function getFile(){
	$filecontent = "";
	if ($file = fopen("/etc/hostapd/hostapd.conf", "r")) {
    	    while(!feof($file)) {
        	$line = fgets($file);
        	$filecontent = $filecontent .= $line . "<br>";
		//# do same stuff with the $line
		//echo "$line<br>";
    	    }
    	    fclose($file);
	}

	//echo $file;
	echo $filecontent;
}*/

function writeDHCPCDConf($staticIP, $staticDNS){

	$interface = "wlan0";
	$staticIPMask = "/24";

	$dhcpcd_file = fopen('/var/www/html/tmp/dhcpcd.conf', 'w') or die("Unable to open file!");
	fwrite($dhcpcd_file, "hostname".PHP_EOL);
	fwrite($dhcpcd_file, "clientid".PHP_EOL);
	fwrite($dhcpcd_file, "persistent".PHP_EOL);
	fwrite($dhcpcd_file, "option rapid_commit".PHP_EOL);
	fwrite($dhcpcd_file, "option domain_name_servers, domain_name, domain_search, host_name".PHP_EOL);
	fwrite($dhcpcd_file, "option classless_static_routes".PHP_EOL);
//	fwrite($dhcpcd_file, "option interface_mtu".PHP_EOL);
	fwrite($dhcpcd_file, "require dhcp_server_identifier".PHP_EOL);
//	fwrite($dhcpcd_file, "option ntp_servers".PHP_EOL);
	fwrite($dhcpcd_file, "slaac private".PHP_EOL);
	fwrite($dhcpcd_file, "nohook lookup-hostname".PHP_EOL);
	fwrite($dhcpcd_file, "interface $interface".PHP_EOL);
	fwrite($dhcpcd_file, "\tstatic ip_address=$staticIP$staticIPMask".PHP_EOL);
	fwrite($dhcpcd_file, "\tnohook wpa_supplicant".PHP_EOL);
	if (strlen($staticDNS) == 0){
		//echo "dhcpcd_file";
	}
	else {
		fwrite($dhcpcd_file, "\tstatic domain_name_server=$staticDNS".PHP_EOL);
	}
	fclose($dhcpcd_file);
}

function writeDNSMasqConf($staticIP, $DHCPRangeStart, $DHCPRangeEnd, $LeaseTime, $DNSServer){

        //$staticIP = strrev($staticIP);
        $result = explode('.',$staticIP);
        $IPRange = $result[0].".".$result[1].".".$result[2].".";
        //$IPRange = strrev($IPRange);

	$interface = "wlan0";
	$DHCPRangeStart = $IPRange . $DHCPRangeStart;
	$DHCPRangeEnd = $IPRange . $DHCPRangeEnd;
	$Subnetmask = "255.255.255.0";
	$NoDHCPInterface = "eth0,wlan1";

	$dnsmasq_file = fopen('/var/www/html/tmp/090_raspap.conf', 'w') or die("Unable to open file!");
	fwrite($dnsmasq_file, "interface=$interface".PHP_EOL);
	fwrite($dnsmasq_file, "dhcp-range=$DHCPRangeStart,$DHCPRangeEnd,$Subnetmask,$LeaseTime".PHP_EOL);
	fwrite($dnsmasq_file, "dhcp-option=option:dns-server,$DNSServer".PHP_EOL);
	fwrite($dnsmasq_file, "no-dhcp-interface=$NoDHCPInterface".PHP_EOL);
	fclose($dnsmasq_file);
}

function writeWPAConf($ssid, $psk, $hidden){

	$wpa_file = fopen('/var/www/html/tmp/wpa_supplicant.conf', 'w') or die("Unable to open file!");

	fwrite($wpa_file, "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev".PHP_EOL);
	fwrite($wpa_file, "update_config=1".PHP_EOL);
	if(strlen($psk) == 0){
	  fwrite($wpa_file, "network={".PHP_EOL);
	  fwrite($wpa_file, "\tssid=\"".$ssid."\"".PHP_EOL);
	  fwrite($wpa_file, "\tkey_mgmt=NONE".PHP_EOL);
	  fwrite($wpa_file, "}".PHP_EOL);
	}
	else{
	  fwrite($wpa_file, "network={".PHP_EOL);
	  fwrite($wpa_file, "\tssid=\"".$ssid."\"".PHP_EOL);
	  fwrite($wpa_file, "\tpsk=\"".$psk."\"".PHP_EOL);
          if($hidden == "true"){
            fwrite($wpa_file, "\tscan_ssid=1".PHP_EOL);
          }
	  fwrite($wpa_file, "\tkey_mgmt=WPA-PSK".PHP_EOL);
	  fwrite($wpa_file, "}".PHP_EOL);
	  fclose($wpa_file);
	}
        exec('sudo /bin/cp /var/www/html/tmp/wpa_supplicant.conf /etc/wpa_supplicant/wpa_supplicant.conf');
        exec('sudo /sbin/wpa_cli -i wlan1 reconfigure');
}

function writeHostAPDConf($ssid, $psk, $country_code, $hw_mode, $channel){

	//$ssid = "Beitenu";
	//$psk = "abbaotto";
	//$country_code = "DE";
	$interface = "wlan0";
	//$hw_mode = "g";
	//$channel = "7";

	$hostapd_file = fopen('/var/www/html/tmp/hostapd.conf', 'w') or die("Unable to open file!");

	fwrite($hostapd_file, "ctrl_interface=/var/run/hostapd".PHP_EOL);
	fwrite($hostapd_file, "ctrl_interface_group=0".PHP_EOL);
	fwrite($hostapd_file, "country_code=$country_code".PHP_EOL);
	fwrite($hostapd_file, "interface=$interface".PHP_EOL);
	fwrite($hostapd_file, "ssid=$ssid".PHP_EOL);
	fwrite($hostapd_file, "hw_mode=$hw_mode".PHP_EOL);
	fwrite($hostapd_file, "channel=$channel".PHP_EOL);
	fwrite($hostapd_file, "macaddr_acl=0".PHP_EOL);
	fwrite($hostapd_file, "auth_algs=1".PHP_EOL);
	fwrite($hostapd_file, "ignore_broadcast_ssid=0".PHP_EOL);
	fwrite($hostapd_file, "wpa=2".PHP_EOL);
	fwrite($hostapd_file, "wpa_passphrase=$psk".PHP_EOL);
	fwrite($hostapd_file, "wpa_key_mgmt=WPA-PSK".PHP_EOL);
	fwrite($hostapd_file, "wpa_pairwise=TKIP".PHP_EOL);
	fwrite($hostapd_file, "rsn_pairwise=CCMP".PHP_EOL);
	fclose($hostapd_file);
}
function cleanUp(){
	echo "cleanUp";
	unlink('/var/www/html/tmp/090_raspap.conf');
	unlink('/var/www/html/tmp/dhcpcd.conf');
	unlink('/var/www/html/tmp/hostapd.conf');
	unlink('/var/www/html/tmp/wpa_supplicant.conf');
}

/*function wifiscan(){
  $chkok = exec('sudo /sbin/wpa_cli -i wlan1 scan');
  //echo $stdout;
  if ($chkok === "OK") {
    sleep(3);
    exec('sudo /sbin/wpa_cli -i wlan1 scan_results', $result);

    $networks = array();
    foreach ($result as $key => $value) {
      $row = explode("\t", $value);
      if (!isset($row[4])) {
        continue;
      }

      $row_assoc['bssid'] = $row[0];
      $row_assoc['frequency'] = $row[1];
      $row_assoc['signal level'] = $row[2];
      $row_assoc['flags'] = $row[3];
      $row_assoc['ssid'] = $row[4];

      $networks[] = $row_assoc;
    }

    return $networks;

  }
  else {
    return false;
  }
}*/

//exec('sudo /bin/mv /var/www/html/tmp/hostapd.conf /etc/wpa_supplicant/hostapd.conf');
//exec('sudo /bin/mv /var/www/html/tmp/hostapd.conf /etc/wpa_supplicant/test.txt');
//exec('sudo /bin/rm /etc/wpa_supplicant/test.txt');
//$file = fopen('/var/www/html/tmp/test.txt', 'w') or die("Unable to open file!");
//fclose($file);


//getFile();
//writeWPAConf();
//writeHostAPDConf();
//writeDNSMasqConf();
//writeDHCPCDConf();
//cleanUp()

//echo "test";
?>
