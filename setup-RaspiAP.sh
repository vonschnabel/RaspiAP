#!/bin/bash

if [ "$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi

echo "Install RaspiAP";
echo "";
echo "####################################";
echo "";

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )/RaspiAP"
if [ -d "$DIR" ]; then
  echo "Directory RaspiAP found. Proceeding with installation..."
else
  echo "Directory RaspiAP not found. Cloning from Github..."
  git clone https://github.com/vonschnabel/RaspiAP.git
fi

sudo apt install apache2 php php-mbstring libapache2-mod-php hostapd dnsmasq netfilter-persistent iptables-persistent -y

sudo sed -i 's/Priv/#Priv/g' /lib/systemd/system/apache2.service

sudo mkdir /var/www/html/tmp
sudo chown www-data:www-data /var/www/html/tmp

sudo systemctl unmask hostapd

sudo /bin/su -c "echo net.ipv4.ip_forward=1 >> /etc/sysctl.d/99-sysctl.conf"

echo ""
echo ""
read -p "Do you want to install wireguard (y/n): " answer </dev/tty
echo ""

case ${answer:0:1} in
    y|Y|j|J )
        echo "installing wireguard..."
        echo ""
        read -p "enter a name for the wireguard device. default name is wg0: " wgdevicename </dev/tty

	echo "deb http://archive.raspbian.org/raspbian testing main" | sudo tee --append /etc/apt/sources.list.d/testing.list
	printf 'Package: *\nPin: release a=testing\nPin-Priority: 50\n' | sudo tee --append /etc/apt/preferences.d/limit-testing
	sudo apt update
	sudo apt install wireguard -y

	sudo rfkill unblock 0
	sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
	sudo iptables -t nat -A POSTROUTING -o wlan1 -j MASQUERADE
	sudo iptables -t nat -A POSTROUTING -o $wgdevicename -j MASQUERADE
	sudo iptables -A FORWARD -i $wgdevicename -o wlan1 -m state --state RELATED,ESTABLISHED -j ACCEPT
	sudo iptables -A FORWARD -i wlan1 -o $wgdevicename -j ACCEPT
	sudo netfilter-persistent save
	echo ""
        ;;
    *)
        echo 'install skipped'
	echo ""
	sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
	sudo iptables -t nat -A POSTROUTING -o wlan1 -j MASQUERADE
	sudo netfilter-persistent save
	echo ""
        ;;
esac

echo ""
echo ""
read -p "Do you want to install TOR (Onion Routing) (y/n): " answer </dev/tty
echo ""

case ${answer:0:1} in
    y|Y|j|J )        
        echo "installing TOR..."
        echo ""
	sudo apt install tor -y
	
	timestamp=$(date +"%Y-%m-%d-%H-%M")
	if [ -w /etc/tor/torrc ]; then
	    mv /etc/tor/torrc /etc/tor/torrc.bak-$timestamp
	    sudo echo "Log notice file /var/log/tor/tor-notices.log" >> /etc/tor/torrc
	    sudo echo "VirtualAddrNetwork 10.192.0.0/10" >> /etc/tor/torrc
	    sudo echo "AutomapHostsSuffixes .onion,.exit" >> /etc/tor/torrc
	    sudo echo "AutomapHostsOnResolve 1" >> /etc/tor/torrc
	    sudo echo "TransPort 0.0.0.0:9040" >> /etc/tor/torrc
	    sudo echo "DNSPort 0.0.0.0:53" >> /etc/tor/torrc
	    
	    sudo iptables -t nat -A PREROUTING -i wlan0 -p tcp --dport 22 -j REDIRECT --to-ports 22
	    sudo iptables -t nat -A PREROUTING -i wlan0 -p udp --dport 53 -j REDIRECT --to-ports 53
	    sudo iptables -t nat -A PREROUTING -i wlan0 -p tcp -d 192.168.4.1 --dport 80 -j REDIRECT --to-ports 80
	    sudo iptables -t nat -A PREROUTING -i wlan0 -p tcp --syn -j REDIRECT --to-ports 9040
	    
	    sudo netfilter-persistent save
	    
	    touch /var/log/tor/notices.log
	    chown debian-tor /var/log/tor/notices.log
	    chmod 644 /var/log/tor/notices.log
	    sudo touch /var/log/tor/debug.log
	    sudo chown debian-tor /var/log/tor/debug.log
	    sudo chmod 644 /var/log/tor/debug.log
	else
  	    echo "Datei "/etc/tor/torrc" nicht vorhanden"
  	    break
	fi
	sudo echo
	echo ""
        ;;
    *)
        echo 'install skipped'	
	echo ""
        ;;
esac





wget https://www.w3schools.com/w3css/4/w3.css
wget http://code.jquery.com/jquery-1.11.3.min.js
wget http://code.jquery.com/jquery-migrate-1.2.1.min.js

sudo mv ./RaspiAP/disable-hostapd.service /etc/systemd/system
sudo systemctl enable disable-hostapd.service

mv ./RaspiAP/setup-hotspot.sh ./
chmod +x setup-hotspot.sh
sudo mv ./w3.css /var/www/html/
sudo chown www-data:www-data /var/www/html/w3.css
sudo mv ./jquery-1.11.3.min.js /var/www/html/
sudo chown www-data:www-data /var/www/html/jquery-1.11.3.min.js
sudo mv ./jquery-migrate-1.2.1.min.js /var/www/html/jquery-migrate-1.2.1.min.js
sudo chown www-data:www-data /var/www/html/jquery-migrate-1.2.1.min.js
sudo mv ./RaspiAP/090_raspap /etc/sudoers.d
sudo chown root:root /etc/sudoers.d/090_raspap
sudo chmod 440 /etc/sudoers.d/090_raspap

sudo mv ./RaspiAP/functions.php /var/www/html/
sudo chown www-data:www-data /var/www/html/functions.php
sudo mv ./RaspiAP/hotspot.php /var/www/html/
sudo chown www-data:www-data /var/www/html/hotspot.php
rm -rf RaspiAP

sudo systemctl status apache2.service

echo ""
echo "####################################"
echo ""
echo ""
echo "Install complete"
echo ""
