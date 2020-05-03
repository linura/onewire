#!/bin/bash

echo "********************************************************"
echo "*            Configuration OWFS avec dongle USB          *"
echo "********************************************************"
echo "Configuration par defaut: dongle 1-wire/USB DS9490R"
mv /etc/owfs.conf /etc/owfs.conf.save
cat << EOF > /etc/owfs.conf
! server: server = localhost:4304
server: usb = all
#server: device = /dev/ttyS1
EOF
echo