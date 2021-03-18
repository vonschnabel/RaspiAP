<?php
  require_once 'functions.php';
?>

<!DOCTYPE HTML>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
<link rel="stylesheet" href="w3.css">
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




.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
    <button class="tablinks" onclick="openTab(event, 'VPNConfig')">VPN Config</button>
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
<!--        <li>IP: <?=$ipwlan1?> /24</li> -->
<!--        <li>SSID: <?=$ssidwlan1?></li> -->
	<li>Status: <?=getWPAState()?></li>
	<li>SSID: <?=getWLAN1_SSID()?></li>
	<li>IP: <?=getWLAN1_IP()?> /24</li>
        <li>SIGNAL: <?=getWLAN1_Signal()[0]?></li>
        <li>SPEED: <?=getWLAN1_Signal()[1]?> Mb/s</li>
        <li>FrEQEUNCY: <?=getWLAN1_Signal()[3]?> MHz</li>
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
    <button onclick="AddWifiNetwork(false,true)">Add hidden WiFi Network</button>
    <div id="emptyShell"></div>
  </div>
  <div id="VPNConfig" class="tabcontent">
<!--    <div id="emptyShellVPN"></div> -->
    VPN is <span id="status">turned off</span>.
    <label class="switch">
      <input id="switchVPN" type="checkbox" <?=getVPNStatushtmltag()?>>
      <span class="slider round"></span>
    </label>
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




/*
    var element =  document.getElementById("emptyShellVPN");
    var vpnstatus = "empty";
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {VPNStatus : true},
      dataType: "json",
      success: function(data){
        //alert(data);
        //location.reload();
        console.log(data);
        vpnstatus = data;
//        getAJAXResponse(data);
      },
    });
//	console.log(vpnstatus);

    var nodeDIV = document.createElement("DIV");
    var nodeTextVPN = document.createTextNode("VPN Text");
//    var nodeINPUT = document.createElement("INPUT");
//    if(vpnstatus == "active"){
//      nodeINPUT.setAttribute("type", "checkbox" checked);
//	alert("active text");
//    }
//    else if(vpnstatus == "inactive"){
//      nodeINPUT.setAttribute("type", "checkbox");
//	alert("inactive text");
//    }
//    nodeINPUT.setAttribute("id", "switchVPN");

    nodeDIV.appendChild(nodeTextVPN);
//    nodeDIV.appendChild(nodeINPUT);
    element.appendChild(nodeDIV);
*/
    var input = document.getElementById('switchVPN');
    var outputtext = document.getElementById('status');

    input.addEventListener('change',function(){
      if(this.checked) {
        outputtext.innerHTML = "turned on";
        StartVPN();
      }
      else {
        outputtext.innerHTML = "turned off";
        StopVPN();
      }
    });




  function Sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
  }

  function AddWifiNetwork(ssid,hidden){
    if(ssid === false){
      while(ssid === false || ssid == ""){
        ssid = window.prompt("Enter the SSID");
        if(!ssid){
          return; // Cancel Button clicked
        }
      }
    }
    var password = window.prompt("Enter the Password");
    while(password != "" && password.length <8){
      var password = window.prompt("Enter the Password");
      if (!password) {
          return; // Cancel Button clicked
      }
    }
    if(password == "" || password.length >= 8){
      $.ajax({
        type: "POST",
        url: 'functions.php',
        data: {btnAddNetwork : true, ssid : ssid, password : password, hidden : hidden},
        dataType: "json",
        success: function(data){
          alert(data);
          location.reload();
        },
      });
    }
  }

