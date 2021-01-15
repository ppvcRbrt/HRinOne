<?php

class QuestionTable
{

    protected $_question_ID, $_question; //Question table
    protected $_add_question_ID, $_add_question; //Additional_questions table

    public function __construct($dbRow)
    {

        $this->_question_ID = $dbRow['question_ID']?? null;
        $this->_question = $dbRow['question']?? null;
    }

    public function getID()
    {
        return $this->_question_ID;
    }

    public function getQuestion()
    {
        return $this->_question;
    }

    public function getAddQuestionID()
    {
        return $this->_add_question_ID;
    }
    public function getAddQuestion()
    {
        return $this->_add_question;
    }
}
