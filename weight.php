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


class AliasMethod
{
    private $length;
    private $prob_arr;
    private $alias;

    public function __construct($pdf)
    {
        $this->length = 0;
        $this->prob_arr = $this->alias = array();
        $this->_init($pdf);
    }
    private function _init($pdf)
    {
        $this->length = count($pdf);
        if ($this->length == 0)
            die("pdf is empty");
        if (array_sum($pdf) != 1.0)
            die("pdf sum not equal 1, sum:" . array_sum($pdf));

        $small = $large = array();
        for ($i = 0; $i < $this->length; $i++) {
            $pdf[$i] *= $this->length;
            if ($pdf[$i] < 1.0)
                $small[] = $i;
            else
                $large[] = $i;
        }

        while (count($small) != 0 && count($large) != 0) {
            $s_index = array_shift($small);
            $l_index = array_shift($large);
            $this->prob_arr[$s_index] = $pdf[$s_index];
            $this->alias[$s_index] = $l_index;
            
            $pdf[$l_index] -= 1.0 - $pdf[$s_index];
            
            if ($pdf[$l_index] < 1.0)
                $small[] = $l_index;
            else
                $large[] = $l_index;
            
        }
        while (!empty($small))
            $this->prob_arr[array_shift($small)] = 1.0;
        while (!empty($large))
            $this->prob_arr[array_shift($large)] = 1.0;
        
    }
    public function next_rand()
    {
        $column = mt_rand(0, $this->length - 1);
        return mt_rand() / mt_getrandmax() < $this->prob_arr[$column] ? $column : $this->alias[$column];
    }
}
// $fuc = ['linear_random' =>linear_random($weight),'sort_linear_random' =>sort_linear_random($weight)];
// $res = prepare_weighted_random1($values, $weight);
// var_dump($fuc[1]);

$aliasweight = [0.1, 0.3, 0.2, 0.4];
$table = new AliasMethod($aliasweight);

// var_dump($table->next_rand());die;
// var_dump(binary_random($weight));die;
$count = 1000000;
$ratio = [];
$count_arr = [];
for ($i=1; $i <= $count ; $i++) {
    $key = $table->next_rand();
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