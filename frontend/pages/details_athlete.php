<h1>Athlete</h1>

<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultRaw = $SQL->query("SELECT A.NAME FROM ATHLETES A WHERE A.AID = " . $SQL->quote($_GET['id']));

	$data = $resultRaw->fetch();

	echo "<h4>" . htmlspecialchars($data['NAME']) . "</h4>";
	?>
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#participations">Participations</a></li>
		<li><a href="#medals">Medals</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="participations">
			<?php
			$resultRaw = $SQL->query("SELECT P.PID, C.NAME AS CNAME, G.YEAR AS GYEAR, G.TYPE AS GTYPE, S.NAME AS SNAME
				FROM GAMES G, SPORTS S, PARTICIPANTS P LEFT OUTER JOIN COUNTRIES C ON P.CID = C.CID
				WHERE P.GID = G.GID
				AND P.SID = S.SID
				AND P.AID = " . $SQL->quote($_GET['id'])) or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';
			
			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Game</th>
						<th>Sport</th>
						<th>Country</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . $entry['GYEAR'] . ' ' . $entry['GTYPE'] . '</td>';
					echo '<td>' . $entry['SNAME'] . '</td>';
					echo '<td>' . $entry['CNAME'] . '</td>';
					echo '<td><a href="?p=delete&amp;type=participant&amp;id=' . $entry['PID'] . '"><i class="icon-remove"></i></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
				</table>
				<?php
			}
			?>
		</div>
		<div class="tab-pane" id="medals">
			<?php
			$resultRaw = $SQL->query("SELECT M.MID, M.TYPE AS MTYPE, C.NAME AS CNAME, G.YEAR AS GYEAR, G.TYPE AS GTYPE, D.NAME AS DNAME, S.NAME AS SNAME
				FROM EVENTS E, DISCIPLINES D, GAMES G, SPORTS S, MEDALS M LEFT OUTER JOIN COUNTRIES C ON M.CID = C.CID
				WHERE M.EID = E.EID
				AND E.DID = D.DID
				AND E.GID = G.GID
				AND D.SID = S.SID
				AND M.AID = " . $SQL->quote($_GET['id']) . "
				ORDER BY G.YEAR") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';

			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Game</th>
						<th>Medal</th>
						<th>Sport</th>
						<th>Discipline</th>
						<th>Country</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . $entry['GYEAR'] . ' ' . htmlspecialchars($entry['GTYPE']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['MTYPE']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['SNAME']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['DNAME']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['CNAME']) . '</td>';
					echo '<td><a href="?p=delete&amp;type=medal&amp;id=' . $entry['MID'] . '"><i class="icon-remove"></i></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
				</table>
				<?php
			}
			?>
		</div>
	</div>

	<script>
	$(document).ready(function () {
		$('#tabs a').click(function (e) {
			e.preventDefault();
			$(this).tab('show');
		});
	});
	</script>
	<?php
}
?>