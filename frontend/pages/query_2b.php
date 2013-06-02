<h1>Query B</h1>

<blockquote>
	<p>Print the names of gold medalists in sports which appeared only once at the Olympics.</p>
</blockquote>

<?php
$query = "SELECT A.NAME AS ANAME, S.NAME AS SNAME
FROM SPORTS S, DISCIPLINES D, EVENTS E, ATHLETES A, MEDALS M
WHERE A.AID = M.AID 
AND M.TYPE = 'Gold' 
AND M.EID = E.EID 
AND E.DID = D.DID 
AND D.SID = S.SID 
AND S.SID IN (
    SELECT S2.SID
    FROM SPORTS S2, DISCIPLINES D2, EVENTS E2, GAMES G
    WHERE S2.SID = D2.SID 
    AND D2.DID = E2.DID 
    AND E2.GID = G.GID
    GROUP BY S2.SID
    HAVING Count(*)=1
)
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
			<th>Sport</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($entry = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . htmlspecialchars($entry['ANAME']) . '</td><td>' . htmlspecialchars($entry['SNAME']) . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>