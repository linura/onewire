#!/bin/bash

if [ $UID -ne 0 ]
then
        echo "L'utilisateur doit être root (ou utiliser sudo)."
        exit 1
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
echo

echo "******************************************************************"
echo "* Make sure that the i2c-dev module is included in /etc/modules  *"
echo "******************************************************************"
if grep -q "i2c-dev" "/etc/modules"
then
        echo "i2c-dev est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-dev dans le fichier /etc/modules"
        echo i2c-dev >>  /etc/modules
fi
echo

echo "********************************************************"
echo "*             Installation des dependances       *"
echo "********************************************************"
sudo apt-get update
sudo  apt-get -y install automake autoconf autotools-dev gcc-4.7 libtool libusb-dev libfuse-dev swig python2.7-dev tcl8.5-dev php5-dev i2c-tools
echo

echo "********************************************************"
echo "*                Installation de OWFS                  *"
echo "********************************************************"
sudo  apt-get -y purge ow-shell owfs-common owfs-fuse owhttpd owserver libow-2.8-15:armhf
sudo  apt-get -y install owserver ow-shell
echo

echo "********************************************************"
echo "*            Configuration OWFS avec dongle USB          *"
echo "********************************************************"
echo "Configuration par defaut: dongle 1-wire/USB DS9490R"
mv /etc/owfs.conf /etc/owfs.conf.$(date +'%Y%m%d-%H%M')
cat << EOF > /etc/owfs.conf
! server: server = localhost:4304
server: usb = all
#server: device = /dev/ttyS1
EOF
echo

echo "********************************************************"
echo "*    Lancement du daemon OWFS avec dongle USB          *"
echo "********************************************************"
/etc/init.d/owserver restart
echo

echo "********************************************************"
echo "*        List des composants 1-wire installés          *"
echo "********************************************************"
/usr/bin/owdir | grep -E "^/[0-9].*$"
echo

echo "****************************************************************************"
echo "*                       Installation termine                               *"
echo "****************************************************************************"
echo
