<h1>Query N</h1>

<blockquote>
	<p>List all nations whose first medal was gold, all nations whose first medal was silver and all nations whose first medal was bronze. If nation won more than one medal at the first Olympics it won a medal, consider that it won the "shinier" medal first. For example if a country didn't win any medals before games in 1960 and then it won a gold and a bronze, then its first medal is a gold.</p>
</blockquote>

<?php
$query = "SELECT C.NAME, 
case MIN(case M.TYPE 
		WHEN 'Gold' THEN 1 
		WHEN 'Silver' THEN 2 
		WHEN 'Bronze' THEN 3 
		end) 
	WHEN 1 THEN 'Gold' 
	WHEN 2 THEN 'Silver' 
	WHEN 3 THEN 'Bronze' 
end AS TYPE, G.YEAR
FROM COUNTRIES C, MEDALS M, EVENTS E, GAMES G
WHERE M.CID = C.CID
AND M.EID = E.EID
AND E.GID = G.GID
GROUP BY C.NAME
HAVING G.YEAR = MIN(G.YEAR)
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
		<th>Medal</th>
		<th>Year</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($country = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $country['NAME'] . '</td><td>' . $country['TYPE'] . '</td><td>' . $country['YEAR'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>