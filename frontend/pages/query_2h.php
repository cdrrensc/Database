<h1>Query H</h1>

<blockquote>
	<p>List all countries which didn't ever win a medal.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT C.NAME
FROM COUNTRIES C
WHERE C.CID NOT IN (
	SELECT M.CID
	FROM MEDALS M
	WHERE M.CID IS NOT NULL
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
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($country = $resultRaw->fetch()) {
		echo '<tr><td>' . $i++ . '</td><td>' . $country['NAME'] . '</td></tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>