/*  function Addhidden(){
    var ssid = window.prompt("Enter the SSID");
    if(ssid != null && ssid != ""){
      var password = window.prompt("Enter the Password");
      if(password != null && password != ""){
        $.ajax({
          type: "POST",
          url: 'functions.php',
          data: {btnAddNetwork : true, ssid : ssid, password : password, hidden : true},
          dataType: "json",
          success: function(data){
            alert(data);
            location.reload();
          },
        });
      }
    }
  }*/

  function StartVPN(){
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {btnVPN : true, connect : true},
      dataType: "json",
      success: function(data){
        alert(data);
        //location.reload();
      },
    });
  }

  function StopVPN(){
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {btnVPN : true, connect : false},
      dataType: "json",
      success: function(data){
        alert(data);
        //location.reload();
      },
    });
  }

  function RemoveNetwork(id){
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {btnRemoveNetwork : true, id : id},
      dataType: "json",
      success: function(data){
        alert(data);
        location.reload();
      },
    });
  }

  function SelectNetwork(id){
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {btnSelectNetwork : true, id : id},
      dataType: "json",
      success: function(data){
        alert(data);
        location.reload();
      },
    });
  }

  function scanresults(){ //retrieving scan results
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {scanresults : true},
      dataType: "json",
      success: function(data){
        networks = data;
        console.log(networks); //kann weg
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

  function ConfiguredNetworks(){ //retrieving configured networks from wpa_cli command
    $.ajax({
      type: "POST",
      url: 'functions.php',
      data: {ConfiguredNetworks : true},
      dataType: "json",
      success: function(data){
        networks = data;
        //console.log(networks); //kann weg
      },
    });
  }

  async function showexistingwifinetworks(){
    var elements =  document.getElementById("emptyShell");
    while (elements.hasChildNodes()) {
      elements.removeChild(elements.firstChild);
    }

    ConfiguredNetworks();
    await Sleep(1000);
    var numberOfElements = document.createTextNode("existing Networks: " + networks.length);
    document.getElementById("emptyShell").appendChild(numberOfElements);
    for(var i = 0; i < networks.length; i++){
      var nodeDIV = document.createElement("DIV");
      nodeDIV.className = "w3-panel w3-card";
      var nodeUL = document.createElement("UL");
      nodeUL.className = "w3-ul";
      var nodeLiSSID = document.createElement("LI");
      var nodeLiState = document.createElement("LI");
      var ssidnode = document.createTextNode("SSID: " + networks[i]['ssid']);
      var statenode = document.createTextNode("State: " + networks[i]['state']);

      var nodeBtnRemove = document.createElement("BUTTON");
      nodeBtnRemove.name='btnRemove';
      nodeBtnRemove.onclick = function(){RemoveNetwork(this.id);};
      nodeBtnRemove.innerHTML = "Remove Network";
      nodeBtnRemove.setAttribute("id", networks[i]['id']);

      var nodeBtnSelect = document.createElement("BUTTON");
      nodeBtnSelect.name='btnSelectNetwork';
      nodeBtnSelect.onclick = function(){SelectNetwork(this.id);};
      nodeBtnSelect.innerHTML = "Connect to Network";
      nodeBtnSelect.setAttribute("id", networks[i]['id']);

      nodeLiSSID.appendChild(ssidnode);
      nodeLiState.appendChild(statenode);
      nodeUL.appendChild(nodeLiSSID);
      nodeUL.appendChild(nodeLiState);
      nodeUL.appendChild(nodeBtnRemove);
      nodeUL.appendChild(nodeBtnSelect);
      nodeDIV.appendChild(nodeUL);
      document.getElementById("emptyShell").appendChild(nodeDIV);
    }
  }

  async  function scanwifi(){ // ?? Brauch ich hier eine asynchrone Funktion? Ja für die Await Sleep Funktion!!
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

    var numberOfElements = document.createTextNode("found Networks: " + networks.length);
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
      var nodeBtnAddNetwork = document.createElement("BUTTON");
      nodeBtnAddNetwork.name='btnAddNetwork';
      //nodeBtnConnect.onclick = connectWifi();
      nodeBtnAddNetwork.onclick = function(){AddWifiNetwork(this.id,false);};
      nodeBtnAddNetwork.innerHTML = "Add WiFi Network";
      nodeBtnAddNetwork.setAttribute("id", networks[i]["ssid"]);

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
      nodeUL.appendChild(nodeBtnAddNetwork);
      nodeDIV.appendChild(nodeUL);

      document.getElementById("emptyShell").appendChild(nodeDIV);
    }
  }


</script>
</body>
</html>
