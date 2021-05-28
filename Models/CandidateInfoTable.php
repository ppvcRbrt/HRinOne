<?php

class CandidateInfoTable
{

    protected $_candidate_ID, $_name, $_email, $_refno, $_work_domain_ID; //Candidate_information table

    /**
     * Constructor of the CandidateInfoTable class
     */
    public function __construct($dbRow)
    {
        $this->_candidate_ID = $dbRow['candidate_ID']?? null;
        $this->_name = $dbRow['name']?? null;
        $this->_email= $dbRow['email']?? null;
        $this->_refno= $dbRow['refNo']?? null;
        $this->_work_domain_ID = $dbRow['work_domain_ID']?? null;
    }

    public function getID()
    {
        return $this->_candidate_ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getRefNo()
    {
        return $this->_refno;
    }

    public function getDomain()
    {
        return $this->_work_domain_ID;
    }

}