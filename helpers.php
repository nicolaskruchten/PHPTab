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


function nicedate($olddate)
{
	$ayear = 0;
	$amonth = 0;
	$aday = 0;

	list($ayear, $amonth, $aday, $a, $b, $c) = sscanf($olddate,"%d-%d-%d %d:%d:%d");

	$changestamp = mktime(0, 0, 1, $amonth, $aday, $ayear);
	return date("M d", $changestamp);
}

function deleteEntry($debtentryid)
{
	global $dbtableprefix;
	return mysql_query("delete from " . $dbtableprefix . "debtentries where debtentryid=$debtentryid");
}

function recordEntry($fromid, $toid, $comment, $amount)
{
	global $dbtableprefix;
	return mysql_query("insert into " . $dbtableprefix . "debtentries (debtentryid, thedate, fromid, toid, comment, amount) values (null, '" . date("Y-m-d H:i:s") . "', $fromid, $toid, '$comment', $amount)");
}

function deactivateTarget($targetid)
{
	global $dbtableprefix;
	return (mysql_query("update " . $dbtableprefix . "debttargets, " . $dbtableprefix . "debtratios set " . $dbtableprefix . "debttargets.active='no' where " . $dbtableprefix . "debtratios.targetid=$targetid and " . $dbtableprefix . "debtratios.macroid=" . $dbtableprefix . "debttargets.targetid") && mysql_query("update " . $dbtableprefix . "debttargets set active='no' where targetid=$targetid"));
}

function addPerson($name)
{
	global $dbtableprefix;
	if(!mysql_query("insert into " . $dbtableprefix . "debttargets (targetid, name, type, active) values (null, '$name', 'person', 'yes')"))
	{
		return false;
	}
	
	$newtargetid = mysql_insert_id();

	if(!mysql_query("insert into " . $dbtableprefix . "debtratios (ratioid, macroid, targetid, fraction) values (null, $newtargetid, $newtargetid, 1)"))
	{
		return false;
	}

	return true;
}

function addMacro($name, $ratioarray)
{
	global $dbtableprefix;
	if(!mysql_query("insert into " . $dbtableprefix . "debttargets (targetid, name, type, active) values (null, '$name', 'macro', 'yes')"))
	{
		return false;
	}

	$newtargetid = mysql_insert_id();

	foreach($ratioarray as $key => $value)
	{
		if($value[1])
		{	
			if(!mysql_query("insert into " . $dbtableprefix . "debtratios (ratioid, macroid, targetid, fraction) values (null, $newtargetid, " . $value[0] . ", " . $value[1] . ")"))
			{
				mysql_query("delete from " . $dbtableprefix . "debttargets where targetid=$newtargetid");
				# note: any previously inserted ratios are NOT deleted but they shouldn't impact calculations as the target is gone...
				return false;
			}
		}
	}

	return true;
}

?>