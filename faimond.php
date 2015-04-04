#!/usr/bin/php -q
<?php
/*********functions to update database******************/

function create_new_monitor_entry($host) {
  $con=mysql_connect('localhost', 'root', 'fai');
  mysql_select_db('faimond', $con);
  $sql = "INSERT INTO host_entries (host) VALUES ('".$host."')";
  echo $sql;
  $query=mysql_query($sql,$con);
  echo $query."\n";
  mysql_close($con);
}

function update_monitor_entry($host, $action, $task, $result) {
  $con=mysql_connect('localhost', 'root', 'fai');
  mysql_select_db('faimond', $con);
  $sql = "UPDATE host_entries SET task_".$task." = '".$action.' '.$result."' WHERE host = '".$host."' ORDER BY created_time DESC LIMIT 1";
  $query=mysql_query($sql,$con);
  mysql_close($con);
}


/*************************************/
/********Socket Server*********************/
set_time_limit(0);
// Set the ip and port we will listen on
$address = '127.0.0.1';
$port = 4711;
// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0); // 0 for  SQL_TCP
// Bind the socket to an address/port
socket_bind($sock, 0, $port) or die('Could not bind to address');  //0 for localhost
// Start listening for connections
socket_listen($sock);
//loop and listen
while (true) {
    /* Accept incoming  requests and handle them as child processes */
    $client =  socket_accept($sock);
    // Read the input  from the client – 1024000 bytes
    $input =  socket_read($client, 1024);
    $input = str_replace(array("\n", "\t", "\r"), '', $input);
    // Strip all white  spaces from input
    echo $input;
    $output = explode(' ', $input);
    $host = $output[0];
    $action = $output[1];
    $task = array_key_exists(2,$output)?$output[2]:'';
    $result = array_key_exists(3,$output)?$output[3]:'';
    echo $host." ".$action." ".$result."\n";

    switch ($action) {
      case 'check':
        echo "Creating new host entry for host: ".$host;
        create_new_monitor_entry($host);
        break;
      case 'TASKBEGIN':
        //echo "Updating host entry for host: ".$host;
        update_monitor_entry($host, $action, $task, '');
        break;
      case 'TASKEND':
        update_monitor_entry($host, $action, $task, $result);
        break;
      case 'HOOK':
        update_monitor_entry($host, $action, $task, $result);
        break;
      case 'TASKSKIP':
        update_monitor_entry($host, $action, $task, '');
        break;
      case 'FAIREBOOT':
        echo "Rebooting ".$host."! \n";
        update_monitor_entry($host, $action, 'reboot', '');
      case 'default':
        break;
    }
    // Display output  back to client
    //socket_write($client, $response);
    socket_close($client);
}

// Close the master sockets
socket_close($sock);

?>
