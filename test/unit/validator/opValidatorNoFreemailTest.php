<?php

include(dirname(__FILE__).'/../../bootstrap/unit.php');
$app = 'pc_frontend';
include(dirname(__FILE__).'/../../bootstrap/functional.php');

$t = new lime_test(2, new lime_output_color());

$validator = new opValidatorNoFreemail();

$okList = array('info@77-web.com');
foreach ($okList as $okmail)
{
  try
  {
    $validator->clean($okmail);
    $t->pass('"'.$okmail.'" was correctly distinguished as non-freemail.');
  }
  catch (sfValidatorError $e)
  {
    $t->fail('"'.$okmail.'" was incorrectly distinguished as freemail.');
  }
}

$ngList = array('user@yahoo.co.jp');
foreach ($ngList as $ngmail)
{
  try
  {
    $validator->clean($ngmail);
    $t->fail('"'.$ngmail.'" was incorrectly distinguished as non-freemail.');
  }
  catch (sfValidatorError $e)
  {
    $t->pass('"'.$ngmail.'" was correctly distinguished as freemail.');
  }
}