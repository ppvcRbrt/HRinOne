<?php
require_once('Database.php');
require_once('CandidateInfoTable.php');

/**
 * Class CandidateInfoQueries. This class is used to manipulate data in the Candidate_information table
 * and it contains SQL queries to aid this requirement.
 */

class CandidateInfoQueries
{
    protected $_dbInstance;
    protected $_dbHandle;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * This function is used to insert candidate information into the database.
     * Required information is represented by the candidate name, email, reference number
     * and work domain ID.
     *
     * @param $name
     * @param $email
     * @param $refno
     * @param $workDomID
     */
    public function InsertCandidate($name, $email, $refno, $workDomID)
    {
        $sqlQuery = 'LOCK TABLE Candidate_info WRITE;
                     INSERT INTO Candidate_info (name, email, ref_no, work_domain_ID) 
                        VALUES (:name, :email, :refno, :workDomainID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $name, PDO::PARAM_STR);
        $statement->bindValue(':email', $email, PDO::PARAM_STR);
        $statement->bindValue(':refno', $refno, PDO::PARAM_STR);
        $statement->bindValue(':workDomainID', $workDomID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */
    public function getCandidateName($candidateID)
    {
        $sqlQuery = 'SELECT name FROM Candidate_info
                     WHERE candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    /**
     * This function is used to search for a candidate by returning its details by matching
     * his name against the Candidate_info table.
     *
     * @param $candidateName
     * @return array
     */
    public function getCandIDByName($candidateName)
    {
        $sqlQuery = "SELECT MATCH(name) AGAINST((:candName'*') IN BOOLEAN MODE) score, candidate_ID, name  
                        FROM Candidate_info
                        HAVING score>0
                        ORDER BY score 
                        DESC limit 10";
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candName', $candidateName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new CandidateInfoTable($row);
        }
        return $dataSet;
    }

    /**
     * This method is used to gather a Work domain name given a candidate ID. Basically,
     * this helps us search for candidates who have a certain work domain.
     *
     * @param $candidateID
     * @return mixed
     */
    public function getCandidateWorkDomName($candidateID)
    {
        $sqlQuery = 'SELECT Work_domain.domain_name 
                        FROM Work_domain, Candidate_info 
                        WHERE Work_domain.work_domain_ID = Candidate_info.work_domain_ID
                        AND Candidate_info.candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    /**
     * This function is used to gather an assessment type given a certain candidate ID. Basically,
     * this helps us search for candidates which have a generated template with a certain assessment type.
     *
     * @param $candidateID
     * @return array
     */
    public function getCandidateAssTypes($candidateID)
    {
        $sqlQuery = 'SELECT Assessment_type.name 
                        FROM Assessment_type, Work_domain, Candidate_info 
                        WHERE Work_domain.work_domain_ID = Candidate_info.work_domain_ID
                        AND Assessment_type.work_domain_ID = Work_domain.work_domain_ID
                        AND Candidate_info.candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new CandidateInfoTable($row);
        }
        return $dataSet;
    }

    public function getCandidateEmail($candidateID)
    {
        $sqlQuery = 'SELECT email FROM Candidate_info
                     WHERE candidate_ID = :candID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':candID', $candidateID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
}