<?php
require_once('Database.php');
require_once('UserTable.php');

/**
 * Class UserQueries. This class is used to manipulate data in the User table
 * and it contains SQL queries to aid this requirement.
 */

class UserQueries
{
    protected $_dbInstance;
    protected $_dbHandle;


    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    public function insertUser($uName, $uPassword, $uEmail, $uCatID)
    {
        $sqlQuery = 'LOCK TABLE Users WRITE;
                     INSERT INTO Users (name, email, password, user_category_ID) 
                        VALUES (:name, :email, :password, :userCatID);
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':name', $uName, PDO::PARAM_STR);
        $statement->bindValue(':email', $uEmail, PDO::PARAM_STR);
        $statement->bindValue(':password', $uPassword, PDO::PARAM_STR);
        $statement->bindValue(':userCatID', $uCatID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    public function deleteUser($userID)
    {
        $sqlQuery = 'LOCK TABLE Users WRITE;
                     DELETE FROM Users
                        WHERE user_ID = :userID;
                     UNLOCK TABLES';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
    }

    /**
     * Function to query and return all info from table
     * @return array: will return an array of our rows
     */
    public function getPrivileges($userID)
    {
        $sqlQuery = 'SELECT user_category_ID FROM Users 
                     WHERE user_ID = :userID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    /**
     * This function is used to return the name of an User based
     * on its ID.
     *
     * @param $userID
     * @return array
     */
    public function getName($userID)
    {
        $sqlQuery = 'SELECT name FROM Users 
                     WHERE user_ID = :userID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function getUserID($userName)
    {
        $sqlQuery = 'SELECT user_ID FROM Users 
                     WHERE name = :userName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userName', $userName, PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }

    public function searchForUserName($userName)
    {
        $sqlQuery = 'SELECT user_ID, name, user_category_ID FROM Users
                     WHERE name LIKE :userName';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userName', '%' . $userName . '%' ,PDO::PARAM_STR);
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new UserTable($row);
        }
        return $dataSet;
    }
    public function getUserPassword($userID)
    {
        $sqlQuery = 'SELECT password FROM Users 
                     WHERE user_ID = :userID';
        $statement = $this->_dbHandle->prepare($sqlQuery); // prepare a PDO statement
        $statement->bindValue(':userID', $userID, PDO::PARAM_INT);
        $statement->execute(); // execute the PDO statement
        return $statement->fetch();
    }
}