<?php
require_once("Connection.php");
require_once("Classes/TableUploadFiles.php");

$uri      = explode("/", $_SERVER["REQUEST_URI"]);
$resource = end($uri);

if (strcmp($resource, 'getYears') == 0) {
	$sectionId = $_POST["sectionId"];
	$json = array();
	$Conn = new Connection();
	$Conn->connect();
	
	$query = "SELECT year FROM `uploadFiles` WHERE section_id='".$sectionId."' GROUP BY year";
	$result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
	
	if (!$result) {
		$json = array(
			"error" => "MySQL Query Error"
		);
	} else {
		while ($row = mysql_fetch_array($result)) {
			array_push($json, $row);
		}
	}
	
	header('Content-Type: application/json');
	echo json_encode($json);
} else if (strcmp($resource, 'getpublishedDocuments') == 0) {
	$sectionId = $_POST["sectionId"];
    $year = $_POST["year"];
	$json = array();
	$Conn = new Connection();
	$Conn->connect();
	
	$query = "";
	$queryYear;
	
	if ($year == 0) {
		$queryYear = "SELECT MAX(year) as year FROM uploadFiles WHERE section_id='".$sectionId."'";
		$resultYear = mysql_query($queryYear, $Conn->ConnectionID) or die(mysql_error());
		$row = mysql_fetch_object($resultYear);
		$query = "SELECT * FROM `uploadFiles` WHERE section_id=".$sectionId." AND year='".$row->year."' ORDER BY `quarterly` ASC";
	} else {
		$query = "SELECT * FROM `uploadFiles` WHERE section_id=".$sectionId." AND year='".$year."' ORDER BY `quarterly` ASC";
	}

	$result = mysql_query($query, $Conn->ConnectionID) or die(mysql_error());
	
	if (!$result) {
		$json = array(
			"error" => "MySQL Query Error"
		);
	} else {
		$rows = count( $result);
		if ($rows > 0) {
			while ($row = mysql_fetch_array($result)) {
				$file = new UploadFileHTML($row["file_id"], 
											  utf8_encode($row["title"]),
											  utf8_encode($row["filename"]),
											  $row["fileType"],
											  $row["publicationDate"],
											  $row["year"],
											  $row["quarterly"],
											  $row["section_id"]);
				$json[] = json_encode($file);
			}
		}else {
			$json = array(
				"error" => "Sin datos"
			);
		}
	}
	header('Content-Type: application/json');
	echo json_encode($json);
}
?>
