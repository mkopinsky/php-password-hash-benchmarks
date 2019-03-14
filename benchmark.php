<?php

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

require_once 'vendor/autoload.php';

$password = 'xxxxxxx';
$iterations = 5;

$encodingTimes = [];
foreach (range(4, 14) as $cost) {
  $encoder = new BCryptPasswordEncoder($cost);
  for ($i = 0; $i < $iterations; $i++) {
    $encodingTimes[$cost][] = benchmarkEncoding($encoder, $password);
  }
}

echo "HASHING TIMES:\n";
foreach ($encodingTimes as $cost => $costTimes) {
  echo sprintf("For cost of %d: min %4.3fms, max %4.3fms, avg %4.3fms\n",
    $cost,
    min($costTimes) * 1000,
    max($costTimes) * 1000,
    array_sum($costTimes) / count($costTimes) * 1000
  );
}
$validatingTimes = [];
foreach (range(4, 14) as $cost) {
  $encoder = new BCryptPasswordEncoder($cost);
  for ($i = 0; $i < $iterations; $i++) {
    $validatingTimes[$cost][] = benchmarkValidating($encoder, $password);
  }
}

echo "VALIDATING TIMES:\n";
foreach ($validatingTimes as $cost => $costTimes) {
  echo sprintf("For cost of %d: min %4.3fms, max %4.3fms, avg %4.3fms\n",
    $cost,
    min($costTimes) * 1000,
    max($costTimes) * 1000,
    array_sum($costTimes) / count($costTimes) * 1000
  );
}

function benchmarkValidating(PasswordEncoderInterface $encoder, $password)
{
  $hash = $encoder->encodePassword($password, '');
  $start = microtime(true);
  $encoder->isPasswordValid($hash, $password, '');
  return microtime(true) - $start;
}

function benchmarkEncoding(PasswordEncoderInterface $encoder, $password)
{
  $start = microtime(true);
  $encoder->encodePassword($password, '');
  return microtime(true) - $start;
}
