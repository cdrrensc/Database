<h1>Discipline</h1>

<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultRaw = $SQL->query("SELECT D.NAME AS DNAME, S.NAME AS SNAME
		FROM DISCIPLINES D, SPORTS S
		WHERE D.SID = S.SID
		AND D.DID = " . $SQL->quote($_GET['id']));

	$data = $resultRaw->fetch();

	echo "<h4>" . htmlspecialchars($data['DNAME']) . "</h4>";
	echo "Sport : " . htmlspecialchars($data['SNAME']);
	?>
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#events">Events</a></li>
		<li><a href="#medals">Medals</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="events">
			<?php
			$resultRaw = $SQL->query("SELECT E.EID, G.YEAR, G.TYPE
				FROM EVENTS E, GAMES G
				WHERE E.GID = G.GID
				AND E.DID = " . $SQL->quote($_GET['id']) . "
				ORDER BY G.YEAR") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';

			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Game</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . $entry['YEAR'] . ' - ' . htmlspecialchars($entry['TYPE']) . '</td>';
					echo '<td><a href="?p=delete&amp;type=event&amp;id=' . $entry['EID'] . '"><i class="icon-remove"></i></a></td>';
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
			$resultRaw = $SQL->query("SELECT M.MID, A.NAME AS ANAME, C.NAME AS CNAME, M.TYPE AS MTYPE, G.TYPE AS GTYPE, G.YEAR AS GYEAR
				FROM EVENTS E, GAMES G, ATHLETES A, MEDALS M LEFT JOIN COUNTRIES C ON M.CID = C.CID
				WHERE M.EID = E.EID
				AND E.GID = G.GID
				AND M.AID = A.AID
				AND E.DID = " . $SQL->quote($_GET['id']) . "
				ORDER BY A.NAME") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';

			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Athlete</th>
						<th>Country</th>
						<th>Medal</th>
						<th>Game</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . htmlspecialchars($entry['ANAME']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['CNAME']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['MTYPE']) . '</td>';
					echo '<td>' . $entry['GYEAR'] . ' - ' . htmlspecialchars($entry['GTYPE']) . '</td>';
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