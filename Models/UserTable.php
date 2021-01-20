<?php

/**
 * Class UserTable. This class is used to manipulate data regarding an user
 */
class UserTable
{

    protected $_user_ID, $_name, $_email, $_password, $_user_category_ID;

    /**
     * Constructor of the UserTable class
     */
    public function __construct($dbRow)
    {
        $this->_user_ID = $dbRow['ID']?? null;
        $this->_name = $dbRow['name']?? null;
        $this->_email = $dbRow['email']?? null;
        $this->_password = $dbRow['password']?? null;
        $this->_user_category_ID = $dbRow['user_category_ID']?? null;
    }

    public function getID()
    {
        return $this->_user_ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getUserCategoryID()
    {
        return $this->_user_category_ID;
    }
}