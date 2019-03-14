<?php

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

require_once 'vendor/autoload.php';

$password = 'xxxxxxx';

$encoder = new BCryptPasswordEncoder(10);
$hashed = $encoder->encodePassword($password, '');

$encoder2 = new BCryptPasswordEncoder(11);
var_dump(
  'Validating password using encoder with stronger cost',
  $encoder2->isPasswordValid($hashed, $password, '')
);
//string(52) "Validating password using encoder with stronger cost"
//bool(true)


$encoder3 = new BCryptPasswordEncoder(9);
var_dump(
  'Validating password using encoder with weaker cost',
  $encoder3->isPasswordValid($hashed, $password, '')
);
//string(50) "Validating password using encoder with weaker cost"
//bool(true)
