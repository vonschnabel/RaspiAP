source /boot/hotspot.conf

dhcprangestart=${dhcprange%%-*}
dhcprangeend=${dhcprange%.*}
dhcprangeend+="."
dhcprangeend=$dhcprangeend${dhcprange#*-}

#filepath="/etc/dnsmasq.d/090_raspap.conf.test";
filepath="/etc/dnsmasq.d/090_raspap.conf"
echo "interface=wlan0" > "$filepath";
echo "dhcp-range=$dhcprangestart,$dhcprangeend,255.255.255.0,6h" >> "$filepath";
echo "dhcp-option=option:dns-server,1.1.1.1" >> "$filepath";
echo "no-dhcp-interface=eth0,wlan1" >> "$filepath";

#filepath="/etc/dhcpcd.conf.test";
filepath="/etc/dhcpcd.conf"
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

#filepath="/etc/hostapd/hostapd.conf.test";
filepath="/etc/hostapd/hostapd.conf"
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
echo "wpa_passphrase=$password" >> "$filepath";
echo "wpa_key_mgmt=WPA-PSK" >> "$filepath";
echo "wpa_pairwise=TKIP" >> "$filepath";
echo "rsn_pairwise=CCMP" >> "$filepath";

#sudo /bin/systemctl stop hostapd.service
#sudo /bin/systemctl enable hostapd.service
#sudo /bin/systemctl start hostapd.service
