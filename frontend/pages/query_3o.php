<h1>Query O</h1>

<blockquote>
	<p>For all disciplines, compute the country which waited the most between two successive medals.</p>
</blockquote>

<?php
$query = "SELECT D.NAME AS DNAME, C.NAME AS CNAME, MAX(G2.YEAR-G1.YEAR) AS TIME
FROM COUNTRIES C, EVENTS E1, EVENTS E2, GAMES G1, GAMES G2, DISCIPLINES D, MEDALS M1, MEDALS M2
WHERE M1.EID = E1.EID
AND M2.EID = E2.EID
AND E1.EID <> E2.EID
AND M1.CID = M2.CID
AND M1.CID = C.CID
AND E1.DID = E2.DID
AND E1.DID = D.DID
AND D.DID = ?
AND E1.GID = G1.GID
AND E2.GID = G2.GID
AND G1.YEAR < G2.YEAR
GROUP BY C.NAME
ORDER BY TIME DESC LIMIT 1";


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

<div id="time_field">
Waiting for completion...
</div>

<table class="table table-striped">
<thead>
	<tr>
	<th>#</th>
	<th>Discipline</th>
	<th>Country</th>
	<th>Time</th>
	</tr>
</thead>
<tbody>
<?php
$sum = 0;

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$disciplinesRaw = $SQL->query("SELECT D.DID FROM DISCIPLINES D ORDER BY D.NAME");

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$sum += ($endtime - $starttime);

$i = 1;
while ($discipline = $disciplinesRaw->fetch()) {
	$statement = $SQL->prepare($query);
	$statement->bindParam(1, $discipline['DID']);

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
	
	while ($data = $statement->fetch()) {
		echo '<tr>';
		echo '<td>' . $i++ . '</td>';
		echo '<td>' . $data['DNAME'] . '</td>';
		echo '<td>' . $data['CNAME'] . '</td>';
		echo '<td>' . $data['TIME'] . '</td>';
		echo '</tr>';
	}
}

?>
</tbody>
</table>

<script type="text/javascript">
$('#time_field').text("<?php echo $i; ?> results fetched in <?php echo round($sum, 3); ?> seconds");
</script> 