<?php
/*

PHPTab 2.0 Copyright Nicolas Kruchten 2004

    This file is part of PHPTab.

    PHPTab is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    PHPTab is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHPTab; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

$query = "CREATE TABLE IF NOT EXISTS `" . $dbtableprefix . "debtentries` (  `debtentryid` int(11) NOT NULL auto_increment,  `thedate` datetime default NULL,  `fromid` int(11) default NULL,  `toid` int(11) default NULL,  `category` tinytext NOT NULL,  `comment` tinytext NOT NULL,  `amount` double NOT NULL default '0',  PRIMARY KEY  (`debtentryid`))";

mysql_query($query);

$query = "CREATE TABLE IF NOT EXISTS `" . $dbtableprefix . "debtratios` (  `ratioid` int(11) NOT NULL auto_increment,  `macroid` int(11) NOT NULL default '0',  `targetid` int(11) NOT NULL default '0',  `fraction` float NOT NULL default '0',  PRIMARY KEY  (`ratioid`))";

mysql_query($query);

$query = "CREATE TABLE IF NOT EXISTS `" . $dbtableprefix . "debttargets` (  `targetid` int(11) NOT NULL auto_increment,  `type` enum('person','macro') NOT NULL default 'person',  `name` tinytext NOT NULL,  `active` enum('yes','no') NOT NULL default 'yes',  PRIMARY KEY  (`targetid`))";

mysql_query($query);

?>