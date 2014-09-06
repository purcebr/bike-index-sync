<?php

 
class BikeIndexSyncAPI {
 
  protected $endpoint, $data;
  
  function __construct($data=null) {
    $this->endpoint = 'https://bikeindex.org/api/v1/';
    $this->data = $data;
  }
  
  function post_json($data, $action) {
    $formed_uri = $this->endpoint.'/'.$action;
    $full_url = $formed_uri . '?' . urldecode(http_build_query($data));
    $request = new WP_Http();
    $response = $request->get($full_url);
    return $response;
  }
}
 
?>
