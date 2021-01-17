<?php

/**
 * Class FeedbackTable : This class will allow us to get individual columns from Feedback Queries
 */
class UserTable
{

    protected $_user_ID, $_name, $_user_category_ID;

    public function __construct($dbRow)
    {

        $this->_user_ID = $dbRow['ID']?? null;

        $this->_name = $dbRow['name']?? null;
        $this->_user_category_ID = $dbRow['user_category_ID']?? null;
    }

    public function getID()
    {
        return $this->_user_ID;
    }

    public function getUserCategoryID()
    {
        return $this->_user_category_ID;
    }

    public function getName()
    {
        return $this->_name;
    }
}