<?php

/**
 * Class AssessmentTable. This class will allow the manipulation of data
 * regarding an Assessment template
 */
class AssessmentTable
{
    protected $_assessment_ID, $_name, $_description, $_candidate_ID, $_assessment_type_ID;

    public function __construct($dbRow)
    {

        $this->_assessment_ID = $dbRow['assessment_ID']?? null;
        $this->_name = $dbRow['name']?? null;
        $this->_description = $dbRow['description']?? null;
        $this->_candidate_ID = $dbRow['candidate_ID']?? null;
        $this->_assessment_type_ID = $dbRow['assessment_type_ID']?? null;
    }

    public function getAssessmentID()
    {
        return $this->_assessment_ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function getCandidateID()
    {
        return $this->_candidate_ID;
    }

    public function getAssessmentTypeID()
    {
        return $this->_assessment_type_ID;
    }
}