<?php

/**
 * Class FeedbackTable : This class will allow us to get individual columns from Feedback Queries
 */
class AssessorFdbkTable
{

    protected $_feedback_ID, $_question_ID, $_feedback;

    /**
     * Constructor of the AssessorFdbkTable class
     */
    public function __construct($dbRow)
    {
        $this->_feedback_ID = $dbRow['feedback_ID']?? null;
        $this->_question_ID = $dbRow['question_ID']?? null;
        $this->_feedback = $dbRow['feedback']?? null;
    }

    public function getID()
    {
        return $this->_feedback_ID;
    }

    public function getQuestionID()
    {
        return $this->_question_ID;
    }

    public function getFeedback()
    {
        return $this->_feedback;
    }
}