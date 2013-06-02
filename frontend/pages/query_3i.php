<h1>Query I</h1>

<blockquote>
	<p>Compute medal table for the specific Olympic Games supplied by the user. Medal table should contain country's IOC code followed by the number of gold, silver, bronze and total medals. It should first be sorted by the number of gold, then silvers and finally bronzes.</p>
</blockquote>

<form class="form-horizontal" method="post" action="/?p=query_3i">
	<div class="control-group">
		<label class="control-label" for="inputName">Game</label>
		<div class="controls">
			<select name="gid">
			<option value="">All</option>
			<?php
			$resultRaw = $SQL->query("SELECT GID, TYPE, YEAR FROM GAMES ORDER BY YEAR");
			while($game = $resultRaw->fetch()) {
				if ($game['GID'] == $_POST['gid']) {
					echo '<option value="' . $game['GID'] . '" selected="selected">' . $game['YEAR'] . ' - ' . htmlspecialchars($game['TYPE']) . '</option>';
				} else {
					echo '<option value="' . $game['GID'] . '">' . $game['YEAR'] . ' - ' . htmlspecialchars($game['TYPE']) . '</option>';
				}
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-actions">
    	<button type="submit" class="btn btn-primary">Show</button>
    </div>
</form>

<?php



/*$query = "SELECT IOC.CODE,
	SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
	SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
	SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
FROM (SELECT M1.MID, M1.TYPE, M1.CID FROM MEDALS M1 GROUP BY M1.EID, M1.TYPE) M, IOC_CODE IOC
WHERE M.CID = IOC.CID
GROUP BY M.CID
ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC";

if (is_numeric($_POST['gid'])) {
	$query = "SELECT IOC.CODE,
	SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
	SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
	SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
FROM (SELECT M1.MID, M1.TYPE, M1.CID, M1.EID FROM MEDALS M1 GROUP BY M1.EID, M1.TYPE) M, IOC_CODE IOC, EVENTS E
WHERE M.CID = IOC.CID
AND M.EID = E.EID
AND E.GID = " . $SQL->quote($_POST['gid']) . "
GROUP BY M.CID
ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC";
}*/

$query = "SELECT C.NAME, IOC.CODE,
	SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
	SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
	SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
FROM MEDALS_UNIQUE M, COUNTRIES C LEFT JOIN IOC_CODE IOC ON C.CID = IOC.CID
WHERE M.CID = C.CID
GROUP BY M.CID
ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC";

if (is_numeric($_POST['gid'])) {

	$query = "SELECT C.NAME, IOC.CODE,
	SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
	SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
	SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
FROM MEDALS_UNIQUE M, EVENTS E, COUNTRIES C LEFT JOIN IOC_CODE IOC ON C.CID = IOC.CID
WHERE M.CID = C.CID
AND M.EID = E.EID
AND E.GID = " . $SQL->quote($_POST['gid']) . "
GROUP BY M.CID
ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC";
}
?>

<a href="#sql" data-toggle="collapse">
  Show SQL
</a>
<div id="sql" class="collapse">
	<pre>
<?php
echo $query;
?>
	</pre>
</div>

<?php
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$resultRaw = $SQL->query($query);

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$sqltime = ($endtime - $starttime);


echo $resultRaw->rowCount() . ' results fetched in ' . round($sqltime, 3) . ' seconds';

if ($resultRaw->rowCount() > 0) {
	
	?>
	<table class="table table-striped">
	<thead>
		<tr>
		<th>#</th>
		<th>Country</th>
		<th>IOC</th>
		<th># Gold</th>
		<th># Silver</th>
		<th># Bronze</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($data = $resultRaw->fetch()) {
		echo '<tr>';
		echo '<td width="10%">' . $i++ . '</td>';
		echo '<td width="40%">' . htmlspecialchars($data['NAME']) . '</td>';
		echo '<td width="20%">' . htmlspecialchars($data['CODE']) . '</td>';
		echo '<td width="10%">' . $data['COUNT_GOLD'] . '</td>';
		echo '<td width="10%">' . $data['COUNT_SILVER'] . '</td>';
		echo '<td width="10%">' . $data['COUNT_BRONZE'] . '</td>';
		echo '</tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>