<h1>Delete</h1>

<?php
if (!isset($_GET['type']) OR !is_numeric($_GET['id'])) {
  echo "Error";
} else {
  $affectedRows = -1;

  switch ($_GET['type']) {
    case 'athlete':
      echo 'Query : delete athlete (AID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM ATHLETES WHERE AID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'sport':
      echo 'Query : delete sport (SID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM SPORTS WHERE SID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'discipline':
      echo 'Query : delete discipline (DID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM DISCIPLINES WHERE DID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'participant':
      echo 'Query : delete participant (PID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM PARTICIPANTS WHERE PID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'games':
      echo 'Query : delete games (GID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM GAMES WHERE GID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'medal':
      echo 'Query : delete medal (MID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM MEDALS WHERE MID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'event':
      echo 'Query : delete event (EID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM EVENTS WHERE EID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'country':
      echo 'Query : delete country (CID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM COUNTRIES WHERE CID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
    case 'ioc':
      echo 'Query : delete IOC code (ICID : ' . $_GET['id'] . ')<br />';
      $affectedRows = $SQL->exec("DELETE FROM IOC_CODE WHERE ICID = " . $SQL->quote($_GET['id'], PDO::PARAM_INT));
      break;
  }

  if ($affectedRows == -1) {
    echo "Invalid request";
  } else {
    $errorInfo = $SQL->errorInfo();

    switch ($errorInfo[0]) {
      case "00000":
        if ($affectedRows == 1) {
          echo '<span class="label label-success">Success</span>';
        } else if ($affectedRows == 0) {
          echo '<span class="label label-important">Not found</span>';
        }
        break;
      default:
        echo "Error : " . $errorInfo[2];
        break;
    }
  }
}
?>
<br /><br />
<a class="btn btn-primary" href="javascript:history.back();">Go back</a>