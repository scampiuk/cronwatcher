<?php

/**
 * Cronwatcher class to talk to the cronwatcher service.
 *
 */

class cronwatcherClient {

    var $_endpoint   = "http://blah.com/api.php";
    var $_useCurl = true; // Use curl by default.
    var $cronId = null;
    var $siteId = null;
    var $note = null;
    var $identifier = null;
    var $status = null;
    var $_error = null;

    /**
     * @param Integer $siteId The uniqie site ID number provided for the site being monitored (Required)
     * @param Integer $cronId A number to identify your cron job. (Required)
     */
    function __construct($siteId, $cronId){
        $this->identifier = md5(rand(0,10000) + time() + $siteId + $cronId); // Generate the unique identifier for this call.
    }

    /**
     * Starts a cronwatch timer
     * @return bool
     */
    function start(){
        $this->status = "S";
        return $this->talk();
    }

    /**
     * Ends the cronwatch timer
     * @return bool
     */
    function end(){
        $this->status = "C";
        return $this->talk();
    }


    /**
     * Init's the call to the endpoint, returns a very rough determination of success or failure.
     * @return bool
     */
    private function talk(){
        try {
            $url = $this->_endpoint . "?siteId=" . (int)$this->siteId . "&cronId=" . (int)$this->cronId . "&identifier=" . urlencode($this->identifier) . "&status=" . $this->status;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2); // timeout after 2 seconds, don't hold up the other scripts.
            curl_exec($ch);
            return true;
        } catch (Exception $e){
            $this->_error = $;
            return false;
        }
    }
}