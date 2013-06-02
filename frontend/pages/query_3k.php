<h1>Query K</h1>

<blockquote>
	<p>Compute which country in which Olympics has benefited the most from playing in front of the home crowd. The benefit is computed as the number of places it has advanced its position on the medal table compared to its average position for all Olympic Games. Repeat this computation separately for winter and summer games.</p>
</blockquote>

<?php
$query = "(
  SELECT G.YEAR, G.TYPE, C.NAME, RANKS_PER_GAMES.GID, AVERAGE_RANKS.RANK - RANKS_PER_GAMES.RANK AS BENEFIT FROM (
    SELECT RANKING_GLOBAL.GID, RANKING_GLOBAL.CID, RANKING_GLOBAL.RANK - MIN_RANKS.RANK + 1 AS RANK FROM (
      SELECT GID, MIN(RANK) AS RANK FROM (
        SELECT GID, CID, @rankA:=@rankA+1 AS RANK FROM (
          SELECT E.GID, M.CID,
            SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
            SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
            SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
          FROM MEDALS_UNIQUE M, EVENTS E
          WHERE M.CID IS NOT NULL
          AND M.EID = E.EID
          GROUP BY M.CID, E.GID
          ORDER BY E.GID, COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC
        ) A, (SELECT @rankA:=0) INIT
      ) RANKING_GLOBAL
      GROUP BY RANKING_GLOBAL.GID
    ) MIN_RANKS, (
        SELECT GID, CID, @rankB:=@rankB+1 AS RANK FROM (
          SELECT E.GID, M.CID,
            SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
            SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
            SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
          FROM MEDALS_UNIQUE M, EVENTS E
          WHERE M.CID IS NOT NULL
          AND M.EID = E.EID
          GROUP BY M.CID, E.GID
          ORDER BY E.GID, COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC
        ) B, (SELECT @rankB:=0) INIT
    ) RANKING_GLOBAL
    WHERE RANKING_GLOBAL.GID = MIN_RANKS.GID
  ) RANKS_PER_GAMES, (
    SELECT @rank1:=@rank1+1 AS RANK, MEDAL_TABLE.CID 
    FROM (SELECT M.CID,
        SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
        SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
        SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
    FROM MEDALS_UNIQUE M
    GROUP BY M.CID
    ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC) MEDAL_TABLE, (SELECT @rank1:=0) INIT
  ) AVERAGE_RANKS, GAMES G, COUNTRIES C
  WHERE RANKS_PER_GAMES.GID = G.GID
  AND G.CID = C.CID
  AND AVERAGE_RANKS.CID = RANKS_PER_GAMES.CID
  AND G.CID = AVERAGE_RANKS.CID
  AND G.TYPE = 'Summer'
  ORDER BY BENEFIT DESC
  LIMIT 1
) UNION (
  SELECT G.YEAR, G.TYPE, C.NAME, RANKS_PER_GAMES.GID, AVERAGE_RANKS.RANK - RANKS_PER_GAMES.RANK AS BENEFIT FROM (
    SELECT RANKING_GLOBAL.GID, RANKING_GLOBAL.CID, RANKING_GLOBAL.RANK - MIN_RANKS.RANK + 1 AS RANK FROM (
      SELECT GID, MIN(RANK) AS RANK FROM (
        SELECT GID, CID, @rankC:=@rankC+1 AS RANK FROM (
          SELECT E.GID, M.CID,
            SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
            SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
            SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
          FROM MEDALS_UNIQUE M, EVENTS E
          WHERE M.CID IS NOT NULL
          AND M.EID = E.EID
          GROUP BY M.CID, E.GID
          ORDER BY E.GID, COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC
        ) A, (SELECT @rankC:=0) INIT
      ) RANKING_GLOBAL
      GROUP BY RANKING_GLOBAL.GID
    ) MIN_RANKS, (
        SELECT GID, CID, @rankD:=@rankD+1 AS RANK FROM (
          SELECT E.GID, M.CID,
            SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
            SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
            SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
          FROM MEDALS_UNIQUE M, EVENTS E
          WHERE M.CID IS NOT NULL
          AND M.EID = E.EID
          GROUP BY M.CID, E.GID
          ORDER BY E.GID, COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC
        ) B, (SELECT @rankD:=0) INIT
    ) RANKING_GLOBAL
    WHERE RANKING_GLOBAL.GID = MIN_RANKS.GID
  ) RANKS_PER_GAMES, (
    SELECT @rank2:=@rank2+1 AS RANK, MEDAL_TABLE.CID 
    FROM (SELECT M.CID,
        SUM(case M.TYPE when 'Gold' then 1 else 0 end) AS COUNT_GOLD,
        SUM(case M.TYPE when 'Silver' then 1 else 0 end) AS COUNT_SILVER,
        SUM(case M.TYPE when 'Bronze' then 1 else 0 end) AS COUNT_BRONZE
    FROM MEDALS_UNIQUE M
    GROUP BY M.CID
    ORDER BY COUNT_GOLD DESC, COUNT_SILVER DESC, COUNT_BRONZE DESC) MEDAL_TABLE, (SELECT @rank2:=0) INIT
  ) AVERAGE_RANKS, GAMES G, COUNTRIES C
  WHERE RANKS_PER_GAMES.GID = G.GID
  AND G.CID = C.CID
  AND AVERAGE_RANKS.CID = RANKS_PER_GAMES.CID
  AND G.CID = AVERAGE_RANKS.CID
  AND G.TYPE = 'Winter'
  ORDER BY BENEFIT DESC
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
		<th>Type</th>
		<th>Country</th>
		<th>Benefit</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$i = 1;
	while($data = $resultRaw->fetch()) {
		echo '<tr>';
		echo '<td>' . $i++ . '</td>';
    echo '<td>' . $data['YEAR'] . " " . htmlspecialchars($data['TYPE']) . '</td>';
		echo '<td>' . htmlspecialchars($data['NAME']) . '</td>';
		echo '<td>' . $data['BENEFIT'] . '</td>';
		echo '</tr>';
	}
	
	?>
	</tbody>
	</table>
	<?php
}
?>