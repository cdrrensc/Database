<h1>Game</h1>

<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultRaw = $SQL->query("SELECT G.YEAR AS GYEAR, G.TYPE AS GTYPE, C.NAME AS CNAME, G.HOST_CITY AS GHOST
		FROM GAMES G, COUNTRIES C 
		WHERE G.CID = C.CID 
		AND G.GID = " . $SQL->quote($_GET['id']));

	$data = $resultRaw->fetch();

	echo "<h4>" . $data['GYEAR'] . " " . htmlspecialchars($data['GTYPE']) . "</h4>";
	echo htmlspecialchars($data['GHOST']) . ", " . htmlspecialchars($data['CNAME']) . "<br />";
	?>
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#participants">Participants</a></li>
		<li><a href="#medals">Medals</a></li>
		<li><a href="#events">Events</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="participants">
			<?php
			$resultRaw = $SQL->query("SELECT P.PID, A.NAME AS ANAME, C.NAME AS CNAME, S.NAME AS SNAME
				FROM ATHLETES A, SPORTS S, PARTICIPANTS P LEFT JOIN COUNTRIES C ON P.CID = C.CID
				WHERE A.AID = P.AID
				AND P.SID = S.SID
				AND P.GID = " . $SQL->quote($_GET['id']) . "
				ORDER BY A.NAME") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';
			
			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Country</th>
						<th>Sport</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . $entry['ANAME'] . '</td>';
					echo '<td>' . $entry['CNAME'] . '</td>';
					echo '<td>' . $entry['SNAME'] . '</td>';
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
			$resultRaw = $SQL->query("SELECT M.MID, A.NAME AS ANAME, M.TYPE AS MTYPE, C.NAME AS CNAME, D.NAME AS DNAME, S.NAME AS SNAME
				FROM ATHLETES A, EVENTS E, DISCIPLINES D, SPORTS S, MEDALS M LEFT JOIN COUNTRIES C ON M.CID = C.CID
				WHERE M.AID = A.AID
				AND M.EID = E.EID
				AND E.DID = D.DID
				AND D.SID = S.SID
				AND E.GID = " . $SQL->quote($_GET['id']) . "
				ORDER BY A.NAME") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';

			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Athlete</th>
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
					echo '<td>' . htmlspecialchars($entry['ANAME']) . '</td>';
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
		<div class="tab-pane" id="events">
			<?php
			$resultRaw = $SQL->query("SELECT E.EID, D.NAME AS DNAME, S.NAME AS SNAME
				FROM EVENTS E, DISCIPLINES D, SPORTS S
				WHERE D.SID = S.SID
				AND E.DID = D.DID
				AND E.GID = " . $SQL->quote($_GET['id']) . "
				ORDER BY S.NAME") or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';

			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Sport</th>
						<th>Discipline</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . htmlspecialchars($entry['SNAME']) . '</td>';
					echo '<td>' . htmlspecialchars($entry['DNAME']) . '</td>';
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