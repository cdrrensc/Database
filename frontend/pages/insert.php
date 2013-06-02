<?php 
if (!empty($_POST)) {
    $done = false;
    switch ($_POST['type']) {
        case 'athlete':
            if (!empty($_POST['name'])) {
                $SQL->exec("INSERT INTO ATHLETES(name) VALUES(".$SQL->quote($_POST['name'], PDO::PARAM_STR).")");
                $done = true;
            }
            break;

        case 'participant':
            if (!empty($_POST['aid']) && !empty($_POST['gid']) && !empty($_POST['sid'])) {
                $SQL->exec("INSERT INTO PARTICIPANTS(AID, CID, GID, SID) VALUES(".$SQL->quote($_POST['aid'], PDO::PARAM_INT).",
                ".(empty($_POST['cid']) ? "NULL" : $SQL->quote($_POST['cid'], PDO::PARAM_INT)).", ".$SQL->quote($_POST['gid'], PDO::PARAM_INT).",
                ".$SQL->quote($_POST['sid'], PDO::PARAM_INT).")");
                $done = true;
            }
            break;
            
        case 'sport':
            if (!empty($_POST['name'])) {
                $SQL->exec("INSERT INTO SPORTS(NAME) VALUES(".$SQL->quote($_POST['name'], PDO::PARAM_STR).")");
                $done = true;
            }
            break;
            
        case 'discipline':
            if (!empty($_POST['name']) && !empty($_POST['sid'])) {
                $SQL->exec("INSERT INTO DISCIPLINES(NAME, SID) VALUES(".$SQL->quote($_POST['name'], PDO::PARAM_STR).",
                ".$SQL->quote($_POST['sid'], PDO::PARAM_INT).")");
                $done = true;
            }
            break;
            
        case 'event':
            if (!empty($_POST['did']) && !empty($_POST['gid'])) {
                $SQL->exec("INSERT INTO EVENTS(DID, GID) VALUES(".$SQL->quote($_POST['did'], PDO::PARAM_INT).",
                ".$SQL->quote($_POST['gid'], PDO::PARAM_INT).")");
                $done = true;
            }
            break;

        case 'medal':
            if (!empty($_POST['medal_type']) && !empty($_POST['aid']) && !empty($_POST['eid'])) {
                $SQL->exec("INSERT INTO MEDALS(TYPE, AID, CID, EID, TID) VALUES(".$SQL->quote($_POST['medal_type'], PDO::PARAM_STR).",
                ".$SQL->quote($_POST['aid'], PDO::PARAM_INT).", ".(empty($_POST['cid']) ? "NULL" : $SQL->quote($_POST['cid'], PDO::PARAM_INT)).",
                ".$SQL->quote($_POST['eid'], PDO::PARAM_INT).", ".(empty($_POST['tid']) ? "NULL" : $SQL->quote($_POST['tid'], PDO::PARAM_INT)).")");
                $done = true;
            }
            break;

        case 'game':
            if (!empty($_POST['host_city']) && !empty($_POST['cid']) && !empty($_POST['year']) && !empty($_POST['game_type'])) {
                $SQL->exec("INSERT INTO GAMES(HOST_CITY, CID, YEAR, TYPE) VALUES(".$SQL->quote($_POST['host_city'], PDO::PARAM_STR).",
                ".$SQL->quote($_POST['cid'], PDO::PARAM_INT).", ".$SQL->quote($_POST['year'], PDO::PARAM_INT).",
                ".$SQL->quote($_POST['game_type'], PDO::PARAM_STR).")");
                $done = true;
            }
            break;
            
        case 'country':
            if (!empty($_POST['name'])) {
                $SQL->exec("INSERT INTO COUNTRIES(NAME) VALUES(".$SQL->quote($_POST['name'], PDO::PARAM_STR).")");
                $done = true;
            }
            break;
            
        case 'ioc':
            if (!empty($_POST['ioc']) && !empty($_POST['cid'])) {
                $SQL->exec("INSERT INTO IOC_CODE(CID, CODE) VALUES(".$SQL->quote($_POST['cid'], PDO::PARAM_INT).", ".$SQL->quote($_POST['ioc'], PDO::PARAM_STR).")");
                $done = true;
            }
            break;
            
        default:
            break;
    }
    if ($done) {
      $errorInfo = $SQL->errorInfo();

      switch ($errorInfo[0]) {
        case "00000":
          echo '<div class="alert alert-success">Insertion done !</div>';
          break;
        default:
          echo '<div class="alert alert-error">Insertion failed !<br /><pre>';
          echo $errorInfo[2];
          echo '</pre></div>';
      }
    } else {
      echo '<div class="alert alert-error">Insertion failed ! Check the fields\' values...</div>';
    }
}

