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
    
    if ('opRequestRegisterURLForm' == get_class($form))
    {
      $form->mergePostValidator(new sfValidatorCallback(array('callback' => array($this, 'isNotFreemail'))));
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
}