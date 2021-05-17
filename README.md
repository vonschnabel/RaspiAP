# RaspiAP

The WiFi drivers for additional WiFi device (wlan1) need to be installed beforehand. 
The systems only supports wlan0 as hotspot delivering device. 
The connection to other WiFi's is only possible with wlan1.
To use Wireguard, please Install it beforehand.

Installation:

``` 
sudo apt update && sudo apt upgrade -y

sudo DEBIAN_FRONTEND=noninteractive apt install -y netfilter-persistent iptables-persistent
sudo apt install apache2 php php-mbstring libapache2-mod-php hostapd dnsmasq git -y

sudo sed -i 's/Priv/#Priv/g' /lib/systemd/system/apache2.service

sudo mkdir /var/www/html/tmp
sudo chown www-data:www-data /var/www/html/tmp

sudo systemctl unmask hostapd
sudo systemctl enable hostapd

sudo /bin/su -c "echo net.ipv4.ip_forward=1 >> /etc/sysctl.d/99-sysctl.conf"

sudo reboot

<wireguard device name> is the name of the wireguard config file without ".conf"

sudo rfkill unblock 0
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -t nat -A POSTROUTING -o wlan1 -j MASQUERADE
sudo iptables -t nat -A POSTROUTING -o <wireguard device name> -j MASQUERADE
sudo iptables -A FORWARD -i <wireguard device name> -o wlan1 -m state --state RELATED,ESTABLISHED -j ACCEPT
sudo iptables -A FORWARD -i wlan1 -o <wireguard device name> -j ACCEPT
sudo netfilter-persistent save

wget https://www.w3schools.com/w3css/4/w3.css
wget http://code.jquery.com/jquery-1.11.3.min.js
wget http://code.jquery.com/jquery-migrate-1.2.1.min.js
git clone https://github.com/vonschnabel/RaspiAP.git

sudo mv ./w3.css /var/www/html/
sudo mv ./jquery-1.11.3.min.js /var/www/html/
sudo mv ./jquery-migrate-1.2.1.min.js /var/www/html/
sudo mv ./RaspiAP/090_raspap /etc/sudoers.d
sudo mv ./RaspiAP/functions.php /var/www/html/
sudo mv ./RaspiAP/hotspot.php /var/www/html/
```
![Actual-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/01-Actual-Config-1.PNG)
![Actual-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/01-Actual-Config-2.PNG)
![Hotspot](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/02-Hotspot.PNG)
![Network-Settings](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/03-Network-Settings.PNG)
![Connect-to-WIFI](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-to-WIFI-1.PNG)
![Connect-to-WIFI](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-to-WIFI-2.PNG)
![VPN-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/05-VPN-Config.PNG)
![Connected-Clients](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/06-Connected-Clients.PNG)
