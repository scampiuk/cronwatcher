<?php



/**
 * API end for receiving the calls that are to be wrapped around the cron jobs as they are fired.
 * Returns a 200 stats as quickly as possible without any content being send back ( lowers bandwidth,
 * speeds up the process)
 *
 *
 */

require_once("inc/logHelper.php");
$logHelper =new logHelper();
// Have we been passed the required details?
$siteId = $_GET['siteId'];
$cronId = $_GET['cronId'];
$identifier = $_GET['identifier'];
$status = $_GET['status'];

if(!$status){$status='N';}
$notes  = $_GET['notes'] || false;


if(!$siteId || !$cronId || !$status || !$identifier){
    $logHelper->error("Missing variables passed to API", $siteId, $cronId, $notes, $status);
}

require_once("inc/watcher.php");
$watcher = new watcher();

$watcher->log($siteId, $cronId, $identifier, $notes,$status);