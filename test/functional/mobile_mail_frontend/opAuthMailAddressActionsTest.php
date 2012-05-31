<?php

include dirname(__FILE__).'/../../bootstrap/unit.php';

$configuration = ProjectConfiguration::getApplicationConfiguration('mobile_mail_frontend', 'test', true);

new sfDatabaseManager($configuration);

$task = new sfDoctrineBuildTask($configuration->getEventDispatcher(), new sfFormatter());
$task->setConfiguration($configuration);
$task->run(array(), array(
  'no-confirmation' => true,
  'db'              => true,
  'and-load'        => dirname(__FILE__).'/../../fixtures',
));

$t = new lime_test(3, new lime_output_color());
$ngMails = array('user@yahoo.co.jp', 'user2@hotmail.co.jp');
foreach ($ngMails as $mail)
{
  $returnMail = getReturnMail($mail);
  $t->ok('' == $returnMail, '"'.$mail.'" is correctly recognized as freemail.');
}

$okMails = array('info@77-web.com');
foreach ($okMails as $mail)
{
  $returnMail = getReturnMail($mail);
  $t->ok('' != $returnMail, '"'.$mail.'" is correctly recognized as non-freemail.');
}


function getReturnMail($mail)
{
  $testmail = file_get_contents(dirname(__FILE__).'/../../data/testmail.txt');
  
  opApplicationConfiguration::registerZend();
  
  $message = new opMailMessage(array('raw' => sprintf($testmail, $mail)));
  opMailRequest::setMailMessage($message);
  
  opApplicationConfiguration::unregisterZend();
  
  global $configuration;
  $context = sfContext::createInstance($configuration);
  
  ob_start();
  $context->getController()->dispatch();
  
  return ob_get_clean();
}