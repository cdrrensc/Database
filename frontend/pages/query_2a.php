<h1>Query A</h1>

<blockquote>
	<p>Print the names of athletes who won medals at both summer and winter Olympics.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT A.NAME 
FROM ATHLETES A, MEDALS M1, MEDALS M2, EVENTS E1, EVENTS E2, GAMES G1, GAMES G2
WHERE A.AID = M1.AID 
AND A.AID = M2.AID 
AND M1.MID <> M2.MID 
AND E1.EID = M1.EID 
AND E2.EID = M2.EID 
AND G1.GID = E1.GID 
AND G2.GID = E2.GID 
AND G1.TYPE <> G2.TYPE";

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
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($entry = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $entry['NAME'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>