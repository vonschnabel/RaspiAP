# RaspiAP

The WiFi drivers for additional WiFi device (wlan1) need to be installed beforehand. 
The systems only supports wlan0 as hotspot delivering device. 
The connection to other WiFi's is only possible with wlan1.

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

sudo rfkill unblock 0
sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -t nat -A POSTROUTING -o wlan1 -j MASQUERADE
sudo netfilter-persistent save

sudo wget https://www.w3schools.com/w3css/4/w3.css
git clone https://github.com/vonschnabel/RaspiAP.git

sudo mv w3.css /var/www/html/
sudo mv ./RaspiAP/090_raspap /etc/sudoers.d
sudo mv ./RaspiAP/functions.php /var/www/html/
sudo mv ./RaspiAP/hotspot.php /var/www/html/
```
![Actual-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/01-Actual-Config.PNG)
![Hotspot](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/02-Hotspot.PNG)
![Network-Settings](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/03-Network-Settings.PNG)
![Connect-Wifi-1](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-Wifi-1.PNG)
![Connect-Wifi-2](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-Wifi-2.PNG)
