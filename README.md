# RaspiAP

The WiFi drivers for additional WiFi device (wlan1) need to be installed beforehand. 
The systems only supports wlan0 as hotspot delivering device. 
The connection to other WiFi's is only possible with wlan1.
To use Wireguard, confirm to install it when executing the setup script. Wireguard configs need to be placed in the folder /etc/wireguard.

Reset the Hotspot:
To disable the Hotspot, insert a file named "disable-hotspot" into the /boot partition. The Hotspot service hostapd, will be disabled.   


Installation:

``` 
sudo apt update && sudo apt upgrade -y
sudo apt install git

git clone https://github.com/vonschnabel/RaspiAP.git
mv ./RaspiAP/setup-RaspiAP.sh ./
chmod +x ./setup-RaspiAP.sh
sudo ./setup-RaspiAP.sh

```
![Actual-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/01-Actual-Config-1.PNG)
![Actual-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/01-MAC-Address.PNG)
![Hotspot](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/02-Hotspot.PNG)
![Network-Settings](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/03-Network-Settings.PNG)
![Connect-to-WIFI](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-to-WIFI-Saved-Networks.PNG)
![Connect-to-WIFI](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/04-Connect-to-WIFI-Scan.PNG)
![VPN/Tor-Config](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/05-VPN-Config.PNG)
![Connected-Clients](https://github.com/vonschnabel/RaspiAP/blob/main/screenshots/06-Connected-Clients.PNG)
