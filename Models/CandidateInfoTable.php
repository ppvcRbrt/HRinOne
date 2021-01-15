<?php

class CandidateInfoTable
{

    protected $_candidate_ID, $_name, $_email; //Candidate_information table


    public function __construct($dbRow)
    {

        $this->_candidate_ID = $dbRow['ID']?? null;
        $this->_name = $dbRow['name']?? null;
        $this->_email= $dbRow['email']?? null;
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
}