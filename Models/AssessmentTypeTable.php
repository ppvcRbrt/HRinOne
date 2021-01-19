<?php

/**
 * Class AssessmentTypeTable: This class will allow us to get individual columns from Assessment type queries
 */
class AssessmentTypeTable
{

    protected $_assessment_type_ID, $_name, $_description, $_work_domain_ID;

    public function __construct($dbRow)
    {
        $this->_assessment_type_ID = $dbRow['assessment_type_ID']?? null;
        $this->_name = $dbRow['name']?? null;
        $this->_description = $dbRow['description']?? null;
        $this->_work_domain_ID = $dbRow['work_domain_ID']?? null;
    }

    public function getAssessmentTypeID()
    {
        return $this->_assessment_type_ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function getWorkDomains()
    {
        return $this->_work_domain_ID;
    }
}