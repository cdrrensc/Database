<h1>Query D</h1>

<blockquote>
	<p>Print the name of the country which won the most medals in summer Olympics and the country which won the most medals in winter Olympics.</p>
</blockquote>

<?php
$query = "(
	SELECT COUNT(M.CID) AS MAXIMUM, C.NAME, G.TYPE
	FROM COUNTRIES C, GAMES G, EVENTS E, MEDALS_UNIQUE M
	WHERE C.CID = M.CID 
	AND M.EID = E.EID 
	AND E.GID = G.GID 
	AND G.TYPE = 'Summer'
	GROUP BY C.NAME 
	ORDER BY MAXIMUM DESC 
	LIMIT 1
) UNION (
	SELECT COUNT(M.CID) AS MAXIMUM, C.NAME, G.TYPE
	FROM COUNTRIES C, GAMES G, EVENTS E, MEDALS_UNIQUE M
	WHERE C.CID = M.CID 
	AND M.EID = E.EID 
	AND E.GID = G.GID 
	AND G.TYPE = 'Winter'
	GROUP BY C.NAME 
	ORDER BY MAXIMUM DESC 
	LIMIT 1
)";

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

$resultRaw = $SQL->query($query) or die(var_dump($SQL->errorInfo()));

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
			<th>Game type</th>
			<th>Country</th>
			<th># Medals</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($entry = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $entry['TYPE'] . '</td><td>' . $entry['NAME'] . '</td><td>' . $entry['MAXIMUM'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>