<?php
require_once('Database.php');
require_once('AssessmentTable.php');

/**
 * Class AssessmentQueries. This class is used to manipulate data in the Assessment table
 * and it contains SQL queries to aid this requirement.
 */

class AssessmentInfoQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function InsertAssessmentInfo($assessmentID, $sectionID, $questionID, $indicatorID)
    {
        $sqlQuery = 'LOCK TABLE Assessment_info WRITE;
                     INSERT INTO Assessment_info (assessment_ID, section_ID, question_ID, indicator_ID) 
                        VALUES (:assessmentID, :sectionID, :questionID, :indicatorID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':assessmentID', $assessmentID, PDO::PARAM_STR);
        $statement->bindValue(':sectionID', $sectionID, PDO::PARAM_STR);
        $statement->bindValue(':questionID', $questionID, PDO::PARAM_INT);
        $statement->bindValue(':indicatorID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

}
