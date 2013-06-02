<?php
header('Content-Type: application/json; charset=ISO-8859-1');
include 'db.php';
if(!empty($_GET['q'])) {
	$q = htmlentities($_GET['q']);

	switch ($_GET['action']) {
	    case 'athletes':
	        $resultat = $SQL->query("SELECT * FROM ATHLETES WHERE NAME LIKE '%".$q."%' ORDER BY NAME LIMIT 0, 10");
	        echo '[';
	        $first = true;
	        while($athlete = $resultat->fetch()) {
	        	if(!$first)
	        		echo ',';

	        	echo 	'{' . 
	        			'"id" : ' . $athlete['AID'] . ',' .
	        			'"name" : "' . str_replace("\"", "\\\"", htmlspecialchars($athlete['NAME'])) . '"' .
	        			"}";

	        	$first = false;
	        }
	        echo ']';
	        break;
	    default:break;
	}
}
?>