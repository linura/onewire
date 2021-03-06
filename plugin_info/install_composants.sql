CREATE TABLE IF NOT EXISTS `onewire` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `family_code` VARCHAR(2) DEFAULT NULL,
  `name` VARCHAR(255) DEFAULT NULL,
  `type` VARCHAR(500) DEFAULT NULL,
  `class` LONGTEXT,
  `link` VARCHAR(500) DEFAULT NULL,
  `groupe` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = INNODB AUTO_INCREMENT = 37 DEFAULT CHARSET = latin1;
truncate onewire;
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '1',
    '0',
    'DS1821',
    'Thermostat (No unique ID)',
    '[temperature|temphigh|templow|temphighflag|templowflag|thermostatmode|polarity|1shot]',
    'http://owfs.org/index.php?page=ds1821',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '2',
    '28',
    'DS18B20',
    'Temperature',
    '[fasttemp|temperature|temperature9|temperature10|temperature11|temperature12 die|power|temphigh|templow|errata/die|errata/trim|errata/trimblanket|errata/trimvalid|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds18b20',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '3',
    '22',
    'DS1822',
    'Temperature',
    '[fasttemp|temperature|temperature9|temperature10|temperature11|temperature12|die|power|temphigh|templow|trim|trimblanket|trimvalid|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1822',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '4',
    '10',
    'DS18S20/DS1920',
    'Temperature',
    '[die|power|temperature|temphigh|templow|trim|trimblanket|trimvalid|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds18s20',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '6',
    '42',
    'DS28EA00 ',
    '1-Wire Digital Thermometer with Sequence Detect and PIO',
    '[fasttemp|temperature|temperature9|temperature10|temperature11|temperature12 die|power|temphigh|templow|PIO.A|PIO.B|PIO.ALL|PIO.BYTE|latch.A|latch.B|latch.ALL|latch.BYTE|sensed.A|sensed.B|sensed.ALL|sensed.BYTE]',
    'http://owfs.org/index.php?page=ds28ea00',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '8',
    '3B',
    'DS1825',
    'Programmable Resolution 1-Wire Digital Thermometer with ID',
    '[fasttemp|temperature|temperature9|temperature10|temperature11|temperature12|power|prog_addr|temphigh|templow|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1825',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '9',
    '3B',
    'MAX31826',
    'Digital Temperature Sensor with 1Kb Lockable EEPROM',
    '[temperature|power|memory|prog_addr|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1825',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '10',
    '3B',
    'MAX31850/MAX31851',
    'Cold-Junction Compensated Thermocouple',
    '[temperature|thermocouple|fault|open_circuit|ground_short|vdd_short|power|prog_addr|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1825',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '11',
    '24',
    'DS1904/DS2415',
    '1-Wire Time Chip DS1904 RTC iButton',
    '[date|flags|running|udate|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1904',
    ''
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '12',
    '27',
    'DS1904/DS2417',
    '1-Wire Time Chip with Interrupt',
    '[date|enable|interval|itime|running|udate|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds1904',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '22',
    '26',
    'DS2438',
    'Smart Battery Monitor',
    '[CA|EE|date|disconnect/date|disconnect/udate|endcharge/date|endcharge/udate|IAD|offset|temperature|udate|VAD|VDD|vis|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2438',
    'Temperature Voltages and Current.'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '23',
    '26',
    'DS2438',
    'Smart Battery Monitor',
    '[HIH4000/humidity|HTM1735/humidity|DATANAB/reset|DATANAB/humidity|humidity|temperature]',
    'http://owfs.org/index.php?page=ds2438',
    'Humidity sensor'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '24',
    '26',
    'DS2438',
    'Smart Battery Monitor',
    '[B1-R1-A/pressure|B1-R1-A/gain|B1-R1-A/offset]',
    'http://owfs.org/index.php?page=ds2438',
    'Barometer'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '25',
    '26',
    'DS2438',
    'Smart Battery Monitor',
    '[S3-R1-A/current|S3-R1-A/illuminance|S3-R1-A/gain]',
    'http://owfs.org/index.php?page=ds2438',
    'Light'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '26',
    '1D',
    'DS2423',
    '4kbit 1-Wire RAM with Counter',
    '[counters.A|counters.B|counters.ALL|memory|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2423',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '27',
    '12',
    'DS2406',
    'Dual Addressable Switch with 1kbit Memory',
    '[channels|latch.A|latch.B|latch.ALL|latch.BYTE|memory|PIO.A|PIO.B|PIO.ALL|PIO.BYTE|power|sensed.A|sensed.B|sensed.ALL|sensed.BYTE|set_alarm|TAI8570/sibling|TAI8570/temperature|TAI8570/pressure|T8A/volt.address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2406',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '28',
    '12',
    'DS2407',
    'Hidable Dual Addressable Switch with 1kbit Memory',
    '[channels|latch.A|latch.B|latch.ALL|latch.BYTE|memory|PIO.A|PIO.B|PIO.ALL|PIO.BYTE|power|sensed.A|sensed.B|sensed.ALL|sensed.BYTE|set_alarm|TAI8570/sibling|TAI8570/temperature|TAI8570/pressure|T8A/volt.address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2406',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '29',
    '20',
    'DS2450',
    'Quad A/D Converter',
    '[PIO.A-D|PIO.ALL|volt.A-D|volt.ALL|volt2.A-D|volt2.ALL|8bit/volt.A-D|8bit/volt.ALL|8bit/volt2.A-D|8bit/volt2.ALL|memory|power|alarm/high.A-D|alarm/high.ALL|alarm/low.A-D|alarm/low.ALL|set_alarm/high.A-D|set_alarm/high.ALL|set_alarm/low.A-D|set_alarm/low.ALL|set_alarm/unset|set_alarm/volthigh.A-D|set_alarm/volthigh.ALL|set_alarm/volt2high.A-D|set_alarm/volt2high.ALL|set_alarm/voltlow.A-D|set_alarm/voltlow.ALL|set_alarm/volt2low.A-D|set_alarm/volt2low.ALL|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2450',
    'Voltage 4 and Memory'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '30',
    '20',
    'DS2450',
    'Quad A/D Converter',
    '[CO2/ppm|CO2/power|CO2/status]',
    'http://owfs.org/index.php?page=ds2450',
    'CO2 sensor'
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '35',
    '05',
    'DS2405',
    'Addressable Switch',
    '[PIO|sensed|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2405',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '36',
    '1F',
    'DS2409',
    'MicroLAN Coupler',
    '[aux|branch.0|branch.1|branch.ALL|branch.BYTE|control|discharge|event.0|event.1|event.ALL|event.BYTE|clearevent|main|sensed.0|sensed.1|sensed.ALL|sensed.BYTE|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=ds2409',
    NULL
  );
