<?php

/**
 * Template actions.
 *
 * @package    symfony
 * @subpackage host
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class templateActions extends MyActions
{

  /**
   * List
   *
   */
  public function executeList()
  {
    $this->output = array();
    
    foreach (TemplatePeer::doSelect(new Criteria()) as $template)
    {
      $data = $template->toArray(BasePeer::TYPE_FIELDNAME);
      
      $records = array();
      
      foreach ($template->getTemplateRecords() as $record)
      {
        $records[] = $record->toArray(BasePeer::TYPE_FIELDNAME);
      }
      
      $data['records'] = $records;
      
      $this->output[] = $data;
    }
    
    if ($this->isAjax())
    {
      return $this->renderStore('Template',$this->output);
    }
  }
  
  /**
   * Add
   */
  public function executeAdd()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST Only."));
    }
    else
    {
      $template = new Template();
      $template->setName($this->getRequestParameter('name'));
      $template->setType($this->getRequestParameter('type'));
      $template->save();
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        $record = new TemplateRecord();
        $record->setTemplateId($template->getId());
        $record->setName($data['name']);
        $record->setType($data['type']);
        $record->setContent($data['content']);
        $record->setTtl($data['ttl']);
        
        if ($data['type'] == 'MX')
        {
          $record->setPrio($data['prio']);
        }
        
        $record->save();
      }
      
      return $this->renderJson(array("success"=>true,"info"=>"Template added."));
    }
  }
  
  public function validateAdd()
  {
    if ($this->isPOST())
    {
      $c = new Criteria();
      $c->add(TemplatePeer::NAME, $this->getRequestParameter('name'));
      
      if (TemplatePeer::doSelectOne($c))
      {
        $this->getRequest()->setError('name','Name already in use.');
        return false;
      }
      
      return $this->commonValidate();
    }
    
    return true;
  }
  
  /**
   * Edit
   */
  public function executeEdit()
  {
    if ($this->isGET())
    {
      return $this->renderJson(array("success"=>false,"info"=>"POST Only."));
    }
    else
    {
      $template = $this->template;
      $template->setName($this->getRequestParameter('name'));
      $template->setType($this->getRequestParameter('type'));
      $template->save();
      
      $ids = array();
      
      foreach ($this->getRequestParameter('record') as $data)
      {
        if (!$record = TemplateRecordPeer::retrieveByPK($data['id']))
        {
          $record = new TemplateRecord();
          $record->setTemplateId($template->getId());
          $record->setName($data['name']);
          $record->setType($data['type']);
        }

        $record->setContent($data['content']);
        $record->setTtl($data['ttl']);
        
        if ($data['type'] == 'MX')
        {
          $record->setPrio($data['prio']);
        }
        
        $record->save();
        
        $ids[] = $record->getId();
      }
      
      $c = new Criteria();
      $c->add(TemplateRecordPeer::TEMPLATE_ID, $template->getId());
      $c->add(TemplateRecordPeer::ID, $ids, Criteria::NOT_IN);
      
      TemplateRecordPeer::doDelete($c);
    
      return $this->renderJson(array("success"=>true,"info"=>"Template updated."));
    }
  }
  
  public function validateEdit()
  {
    if ($this->isPOST())
    {
      if (!$this->template = TemplatePeer::retrieveByPK($this->getRequestParameter('id')))
      {
        $this->getRequest()->setError('id','Invalid template id.');
        return false;
      }
      
      $c = new Criteria();
      $c->add(TemplatePeer::ID, $this->getRequestParameter('id'), Criteria::NOT_EQUAL);
      $c->add(TemplatePeer::NAME, $this->getRequestParameter('name'));
      
      if (TemplatePeer::doSelectOne($c))
      {
        $this->getRequest()->setError('name','Name already in use.');
        return false;
      }
      
      return $this->commonValidate();
    }
    
    return true;
  }
  
  /**
   * Common validation
   */
  private function commonValidate()
  {
    if (!is_array($this->getRequestParameter('record')))
    {
      $this->getRequest()->setError('record','record[] needs to be an array.');
      return false;
    }
    
    $i = 1;
  
    $SOA_count = 0;
    $NS_count = 0;
    
    foreach ($this->getRequestParameter('record') as $data)
    {
      
      if (!isset($data['name']) || !isset($data['type']) || !isset($data['content']) 
        || !isset($data['ttl']))
      {
        $this->getRequest()->setError('record',"Row $i: some data is missing.");
        return false;
      }
      
      if (!$data['name'])
      {
        $this->getRequest()->setError('record',"Row $i: name can't be left blank.");
        return false;
      }
      
      if (!in_array($data['type'],array("SOA","NS","MX","A","CNAME","TXT")))
      {
        $this->getRequest()->setError('record',"Row $i: invalid record type.");
        return false;
      }
      
      switch ($data['type'])
      {
        case 'SOA':
          if (!preg_match('/^[a-z,\.,0-9,-,_]+\s[a-z,\.,0-9,-,_]+%DOMAIN%\s%SERIAL%$/',$data['content']))
          {
            $this->getRequest()->setError('record',"Row $i: invalid SOA content.");
            return false;
          }
          break;
        case 'NS':
          if (!preg_match('/^[a-z,.,0-9,-,_]+$/',$data['content']))
          {
            $this->getRequest()->setError('record',"Row $i: invalid NS content.");
            return false;
          }
          break;
      }
      
      if (!preg_match('/^[0-9]+$/',$data['ttl']))
      {
        $this->getRequest()->setError('record',"Row $i: TTL has to be a number.");
        return false;
      }
      
      if ($data['ttl'] < 5 || $data['ttl'] > 86400)
      {
        $this->getRequest()->setError('record',"Row $i: TTL has to be in a range of 5-86400.");
        return false;
      }
      
      
      if ($data['type'] == 'MX')
      {
        
        if (!preg_match('/^[0-9]+$/',$data['prio']))
        {
          $this->getRequest()->setError('record',"Row $i: Prio has to be a number.");
          return false;
        }
        
        if ($data['prio'] < 0 || $data['prio'] > 100)
        {
          $this->getRequest()->setError('record',"Row $i: Prio has to be in a range of 0-100.");
          return false;
        }
      }
      
      if ($data['type'] == 'SOA') $SOA_count++;
      if ($data['type'] == 'NS') $NS_count++;
      
      $i++;
    }
    
    if ($SOA_count !== 1)
    {
      $this->getRequest()->setError('record',"Only one SOA record allowed.");
      return false;
    }
    
    if ($NS_count < 1 || $NS_count > 10)
    {
      $this->getRequest()->setError('record',"Number of NS records should be in a range of 1-10.");
      return false;
    }
    
    return true;
  }
}
