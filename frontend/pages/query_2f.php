<h1>Query F</h1>

<blockquote>
	<p>List names of all athletes who competed for more than one country.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT(A.NAME)
FROM ATHLETES A, PARTICIPANTS P1, PARTICIPANTS P2, COUNTRIES C1, COUNTRIES C2
WHERE A.AID = P1.AID 
AND A.AID = P2.AID 
AND P1.PID <> P2.PID 
AND P1.CID = C1.CID 
AND P2.CID = C2.CID 
AND C1.CID <> C2.CID
ORDER BY A.NAME";

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
			<th>Athlete</th>
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