<h1>Query C</h1>

<blockquote>
	<p>For each country print the place where it won its first medal.</p>
</blockquote>

<?php
/*$query = "SELECT DISTINCT C.NAME, G.HOST_CITY
FROM COUNTRIES C, MEDALS M, EVENTS E, GAMES G
WHERE C.CID = M.CID
AND M.EID = E.EID
AND E.GID = G.GID
AND G.YEAR = (
	SELECT MIN( G2.YEAR ) 
	FROM MEDALS M2, EVENTS E2, GAMES G2
	WHERE C.CID = M2.CID
	AND M2.EID = E2.EID
	AND E2.GID = G2.GID
	GROUP BY C.CID
)
ORDER BY C.NAME"*/

$query = "SELECT DISTINCT C.NAME, G.HOST_CITY
FROM COUNTRIES C, MEDALS M, EVENTS E, GAMES G, (
	SELECT C2.CID, MIN( G2.YEAR ) AS MIN_YEAR
	FROM COUNTRIES C2, MEDALS M2, EVENTS E2, GAMES G2
	WHERE C2.CID = M2.CID
	AND M2.EID = E2.EID
	AND E2.GID = G2.GID
	GROUP BY C2.CID
) TMP
WHERE C.CID = M.CID
AND M.EID = E.EID
AND E.GID = G.GID
AND C.CID = TMP.CID
AND G.YEAR = TMP.MIN_YEAR
ORDER BY C.NAME";

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
			<th>City</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($entry = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $entry['NAME'] . '</td><td>' . $entry['HOST_CITY'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>