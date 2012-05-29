<?php

class opValidatorNoFreemail extends sfValidatorBase
{
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('notallowed', 'Free mail is not allowed.');
  }
  
  protected function doClean($value)
  {
    list(, $domain) = explode('@', $value);
    $freemailDomainList = $this->getDomainList();
    
    if (in_array($domain, $freemailDomainList))
    {
      throw new sfValidatorError($this, 'notallowed');
    }
    
    return $value;
  }
  
  protected function getDomainList()
  {
    return sfConfig::get('app_freemail_domain_list', array());
  }
}