<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function onewire_install() {

    $sql = file_get_contents(dirname(__FILE__) . '/install.sql');
    $sql2 = file_get_contents(dirname(__FILE__) . '/install_composants.sql');
    DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    DB::Prepare($sql2, array(), DB::FETCH_TYPE_ROW);
}


function onewire_remove() {

    DB::Prepare('DROP TABLE IF EXISTS `onewire`', array(), DB::FETCH_TYPE_ROW);
    DB::Prepare('DROP TABLE IF EXISTS `onewire_send_history`', array(), DB::FETCH_TYPE_ROW);
}


function onewire_update() {

    $sql = "SELECT COUNT(*) AS nb FROM information_schema.columns  WHERE table_name = 'onewire'  AND column_name = 'class2' ";
    $result =DB::Prepare($sql,  array(), DB::FETCH_TYPE_ROW);
    if($result['nb'] ==0) {
        $sql = "ALTER TABLE onewire ADD COLUMN `class2` LONGTEXT NULL;";
        log::add('onewire', 'debug', 'Ajout du champ class2 dans table onewire');
        DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
    }else{
        log::add('onewire', 'debug', 'Les  champs class2  existes deja dans table onewire');
    }

     //UPDATE onewire SET `class2`='[address|alias|crc8|errata|family|fasttemp|id|locator|power|r_address|r_id|r_locator|scratchpad|temperature|temperature10|temperature11|temperature12|temperature9|temphigh|templow|type]' WHERE `id`='36';
	//$sql = file_get_contents(dirname(__FILE__) . '/update.sql');
	//DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);

	$sql = " UPDATE onewire SET `class2`='[address|alias|crc8|errata|family|fasttemp|id|locator|power|r_address|r_id|r_locator|scratchpad|temperature|temperature10|temperature11|temperature12|temperature9|temphigh|templow|type]' WHERE `id`='36';";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);

	$sql = " insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('37','29','DS2408','1-Wire 8 Channel Addressable Switch','[latch.0|latch.1|latch.2|latch.3|latch.4|latch.5|latch.6|latch.7|latch.ALL|latch.BYTE|LCD_M/clear|LCD_M/home|LCD_M/screen|LCD_M/message|LCD_H/[clear|LCD_H/home|LCD_H/yxscreen|LCD_H/screen|LCD_H/message|LCD_H/onoff|LCD_H/redefchar.0|LCD_H/redefchar.1|LCD_H/redefchar.2|LCD_H/redefchar.3|LCD_H/redefchar.4|LCD_H/redefchar.5|LCD_H/redefchar.6|LCD_H/redefchar.7|LCD_H/redefchar.ALL|LCD_H/redefchar_hex.0|LCD_H/redefchar_hex.1|LCD_H/redefchar_hex.2|LCD_H/redefchar_hex.3|LCD_H/redefchar_hex.4|LCD_H/redefchar_hex.5|LCD_H/redefchar_hex.6|LCD_H/redefchar_hex.7|LCD_H/redefchar_hex.ALL|PIO.0|PIO.1|PIO.2|PIO.3|PIO.4|PIO.5|PIO.6|PIO.7|PIO.ALL|PIO.BYTE|power|sensed.0|sensed.1|sensed.2|sensed.3|sensed.4|sensed.5|sensed.6|sensed.7|sensed.ALL|sensed.BYTE|strobe|por|set_alarm|out_of_testmode|address|crc8|id|locator|r_address|r_id|r_locator|type]','http://owfs.org/index.php?page=DS2408','NULL') ";
	DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);

