<?php

  $sit_message = '{
          "MsgType":"core_release_msg",
          \"RelTag\":\"010.000.040\",
          \"GlobalPackages\":
          [ 
          {
            \"Package\": {
              \"Name\":\"gts-3pp-jboss-eap-6.2.0\",
              \"Version\":\"2.0.10\",
              \"Release\":\"9000950.11.ga.el6.vendor.6.2.0\",
              \"Arch\":\"noarch\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-env-mgr-tools\",
              \"Version\":\"1.4.95\",
              \"Release\":\"10000010.21.ga.el6\",
              \"Arch\":\"noarch\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-ise-requirements-acceptance\",
              \"Version\":\"0.1.130\",
              \"Release\":\"10000000.299.ga.el6\",
              \"Arch\":\"x86_64\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-ise-system-config\",
              \"Version\":\"0.1.0\",
              \"Release\":\"9000950.2.ga.el6\",
              \"Arch\":\"x86_64\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-system-accounts\",
              \"Version\":\"1.2.0\",
              \"Release\":\"9000950.2.ga.el6\",
              \"Arch\":\"noarch\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-system-config\",
              \"Version\":\"1.3.0\",
              \"Release\":\"9000950.2.ga.el6\",
              \"Arch\":\"noarch\",
              \"Epoch\":\"(none)\" }
          },
          {
            \"Package\": {
              \"Name\":\"gts-yum-deployer\",
              \"Version\":\"1.11.10\",
              \"Release\":\"9000950.2.ga.el6\",
              \"Arch\":\"x86_64\",
              \"Epoch\":\"(none)\" }
          }
          ],
          \"GTSPackages\":
          [ 
          {
            \"Package\": {
              \"Name\":\"gts-ise-release-acceptance-environment\",
              \"Version\":\"10.0.40\",
              \"Release\":\"22.ga.el6\",
              \"Arch\":\"x86_64\",
              \"Epoch\":\"(none)\" }
          }
          ]
        }';


  //$sit_message = '{ "MsgType":"core_release_msg", "SchemaVersion":"1.0.0", "RepoName":"CMQA", "RelTag":"10.0.40", "GlobalPackages":[ { "Package":{ "Name":"gts-3pp-jboss-eap-6.2.0", "Version":"6.2.0", "Release":"2.0.10-9000950.11.ga.el6.vendor.6.2.0", "Arch":"noarch" } }, { "Package":{ "Name":"gts-env-mgr-tools", "Version":"1.4.95", "Release":"10000010.21.ga.el6", "Arch":"noarch" } } ], "GTSPackages":[ { "Package":{ "Name":"gts-ise-release-acceptance-environment", "Version":"10.0.40", "Release":"22.ga.el6", "Arch":"noarch" } } ] }';
  $sit_message = preg_replace( "/\r|\n/", "", $sit_message);
  $sit_message = stripslashes($sit_message);
  echo $sit_message;
  $json_message = json_decode($sit_message);
  $send_msg = json_encode($json_message);
  //$json_again = json_decode($send_msg);
  //echo "<br>";
  //echo $json_again;
  //file_put_contents("json_from_sit.log", $sit_message);
  
  //$host = "51.4.67.61";
	$host = "51.3.66.90"; //PAT server
	$port = "18900";
  
	//$send_msg = $sit_message;
	$length = strlen($send_msg);
	$socket = socket_create(AF_INET,SOCK_STREAM,0)or die("Could not create socket\n");
	
	//socket_set_option($socket, SOL_SOCKET,SO_SNDBUF,5000) or die("Could not create socket\n");
	
	$result = socket_connect($socket,$host,$port) or die("Could not connect to M.A.P. Server\n");
	
	$sent = socket_write($socket,$send_msg,$length) or die("Could not send json data to M.A.P. Server\n");
  //$sent = socket_send($socket,$send_msg,$length,MSG_WAITALL);
	
	$result = socket_read($socket,1024) or die("Could not read from M.A.P. Server\n");
	
	socket_close($socket);
  echo "<br>";
  echo "<br>original msg is: ".strlen($sit_message);
  echo "<br>msg is: ".$length;
  echo "<br>sent: ".$sent;

?>