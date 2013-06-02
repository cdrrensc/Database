<h1>Query J</h1>

<blockquote>
	<p>For each sport, list the 3 nations which have won the most medals.</p>
</blockquote>

<?php
$query = "SELECT C.NAME
FROM MEDALS_UNIQUE M, EVENTS E, DISCIPLINES D, COUNTRIES C
WHERE M.EID = E.EID
AND E.DID = D.DID
AND M.CID = C.CID
AND D.SID = ?
GROUP BY M.CID
ORDER BY COUNT(*) DESC
LIMIT 3";
?>

<a href="#sql" data-toggle="collapse">
  Show SQL
</a>
<div id="sql" class="collapse">
	For each sport, we replace ? by the sport's SID
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
	<th>Sport</th>
	<th>1st</th>
	<th>2nd</th>
	<th>3rd</th>
	</tr>
</thead>
<tbody>
<?php

$sum = 0;

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$sportsRaw = $SQL->query("SELECT S.NAME, S.SID FROM SPORTS S ORDER BY S.NAME");

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$sum += ($endtime - $starttime);

$i = 1;
while ($sport = $sportsRaw->fetch()) {
	$statement = $SQL->prepare($query);
	$statement->bindParam(1, $sport['SID']);

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

	echo '<tr><td width="10%">' . $i++ . '</td><td width="30%">' . $sport['NAME'] . '</td>';
	$countryCount = 0;
	while ($country = $statement->fetch()) {
		echo '<td width="20%">' . $country['NAME'] . '</td>';
		$countryCount++;
	}
	for (; $countryCount < 3; $countryCount++) {
		echo '<td width="20%">-</td>';
	}
	echo '<tr>';
}

?>
</tbody>
</table>

<script type="text/javascript">
$('#time_field').text("<?php echo $i; ?> results fetched in <?php echo round($sum, 3); ?> seconds");
</script> 