#!/bin/bash
if [ $UID -ne 0 ]
then
        echo "L'utilisateur doit être root (ou utiliser sudo)."
        exit 1
fi
echo "Si vous avez un probleme d espace disque"
echo "sudo raspi-config et choisir Expand Filesystem"
echo "****************************************************************************"
echo "* Installation des outils nécessaires :  *"
echo "****************************************************************************"
sudo apt-get install i2c-tools owfs ow-shell

echo "******************************************************************"
echo "*  /etc/modules => i2c-dev *"
echo "******************************************************************"
if grep -q "i2c-dev" "/etc/modules"
then
        echo "i2c-dev est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-dev dans le fichier /etc/modules"
        echo "i2c-dev" >>  /etc/modules
fi
echo
echo "******************************************************************"
echo "*  /etc/modules => i2c-bcm2708 *"
echo "******************************************************************"
if grep -q "i2c-bcm2708" "/etc/modules"
then
        echo "i2c-bcm2708 est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-bcm2708 dans le fichier /etc/modules"
       sudo  echo "i2c-bcm2708" >>  /etc/modules
fi
echo
echo "******************************************************************"
echo "*  /etc/modules => i2c-bcm2835  *"
echo "******************************************************************"
if grep -q "i2c-bcm2835" "/etc/modules"
then
        echo "i2c-bcm2835 est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-bcm2835 dans le fichier /etc/modules"
        echo "i2c-bcm2835" >>  /etc/modules
fi
echo
echo "******************************************************************"
echo "*  /etc/modules => i2c-bcm2835  *"
echo "******************************************************************"
if grep -q "i2c-bcm2835" "/etc/modules"
then
        echo "i2c-bcm2835 est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-bcm2835 dans le fichier /etc/modules"
        echo "i2c-bcm2835" >>  /etc/modules
fi
echo "****************************************************************************"
echo "* Make sure that the i2c-bus module is not included in the blacklist file  *"
echo "****************************************************************************"
grep -i '#blacklist i2c-bcm2708' /etc/modprobe.d/raspi-blacklist.conf
retval=$?
if [ "$retval" = 1 ]
then
        sed -i -e "s/blacklist i2c-bcm2708/#blacklist i2c-bcm2708/g" /etc/modprobe.d/raspi-blacklist.conf
fi

echo "****************************************************************************"
echo "* Make sure that the param on boot/config.txt  *"
echo "****************************************************************************"
if grep -q "dtparam=i2c1=on" "/boot/config.txt"
then
        echo "dtparam=i2c1=on est deja dans le fichier /boot/config.txt"
else
        echo "ajout de dtparam=i2c1=on dans le fichier /boot/config.txt"
        echo "dtparam=i2c1=on" >>  /boot/config.txt
fi

if grep -q "dtparam=i2c_arm=on" "/boot/config.txt"
then
        echo "dtparam=i2c_arm=on est deja dans le fichier /boot/config.txt"
else
        echo "ajout de dtparam=i2c_arm=on dans le fichier /boot/config.txt"
        echo "dtparam=i2c_arm=on" >>  /boot/config.txt
fi
echo
echo
echo "********************************************************"
echo "*            Configuration OWFS I2C          *"
echo "********************************************************"

echo -n "Quelle est votre adresse ip ? localhost "
read adresseip

if [ -z "$adresseip" ]
then
   adresseip="localhost"
fi
rm /etc/owfs.conf
cat << EOF > /etc/owfs.conf
 #Sample configuration file for the OWFS suite for Debian GNU/Linux.
#
#
# This is the main OWFS configuration file. You should read the
# owfs.conf(5) manual page in order to understand the options listed
# here.

######################## SOURCES ########################
#
# With this setup, any client (but owserver) uses owserver on the
# local machine...
! server: server = localhost:4304
#
# ...and owserver uses the real hardware, by default fake devices
# This part must be changed on real installation
#server: FAKE = DS18S20,DS2405
#
# USB device: DS9490
#server: usb = all
#
# Serial port: DS9097
#server: device = /dev/ttyS1
#
# owserver tcp address
#server: server = 192.168.10.1:3131
#
# random simulated device
#server: FAKE = DS18S20,DS2405
#
######################### OWFS ##########################
#
#mountpoint = /mnt/1wire
allow_other
#
####################### OWHTTPD #########################

http: port = 2121

####################### OWFTPD ##########################

ftp: port = 2120

####################### OWSERVER ########################

server: port = ${adresseip}:4304
device = /dev/i2c-1
EOF


echo
echo "******************************************************************"
echo "* Chargement des modules  i2c-bcm2708 / i2c-dev  *"
echo "******************************************************************"
sudo modprobe i2c-bcm2708
sudo modprobe i2c-dev
echo
echo "******************************************************************"
echo "**Demarrage de OWFS *"
echo "******************************************************************"
sudo /etc/init.d/owserver restart
echo
echo "********************************************************"
echo "*        List des composants 1-wire installés          *"
echo "********************************************************"
sudo i2cdetect -y 1
/usr/bin/owdir | grep -E "^/[0-9].*$"
echo
echo "****************************************************************************"
echo "*                      Insterface OWFS                        *"
echo "*                    http://ip_owfs:2121/ :                     *"
echo "****************************************************************************"
owdir
echo "****************************************************************************"
echo "*                       Installation termine                               *"
echo "****************************************************************************"
echo