<h1>Search</h1>

<form method="get">
  <input type="hidden" name="p" value="search" />
  <input type="text" name="q" value="<?php echo htmlspecialchars($_GET['q']); ?>" placeholder="Type a keyword..." class="input-large search-query" />
  <input type="submit" value="Search" class="btn btn-primary" />
</form>

<?php
if (!empty($_GET['q'])) {
?>
<ul class="nav nav-tabs" id="tabs">
  <li class="active"><a href="#athletes">Athletes</a></li>
  <li><a href="#sports">Sports</a></li>
  <li><a href="#disciplines">Disciplines</a></li>
  <li><a href="#games">Games</a></li>
  <li><a href="#countries">Countries</a></li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="athletes">
    <?php
    $resultRaw = $SQL->query("SELECT * FROM ATHLETES WHERE NAME LIKE " . $SQL->quote("%" . $_GET['q'] . "%"));

    echo $resultRaw->rowCount() . " results";

    if ($resultRaw->rowCount() > 0) {
      ?>
      <table class="table table-striped">
      <thead>
        <th>#</th>
        <th>Name</th>
        <th></th>
      </thead>
      <tbody>
      <?php
      $i = 1;
      while ($entry = $resultRaw->fetch()) {
        echo '<tr>';
        echo '<td width="10%">' . $i++ . '</td>';
        echo '<td width="70%">' . htmlspecialchars($entry['NAME']) . '</td>';
        echo '<td width="10%"><a href="?p=details_athlete&amp;id=' . $entry['AID'] . '"><i class="icon-search"></i></a></td>';
        echo '<td width="10%"><a href="?p=delete&amp;type=athlete&amp;id=' . $entry['AID'] . '"><i class="icon-remove"></i></a></td>';
        echo '</tr>';
      }
      ?>
      </tbody>
      </table>
      <?php
    }
    ?>
  </div>
  <div class="tab-pane" id="sports">
    <?php
    $resultRaw = $SQL->query("SELECT * FROM SPORTS WHERE NAME LIKE " . $SQL->quote("%" . $_GET['q'] . "%"));

    echo $resultRaw->rowCount() . " results";

    if ($resultRaw->rowCount() > 0) {
      ?>
      <table class="table table-striped">
      <thead>
        <th>#</th>
        <th>Name</th>
        <th></th>
      </thead>
      <tbody>
      <?php
      $i = 1;
      while ($entry = $resultRaw->fetch()) {
        echo '<tr>';
        echo '<td width="10%">' . $i++ . '</td>';
        echo '<td width="70%">' . htmlspecialchars($entry['NAME']) . '</td>';
        echo '<td width="10%"><a href="?p=details_sport&amp;id=' . $entry['SID'] . '"><i class="icon-search"></i></a></td>';
        echo '<td width="10%"><a href="?p=delete&amp;type=sport&amp;id=' . $entry['SID'] . '"><i class="icon-remove"></i></a></td>';
        echo '</tr>';
      }
      ?>
      </tbody>
      </table>
      <?php
    }
    ?>
  </div>
  <div class="tab-pane" id="disciplines">
  	<?php
    $resultRaw = $SQL->query("SELECT * FROM DISCIPLINES WHERE NAME LIKE " . $SQL->quote("%" . $_GET['q'] . "%"));

    echo $resultRaw->rowCount() . " results";

    if ($resultRaw->rowCount() > 0) {
      ?>
      <table class="table table-striped">
      <thead>
        <th>#</th>
        <th>Name</th>
        <th></th>
      </thead>
      <tbody>
      <?php
      $i = 1;
      while ($entry = $resultRaw->fetch()) {
        echo '<tr>';
        echo '<td width="10%">' . $i++ . '</td>';
        echo '<td width="70%">' . htmlspecialchars($entry['NAME']) . '</td>';
        echo '<td width="10%"><a href="?p=details_discipline&amp;id=' . htmlspecialchars($entry['DID']) . '"><i class="icon-search"></i></a></td>';
        echo '<td width="10%"><a href="?p=delete&amp;type=discipline&amp;id=' . $entry['DID'] . '"><i class="icon-remove"></i></a></td>';
        echo '</tr>';
      }
      ?>
      </tbody>
      </table>
      <?php
    }
    ?>
  </div>
  <div class="tab-pane" id="games">
  	<?php
    $resultRaw = $SQL->query("SELECT * FROM GAMES 
      WHERE YEAR = " . $SQL->quote($_GET['q']) . " 
      OR TYPE LIKE " . $SQL->quote("%" . $_GET['q'] . "%") . "
      OR HOST_CITY = " . $SQL->quote("%" . $_GET['q'] . "%"));

    echo $resultRaw->rowCount() . " results";

    if ($resultRaw->rowCount() > 0) {
      ?>
      <table class="table table-striped">
      <thead>
        <th>#</th>
        <th>Game</th>
        <th>Host city</th>
        <th></th>
      </thead>
      <tbody>
      <?php
      $i = 1;
      while ($entry = $resultRaw->fetch()) {
        echo '<tr>';
        echo '<td width="10%">' . $i++ . '</td>';
        echo '<td width="35%">' . $entry['YEAR'] . ' - ' . htmlspecialchars($entry['TYPE']) . '</td>';
        echo '<td width="35%">' . htmlspecialchars($entry['HOST_CITY']) . '</td>';
        echo '<td width="10%"><a href="?p=details_game&amp;id=' . $entry['GID'] . '"><i class="icon-search"></i></a></td>';
        echo '<td width="10%"><a href="?p=delete&amp;type=games&amp;id=' . $entry['GID'] . '"><i class="icon-remove"></i></a></td>';
        echo '</tr>';
      }
      ?>
      </tbody>
      </table>
      <?php
    }
    ?>
  </div>
  <div class="tab-pane" id="countries">
  	<?php
    $resultRaw = $SQL->query("SELECT * FROM COUNTRIES WHERE NAME LIKE " . $SQL->quote("%" . $_GET['q'] . "%"));

    echo $resultRaw->rowCount() . " results";

    if ($resultRaw->rowCount() > 0) {
      ?>
      <table class="table table-striped">
      <thead>
        <th>#</th>
        <th>Name</th>
        <th></th>
      </thead>
      <tbody>
      <?php
      $i = 1;
      while ($entry = $resultRaw->fetch()) {
        echo '<tr>';
        echo '<td width="10%">' . $i++ . '</td>';
        echo '<td width="70%">' . htmlspecialchars($entry['NAME']) . '</td>';
        echo '<td width="10%"><a href="?p=details_country&amp;id=' . $entry['CID'] . '"><i class="icon-search"></i></a></td>';
        echo '<td width="10%"><a href="?p=delete&amp;type=country&amp;id=' . $entry['CID'] . '"><i class="icon-remove"></i></a></td>';
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
	})
  });
</script>
<?php
}
?>