<h1>Query R</h1>

<blockquote>
	<p>For all individual sports, compute the most top 10 countries according to their success score. Success
score of a country is sum of success points of all its medalists: gold medal is worth 3 points, silver 2
points, and bronze 1 point. Shared medal is worth half the points of the non-shared medal.</p>
</blockquote>

<?php
$query = "SELECT C.NAME, SUM(
    case M.TYPE 
        when 'Gold' then 3/SHARED_COEFF.COEFF
        when 'Silver' then 2/SHARED_COEFF.COEFF
        when 'Bronze' then 1/SHARED_COEFF.COEFF
    end
) AS SCORE
FROM MEDALS M, COUNTRIES C, EVENTS E, DISCIPLINES D, (
    SELECT M.EID, M.TYPE, (
        case COUNT(M.MID) 
            when 1 then 1 
            else 2 
        end
    ) AS COEFF
    FROM MEDALS M
    GROUP BY M.EID, M.TYPE
) AS SHARED_COEFF
WHERE M.CID = C.CID
AND M.TID IS NULL
AND M.EID = E.EID
AND E.DID = D.DID
AND M.EID = SHARED_COEFF.EID
AND M.TYPE = SHARED_COEFF.TYPE
AND D.SID = ?
GROUP BY C.CID
ORDER BY SCORE DESC
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

<div id="time_field">
Waiting for completion...
</div>

<?php
$sum = 0;

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

$sportsRaw = $SQL->query("SELECT S.NAME, S.SID FROM SPORTS S ORDER BY S.NAME");

$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$sum += ($endtime - $starttime);

$i = 0;
while ($sport = $sportsRaw->fetch()) {
	$statement = $SQL->prepare($query);
	$statement->bindParam(1, $sport['SID']);

	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;

	$statement->execute();

	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$sum += ($endtime - $starttime);

	if ($statement->rowCount() > 0) {
		if ($i % 2 == 0) {
			echo '<div class="row">';
			echo '<div class="span6">';
		} else {
			echo '<div class="span6">';
		}
		?>
		<h2><?php echo htmlspecialchars($sport['NAME']); ?></h2>
		<table class="table table-striped">
		<thead>
			<tr>
			<th>#</th>
			<th>Country</th>
			<th>Score</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$rowCount = 1;
		while ($data = $statement->fetch()) {
			echo '<tr>';
			echo '<td width="10%">' . $rowCount++ . '</td>';
			echo '<td width="70%">' . $data['NAME'] . '</td>';
			echo '<td width="20%">' . $data['SCORE'] . '</td>';
			echo '</tr>';
		}
		?>
		</tbody>
		</table>
		</div>
		<?php
		if ($i % 2 == 1) {
			echo '</div>';
		}
		$i++;
	}
}
?>

<script type="text/javascript">
$('#time_field').text("<?php echo $i; ?> results fetched in <?php echo round($sum, 3); ?> seconds");
</script> 