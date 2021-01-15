<?php

/**
 * Class FeedbackTable : This class will allow us to get individual columns from Feedback Queries
 */
class FeedbackTable
{

    protected $_idfeedback, $_score, $_feedback;

    public function __construct($dbRow)
    {

        $this->_idfeedback = $dbRow['feedback_id']?? null;

        $this->_score = $dbRow['score']?? null;
        $this->_feedback = $dbRow['feedback']?? null;
    }

    public function getID()
    {
        return $this->_idfeedback;
    }

    public function getScore()
    {
        return $this->_score;
    }

    public function getFeedback()
    {
        return $this->_feedback;
    }
}