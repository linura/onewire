echo "****************************************************************************"
echo "* Make sure that the i2c-bus module is not included in the blacklist file  *"
echo "****************************************************************************"
grep -i '#blacklist i2c-bcm2708' /etc/modprobe.d/raspi-blacklist.conf
retval=$?
if [ "$retval" = 1 ]
then sed -i -e "s/blacklist i2c-bcm2708/#blacklist i2c-bcm2708/g" /etc/modprobe.d/raspi-blacklist.conf
fi
echo "******************************************************************"
echo "* Make sure that the i2c-dev module is included in /etc/modules  *"
echo "******************************************************************"
if grep -q "i2c-dev" "/etc/modules" ; then
    echo "i2c-dev est deja dans le fichier /etc/modules"
else
    echo "ajout de i2c-dev dans le fichier /etc/modules"
    echo i2c-dev >>  /etc/modules
fi
echo "********************************************************"
echo "*             Installation des dependances       *"
echo "********************************************************"
sudo apt-get update
sudo apt-get -y install automake autoconf autotools-dev gcc-4.7 libtool libusb-dev libfuse-dev swig python2.7-dev tcl8.5-dev php5-dev i2c-tools
echo "********************************************************"
echo "*                Installation de OWFS                  *"
echo "********************************************************"
sudo apt-get  -y autoremove owserver ow-shell owhttpd owfs-fuse
sudo apt-get -y install owserver ow-shell owhttpd owfs-fuse
echo "********************************************************"
echo "*    Create a mountpoint for the 1wire folder          *"
echo "********************************************************"
sudo mkdir /mnt/1wire
echo "****************************************************************************"
echo "* To make it possible to access the 1wire devices without root privileges you'll have to modify the FUSE settings. Open the fuse configuration file:  *"
echo "****************************************************************************"
if grep -q "#user_allow_other" "/etc/fuse.conf" ;
   then
   echo "user_allow_other n est plus en commentaire"
   sed -i -e "s/#user_allow_other/user_allow_other/g" /etc/fuse.conf
else
    echo "user_allow_other n est pas en commentaire"
fi
echo "****************************************************************************"
echo "*                          START OWFS WITH USB  ADAPTATOR                  *"
echo "****************************************************************************"
umount /mnt/1wire

sudo /usr/bin/owfs -u --allow_other /mnt/1wire/
echo "****************************************************************************"
echo "*         Make sure OWFS is started automatically at boot                  *"
echo "****************************************************************************"
cp /usr/share/nginx/www/jeedom/plugins/onewire/ressources/start1wire.sh /etc/init.d
sudo chmod +x /etc/init.d/start1wire.sh
sudo update-rc.d start1wire.sh defaults
sudo /etc/init.d/owserver restart
umount /mnt/1wire
echo "****************************************************************************"
echo "*                       Installation termine                               *"
echo "****************************************************************************"
