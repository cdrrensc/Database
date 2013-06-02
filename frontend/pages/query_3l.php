<h1>Query L</h1>

<blockquote>
	<p>List top 10 nations according to their success in team sports. Use average number of medalists for each medal awarded to a particular nation.</p>
</blockquote>

<?php
$query = "SELECT C.NAME, MEDALISTS_COUNT.COUNT / MEDALS_COUNT.COUNT AS MEDALISTS_PER_MEDAL 
FROM COUNTRIES C, (
	SELECT M.CID, COUNT(*) AS COUNT
	FROM MEDALS M
	GROUP BY M.CID
) MEDALISTS_COUNT,
(
	SELECT MU.CID, COUNT(*) AS COUNT
	FROM MEDALS_UNIQUE MU
	GROUP BY MU.CID
) MEDALS_COUNT
WHERE MEDALISTS_COUNT.CID = MEDALS_COUNT.CID
AND MEDALISTS_COUNT.CID = C.CID
ORDER BY MEDALISTS_PER_MEDAL DESC
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
		<th>Average number of medalists per medal</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($country = $resultRaw->fetch()) {
		echo '<tr>';
		echo '<td>' . $i++ . '</td>';
		echo '<td>' . htmlspecialchars($country['NAME']) . '</td>';
		echo '<td>' . $country['MEDALISTS_PER_MEDAL'] . '</td>';
		echo '</tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>