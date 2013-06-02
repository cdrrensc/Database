<h1>Query G</h1>

<blockquote>
	<p>For each Olympic Games print the name of the country with the most participants.</p>
</blockquote>

<?php
$query = "SELECT G.YEAR, G.TYPE, C.NAME, COUNT(*) AS COUNT 
FROM COUNTRIES C, PARTICIPANTS P, GAMES G 
WHERE G.GID = P.GID AND C.CID=P.CID 
GROUP BY P.GID, P.CID 
HAVING COUNT(*) >= ALL (
	SELECT COUNT(*) 
	FROM PARTICIPANTS P2 
	WHERE P.GID=P2.GID 
	GROUP BY P2.GID, P2.CID
)
ORDER BY G.YEAR"

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
			<th>Year</th>
			<th>Type</th>
			<th>Country</th>
			<th># participants</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($entry = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $entry['YEAR'] . '</td><td>' . $entry['TYPE'] . '</td><td>' . $entry['NAME'] . '</td><td>' . $entry['COUNT'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>