?>
<h1>Insert data</h1>

<ul class="nav nav-tabs" id="tabs">
  <li class="active"><a href="#athlete">Athlete</a></li>
  <li><a href="#participant">Participant</a></li>
  <li><a href="#sport">Sport</a></li>
  <li><a href="#discipline">Discipline</a></li>
  <li><a href="#event">Event</a></li>
  <li><a href="#medal">Medal</a></li>
  <li><a href="#game">Game</a></li>
  <li><a href="#country">Country</a></li>
  <li><a href="#ioc">Country code</a></li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="athlete">
    <form class="form-horizontal" method="post" action="/?p=insert">
      <input type="hidden" name="type" value="athlete" />
      <div class="control-group">
        <label class="control-label" for="inputName">Name</label>
        <div class="controls">
          <input type="text" id="inputName" name="name" placeholder="Name">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="participant">
    <form class="form-horizontal" method="post" action="/?p=insert">
      <input type="hidden" name="type" value="participant" />
      <input type="hidden" name="aid" id="aid" />
      <div class="control-group">
        <label class="control-label" for="inputName">Athlete</label>
        <div class="controls">
          <input type="text" class="inputACAthlete" idHelpText="participantHelpText" idHiddenField="participantAIDHidden" autocomplete="off" id="inputName" name="name" placeholder="Athlete">
          <span class="help-inline" id="participantHelpText">AID : none</span>
          <input type="hidden" name="aid" id="participantAIDHidden" value="" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Country</label>
        <div class="controls">
          <select name="cid">
            <option value="">No country</option>
          <?php
          $resultRaw = $SQL->query("SELECT * FROM COUNTRIES ORDER BY NAME");
          while($country = $resultRaw->fetch()) {
            echo '<option value="' . $country['CID'] . '">' . htmlspecialchars($country['NAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Game</label>
        <div class="controls">
          <select name="gid">
          <?php
          $resultRaw = $SQL->query("SELECT GID, TYPE, YEAR FROM GAMES ORDER BY YEAR");
          while($game = $resultRaw->fetch()) {
            echo '<option value="' . $game['GID'] . '">' . $game['YEAR'] . ' - ' . htmlspecialchars($game['TYPE']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Sport</label>
        <div class="controls">
          <select name="sid">
          <?php
          $resultRaw = $SQL->query("SELECT * FROM SPORTS ORDER BY NAME");
          while($sport = $resultRaw->fetch()) {
            echo '<option value="' . $sport['SID'] . '">' . htmlspecialchars($sport['NAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="sport">
    <form class="form-horizontal" method="post" action="/?p=insert">
      <input type="hidden" name="type" value="sport" />
      <div class="control-group">
        <label class="control-label" for="inputName">Name</label>
        <div class="controls">
          <input type="text" id="inputName" name="name" placeholder="Name">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="discipline">
  	<form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="discipline" />
      <div class="control-group">
        <label class="control-label" for="inputName">Name</label>
        <div class="controls">
          <input type="text" id="inputName" name="name" placeholder="Name">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Sport</label>
        <div class="controls">
          <select name="sid">
          <?php
          $resultRaw = $SQL->query("SELECT * FROM SPORTS ORDER BY NAME");
          while($sport = $resultRaw->fetch()) {
            echo '<option value="' . $sport['SID'] . '">' . htmlspecialchars($sport['NAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="event">
    <form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="event" />
      <div class="control-group">
        <label class="control-label" for="inputName">Discipline</label>
        <div class="controls">
          <select name="did">
          <?php
          $resultRaw = $SQL->query("SELECT D.DID, D.NAME AS DNAME, S.NAME AS SNAME FROM DISCIPLINES D, SPORTS S WHERE D.SID=S.SID ORDER BY S.NAME");
          while($discipline = $resultRaw->fetch()) {
            echo '<option value="' . $discipline['DID'] . '">' . htmlspecialchars($discipline['SNAME']) . ' - ' . htmlspecialchars($discipline['DNAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Game</label>
        <div class="controls">
          <select name="gid">
          <?php
          $resultRaw = $SQL->query("SELECT GID, TYPE, YEAR FROM GAMES ORDER BY YEAR");
          while($game = $resultRaw->fetch()) {
            echo '<option value="' . $game['GID'] . '">' . $game['YEAR'] . ' - ' . htmlspecialchars($game['TYPE']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="medal">
    <form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="medal" />
      <div class="control-group">
        <label class="control-label" for="inputName">Type</label>
        <div class="controls">
          <select name="medal_type">
            <option value="Gold">Gold</option>
            <option value="Silver">Silver</option>
            <option value="Bronze">Bronze</option>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Athlete</label>
        <div class="controls">
          <input type="text" class="inputACAthlete" idHelpText="medalHelpText" idHiddenField="medalAIDHidden" autocomplete="off" id="inputName" name="name" placeholder="Athlete">
          <span class="help-inline" id="medalHelpText">AID : none</span>
          <input type="hidden" name="aid" id="medalAIDHidden" value="" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Team id</label>
        <div class="controls">
          <?php
          $resultRaw = $SQL->query("SELECT TID FROM MEDALS ORDER BY TID DESC LIMIT 1");
          $tid = $resultRaw->fetch();
          ?>
          <input type="text" id="inputName" name="tid" placeholder="Empty if individual">
          <span class="help-inline">Next free team ID : <?php echo ($tid['TID']+1); ?></span>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Country</label>
        <div class="controls">
          <select name="cid">
            <option value="">No country</option>
            <?php
            $resultRaw = $SQL->query("SELECT * FROM COUNTRIES ORDER BY NAME");
            while($country = $resultRaw->fetch()) {
              echo '<option value="' . $country['CID'] . '">' . htmlspecialchars($country['NAME']) . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Event</label>
        <div class="controls">
          <select name="eid">
          <?php
          $resultRaw = $SQL->query("SELECT E.EID, G.YEAR, G.TYPE, D.NAME AS DNAME, S.NAME AS SNAME 
            FROM EVENTS E, GAMES G, DISCIPLINES D, SPORTS S 
            WHERE E.GID = G.GID AND E.DID = D.DID AND D.SID = S.SID
            ORDER BY G.YEAR DESC, S.NAME, D.NAME");

          while($event = $resultRaw->fetch()) {
            echo '<option value="' . $event['EID'] . '">' . $event['YEAR'] . ' ' . htmlspecialchars($event['TYPE']) . ' Olympics : ' . htmlspecialchars($event['SNAME']) . ' - ' . htmlspecialchars($event['DNAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="game">
  	<form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="game" />
      <div class="control-group">
        <label class="control-label" for="inputName">Host city</label>
        <div class="controls">
          <input type="text" id="inputName" name="host_city" placeholder="Host city">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Country</label>
        <div class="controls">
          <select name="cid">
            <option value="">No country</option>
            <?php
            $resultRaw = $SQL->query("SELECT * FROM COUNTRIES ORDER BY NAME");
            while($country = $resultRaw->fetch()) {
              echo '<option value="' . $country['CID'] . '">' . htmlspecialchars($country['NAME']) . '</option>';
            }
            ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Year</label>
        <div class="controls">
          <input type="text" id="inputName" name="year" placeholder="Year">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">Type</label>
        <div class="controls">
          <select name="game_type">
            <option value="Summer">Summer</option>
            <option value="Winter">Winter</option>
          </select>
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="country">
  	<form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="country" />
      <div class="control-group">
        <label class="control-label" for="inputName">Name</label>
        <div class="controls">
          <input type="text" id="inputName" name="name" placeholder="Name">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="tab-pane" id="ioc">
    <form class="form-horizontal"  method="post" action="/?p=insert">
      <input type="hidden" name="type" value="ioc" />
      <div class="control-group">
        <label class="control-label" for="inputName">Country</label>
        <div class="controls">
          <select name="cid">
          <?php
          $resultRaw = $SQL->query("SELECT * FROM COUNTRIES ORDER BY NAME");
          while($country = $resultRaw->fetch()) {
            echo '<option value="' . $country['CID'] . '">' . htmlspecialchars($country['NAME']) . '</option>';
          }
          ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="inputName">IOC</label>
        <div class="controls">
          <input type="text" id="inputName" name="ioc" placeholder="IOC">
        </div>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  var map = {};
  $(document).ready(function () {
  	$('#tabs a').click(function (e) {
 	    e.preventDefault();
  	  $(this).tab('show');
	  });

    $('.inputACAthlete').each(function() {
      var idHiddenField = $(this).attr('idHiddenField');
      var idHelpText = $(this).attr('idHelpText');
      $(this).typeahead({
        source: function(query, callback) {
          $.ajax({
            url: 'ajax.php',
            type: 'get',
            data: 'action=athletes&q=' + escape(query),
            dataType: 'json',
            success: function(data) {
              var array = new Array();
              map = {};
              for (var i = 0; i < data.length; i++) {
                array[i] = data[i].name;
                map[data[i].name] = data[i].id;
              }
              callback(array);
            }
          });
        },
        updater: function(item) {
          if (item in map) {
            $('#'+idHiddenField).val(map[item]);
            $('#'+idHelpText).text("AID : "+map[item]);
            return $('<div />').html(item).text();
          } else {
            $('#'+idHiddenField).val("");
            $('#'+idHelpText).text("AID : none");
            return "Error ! Please reload.";
          }
          
        }
      });
    });
  });
</script>