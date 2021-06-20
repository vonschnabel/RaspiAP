#!/bin/bash

if [ "$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi

echo "Setup hostapd as WPA2 Hotspot";
echo "";
echo "####################################";
echo "";
echo "insert Hotspot IP:";
echo "/24 Network will be used";
echo "e.g. 192.168.1.1";
read hotspotip;
echo "";
echo "insert the Start IP for DHCP Server between 2 - 254";
read iprangestart;
echo "";
echo "insert the End IP for DHCP Server between 3 - 254";
echo "the End IP needs to be bigger than the Start IP";
read iprangeend;
echo "";
echo "insert the Hotspot SSID without spaces";
read ssid;
echo "";
echo "insert the Hotspot passphrase";
echo "at least 8 characters are needed";
read pass;
echo "";
echo "enter Country Code for Wifi";
echo "2 chars e.g. DE, GB, US";
read countrycode;
echo "";
echo "enter the channel for 2.4 GHz Wifi";
echo "number between 1 - 11";
read channel;
echo "";
echo "####################################";
echo "";
echo "";
echo "";
echo "************************************";
echo "Hotspot IP: $hotspotip";
echo "IP range start: $iprangestart";
echo "IP range end: $iprangeend";
echo "SSID: $ssid";
echo "Passphrase: $pass";
echo "Country Code: $countrycode";
echo "Channel: $channel";
echo "************************************";

IFS='.';
read -a strarr <<< "$hotspotip";
iprangestart="${strarr[0]}.${strarr[1]}.${strarr[2]}.$iprangestart"
iprangeend="${strarr[0]}.${strarr[1]}.${strarr[2]}.$iprangeend"

filepath="/etc/dnsmasq.d/090_raspap.conf";
echo "interface=wlan0" > "$filepath";
echo "dhcp-range=$iprangestart,$iprangeend,255.255.255.0,6h" >> "$filepath";
echo "dhcp-option=option:dns-server,1.1.1.1" >> "$filepath";
echo "no-dhcp-interface=eth0,wlan1" >> "$filepath";
echo "created $filepath";

filepath="/etc/dhcpcd.conf";
echo "hostname" > "$filepath";
echo "clientid" >> "$filepath";
echo "persistent" >> "$filepath";
echo "option rapid_commit" >> "$filepath";
echo "option domain_name_servers, domain_name, domain_search, host_name" >> "$filepath";
echo "option classless_static_routes" >> "$filepath";
echo "require dhcp_server_identifier" >> "$filepath";
echo "slaac private" >> "$filepath";
echo "nohook lookup-hostname" >> "$filepath";
echo "interface wlan0" >> "$filepath";
echo -e "\tstatic ip_address=$hotspotip/24" >> "$filepath";
echo -e "\tnohook wpa_supplicant" >> "$filepath";
echo "created $filepath";

filepath="/etc/hostapd/hostapd.conf";
echo "ctrl_interface=/var/run/hostapd" > "$filepath";
echo "ctrl_interface_group=0" >> "$filepath";
echo "country_code=$countrycode" >> "$filepath";
echo "interface=wlan0" >> "$filepath";
echo "ssid=$ssid" >> "$filepath";
echo "hw_mode=g" >> "$filepath";
echo "channel=$channel" >> "$filepath";
echo "macaddr_acl=0" >> "$filepath";
echo "auth_algs=1" >> "$filepath";
echo "ignore_broadcast_ssid=0" >> "$filepath";
echo "wpa=2" >> "$filepath";
echo "wpa_passphrase=$pass" >> "$filepath";
echo "wpa_key_mgmt=WPA-PSK" >> "$filepath";
echo "wpa_pairwise=TKIP" >> "$filepath";
echo "rsn_pairwise=CCMP" >> "$filepath";
echo "created $filepath";

sudo /bin/systemctl stop hostapd.service
sudo /bin/systemctl enable hostapd.service
sudo /bin/systemctl start hostapd.service
