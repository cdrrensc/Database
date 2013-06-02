<h1>Query U</h1>

<blockquote>
	<p>List names of all events and Olympic Games for which the individual or team has defended a title from the previous games.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT D.NAME, C.NAME AS CNAME, G.TYPE, G.YEAR AS YEAR1, G2.YEAR AS YEAR2, M.TYPE AS MTYPE
FROM MEDALS M, EVENTS E, GAMES G, DISCIPLINES D, COUNTRIES C, GAMES G2, EVENTS E2, MEDALS M2
WHERE M.TYPE = 'Gold'
AND M.EID = E.EID
AND G.GID = E.GID
AND C.CID = M.CID
AND D.DID = E.DID
AND M2.TYPE = 'Gold'
AND M2.EID = ( 
	SELECT E1.EID
	FROM GAMES G1, 
	EVENTS E1
	WHERE G1.YEAR < G.YEAR
	AND G1.TYPE = G.TYPE
	AND E1.GID = G1.GID
	AND E1.DID = E.DID
	AND NOT EXISTS (
		SELECT * 
		FROM GAMES G2
		WHERE G2.YEAR < G.YEAR
		AND G2.YEAR > G1.YEAR
		AND G2.TYPE = G.TYPE
	)
	ORDER BY G1.YEAR DESC 
	LIMIT 1 
)
AND E2.EID = M2.EID
AND G2.GID = E2.GID
AND M.CID = M2.CID";
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
		<th>Event</th>
		<th>Previous Event</th>
		<th>Country</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($data = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . htmlspecialchars($data['NAME']) . ' at the ' . htmlspecialchars($data['TYPE']) . ' ' . $data['YEAR1'] . ' Olympics</td><td>' . htmlspecialchars($data['NAME']) . ' at the ' . htmlspecialchars($data['TYPE']) . ' ' . $data['YEAR2'] . ' Olympics</td><td>' . htmlspecialchars($data['CNAME']) . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>