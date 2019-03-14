<?php

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

require_once 'vendor/autoload.php';

$password = 'xxxxxxx';
$iterations = 10;

$times = [];
foreach (range(4, 14) as $cost) {
  $encoder = new BCryptPasswordEncoder($cost);
  for ($i = 0; $i < $iterations; $i++) {
    $times[$cost][] = benchmarkEncoding($encoder, $password);
  }
}


foreach ($times as $cost => $costTimes) {
  echo sprintf("For cost of %d: min %4.3fms, max %4.3fms, avg %4.3fms\n",
    $cost,
    min($costTimes) * 1000,
    max($costTimes) * 1000,
    array_sum($costTimes) / count($costTimes) * 1000
  );
}


function benchmarkEncoding(PasswordEncoderInterface $encoder, $password)
{
  $start = microtime(true);
  $encoder->encodePassword($password, '');
  return microtime(true) - $start;
}
