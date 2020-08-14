<?php
require_once("StatusRestHandler.php");

require_once("Logger.php");
		
$view = "";
if(isset($_GET["view"]))
	$view = $_GET["view"];

/*
controls the RESTful services
URL mapping
*/
switch($view){

	case "all":
		// to handle REST Url /status/list/
		Logger::Info("all");
                $statusRestHandler = new StatusRestHandler();
		$statusRestHandler->getAllStatuses($_POST["host"], $_POST["port"], $_POST["password"]);
		break;
		
	case "byname":
	case "by_name":
		// to handle REST Url /status/show/<id>/
		$statusRestHandler = new StatusRestHandler();
		$statusRestHandler->getStatus($_POST["host"], $_POST["port"], $_POST["password"],$_POST["name"], );
		break;

	case "" :
		//404 - not found;
		break;
}
?>
