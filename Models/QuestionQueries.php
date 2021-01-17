<?php
require_once('Database.php');
require_once('QuestionTable.php');

class QuestionQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function DeleteQuestion($question)
    {
        $sqlQuery = 'DELETE FROM Question
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $question, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertQuestion($question, $sectionID)
    {
        $sqlQuery = 'INSERT INTO Question (question, section_ID) 
                        VALUES (:question, :sectionID)';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':question', $question, PDO::PARAM_STR);
        $statement->bindValue(':sectionID', $sectionID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function GetQuestionID($question)
    {
        $sqlQuery = 'SELECT question 
                        FROM Question 
                        WHERE name = :name';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $question, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getAll()
    {
        $sqlQuery = 'SELECT question FROM Question';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new QuestionTable($row);
        }
        return $dataSet;
    }


}