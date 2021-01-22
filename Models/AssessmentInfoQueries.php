<?php
require_once('Database.php');
require_once('AssessmentInfoTable.php');

/**
 * Class AssessmentInfoQueries. This class is used to manipulate data in the Assessment Info table
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

    /**
     * This method is used to gather Assessment information about a candidate
     * using its ID and an assessment type ID.
     *
     * @param $candID
     * @param $assessmentTypeID
     * @return array
     */
    public function getInfoByCandID($candID, $assessmentTypeID)
    {
        $sqlQuery = 'SELECT Assessment_info.section_ID, Assessment_info.question_ID, Assessment_info.indicator_ID, Assessment.assessment_type_ID 
                     FROM Assessment_info, Assessment 
                     WHERE Assessment_info.assessment_ID = Assessment.assessment_ID
                     AND Assessment.candidate_ID = :candID
                     AND Assessment.assessment_type_ID = :assessmentTypeID
                     ORDER BY section_ID ASC, question_ID, indicator_ID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candID, PDO::PARAM_INT);
        $statement->bindValue(':assessmentTypeID', $assessmentTypeID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new AssessmentInfoTable($row);
        }
        return $dataSet;
    }

    /**
     * This method is used to insert assessment information into the database and it
     * required the assessment ID, section ID, question ID and the indicator ID.
     *
     * @param $assessmentID
     * @param $sectionID
     * @param $questionID
     * @param $indicatorID
     */
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
