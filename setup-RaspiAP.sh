#!/bin/bash

if [ "$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi

echo "Install RaspiAP";
echo "";
echo "####################################";
echo "";
echo "hallo"
sudo DEBIAN_FRONTEND=noninteractive
echo "welt"
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
    y|Y|j )
        # Write value to file.
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
        ;;
esac

wget https://www.w3schools.com/w3css/4/w3.css
wget http://code.jquery.com/jquery-1.11.3.min.js
wget http://code.jquery.com/jquery-migrate-1.2.1.min.js

sudo mv ./RaspiAP/disable-hostapd.service /etc/systemd/system
sudo systemctl enable disable-hostapd.service

sudo mv ./w3.css /var/www/html/
sudo mv ./jquery-1.11.3.min.js /var/www/html/
sudo mv ./jquery-migrate-1.2.1.min.js /var/www/html/
sudo mv ./RaspiAP/090_raspap /etc/sudoers.d
sudo chown root:root /etc/sudoers.d/090_raspap
sudo chmod 440 /etc/sudoers.d/090_raspap
sudo mv ./RaspiAP/functions.php /var/www/html/
sudo mv ./RaspiAP/hotspot.php /var/www/html/
rm -rf RaspiAP


echo ""
echo "####################################"
echo ""
echo ""
echo "Install complete"
echo ""
