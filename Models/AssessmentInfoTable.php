<?php

/**
 * Class AssessmentInfoTable. This class will allow the manipulation of data
 * regarding an Assessment_info table
 */
class AssessmentInfoTable
{
    protected $_assessment_info_ID, $_assessment_ID, $_section_ID, $_question_ID, $_indicator_ID;

    /**
     * Constructor of the AssessmentInfoTable class
     */

    public function __construct($dbRow)
    {
        $this->_assessment_info_ID = $dbRow['assessment_info_ID']?? null;
        $this->_assessment_ID = $dbRow['assessment_ID']?? null;
        $this->_section_ID = $dbRow['section_ID']?? null;
        $this->_question_ID = $dbRow['question_ID']?? null;
        $this->_indicator_ID = $dbRow['indicator_ID']?? null;
    }

    public function getAssessmentInfoID()
    {
        return $this->_assessment_info_ID;
    }

    public function getAssessmentID()
    {
        return $this->_assessment_ID;
    }

    public function getSectionID()
    {
        return $this->_section_ID;
    }

    public function getQuestionID()
    {
        return $this->_question_ID;
    }

    public function getIndicatorID()
    {
        return $this->_indicator_ID;
    }

}