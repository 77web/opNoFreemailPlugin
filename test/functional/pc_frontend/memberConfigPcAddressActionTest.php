<?php

include dirname(__FILE__).'/../../bootstrap/functional.php';
include dirname(__FILE__).'/../../bootstrap/database.php';

$browser = new opTestFunctional(new opBrowser(), new lime_test(null, new lime_output_color()));
$browser->setCulture('en');

$browser->login('sns@example.com', 'password')->with('user')->isAuthenticated()->setCulture('en');
sfConfig::set('op_is_use_captcha', false);

$browser
  ->get('/member/config/category/pcAddress')
    ->with('request')->begin()
      ->isParameter('module', 'member')
      ->isParameter('action', 'config')
      ->isParameter('category', 'pcAddress')
    ->end()
    ->with('response')->begin()
      ->isStatusCode(200)
      ->checkElement('#pcAddressForm')
    ->end()
    ->setField('member_config[pc_address]', 'user@yahoo.co.jp')
    ->setField('member_config[pc_address_confirm]', 'user@yahoo.co.jp')
    ->click('Send')
    
    ->with('request')->begin()
      ->isParameter('module', 'member')
      ->isParameter('action', 'config')
      ->isParameter('category', 'pcAddress')
    ->end()
    ->with('response')->begin()
      ->isStatusCode(200)
      ->checkElement('.error_list li:contains("Free mail is not allowed.")')
    ->end()
;

sfConfig::set('op_is_use_captcha', false);
$browser
  ->get('/member/config/category/pcAddress')
    ->setField('member_config[pc_address]', 'sns2@example.com')
    ->setField('member_config[pc_address_confirm]', 'sns2@example.com')
    ->click('Send')
    
    ->with('response')->begin()
      ->isStatusCode(302)
    ->end()
    ->followRedirect()
    
    ->isForwardedTo('member', 'config')
;