# RaspiAP

Install WiFi drivers for additional WiFi device (wlan1). The systems only supports wlan0 as hotspot delivering device. 
The connection to other WiFi's is only possible with wlan1.

Installation:

``` sudo apt update && sudp apt upgrade -y

sudo DEBIAN_FRONTEND=noninteractive apt install -y netfilter-persistent iptables-persistent
sudo apt install apache2
sudo apt install  php php-mbstring
sudo apt install hostapd
sudo apt install dnsmasq

sudo mkdir /var/www/html/tmp
sudo chown www-data:www-data /var/www/html/tmp

sudo systemctl unmask hostapd
sudo systemctl enable hostapd

echo net.ipv4.ip_forward=1 >> sudo nano /etc/sysctl.d/99-sysctl.conf

sudo iptables -t nat -A POSTROUTING -o eth0 -j MASQUERADE
sudo iptables -t nat -A POSTROUTING -o wlan1 -j MASQUERADE
sudo netfilter-persistent save

git clone https://github.com/vonschnabel/RaspiAP.git

sudo cp ./RaspiAP/090_raspap /etc/sudoers.d
sudo cp ./RaspiAP/functions.php /var/www/html/
sudo cp ./RaspiAP/hotspot.php /var/www/html/
```
