<?php

// $weight = [];
// function innit(&$weight)
// {
//     for ($i = 0; $i < 100000; $i++) {
//         $values[$i] = $i;
//         $weight[$i] = mt_rand(1, 1000);
//     }
// }

// innit($weight);
$weight = [
    5 => 35,
    1 => 5,
    2 => 10,
    3 => 20,
    4 => 30,
];
function linear_random($weight)
{
    $sum = array_sum($weight);
    $n = mt_rand(1, $sum);
    foreach ($weight as $key => $value) {
        if ($n <= $value) {
            return $key;
        }
        $n = $n - $value;
    }
}

function sort_linear_random($weight)
{
    arsort($weight);
    $sum = array_sum($weight);
    $n = mt_rand(1, $sum);
    foreach ($weight as $key => $value) {
        if ($n <= $value) {
            return $key;
        }
        $n = $n - $value;
    }
}

function jump_random($weight)
{
    arsort($weight);
    $add = 0;
    $pool = [];
    foreach ($weight as $key => $value) {
        $pool[] = [$key, $value + $add];
        $add += $value;
    }
    $sum_weight = $add;
    $pool_length = count($pool);
    $n = mt_rand(1, $sum_weight);
    $i = 0;
    while ($i < $pool_length - 1) {
        list($key, $weight) = $pool[$i];
        if ($weight > $n) {
            return $key;
        } else {
            $mutiple = round($n / $weight);
            $i += $mutiple;
        }
    }
    return $pool[$i][0];
}

function binary_random($weight)
{
    $add = 0;
    $pool = [];
    foreach ($weight as $key => $value) {
        $pool[] = [$key, $value + $add];
        $add += $value;
    }
    $sum_weight = $add;
    $pool_length = count($pool);
    $n = mt_rand(1, $sum_weight);
    list($left, $mid, $right) = [0, 0, $pool_length - 1];
    var_dump($pool, $n);
    while ($left <$right){
        $mid = floor(($right + $left)/2);
        list($key, $mid_num) = $pool[$mid];
        var_dump($left,$right, $mid, $mid_num);
        if($mid_num <$n){
            $left = $mid+1;
        }elseif($mid_num >$n){
            $right = $mid;
        }else{
            return $key;
        }
    }
    return $pool[$mid][0];
}
// $fuc = ['linear_random' =>linear_random($weight),'sort_linear_random' =>sort_linear_random($weight)];
// $res = prepare_weighted_random1($values, $weight);
// var_dump($fuc[1]);
var_dump(binary_random($weight));die;
$count = 100000;
$ratio = [];
$count_arr = [];
for ($i=1; $i <= $count ; $i++) {
    $key = binary_random($weight);
    $count_arr[$key] +=1;
}
foreach ($count_arr as $key => $value) {
    $ratio[$key] = $count_arr[$key]/$count;
}
var_dump($count_arr,$ratio);die;
$start_time = microtime(true);
var_dump($start_time);
$result['start_time'] = ($start_time ) . 'ms';
$key = linear_random($weight);
var_dump($key);die;
$linear_random = microtime(true);
var_dump($linear_random);
$result['linear_random'] = ($linear_random  - $start_time ) . 'ms';
sort_linear_random($weight);
$sort_linear_random = microtime(true);
var_dump($sort_linear_random);
$result['sort_linear_random'] = ($sort_linear_random  - $linear_random ) . 'ms';
jump_random($weight);
$jump_random = microtime(true);
var_dump($jump_random);
$result['jump_random'] = ($jump_random  - $sort_linear_random ) . 'ms';

var_dump($result);