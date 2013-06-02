<h1>Query M</h1>

<blockquote>
	<p>List all Olympians who won medals for multiple nations.</p>
</blockquote>

<?php
$query = "SELECT DISTINCT A.NAME
FROM ATHLETES A, MEDALS M1, MEDALS M2
WHERE M1.CID <> M2.CID
AND M1.AID = M2.AID
AND M1.AID = A.AID
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
	<div class="row">
		<div class="span6">
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