<?php

 
class BikeIndexAPI {
 
  protected $endpoint, $data;
  
  function __construct($data=null) {
    $this->endpoint = 'https://bikeindex.org/api/v1/';
    $this->data = $data;
  }
  
  function post_json($data, $action, $method = "GET") {
    $formed_uri = $this->endpoint.'/'.$action;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $formed_uri);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 20);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
    
    if(isset($data)) {
      $data = json_encode($data);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    
    $json_return_data = curl_exec($ch);
    return $json_return_data;
  }
  
  function post_media($data=null, $action="uploadAudio", $method = "POST") {
    $formed_uri = $this->endpoint.'/'.$action;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($ch, CURLOPT_URL, $formed_uri);
    curl_setopt($ch, CURLOPT_POST, true);
    
    if(isset($data)) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    
    $return_data = curl_exec($ch);
    return $return_data;
  }
 
}
 
?>
