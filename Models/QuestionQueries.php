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
        $sqlQuery = 'LOCK TABLE Question WRITE;
                     DELETE FROM Question
                        WHERE name = :name;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $question, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function InsertQuestion($question, $sectionID)
    {
        $sqlQuery = 'LOCK TABLE Question WRITE;
                     INSERT INTO Question (question, section_ID)
                        VALUES (:question, :sectionID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':question', $question, PDO::PARAM_STR);
        $statement->bindValue(':sectionID', $sectionID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
    }

    public function GetQuestionID($question)
    {
        $sqlQuery = 'SELECT question_ID 
                        FROM Question 
                        WHERE question = :name';
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

    /**
     * This needs to be TESTED before deployment.
     */

    /**
     * This method should get the questions specific to section name, assessment type name
     * and work domain name.
     */
    public function GetQuestionsByInfo($sectionName, $assessmentTypeName, $workDomainName)
    {
        $sqlQuery = 'SELECT Question.question
                     FROM Question, Assessment_type, Work_domain,Section
                     WHERE Question.section_ID=Section.section_ID
                     AND Section.name=:sectionName
                     AND Section.assessment_type_ID = Assessment_type.assessment_type_ID
                     AND Assessment_type.name= :assessmentTypeName
                     AND Work_domain.work_domain_ID = Assessment_type.work_domain_ID
                     AND Work_domain.domain_name = :workDomainName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':sectionName', $sectionName, PDO::PARAM_STR);
        $statement->bindValue(':assessmentTypeName', $assessmentTypeName, PDO::PARAM_STR);
        $statement->bindValue(':workDomainName', $workDomainName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }




}