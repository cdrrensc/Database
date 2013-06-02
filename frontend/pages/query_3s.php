<h1>Query S</h1>

<blockquote>
	<p>List names of all athletes who won medals both in individual and team sports.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT A.NAME
FROM ATHLETES A, MEDALS M1, MEDALS M2
WHERE A.AID = M1.AID
AND A.AID = M2.AID
AND M1.TID IS NOT NULL
AND M2.TID IS NULL
ORDER BY A.NAME	";
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
		<div class="span4">
			<table class="table table-striped">
			<thead>
				<tr>
				<th>#</th>
				<th>Athlete</th>
				</tr>
			</thead>
			<tbody>
			<?php
			$i = 1;
			while($country = $resultRaw->fetch()) {
				echo '<tr><td>' . $i++ . '</td><td>' . $country['NAME'] . '</td></tr>';
				if ($i == ceil($resultRaw->rowCount() / 3) + 1 || $i == 2*ceil($resultRaw->rowCount() / 3) + 1) {
					?>
			</tbody>
			</table>
		</div>
		<div class="span4">
			<table class="table table-striped">
			<thead>
				<tr>
				<th>#</th>
				<th>Athlete</th>
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