<h1>Sport</h1>

<?php
if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultRaw = $SQL->query("SELECT S.NAME FROM SPORTS S WHERE S.SID = " . $SQL->quote($_GET['id']));

	$data = $resultRaw->fetch();

	echo "<h4>" . $data['NAME'] . "</h4>";
	?>
	<ul class="nav nav-tabs" id="tabs">
		<li class="active"><a href="#disciplines">Disciplines</a></li>
		<li><a href="#participants">Participants</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="disciplines">
			<?php
			$resultRaw = $SQL->query("SELECT D.DID, D.NAME
				FROM DISCIPLINES D
				WHERE D.SID = " . $SQL->quote($_GET['id'])) or die(var_dump($SQL->errorInfo()));

			echo $resultRaw->rowCount() . ' results';
			
			if ($resultRaw->rowCount() > 0) {
				?>
				<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$i = 1;
				while($entry = $resultRaw->fetch()) {
					echo '<tr>';
					echo '<td>' . $i++ . '</td>';
					echo '<td>' . $entry['NAME'] . '</td>';
					echo '<td><a href="?p=delete&amp;type=discipline&amp;id=' . $entry['DID'] . '"><i class="icon-remove"></i></a></td>';
					echo '</tr>';
				}
				?>
				</tbody>
				</table>
				<?php
			}
			?>
		</div>
		<div class="tab-pane" id="participants">
			<?php
			$resultRaw = $SQL->query("SELECT P.PID, A.NAME AS ANAME, C.NAME AS CNAME, G.YEAR AS GYEAR, G.TYPE AS GTYPE
				FROM ATHLETES A, GAMES G, PARTICIPANTS P LEFT JOIN COUNTRIES C ON P.CID = C.CID
				WHERE P.AID = A.AID
				AND P.GID = G.GID
				AND P.SID = " . $SQL->quote($_GET['id']) . "
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
					echo '<td>' . $entry['GYEAR'] . ' ' . htmlspecialchars($entry['GTYPE']) . '</td>';
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