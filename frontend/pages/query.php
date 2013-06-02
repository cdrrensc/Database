<h1>Execute a query</h1>

<form class="form-horizontal" method="post" action="#">
	<div class="control-group">
		<label class="control-label" for="inputEmail">SQL</label>
		<div class="controls">
			<textarea id="sqlField" name="sql"><?php
				if (isset($_POST['sql'])) {
					echo htmlspecialchars($_POST['sql']);
				}
			?></textarea>
		</div>
	</div>
	<div class="form-actions">
	  <input type="submit" value="Execute" class="btn btn-primary">
	</div>
</form>

<script type="text/javascript">
var textArea = document.getElementById('sqlField');
var sqlCode = CodeMirror.fromTextArea(textArea, {
	mode:  "text/x-mysql",
	indentWithTabs: true,
	smartIndent: true,
	lineNumbers: true,
	lineWrapping: true,
	matchBrackets : true,
	autofocus: true
});
</script>

<?php
if (!empty($_POST['sql'])) {
	$statement = $SQL->prepare($_POST['sql']);

	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$starttime = $mtime;

	if ($statement->execute()) {
		$mtime = microtime(); 
		$mtime = explode(" ",$mtime); 
		$mtime = $mtime[1] + $mtime[0]; 
		$endtime = $mtime; 
		$sqltime = ($endtime - $starttime);

		echo "<h2>Success</h2>";

		$data = $statement->fetchAll();
		
		if (count($data) == 0) {
			echo "Nothing to display";
		} else {
			$rows = array_keys($data);
			
			echo "Number of results : " . count($data) . " fetched in " . round($sqltime, 3) . "s";
			echo "<table class=\"table table-striped\">";
			echo "<tr>";

			$currentColName = "";
			foreach (array_keys($data[0]) as $colName) {
				if (!is_numeric($colName)) {
					$currentColName = $colName;
				} else {
					echo "<th>" . htmlspecialchars($currentColName) . "</th>";
				}
			}
			echo "</tr>";
			$i = 1;
			foreach ($rows as $row) {
				if ($i > 1000) {
					$nbCols = count(array_keys($data[$row])) / 2;
					echo '<tr><td colspan="' . $nbCols . '" style="text-align: center;">Too many results (use "LIMIT from, nb" to display other entries)</td></tr>';
					break;
				}

				$cols = array_keys($data[$row]);
				echo "<tr>";
				foreach ($cols as $col) {
					if (is_numeric($col)) {
						echo "<td>" . htmlspecialchars($data[$row][$col]) . "</td>";
					}
				}
				echo "</tr>";
				$i++;
			}
			echo "</table>";
		}
	} else {
		echo "<h2>Error</h2>";
		$error = $statement->errorInfo();
		echo htmlspecialchars($error[2]);
	}
}
?>