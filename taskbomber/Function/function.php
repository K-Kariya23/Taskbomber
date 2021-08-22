<?php

function calculate_time($day){
    $day1 = new DateTime(date("Y-m-d"));
    $day2 = new DateTime($day);
    $interval = $day1->diff($day2);
    return $interval->format('%a');
}

?>