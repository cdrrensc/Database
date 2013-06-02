<h1>Query V</h1>

<blockquote>
	<p>List top 10 countries according to their success on the events which appear at the Olympics for the first time. Present the list in the form of the medal table (as described for query I).</p>
</blockquote>

<?php
$query = "SELECT C.NAME, IOC.CODE,
	SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
	SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
	SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
FROM MEDALS_UNIQUE M, COUNTRIES C LEFT JOIN IOC_CODE IOC ON C.CID = IOC.CID, (
	SELECT E.EID
	FROM EVENTS E, GAMES G, (
		SELECT E.DID, MIN(G.YEAR) AS YEAR
		FROM EVENTS E, GAMES G
		WHERE E.GID = G.GID
		GROUP BY E.DID
	) FIRST_APPEARANCE
	WHERE E.GID = G.GID
	AND E.DID = FIRST_APPEARANCE.DID
	AND G.YEAR = FIRST_APPEARANCE.YEAR
	ORDER BY E.EID  
) EVENTS_TO_CONSIDER
WHERE M.CID = C.CID
AND M.EID = EVENTS_TO_CONSIDER.EID
GROUP BY M.CID
ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC
LIMIT 10";
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