insert into `onewire` (
    `id`,
    `family_code`,
    `name`,
    `type`,
    `class`,
    `link`,
    `groupe`
  )
values(
    '37',
    '29',
    'DS2408',
    '1-Wire 8 Channel Addressable Switch',
    '[latch.0|latch.1|latch.2|latch.3|latch.4|latch.5|latch.6|latch.7|latch.ALL|latch.BYTE|LCD_M/clear|LCD_M/home|LCD_M/screen|LCD_M/message|LCD_H/[clear|LCD_H/home|LCD_H/yxscreen|LCD_H/screen|LCD_H/message|LCD_H/onoff|LCD_H/redefchar.0|LCD_H/redefchar.1|LCD_H/redefchar.2|LCD_H/redefchar.3|LCD_H/redefchar.4|LCD_H/redefchar.5|LCD_H/redefchar.6|LCD_H/redefchar.7|LCD_H/redefchar.ALL|LCD_H/redefchar_hex.0|LCD_H/redefchar_hex.1|LCD_H/redefchar_hex.2|LCD_H/redefchar_hex.3|LCD_H/redefchar_hex.4|LCD_H/redefchar_hex.5|LCD_H/redefchar_hex.6|LCD_H/redefchar_hex.7|LCD_H/redefchar_hex.ALL|PIO.0|PIO.1|PIO.2|PIO.3|PIO.4|PIO.5|PIO.6|PIO.7|PIO.ALL|PIO.BYTE|power|sensed.0|sensed.1|sensed.2|sensed.3|sensed.4|sensed.5|sensed.6|sensed.7|sensed.ALL|sensed.BYTE|strobe|por|set_alarm|out_of_testmode|address|crc8|id|locator|r_address|r_id|r_locator|type]',
    'http://owfs.org/index.php?page=DS2408',
    'NULL'
  );