#!/bin/bash

echo "***************************************************************************"
echo "* Onewire GPIO mode installation"
echo "***************************************************************************"
if [ $UID -ne 0 ]
then
        echo "Install. user must be root or use sudo."
        exit 1
fi
echo

echo "***************************************************************************"
echo "* Make sure that the 1-wire module is not included in the blacklist file"
echo "***************************************************************************"
sed -i.$(date '+%Y%m%d_%H%M%S') /w1-therm/d /etc/modprobe.d/raspi-blacklist.conf
sed -i.$(date '+%Y%m%d_%H%M%S') /w1-gpio/d /etc/modprobe.d/raspi-blacklist.conf
echo "done."
echo


echo "***************************************************************************"
echo "* Configure 1-wire modules"
echo "***************************************************************************"

sed -i.$(date '+%Y%m%d_%H%M%S') /w1-therm/d /etc/modules
sed -i.$(date '+%Y%m%d_%H%M%S') /w1-gpio/d /etc/modules

echo -n "GPIO pin number use for 1-wire bus (4 for GPIO4 is default) ? : "
read GPIO


if [ -z "$GPIO" ]
then
   GPIO="4"
fi
echo "1-wire bus will be configured on GPIO${GPIO}."
echo "w1-therm" >> /etc/modules
echo "w1-gpio pullup=1 gpiopin=${GPIO}" >> /etc/modules
echo "done."
echo


echo "***************************************************************************"
echo "* Load 1-wire modules and list 1_wire device"
echo "* if 'not found' is display, there no 1-wire device detected"
echo "***************************************************************************"
rmmod w1-therm > /dev/null 2>&1
rmmod w1-gpio > /dev/null 2>&1
modprobe w1-therm
modprobe w1-gpio pullup=1 gpiopin=$GPIO
echo "1-wire devices:"
cat /sys/bus/w1/devices/w1_bus_master1/w1_master_slaves
echo "done."
echo

echo "***************************************************************************"
echo "*            Installation termine"
echo "***************************************************************************"
echo "You can run the 'instal_gpio.sh' script to re-configure the 1-wire bus"
