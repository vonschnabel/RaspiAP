<?php
  require_once 'functions.php';
?>

<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>

body {font-family: Arial;}

/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #ccc;
  background-color: #f1f1f1;
}

/* Style the buttons inside the tab */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 17px;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #ccc;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  -webkit-animation: fadeEffect 1s;
  animation: fadeEffect 1s;
}

/* Fade in tabs */
@-webkit-keyframes fadeEffect {
  from {opacity: 0;}
  to {opacity: 1;}
}

@keyframes fadeEffect {
  from {opacity: 0;}
  to {opacity: 1;}
}

</style>
</head>
<body>

  <h2>RasPi Hotspot</h2>
  <div class="tab">
    <button class="tablinks" onclick="openTab(event, 'ActualConfig')">Actual Config</button>
    <button class="tablinks" onclick="openTab(event, 'Hotspot')">Hotspot</button>
    <button class="tablinks" onclick="openTab(event, 'Network')">Network Settings</button>
    <button class="tablinks" onclick="openTab(event, 'ConnectWifi')">Connect to WiFi</button>
  </div>

  <div id="Hotspot" class="tabcontent">
    <h3>Hotspot Config</h3>
    <form method="post">
      <label>SSID
      <input name="ssid" type="text" placeholder="<?=$ssidwlan0?>">
      </label>
      <br><br>

      <label>Password
      <input name="password" type="password" placeholder="<?=$pskwlan0?>">
      </label>
      <br><br>

      <label>Country Code
      <input name="countrycode" type="text" placeholder="<?=$countrycodewlan0?>">
      </label>
      <br><br>

      <label>Frequenz</label>
      <br>
      <label>2.4 GHz
      <input name="hwmode" type="radio" value="2.4 GHz" id="mode24">
      </label>
      <br>
      <label>5.0 GHz
      <input name="hwmode" type="radio" value="5.0 GHz" id="mode5">
      </label>
      <br><br>

      <label>Channel
      <input name="channel" type="text" placeholder="<?=$channelwlan0?>">
      </label>
      <br><br>

      <button name="btnHotspot" value="1">create Hotspot</button>
    </form>
  </div>

  <div id="Network" class="tabcontent">
    <h3>Network config</h3>
    <form method="post">
      <label>Static IP Interface wlan0
      <input name="staticIP" type="text" placeholder="<?=$ipwlan0?>">
      </label>
      <br><br>

      <label>Static DNS Servers for local Device
      <input name="dnswlan0" type="text" placeholder="<?=$wlan0dns?>" value=""
      </label>
      <br><br>

      <p>DHCP Settings</p>
      <label>First IP
      <input name="rangeStart" type="text" placeholder="<?=$dhcprangestart?>">
      </label>
      <br><br>

      <label>Last IP
      <input name="rangeEnd" type="text" placeholder="<?=$dhcprangeend?>">
      </label>
      <br><br>

      <label>DNS Servers for Clients
      <input name="dnsClients" type="text" placeholder="<?=$dhcpdns?>">
      </label>
      <br><br>

      <label for="leaseTime">Lease Time in Hours</label>
        <select name="leaseTime" id="leaseTime">
	  <option value="" disabled selected><?=$dhcpleasetime?></option>
          <option value="2h">2h</option>
          <option value="4h">4h</option>
          <option value="6h">6h</option>
          <option value="12h">12h</option>
          <option value="24h">24h</option>
        </select>
      <br><br>
      <button name="btnNetwork" value="1">Change Network Settings</button>
    </form>
  </div>

  <div id="ActualConfig" class="tabcontent">
    <div class="w3-panel w3-card">
      <ul class="w3-ul">
        <li><h3>eth0</h3></li>
        <li>IP: <?=$ipeth0?></li>
      </ul>
    </div>
    <div class="w3-panel w3-card-2">
      <ul class="w3-ul">
        <li><h3>wlan0</h3></li>
        <li>IP: <?=$ipwlan0?> /24</li>
        <li>DNS Static: <?=$wlan0dns?></li>
        <li>SSID: <?=$ssidwlan0?></li>
        <li>DHCP Range: <?="$dhcprange$dhcprangestart - $dhcprangeend"?></li>
        <li>DNS Servers (DHCP): <?=$dhcpdns?></li>
        <li>Lease Time: <?=$dhcpleasetime?></li>
      </ul>
    </div>
    <div class="w3-panel w3-card-4">
      <ul class="w3-ul">
        <li><h3>wlan1</h3></li>
        <li>IP: <?=$ipwlan1?> /24</li>
        <li>SSID: <?=$ssidwlan1?></li>
      </ul>
    </div>
    <form method="post">
      <button name="btnReboot" value="1">System Reboot</button>
      <button name="btnShutdown" value="1">System Shutdown</button>
    </form>
  </div>

  <div id="ConnectWifi" class="tabcontent">
    <button onclick="scanwifi()">Scan WiFi Networks</button>
    <button onclick="showexistingwifinetworks()">Show existing WiFi Networks</button>
    <button onclick="connecthidden()">Connect to hidden WiFi</button>
    <div id="emptyShell"></div>
  </div>

  <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script>
  function openTab(evt, TabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(TabName).style.display = "block";
    evt.currentTarget.className += " active";
  }



  function Sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
  }

  function connectWifi(ssid){
    var password = window.prompt("Enter the Password");
    //console.log(password); //kann weg
    //console.log(ssid); //kann weg
    if(password != null && password != ""){
      $.ajax({
        type: "POST",
        url: 'functions.php',
        data: {writeWPAConf : true, ssid : ssid, password : password},
        dataType: "json",
        success: function(data){
          alert(data);
          location.reload();
        },
      });
    }
  }

  function connecthidden(){
    var ssid = window.prompt("Enter the SSID");
    if(ssid != null && ssid != ""){
      var password = window.prompt("Enter the Password");
      if(password != null && password != ""){
        $.ajax({
          type: "POST",
          url: 'functions.php',
          data: {writeWPAConf : true, ssid : ssid, password : password, hidden : true},
          dataType: "json",
          success: function(data){
            alert(data);
            location.reload();
          },
        });
      }
    }
  }

  function scanresults(){ //retrieving scan results
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {scanresults : true},
      dataType: "json",
      success: function(data){
        networks = data;
        //console.log(networks); //kann weg
      },
    });
  }

  function testwifi(){ //exec "wpa_cli scan" to prepare wlan1 to scan for wifi networks
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {btndevicetest : true},
      success: function(data){
        //alert(data);
        returnvalue = data;
      },
    });
  }

  function showexistingwifinetworks(){
    var elements =  document.getElementById("emptyShell");
    while (elements.hasChildNodes()) {
      elements.removeChild(elements.firstChild);
    }
  }

  async  function scanwifi(){ // !!! Brauch ich hier eine asynchrone Funktion?
    // clear existing child nodes
    var elements =  document.getElementById("emptyShell");
    while (elements.hasChildNodes()) {
      elements.removeChild(elements.firstChild);
    }

    testwifi();
    await Sleep(1000);
    //console.log(returnvalue); //kann weg
    while(returnvalue == "false"){
      //console.log("while loop"); //kann weg
      testwifi();
      await Sleep(1500);
      //console.log(returnvalue); //kann weg
    }
    await Sleep(2000);

    scanresults();
    await Sleep(1000);
    //console.log(networks); //kann weg
    while(networks == "undefined"){ // kann warscheinlich weg. wird wohl nie durchlaufen
      scanresults();
      await Sleep(1500);
      //console.log("scan result while loop"); //kann weg
    }

    var numberOfElements = document.createTextNode("gefundene Netzwerke: " + networks.length);
    document.getElementById("emptyShell").appendChild(numberOfElements);
    for(var i = 0; i < networks.length; i++){
      var nodeDIV = document.createElement("DIV");
      nodeDIV.className = "w3-panel w3-card";
      var nodeUL = document.createElement("UL");
      nodeUL.className = "w3-ul";
      var nodeLiBSSID = document.createElement("LI");
      var nodeLiSSID = document.createElement("LI");
      var nodeLiFreq = document.createElement("LI");
      var nodeLiSignal = document.createElement("LI");
      var nodeLiFlags = document.createElement("LI");
      //var bssidnode = document.createTextNode("BSSID " + networks[i]["bssid"]); // wahrscheinlich nioht benoetigt
      var ssidnode = document.createTextNode("SSID: " + networks[i]["ssid"]);
      if (networks[i]["frequency"] > 5000){
        var freqnode = document.createTextNode("Frequency: 5 GHz");
      }
      else if (networks[i]["frequency"] > 2400){
        var freqnode = document.createTextNode("Frequency: 2.4 GHz");
      }
      var signalnode = document.createTextNode("Signal Strength: " + networks[i]["signal level"]);
      var flagsnode = document.createTextNode("Flags: " + networks[i]["flags"]);

      // <button onclick="scanwifi()">get node</button>
      var nodeBtnConnect = document.createElement("BUTTON");
      nodeBtnConnect.name='btnConnect';
      //nodeBtnConnect.onclick = connectWifi();
      nodeBtnConnect.onclick = function(){connectWifi(this.id);};
      nodeBtnConnect.innerHTML = "Connect to WiFi";
      nodeBtnConnect.setAttribute("id", networks[i]["ssid"]);

      //nodeLiBSSID.appendChild(bssidnode); // wahrscheinlich nioht benoetigt
      nodeLiSSID.appendChild(ssidnode);
      nodeLiFreq.appendChild(freqnode);
      nodeLiSignal.appendChild(signalnode);
      nodeLiFlags.appendChild(flagsnode);

      nodeUL.appendChild(nodeLiBSSID);
      nodeUL.appendChild(nodeLiSSID);
      nodeUL.appendChild(nodeLiFreq);
      nodeUL.appendChild(nodeLiSignal);
      nodeUL.appendChild(nodeLiFlags);
      nodeUL.appendChild(nodeBtnConnect);
      nodeDIV.appendChild(nodeUL);

      document.getElementById("emptyShell").appendChild(nodeDIV);
    }
  }


</script>
</body>
</html>
