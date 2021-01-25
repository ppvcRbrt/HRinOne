<?php

require_once('Database.php');
require_once('IndicatorsTable.php');

class IndicatorsQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */

    public function DeleteIndicator($indicatorID)
    {
        $sqlQuery = 'LOCK TABLE Indicator WRITE;
                     DELETE FROM Indicator
                        WHERE indicator_ID = :indicatorID;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indicatorID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    /**
     * This function is used to insert an indicator into the database,
     * which is based on a question. Therefore, the required information
     * to insert an indicator is represented by the indicator description,
     * the feedback that it should generate, the score, score weight and the
     * question ID which the indicator is linked with.
     *
     * @param $description
     * @param $feedback
     * @param $score
     * @param $weight
     * @param $questionID
     */
    public function InsertIndicator($description, $feedback, $score, $weight, $questionID)
    {
        $sqlQuery = 'LOCK TABLE Indicator WRITE;
                     INSERT INTO Indicator (description,feedback,score,weight,question_ID) 
                        VALUES (:description,:feedback,:score,:weight,:question_ID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':description', $description, PDO::PARAM_STR);
        $statement->bindValue(':feedback', $feedback, PDO::PARAM_STR);
        $statement->bindValue(':score', $score, PDO::PARAM_STR);
        $statement->bindValue(':weight', $weight, PDO::PARAM_STR);
        $statement->bindValue(':question_ID', $questionID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    /**
     * This function is used to gather an Indicator ID which is linked
     * with a question. Therefore, specifying a question ID is mandatory.
     *
     * @param $questionID
     * @return array
     */
    public function getIndicatorsByQuesID($questionID)
    {
        $sqlQuery = 'SELECT indicator_ID 
                        FROM Indicator, Question 
                        WHERE Indicator.question_ID = Question.question_ID 
                        AND Question.question_ID= :questionID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':questionID', $questionID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new IndicatorsTable($row);
        }
        return $dataSet;
    }

    /**
     * This function is used to gather the score from an indicator, given
     * the indicator ID.
     *
     * @param $indicatorID
     * @return mixed
     */
    public function getIndicatorScoreByID($indicatorID)
    {
        $sqlQuery = 'SELECT score FROM Indicator
                     WHERE indicator_ID = :indID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    /**
     * This function is used to gather the description of an indicator
     * based on its ID.
     *
     * @param $indicatorID
     * @return array
     */
    public function getIndicatorDescByID($indicatorID)
    {
        $sqlQuery = 'SELECT description FROM Indicator
                     WHERE indicator_ID = :indID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new IndicatorsTable($row);
        }
        return $dataSet;
    }

    public function getIndFeedback($indicatorID)
    {
        $sqlQuery = 'SELECT feedback FROM Indicator
                     WHERE indicator_ID = :indID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getIndScoreAndWeight($indicatorID)
    {
        $sqlQuery = 'SELECT score,weight FROM Indicator
                     WHERE indicator_ID = :indID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':indID', $indicatorID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
    /**
     * This function is used to gather information about indicators,
     * where we are given a section name.
     *
     * @param $sectionName
     * @return array
     */
    public function GetIndicator($sectionName)
    {
        $sqlQuery = 'SELECT feedback, score, Indicators.weight, Indicators.section_ID 
                        FROM Indicators, Section 
                        WHERE Section.section_ID = Indicators.section_ID and Section.name= :sectName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':sectName', $sectionName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new IndicatorsTable($row);
        }
        return $dataSet;
    }
}