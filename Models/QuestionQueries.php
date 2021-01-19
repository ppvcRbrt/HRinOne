<?php
require_once('Database.php');
require_once('QuestionTable.php');

/**
 * Class QuestionQueries. This class is used to manipulate data in the Question table
 * and it contains SQL queries to aid this requirement.
 */

class QuestionQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * This function is used to delete a question given its name.
     *
     * @param $question
     */
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

    /**
     * This function is used to insert a question into the database using the actual
     * content of the question and the section ID which it is part from.
     *
     * @param $question
     * @param $sectionID
     */
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

    /**
     * This function is used to gather a question from the database based on its name.
     *
     * @param $question
     * @return mixed
     */
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

    /**
     * This function is used to gather all the questions from the table.
     *
     * @return array
     */
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