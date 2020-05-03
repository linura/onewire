#!/bin/bash

if [ $UID -ne 0 ]
then
        echo "L'utilisateur doit être root (ou utiliser sudo)."
        exit 1
fi

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

if grep -q "i2c-bcm2708" "/etc/modules"
then
        echo "i2c-bcm2708 est deja dans le fichier /etc/modules"
else
        echo "ajout de i2c-bcm2708 dans le fichier /etc/modules"
        echo i2c-bcm2708 >>  /etc/modules
fi
echo

echo "******************************************************************"
echo "* Make sure that the i2c-dev=on  is included in /boot/config.txt  *"
echo "******************************************************************"
if grep -q "dtparam=i2c1=on" "/boot/config.txt"
then
        echo "dtparam=i2c1=on est deja dans le fichier /boot/config.txt"
else
        echo "ajout de dtparam=i2c1=on dans le fichier /boot/config.txt"
        echo dtparam=i2c1=on >>  /boot/config.txt
fi
echo

if grep -q "dtparam=i2c_arm=on" "/boot/config.txt"
then
        echo "dtparam=i2c_arm=on est deja dans le fichier /boot/config.txt"
else
        echo "ajout de dtparam=i2c_arm=on dans le fichier /boot/config.txt"
        echo dtparam=i2c_arm=on >>  /boot/config.txt
fi
echo
echo "****************************************************************************"
echo "*   Installation Partie 1 termine  redemarrage du raspberry  *"
echo "*   Il faut continuer linstallation avec le fichier  install_i2c_suite.sh *"
echo "****************************************************************************"
echo
sudo reboot