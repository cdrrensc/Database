<h1>Query P</h1>

<blockquote>
	<p>List all events for which all medals are won by athletes from the same country.</p>
</blockquote>

<?php
$query = "SELECT D.NAME, G.TYPE, G.YEAR
FROM (
	SELECT E.EID, COUNT(DISTINCT M.CID) AS NBR
	FROM EVENTS E, MEDALS M
	WHERE M.EID = E.EID
	GROUP BY E.EID
) COUNTRIES_COUNTS, 
EVENTS E, DISCIPLINES D, GAMES G
WHERE E.EID = COUNTRIES_COUNTS.EID
AND COUNTRIES_COUNTS.NBR = 1
AND E.DID = D.DID
AND E.GID = G.GID
ORDER BY G.YEAR";
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
	<div class="row">
		<div class="span6">
			<table class="table table-striped">
			<thead>
				<tr>
				<th>#</th>
				<th>Event</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			while($country = $resultRaw->fetch()) {
				echo '<tr><td>' . $i++ . '</td><td>' . htmlspecialchars($country['NAME']) . ' at the ' . htmlspecialchars($country['TYPE']) . ' ' . $country['YEAR'] . ' Olympics</td></tr>';
				if ($i == ceil($resultRaw->rowCount() / 2) + 1) {
					?>
			</tbody>
			</table>
		</div>
		<div class="span6">
			<table class="table table-striped">
			<thead>
				<tr>
				<th>#</th>
				<th>Events</th>
				</tr>
			</thead>
			<tbody>
					<?php
				}
			}
			
			?>
			</tbody>
			</table>
		</div>
	</div>
	<?php
}
?>