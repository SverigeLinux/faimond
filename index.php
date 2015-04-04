<?php
function get_monitor_entries($states) {
  $con=mysql_connect('localhost', '@FAIMOND_DB_USER@', '@FAIMOND_DB_PWD@');
  mysql_select_db('faimond', $con);
  //$ordered_states = '';
  //$print_r($states);
  //foreach ($state in $states) {
  //  $ordered_states = $ordered_states.', '.$state;
  //}
  $sql = "SELECT * FROM host_entries ORDER BY created_time DESC"; // LIMIT ".$num;
  $query=mysql_query($sql,$con);

     print '<div class="scrollable-table">';
      print '<table class="table table-striped table-header-rotated">';
      print '<thead>';
      print '<tr>';
      print '<th>hostname</th>';

      foreach ($states as $state) {
        print "  <th class='rotate-45'><div><span>$state</span></div></th>";
      }

      print " </tr>\n";
      print '</thead>';
      print '<tbody>';


while ( $db_field = mysql_fetch_assoc($query) ) {


print "<tr><th class='row-header'><a href='/failogview/index.php?hostname=" . $db_field['host'] . "'>".$db_field['host']."</a>&nbsp;&nbsp;<span class='date'>(" . $db_field['created_time'] . ")</span></th>\n";
print "<td>" . process_tasks($db_field['task_confdir']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_setup']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_defclass']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_defvar']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_partition']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_mountdisks']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_extrbase']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_mirror']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_debconf']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_prepareapt']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_updatebase']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_instsoft']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_configure']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_finish']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_tests']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_savelog']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_faiend']) . "</td>\n";
print "<td>" . process_tasks($db_field['task_reboot']) . "</td>\n";
//print "<td><span class='date'>" . $db_field['lastmodified_time'] . "</span></td></tr>\n";

}
      print '</tbody>';
      print "</table>\n";


mysql_close($con);
}

function process_tasks($taskentry) {

      $results = $taskentry;
      # Process each line.
        $line=explode(' ',$results);
        $action=$line[0];
        $result=array_key_exists(1,$line)?$line[1]:'';

        switch ($action) {
          case 'TASKBEGIN':
            $status='arrow running';
            break;
          case 'TASKEND':
            $status=($result==='0'?'tick completed':'cross failed_result_'.$result);
            break;
          case 'HOOK':
            $status='hook running_'.$line[2];
            break;
          case 'TASKSKIP':
            $status='skip skipped';
            break;
          case 'FAIREBOOT':
            $status='cancel manually_rebooted';
            break;
          default:
            $status='unknown -';
        }

          $inf=explode(' ',$status);
          $img=$inf[0].'.png';
          $alt=$inf[1];
          return "<img src='css/".$img."' title='".$alt."'   alt='".$alt."' />";
}


# The name of file where faimond logs
# $filename='/var/log/faimond.log';
# $n=(array_key_exists('rows',$_REQUEST)?0+$_REQUEST['rows']:60);
header( "refresh:5;url=".$_SERVER['REQUEST_URI']);
# $mtime=filemtime ($filename );
?>

<!DOCTYPE html>
<html>
  <head>
    <title>FAI transactions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/faimond.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="faibody">
      <h1>FAI Install monitor</h1>
      <?php
      # Tasks we care about in order of execution
      $states=array(
        'confdir', 'setup', 'defclass', 'defvar', 'partition', 'mountdisks', 'extrbase',
        'mirror', 'debconf', 'prepareapt', 'updatebase', 'instsoft', 'configure',
        'finish', 'tests', 'savelog', 'faiend', 'reboot');
        # Tasks we don't care about, which might be added to $states if we do
        #'chboot',

      $query = get_monitor_entries($states);

      print '<div class="scrollable-table">';
      print '<table class="table table-striped table-header-rotated">';
      print '<thead>';
      print '<tbody>';
      print '<th>Icons:</th><th>Meaning:</th>';
      print '<tr><td class="legend"><img src="css/tick.png"></td><td>Successful</td></tr>';
      print '<tr><td class="legend"><img src="css/arrow.png"></td><td>In progress</td></tr>';
      print '<tr><td class="legend"><img src="css/cross.png"></td><td>Error (tooltip)</td></tr>';
      print '<tr><td class="legend"><img src="css/cancel.png"></td><td>Manual reboot initiated</td></tr>';
      print '</tbody>';
      print '</table>';

      print "Page last loaded : <b>".strftime('%c')."</b></p>";
      print "<p class='info'><a href='index.php'>More data</a>&nbsp;<a href='index.php'>Fewer data</a>\n";
    ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </div>
  </body>
</html>

