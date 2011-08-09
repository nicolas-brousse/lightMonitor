<?php

namespace Form;

Class Setting_Server extends Base
{
  public function init()
  {
    parent::init();

    $this->addElement('text', 'title', array(
      'required' => true,
      'validators' => array(
        array('NotEmpty', true, array('messages' => 'Titre obligatoire'))
      ),
      'attribs' => array('class' => 'text medium')
    ));
    $this->addElement('textarea', 'text', array(
      'required' => true,
      'validators' => array(
        array('NotEmpty', true, array('messages' => 'Contenu obligatoire'))
      ),
      'attribs' => array('class' => 'wysiwyg')
    ));
    $this->addElement('hidden', 'filename', array(
      'required' => true,
      'validators' => array(
        array('UploadExists', true, array('messages' => 'Aperçu incorrect')),
        array('NotEmpty', true, array('messages' => 'Image obligatoire'))
      )
    ));
    $this->getElement('filename')->setDecorators(array(
        array('ViewScript', array(
          'viewScript' => '_upload.phtml'
        ))
    ));
    
    $this->defaultFilters();
  }

  public function set(Model_New $new)
  {
    $this->filename->getValidator('UploadExists')->setToken($new->filename);

    $this->setDefaults($new->toArray());
  }
  
  protected function defaultFilters()
  {
    $this->setElementFilters(array(
      'StringTrim',
      'StripTags',
      array('Null', Zend_Filter_Null::STRING)
    ));
  }

  public function isValid($data)
  {
    if ($data['type'] == 0)
      $this->getElement('max_price')->setRequired(false)->setAllowEmpty(true);
    else
      $this->getElement('max_price')->setRequired(true)->addValidator('NotEmpty', true, array('messages' => 'Montant du plafond obligatoire'));

    return parent::isValid($data);
  }
}