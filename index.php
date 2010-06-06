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

include("dbinfo.php"); # create db connection, set table prefix
include("dbcreation.php"); # create tables if not already there
include("helpers.php"); # functions to handle all form submissions
include("handlers.php"); # detect form submission, pass off to right helper, set message


?>

<html>
<head>
	<title>PHPTab</title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>

<?php
if($_POST["sub"] != 0) # a form was submitted
{
	include("showmessage.php");
} 
elseif($_POST["mode"] == "edit")
{
	include("edittargets.php");
}
elseif($_POST["mode"] == "macros")
{
	include("showmacros.php");
}
else
{ 
	include("mainpage.php");
} 
?>
</body>
</html>