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

<h3>Add a Person</h3>

<form method="post" action="index.php">
	<input type="hidden" name="sub" value="4">
	Name: <input type="text" name="name"> <input type="submit" value="create" class="button">
</form>

<hr width="50%">
<h3>Add a Macro</h3>


<form method="post" action="index.php">
	<input type="hidden" name="sub" value="5">
	Name: <input type="text" name="name"> 

	<table>
	<tr><td colspan="2">Splits: </td></tr>
	<?php 
		$result = mysql_query("select * from " . $dbtableprefix . "debttargets where type='person' and active='yes'");
		$numratios = mysql_num_rows($result);
		$i = 0;
		while($row = mysql_fetch_array($result))
		{
	?>
		<tr><td align="right"><?php echo $row["name"] ?> <input type="hidden" name="id<?php echo $i ?>" value="<?php echo $row["targetid"] ?>"></td><td><input type="text" name="ratio<?php echo $i ?>" size="2"></td></tr>
	<?php 
		$i++;
		} 
	?>
	</table>
	<input type="hidden" name="numratios" value="<?php echo $numratios ?>">
	<input type="submit" value="create" class="button">
</form>

<hr width="50%">
<h3>Deactivate a Person</h3>

<p>Only people with a zero balance may be removed from the system.<br />Any macro pointing to them will also be deactivated.</p>

<form method="post" action="index.php">
	<input type="hidden" name="sub" value="3">
<select name="targetid">
<?php 
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where active='yes' and type='person'");
while($row = mysql_fetch_array($result))
{

	$result2 = mysql_query("select (sum(r.fraction * e.amount)), 1 as rank from " . $dbtableprefix . "debtratios as r, " . $dbtableprefix . "debtentries as e where r.targetid=" . $row[targetid] . " and e.toid=r.macroid UNION select sum(e.amount), 2 as rank from " . $dbtableprefix . "debtentries as e where e.fromid=" . $row[targetid] . " order by rank");
	
	$in = mysql_fetch_array($result2);
	$out = mysql_fetch_array($result2);

	if(number_format($out[0] - $in[0], 2) == "0.00")
	{
?>

<option value="<?php echo $row["targetid"] ?>"><?php echo $row["name"] ?></option>

<?php } } ?>
</select> <input type="submit" value="deactivate" class="button">
</form>

<hr width="50%">
<h3>Deactivate a Macro</h3>


<form method="post" action="index.php">
	<input type="hidden" name="sub" value="3">
<select name="targetid">
<?php 
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where active='yes' and type='macro'");
while($row = mysql_fetch_array($result))
{
?>

<option value="<?php echo $row["targetid"] ?>"><?php echo $row["name"] ?></option>

<?php } ?>
</select> <input type="submit" value="deactivate" class="button">
</form>
</div>
