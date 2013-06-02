<h1>Query Q</h1>

<blockquote>
	<p>For each Olympic Games, list the name of the country which scored the largest percentage of the 
medals.</p>
</blockquote>

<?php
$query = "SELECT C.NAME,
ROUND(COUNT(*) / (
	SELECT COUNT(*) 
	FROM MEDALS M, EVENTS E 
	WHERE E.EID=M.EID 
	AND E.GID=?
) * 100, 2) AS PERCENT
FROM EVENTS E, MEDALS M, COUNTRIES C
WHERE E.GID=? 
AND E.EID=M.EID 
AND C.CID=M.CID
GROUP BY M.CID
ORDER BY PERCENT DESC
LIMIT 1";
?>

<a href="#sql" data-toggle="collapse">
  Show SQL
</a>
<div id="sql" class="collapse">
	For each game, we replace ? by the game's GID
	<pre>
<?php
echo $query;
?>
	</pre>
</div>

<div id="time_field">
Waiting for completion...
</div>

<table class="table table-striped">
<thead>
	<tr>
	<th>#</th>
	<th>Game</th>
	<th>Country</th>
	<th>%</th>
	</tr>
</thead>
<tbody>
<?php
$sum = 0;

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$gamesRaw = $SQL->query("SELECT G.TYPE, G.YEAR, G.HOST_CITY, G.GID FROM GAMES G ORDER BY G.YEAR");

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$sum += ($endtime - $starttime);

$i = 1;
while ($game = $gamesRaw->fetch()) {
	$statement = $SQL->prepare($query);
	$statement->bindParam(1, $game['GID']);
	$statement->bindParam(2, $game['GID']);

	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;

	$statement->execute();
	
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$sum += ($endtime - $starttime);

	echo '<tr>';
	echo '<td width="10%">' . $i++ . '</td>';
	echo '<td width="30%">' . $game['YEAR'] . ' ' . htmlspecialchars($game['TYPE']) . ' Olympics</td>';

	$dataCount = 0;
	while ($data = $statement->fetch()) {
		echo '<td width="50%">' . htmlspecialchars($data['NAME']) . '</td>';
		echo '<td width="10%">' . $data['PERCENT'] . '</td>';
		$dataCount++;
	}
	if ($dataCount == 0) {
    	echo '<td width="50%">-</td>';
    	echo '<td width="10%">-</td>';
	}
	echo '<tr>';
}

?>
</tbody>
</table>

<script type="text/javascript">
$('#time_field').text("<?php echo $i; ?> results fetched in <?php echo round($sum, 3); ?> seconds");
</script> 