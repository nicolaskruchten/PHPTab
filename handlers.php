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


# 1 = delete entry
# 2 = record entry
# 3 = deactivate a given target
# 4 = create a person
# 5 = create a macro

if(isset($_POST['sub']))
{
	if(($_POST["sub"] == 1) && deleteEntry($_POST["debtentryid"]))
	{
		# deleted
		$message = "Deleted OK!";
	}
	elseif($_POST["sub"]==1)
	{
		# couldn't delete
		$message = "NOT Deleted!";
	}
	elseif(($_POST["sub"]==2) && recordEntry($_POST["fromid"], $_POST["toid"], $_POST["comment"], $_POST["amount"]))
	{
		# recorded
		$message = "Recorded OK!";
	}
	elseif($_POST["sub"]==2)
	{
		# couldn't record
		$message = "NOT Recorded!";
	}
	elseif(($_POST["sub"]==3) && deactivateTarget($_POST["targetid"]))
	{
		# deactivated
		$message = "Deactivated OK!";
	}
	elseif($_POST["sub"]==3)
	{
		# couldn't deactivate
		$message = "NOT Deactivated!";
	}
	elseif(($_POST["sub"]==4) && addPerson($_POST["name"]))
	{
		# added
		$message = "Added OK!";
	}
	elseif($_POST["sub"]==4)
	{
		# couldn't add
		$message = "NOT Added!";
	}
	elseif($_POST["sub"]==5)
	{

		$ratioarray = array();
		$numratios = $_POST["numratios"];

		$ratiosum = 0;
		for($i=0; $i<$numratios; $i++)
		{
			$ratioarray[$i] = array($_POST["id" . $i], $_POST["ratio" . $i]);
			$ratiosum += $_POST["ratio" . $i];
		}

		for($i=0; $i<$numratios; $i++)
		{
			$ratioarray[$i][1] = $ratioarray[$i][1] / $ratiosum;
		}

		if(addMacro($_POST["name"], $ratioarray))
		{
			# added
			$message = "Added OK!";
		}
		else
		{
			# couldn't add
			$message = "NOT Added!";
		}
	}
}

?>