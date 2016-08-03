
<?php
echo phpversion();
echo "<br>";
$event_name = 'sit_complete';
$event_name = preg_replace('/_/',' ',$event_name);
  //echo $event_name;

//$test_array = array('label1'=>array(1,2,3),'label2'=>array(1,2,3));
  $test_array = array();
  $test_array['label1'][] = 1;
  $test_array['label1'][] = 2;
  $test_array['label1'][] = 3;
  $test_array['label2'][] = 1;
  $test_array['label2'][] = 2;
  $test_array['label2'][] = 3;
foreach($test_array as $key=>$values)
{
    echo $key;
    echo "<br>";
    foreach($values as $value)
    {
        echo $value;
        echo " | ";
    }
    echo "<br>";
}


$t1 = array('test1','test2');
$counter = array(0,1);
echo $t1[$counter[0]];
?>
