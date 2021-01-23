<?php

class IndicatorsTable
{

    protected $_indicator_ID, $name, $_indicator_description, $_feedback, $_score, $_weight, $question_ID; //Indicators table

    /**
     * Constructor of the IndicatorsTable class
     */
    public function __construct($dbRow)
    {
        $this->_indicator_ID = $dbRow['indicator_ID']?? null;
        $this->_indicator_description = $dbRow['description']?? null;
        $this->_feedback = $dbRow['feedback']?? null;
        $this->_score = $dbRow['score']?? null;
        $this->_weight = $dbRow['weight']?? null;
        $this->question_ID = $dbRow['question_ID']?? null;
    }

    public function getIndicatorID()
    {
        return $this->_indicator_ID;
    }


    public function getDescription()
    {
        return $this->_indicator_description;
    }

    public function getFeedback()
    {
        return $this->_feedback;
    }

    public function getScore()
    {
        return $this->_score;
    }

    public function getWeight()
    {
        return $this->_weight;
    }

    public function getQuestionID()
    {
        return $this->question_ID;
    }
}