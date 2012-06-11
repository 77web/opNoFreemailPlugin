<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser->setCulture('en');

$browser
  ->info('using freemail address is not allowed.')
  ->get('/opAuthMailAddress/requestRegisterURL')
  ->setField('request_register_url[mail_address]', 'user@yahoo.co.jp')
  ->click('Send')
  
  ->with('request')->begin()
    ->isParameter('module', 'opAuthMailAddress')
    ->isParameter('action', 'requestRegisterURL')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('.error_list li:contains("Free mail is not allowed.")')
  ->end();

$browser
  ->info('using non-freemail address is still allowed.')
  ->get('/opAuthMailAddress/requestRegisterURL')
  ->setField('request_register_url[mail_address]', 'info@77-web.com')
  ->click('Send')
  
  ->with('request')->begin()
    ->isParameter('module', 'opAuthMailAddress')
    ->isParameter('action', 'requestRegisterURL')
  ->end()
  ->with('response')->begin()
    ->isStatusCode(200)
    ->checkElement('#Center', '/Begin your registration from a URL in the mail./')
  ->end()
;