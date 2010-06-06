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

<table width=80% align=center>
	<tr>
		<td valign=top align="center" width="50%">
		<table>
		<tr>
		<td valign=middle align=left>
		<form method="post" action="index.php">
		<input type="hidden" name="sub" value=2>

		<!-- show from/to table and form -->
		<table>
		<tr>
			<td align=right valign=top><b>From:</b></td>
			<td>
<?php
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where type='person' and active='yes'");
while($personrow = mysql_fetch_array($result))
{
?> 
				<input type=radio name="fromid" value="<?= $personrow[targetid] ?>" > <?= $personrow[name] ?> <br> 
<?php } ?>
			</td>
		</tr>
		<tr>
			<td align=right valign=top><b>To:</b></td>
			<td>
<?php
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where active='yes' order by type");
while($personrow = mysql_fetch_array($result))
{
?> 
				<input type=radio name="toid" value="<?= $personrow[targetid] ?>" > <?= $personrow[name] ?> <br> 
<?php } ?>
			</td>
		</tr>
		<tr>
			<td align=right><b>Comment:</b></td>
			<td><input type=text name="comment"></td>
		</tr>
		<tr>
			<td align=right><b>Amount:</b></td>
			<td><input type=text name="amount"></td>
		</tr>
		<tr>
			<td align=right>&nbsp;</td>
			<td><input type="submit" value="store entry" class="button"></td>
		</tr>
		</table>
		</form>
		</td>
		</tr>
		</table>


		</td>
		<td valign=middle align="center">

		<!-- show owings table -->
		<table border=1 cellpadding=8>
			<tr>
				<td colspan=2 align=center><b><i>debt</i></b></td>
			</tr>
			<tr>
				<td align=center>name</td>
				<td align=center>owed to</td>
			</tr>
			
<?php 
$plusminus = 0;
$result1 = mysql_query("select * from " . $dbtableprefix . "debttargets where type='person' and active='yes'");
while($personrow = mysql_fetch_array($result1))
{
	$result2 = mysql_query("select (sum(r.fraction * e.amount)), 1 as rank from " . $dbtableprefix . "debtratios as r, " . $dbtableprefix . "debtentries as e where r.targetid=" . $personrow[targetid] . " and e.toid=r.macroid UNION select sum(e.amount), 2 as rank from " . $dbtableprefix . "debtentries as e where e.fromid=" . $personrow[targetid] . " order by rank");
	
	$in = mysql_fetch_array($result2);
	$out = mysql_fetch_array($result2);

	if(($out[0] - $in[0]) < 0)
		{$thecolour='red';}
	else
		{$thecolour='black'; $plusminus += ($out[0] - $in[0]);}
?>
			<tr>
				<td><b><?= $personrow["name"];?></b></td>
				<td align=right><span style="color:<?= $thecolour; ?>;">$<?= number_format(($out[0] - $in[0]),2);?></span></td>
			</tr>
<?php } ?>
			<tr>
				<td><i><b>+/-</b></i></td>
				<td align=right><i>$<?= number_format($plusminus,2) ?></i></td>
			</tr>
		</table>
		</td>
	</tr>
</table>

<table align="center">
	<tr>
		<td>
			<form method="post" action="index.php">
				<input type="hidden" name="mode" value="edit">
				<input type="submit" value="edit targets" class="button">
			</form>
		</td>
		<td>
			<form method="post" action="index.php">
				<input type="hidden" name="mode" value="macros">
				<input type="submit" value="view macros" class="button">
			</form>
		</td>
	</tr>
</table>
		<!-- show transaction list -->
<br>
<?php

$selectquery = "select e.*, a.name as fromname, b.name as toname, a.active as fromactive, b.active as toactive from " . $dbtableprefix . "debtentries as e, " . $dbtableprefix . "debttargets as a, " . $dbtableprefix . "debttargets as b where e.fromid=a.targetid and e.toid=b.targetid order by thedate desc";
$result=mysql_query($selectquery, $db); 

?>
<table cellpadding=5 border=1 align=center>
<tr><td colspan=6><i><?php echo mysql_num_rows($result); ?> transactions</i></td></tr>

	<tr>
		<td width=100><b><i>date</i></b></td>
		<td width=75><b><i>from</i></b></td>
		<td width=75><b><i>to</i></b></td>
		<td width=75><b><i>amount</i></b></td>
		<td width=300><b><i>comment</i></b></td>
		<td><b><i>delete</i></b></td>
	</tr>

<?php while ($therow = mysql_fetch_array($result))
{ ?>
	<tr valign=top>
		<td><?php echo nicedate($therow[thedate]); ?></td>
		<td><?php echo $therow[fromname]; ?></td>
		<td><?php echo $therow[toname]; ?></td>
		<td align=right>$<?= number_format($therow[amount], 2); ?></td>
		<td><?php echo $therow[comment]; ?></td>	
		<?php if(($therow["fromactive"] == "yes") && ($therow["toactive"] == "yes"))
		{
		?>
		<form method="post" action="index.php">
		<input type="hidden" name="sub" value="1">
		<input type="hidden" name="debtentryid" value="<?= $therow[debtentryid]?>"><td><input type="submit" value="delete" class="button"></td></form>
		<?php } else { ?>
		
		<td></td>

		<?php } ?>
	</tr>
<?php } ?>

</table>
<br />
