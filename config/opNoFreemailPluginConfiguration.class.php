<?php

class opNoFreemailPluginConfiguration extends sfPluginConfiguration
{
  public function configure()
  {
    $this->dispatcher->connect('form.post_configure', array($this, 'mergeNoFreemailValidator'));
  }
  
  public function mergeNoFreemailValidator(sfEvent $event)
  {
    $form = $event->getSubject();
    $formName = get_class($form);
    
    if ('opRequestRegisterURLForm' == $formName)
    {
      $form->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'isNotFreemail'))));
    }
    elseif ('MemberConfigPcAddressForm' == $formName)
    {
      $form->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'isNotChangingToFreemail'))));
    }
  }
  
  public function isNotFreemail($validator, $values, $arguments = array())
  {
    if (isset($values['mail_address']))
    {
      $noFreemailValidator = new opValidatorNoFreemail();
      
      try
      {
        $noFreemailValidator->clean($values['mail_address']);
      }
      catch (sfValidatorError $e)
      {
        throw new sfValidatorErrorSchema($validator, array('mail_address' => $e));
      }
    }
    
    return $values;
  }
  
  public function isNotChangingToFreemail($validator, $values, $arguments = array())
  {
    if (isset($values['pc_address']))
    {
      $noFreemailValidator = new opValidatorNoFreemail();
      
      try
      {
        $noFreemailValidator->clean($values['pc_address']);
      }
      catch (sfValidatorError $e)
      {
        throw new sfValidatorErrorSchema($validator, array('pc_address' => $e));
      }
    }
    
    return $values;
  }
}