$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('40','21','DS1921','Thermochron temperature logging iButton.', '[memory|id|locator|family|crc8|alarm_dow|alarm_hour|alarm_minute|alarm_second|alarm_state|alarm_trigger|address|present|r_address|r_id|r_locator|running|set_alarm/date|set_alarm/temphigh|set_alarm/templow|set_alarm/trigger|temperature|type]','http://owfs.org/index.php?page=ds1921','parametrage')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('41','21','DS1921','Thermochron temperature logging iButton.','[about/measuring|about/resolution|about/samples|about/templow|about/temphigh|about/version]','http://owfs.org/index.php?page=ds1921','about')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('42','21','DS1921','Thermochron temperature logging iButton.','[clock/date|clock/running|clock/udate]','http://owfs.org/index.php?page=ds1921','clock')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('43','21','DS1921','Thermochron temperature logging iButton.','[histogram/counts.ALL|histogram/counts.0|histogram/counts.1|histogram/counts.2|histogram/counts.3|histogram/counts.4|histogram/counts.5|histogram/counts.6|histogram/counts.7|histogram/counts.8|histogram/counts.9|histogram/counts.10|histogram/counts.11|histogram/counts.12|histogram/counts.13|histogram/counts.14|histogram/counts.15|histogram/counts.16|histogram/counts.17|histogram/counts.18|histogram/counts.19|histogram/counts.20|histogram/counts.21|histogram/counts.22|histogram/counts.23|histogram/counts.24|histogram/counts.25|histogram/counts.26|histogram/counts.27|histogram/counts.28|histogram/counts.29|histogram/counts.30|histogram/counts.31|histogram/counts.32|histogram/counts.33|histogram/counts.34|histogram/counts.35|histogram/counts.36|histogram/counts.37|histogram/counts.38|histogram/counts.39|histogram/counts.40|histogram/counts.41|histogram/counts.42|histogram/counts.43|histogram/counts.44|histogram/counts.45|histogram/counts.46|histogram/counts.47|histogram/counts.48|histogram/counts.49|histogram/counts.50|histogram/counts.51|histogram/counts.52|histogram/counts.53|histogram/counts.54|histogram/counts.55|histogram/counts.56|histogram/counts.57|histogram/counts.58|histogram/counts.59|histogram/counts.60|histogram/counts.61|histogram/counts.62]','http://owfs.org/index.php?page=ds1921','histogram/counts')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('44','21','DS1921','Thermochron temperature logging iButton.','[histogram/temperature.ALL|histogram/temperature.0|histogram/temperature.1|histogram/temperature.2|histogram/temperature.3|histogram/temperature.4|histogram/temperature.5|histogram/temperature.6|histogram/temperature.7|histogram/temperature.8|histogram/temperature.9|histogram/temperature.10|histogram/temperature.11|histogram/temperature.12|histogram/temperature.13|histogram/temperature.14|histogram/temperature.15|histogram/temperature.16|histogram/temperature.17|histogram/temperature.18|histogram/temperature.19|histogram/temperature.20|histogram/temperature.21|histogram/temperature.22|histogram/temperature.23|histogram/temperature.24|histogram/temperature.25|histogram/temperature.26|histogram/temperature.27|histogram/temperature.28|histogram/temperature.29|histogram/temperature.30|histogram/temperature.31|histogram/temperature.32|histogram/temperature.33|histogram/temperature.34|histogram/temperature.35|histogram/temperature.36|histogram/temperature.37|histogram/temperature.38|histogram/temperature.39|histogram/temperature.40|histogram/temperature.41|histogram/temperature.42|histogram/temperature.43|histogram/temperature.44|histogram/temperature.45|histogram/temperature.46|histogram/temperature.47|histogram/temperature.48|histogram/temperature.49|histogram/temperature.50|histogram/temperature.51|histogram/temperature.52|histogram/temperature.53|histogram/temperature.54|histogram/temperature.55|histogram/temperature.56|histogram/temperature.57|histogram/temperature.58|histogram/temperature.59|histogram/temperature.60|histogram/temperature.61|histogram/temperature.62]','http://owfs.org/index.php?page=ds1921','histogram/temperature' )";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('45','21','DS1921','Thermochron temperature logging iButton.','[histogram/elements|histogram/gap]','http://owfs.org/index.php?page=ds1921','histogram')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('46','21','DS1921','Thermochron temperature logging iButton.','[log/date.ALL|log/elements|log/temperature.ALL|log/udate.ALL]','http://owfs.org/index.php?page=ds1921','log')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('47','21','DS1921','Thermochron temperature logging iButton.','[mission/date|mission/delay|mission/easystart|mission/frequency|mission/rollover|mission/running|mission/samples|mission/sampling|mission/udate','http://owfs.org/index.php?page=ds1921','mission')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('48','21','DS1921','Thermochron temperature logging iButton.','[overtemp/count.ALL|overtemp/count.0|overtemp/count.1|overtemp/count.2|overtemp/count.3|overtemp/count.4|overtemp/count.5|overtemp/count.6|overtemp/count.7|overtemp/count.8|overtemp/count.9|overtemp/count.10|overtemp/count.11]','http://owfs.org/index.php?page=ds1921','overtemp/count')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('49','21','DS1921','Thermochron temperature logging iButton.','[pages/page.ALL|pages/page.0|pages/page.1|pages/page.2|pages/page.3|pages/page.4|pages/page.5|pages/page.6|pages/page.7|pages/page.8|pages/page.9|pages/page.10|pages/page.11|pages/page.12]','http://owfs.org/index.php?page=ds1921','pages/page')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('50','21','DS1921','Thermochron temperature logging iButton.','[undertemp/date.ALL|undertemp/count.0|undertemp/count.1|undertemp/count.2|undertemp/count.3|undertemp/count.4|undertemp/count.5|undertemp/count.6|undertemp/count.7|undertemp/count.8|undertemp/count.9|undertemp/count.10|undertemp/count.11]','http://owfs.org/index.php?page=ds1921','undertemp/count')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('51','21','DS1921','Thermochron temperature logging iButton.','[undertemp/date.ALL|undertemp/date.0|undertemp/date.1|undertemp/date.2|undertemp/date.3|undertemp/date.4|undertemp/date.5|undertemp/date.6|undertemp/date.7|undertemp/date.8|undertemp/date.9|undertemp/date.10|undertemp/date.11]','http://owfs.org/index.php?page=ds1921','undertemp/date')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('52','21','DS1921','Thermochron temperature logging iButton.','[undertemp/end.ALL|undertemp/end.0|undertemp/end.1|undertemp/end.2|undertemp/end.3|undertemp/end.4|undertemp/end.5|undertemp/end.6|undertemp/end.7|undertemp/end.8|undertemp/end.9|undertemp/end.10|undertemp/end.11]','http://owfs.org/index.php?page=ds1921','undertemp/end')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('53','21','DS1921','Thermochron temperature logging iButton.','[undertemp/udate.ALL|undertemp/udate.0|undertemp/udate.1|undertemp/udate.2|undertemp/udate.3|undertemp/udate.4|undertemp/udate.5|undertemp/udate.6|undertemp/udate.7|undertemp/udate.8|undertemp/udate.9|undertemp/udate.10|undertemp/udate.11]','http://owfs.org/index.php?page=ds1921','undertemp/udate')";
DB::Prepare($sql, array(), DB::FETCH_TYPE_ROW);
$sql = "insert ignore into onewire (id, family_code, name, type, class, link, groupe) values('54','21','DS1921','Thermochron temperature logging iButton.','[undertemp/elements|undertemp/temperature]','http://owfs.org/index.php?page=ds1921','undertemp')";
}