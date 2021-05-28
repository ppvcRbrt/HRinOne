<?php

class DomainTable
{

    protected $_work_domain_ID, $_work_domain; //Work_domain table

    /**
     * Constructor of the DomainTable class
     */
    public function __construct($dbRow)
    {
        $this->_work_domain_ID = $dbRow['work_domain_ID']?? null;
        $this->_work_domain = $dbRow['domain_name']?? null;
    }

    public function getID()
    {
        return $this->_work_domain_ID;
    }

    public function getDomain()
    {
        return $this->_work_domain;
    }
}
