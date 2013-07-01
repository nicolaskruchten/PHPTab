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
<div class="row">
  <div class="span4 offset2">
<h4>New Transaction</h4>
		<form method="post" action="index.php" class="form-horizontal">
		<input type="hidden" name="sub" value=2>

		<!-- show from/to table and form -->
		<div class="control-group">
		    <label class="control-label" for="date">Date</label>
		    <div class="controls">
		      <input class="input-medium" type="date" id="date" name="date" value="<?php echo date("Y-m-d") ?>" >
		    </div>
		</div>

		<div class="control-group">
		    <label class="control-label">From</label>
		    <div class="controls">
		    	<?php
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where type='person' and active='yes'");
while($personrow = mysql_fetch_array($result))
{
?> 
				<label class="radio inline">
					<input type=radio name="fromid" value="<?php echo $personrow['targetid'] ?>" > 
					<?php echo $personrow['name'] ?>
				</label>
<?php } ?>
		    </div>
		</div>

		<div class="control-group">
		    <label class="control-label">To</label>
		    <div class="controls">
		<?php
$result = mysql_query("select * from " . $dbtableprefix . "debttargets where active='yes' order by type desc");
while($personrow = mysql_fetch_array($result))
{
?> 
				<label class="radio inline">
					<input type=radio name="toid" value="<?php echo $personrow['targetid'] ?>" >
					<?php echo $personrow['name'] ?>
				</label>
<?php } ?>
		    </div>
		</div>
<?php
$result = mysql_query("select distinct category from " . $dbtableprefix . "debtentries");
$cats = array();
while($c = mysql_fetch_array($result))
{ $cats[] = '"'.$c["category"].'"'; }
?> 
		<div class="control-group">
		    <label class="control-label" for="category">Category</label>
		    <div class="controls">
		      <input class="input-medium" type="text" id="category" name="category"
		      data-provide="typeahead" data-items="4" autocomplete="off" 
		      data-source='[<?php echo join($cats, ",")?>]' >
		    </div>
		</div>
		<div class="control-group">
		    <label class="control-label" for="comment">Comment</label>
		    <div class="controls">
		      <input class="input-medium" type="text" id="comment" name="comment">
		    </div>
		</div>
		<div class="control-group">
		    <label class="control-label" for="amount">Amount</label>
		    <div class="controls">
		     	<input class="input-medium" type="number" name="amount" id="amount" step="0.01" autocomplete="off" >
		    </div>
		</div>  
		<div class="control-group">
		    <div class="controls">
		      <button type="submit" class="btn">save</button>
		    </div>
		</div>

		</form>




	</div>
  	<div class="span2 offset1">

<h4>Standings</h4>

		<!-- show owings table -->
		<table class="table table-striped table-bordered" style="width: 200px; margin: 0 auto;">
			<thead>
			<tr>
				<th>name</th>
				<th>owed to</th>
			</tr>
		</thead>
		<tbody>	
<?php 
$plusminus = 0;
$result1 = mysql_query("select * from " . $dbtableprefix . "debttargets where type='person' and active='yes'");
while($personrow = mysql_fetch_array($result1))
{
	$result2 = mysql_query("select (sum(r.fraction * e.amount)), 1 as rank from " . $dbtableprefix . "debtratios as r, " . $dbtableprefix . "debtentries as e where r.targetid=" . $personrow['targetid'] . " and e.toid=r.macroid UNION select sum(e.amount), 2 as rank from " . $dbtableprefix . "debtentries as e where e.fromid=" . $personrow['targetid'] . " order by rank");
	
	$in = mysql_fetch_array($result2);
	$out = mysql_fetch_array($result2);

	if(($out[0] - $in[0]) < 0)
		{$thecolour='red';}
	else
		{$thecolour='black'; $plusminus += ($out[0] - $in[0]);}
?>
			<tr>
				<td><?php echo $personrow["name"];?></td>
				<td align=right><span style="color:<?php echo $thecolour; ?>;">$<?php echo number_format(($out[0] - $in[0]),2);?></span></td>
			</tr>
<?php } ?>
		</tbody>

		<tfoot>
			<tr>
				<th>+/-</th>
				<th align=right>$<?php echo number_format($plusminus,2) ?></th>
			</tr>
		</tfoot>
		</table>
		</td>
	</tr>
</table>

</div>
</div>


<div class="row hidden-phone">
  <div class="span10 offset1">
<?php

$selectquery = "select e.*, a.name as fromname, b.name as toname, a.active as fromactive, b.active as toactive from " . $dbtableprefix . "debtentries as e, " . $dbtableprefix . "debttargets as a, " . $dbtableprefix . "debttargets as b where e.fromid=a.targetid and e.toid=b.targetid order by thedate desc";
$result=mysql_query($selectquery, $db); 

?>

<h4>Transactions</h4>

<table class="table table-striped table-bordered">

	<tr>
		<th width=100>Date</th>
		<th width=75>From</th>
		<th width=75>To</th>
		<th width=75>Amount</th>
		<th width=100>Category</th>
		<th width=200>Comment</th>
		<th width=20>Delete</th>
	</tr>

<?php while ($therow = mysql_fetch_array($result))
{ ?>
	<tr valign=top>
		<td><?php echo nicedate($therow['thedate']); ?></td>
		<td><?php echo $therow['fromname']; ?></td>
		<td><?php echo $therow['toname']; ?></td>
		<td align=right>$<?php echo number_format($therow['amount'], 2); ?></td>
		<td><?php echo $therow['category']; ?></td>	
		<td><?php echo $therow['comment']; ?></td>	
		<?php if(($therow["fromactive"] == "yes") && ($therow["toactive"] == "yes"))
		{
		?>
		<form method="post" action="index.php">
		<input type="hidden" name="sub" value="1">
		<input type="hidden" name="debtentryid" value="<?php echo $therow['debtentryid']?>"><td><input type="submit" value="delete" class="btn"></td></form>
		<?php } else { ?>
		
		<td></td>

		<?php } ?>
	</tr>
<?php } ?>

</table>
</div>
</div>
