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
?>
<div align="center">
<form method="post" action="index.php">
<input type="submit" value="main page" class="button">
</form>

<h3>Existing Macros</h3>


<?php 
	
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where type='macro'");
while($row = mysql_fetch_array($result))
{

?>

<table border="1" cellpadding=5>
<tr><td colspan="2" align="center"><b><i><?=$row[name]?></i></b>

<?php 
if($row["active"] == "no")
{ ?>
	<br>(deactivated)
<?php }
?>
</td><tr>

<?php 
	$result2 = mysql_query("select * from " . $dbtableprefix . "debtratios as a, " . $dbtableprefix . "debttargets as b where a.macroid=" . $row[targetid] . " and a.targetid=b.targetid");
	while($row2 = mysql_fetch_array($result2))
	{
?>

<tr><td><?= $row2[name] ?> </td><td> <?= number_format(100*$row2[fraction], 1)?>%</td></tr>

<?php } ?>

</table>
<br />

<?php 
}	
?>
</div>
