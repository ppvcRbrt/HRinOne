<?php

class QuestionTable
{

    protected $_question_ID, $_question, $_section_ID; //Question table

    /**
     * Constructor of the QuestionTable class
     */
    public function __construct($dbRow)
    {
        $this->_question_ID = $dbRow['question_ID']?? null;
        $this->_question = $dbRow['question']?? null;
        $this->_section_ID = $dbRow['section_ID']?? null;
    }

    public function getID()
    {
        return $this->_question_ID;
    }

    public function getQuestion()
    {
        return $this->_question;
    }

    public function getSectionID()
    {
        return $this->_section_ID;
    }

}
