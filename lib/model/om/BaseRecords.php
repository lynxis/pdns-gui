<?php


abstract class BaseRecords extends BaseObject  implements Persistent {


	
	protected static $peer;


	
	protected $id;


	
	protected $domain_id;


	
	protected $name;


	
	protected $type;


	
	protected $content;


	
	protected $ttl;


	
	protected $prio;


	
	protected $change_date;

	
	protected $alreadyInSave = false;

	
	protected $alreadyInValidation = false;

	
	public function getId()
	{

		return $this->id;
	}

	
	public function getDomainId()
	{

		return $this->domain_id;
	}

	
	public function getName()
	{

		return $this->name;
	}

	
	public function getType()
	{

		return $this->type;
	}

	
	public function getContent()
	{

		return $this->content;
	}

	
	public function getTtl()
	{

		return $this->ttl;
	}

	
	public function getPrio()
	{

		return $this->prio;
	}

	
	public function getChangeDate()
	{

		return $this->change_date;
	}

	
	public function setId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->id !== $v) {
			$this->id = $v;
			$this->modifiedColumns[] = RecordsPeer::ID;
		}

	} 
	
	public function setDomainId($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->domain_id !== $v) {
			$this->domain_id = $v;
			$this->modifiedColumns[] = RecordsPeer::DOMAIN_ID;
		}

	} 
	
	public function setName($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->name !== $v) {
			$this->name = $v;
			$this->modifiedColumns[] = RecordsPeer::NAME;
		}

	} 
	
	public function setType($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->type !== $v) {
			$this->type = $v;
			$this->modifiedColumns[] = RecordsPeer::TYPE;
		}

	} 
	
	public function setContent($v)
	{

		
		
		if ($v !== null && !is_string($v)) {
			$v = (string) $v; 
		}

		if ($this->content !== $v) {
			$this->content = $v;
			$this->modifiedColumns[] = RecordsPeer::CONTENT;
		}

	} 
	
	public function setTtl($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->ttl !== $v) {
			$this->ttl = $v;
			$this->modifiedColumns[] = RecordsPeer::TTL;
		}

	} 
	
	public function setPrio($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->prio !== $v) {
			$this->prio = $v;
			$this->modifiedColumns[] = RecordsPeer::PRIO;
		}

	} 
	
	public function setChangeDate($v)
	{

		
		
		if ($v !== null && !is_int($v) && is_numeric($v)) {
			$v = (int) $v;
		}

		if ($this->change_date !== $v) {
			$this->change_date = $v;
			$this->modifiedColumns[] = RecordsPeer::CHANGE_DATE;
		}

	} 
	
	public function hydrate(ResultSet $rs, $startcol = 1)
	{
		try {

			$this->id = $rs->getInt($startcol + 0);

			$this->domain_id = $rs->getInt($startcol + 1);

			$this->name = $rs->getString($startcol + 2);

			$this->type = $rs->getString($startcol + 3);

			$this->content = $rs->getString($startcol + 4);

			$this->ttl = $rs->getInt($startcol + 5);

			$this->prio = $rs->getInt($startcol + 6);

			$this->change_date = $rs->getInt($startcol + 7);

			$this->resetModified();

			$this->setNew(false);

						return $startcol + 8; 
		} catch (Exception $e) {
			throw new PropelException("Error populating Records object", $e);
		}
	}

	
	public function delete($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(RecordsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			RecordsPeer::doDelete($this, $con);
			$this->setDeleted(true);
			$con->commit();
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	public function save($con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(RecordsPeer::DATABASE_NAME);
		}

		try {
			$con->begin();
			$affectedRows = $this->doSave($con);
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollback();
			throw $e;
		}
	}

	
	protected function doSave($con)
	{
		$affectedRows = 0; 		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;


						if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = RecordsPeer::doInsert($this, $con);
					$affectedRows += 1; 										 										 
					$this->setId($pk);  
					$this->setNew(false);
				} else {
					$affectedRows += RecordsPeer::doUpdate($this, $con);
				}
				$this->resetModified(); 			}

			$this->alreadyInSave = false;
		}
		return $affectedRows;
	} 
	
	protected $validationFailures = array();

	
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = RecordsPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = RecordsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->getByPosition($pos);
	}

	
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getId();
				break;
			case 1:
				return $this->getDomainId();
				break;
			case 2:
				return $this->getName();
				break;
			case 3:
				return $this->getType();
				break;
			case 4:
				return $this->getContent();
				break;
			case 5:
				return $this->getTtl();
				break;
			case 6:
				return $this->getPrio();
				break;
			case 7:
				return $this->getChangeDate();
				break;
			default:
				return null;
				break;
		} 	}

	
	public function toArray($keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = RecordsPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getId(),
			$keys[1] => $this->getDomainId(),
			$keys[2] => $this->getName(),
			$keys[3] => $this->getType(),
			$keys[4] => $this->getContent(),
			$keys[5] => $this->getTtl(),
			$keys[6] => $this->getPrio(),
			$keys[7] => $this->getChangeDate(),
		);
		return $result;
	}

	
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = RecordsPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setId($value);
				break;
			case 1:
				$this->setDomainId($value);
				break;
			case 2:
				$this->setName($value);
				break;
			case 3:
				$this->setType($value);
				break;
			case 4:
				$this->setContent($value);
				break;
			case 5:
				$this->setTtl($value);
				break;
			case 6:
				$this->setPrio($value);
				break;
			case 7:
				$this->setChangeDate($value);
				break;
		} 	}

	
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = RecordsPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDomainId($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setName($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setType($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setContent($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setTtl($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setPrio($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setChangeDate($arr[$keys[7]]);
	}

	
	public function buildCriteria()
	{
		$criteria = new Criteria(RecordsPeer::DATABASE_NAME);

		if ($this->isColumnModified(RecordsPeer::ID)) $criteria->add(RecordsPeer::ID, $this->id);
		if ($this->isColumnModified(RecordsPeer::DOMAIN_ID)) $criteria->add(RecordsPeer::DOMAIN_ID, $this->domain_id);
		if ($this->isColumnModified(RecordsPeer::NAME)) $criteria->add(RecordsPeer::NAME, $this->name);
		if ($this->isColumnModified(RecordsPeer::TYPE)) $criteria->add(RecordsPeer::TYPE, $this->type);
		if ($this->isColumnModified(RecordsPeer::CONTENT)) $criteria->add(RecordsPeer::CONTENT, $this->content);
		if ($this->isColumnModified(RecordsPeer::TTL)) $criteria->add(RecordsPeer::TTL, $this->ttl);
		if ($this->isColumnModified(RecordsPeer::PRIO)) $criteria->add(RecordsPeer::PRIO, $this->prio);
		if ($this->isColumnModified(RecordsPeer::CHANGE_DATE)) $criteria->add(RecordsPeer::CHANGE_DATE, $this->change_date);

		return $criteria;
	}

	
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(RecordsPeer::DATABASE_NAME);

		$criteria->add(RecordsPeer::ID, $this->id);

		return $criteria;
	}

	
	public function getPrimaryKey()
	{
		return $this->getId();
	}

	
	public function setPrimaryKey($key)
	{
		$this->setId($key);
	}

	
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDomainId($this->domain_id);

		$copyObj->setName($this->name);

		$copyObj->setType($this->type);

		$copyObj->setContent($this->content);

		$copyObj->setTtl($this->ttl);

		$copyObj->setPrio($this->prio);

		$copyObj->setChangeDate($this->change_date);


		$copyObj->setNew(true);

		$copyObj->setId(NULL); 
	}

	
	public function copy($deepCopy = false)
	{
				$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RecordsPeer();
		}
		return self::$peer;
	}

} 