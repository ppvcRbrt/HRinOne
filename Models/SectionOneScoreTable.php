<?php

class SectionOneScoreTable
{

    protected $_role_involvement, $_personal_motivation, $_personal_fit, $_speaking, $_comments; //Scorings_Part1 table

    public function __construct($dbRow)
    {

        $this->_role_involvement = $dbRow['role_involvement'] ?? null;
        $this->_personal_motivation = $dbRow['personal_motivation'] ?? null;
        $this->_personal_fit = $dbRow['personal_fit'] ?? null;
        $this->_speaking = $dbRow['speaking'] ?? null;
        $this->_comments = $dbRow['comments'] ?? null;
    }

    public function getRoleInvolvement()
    {
        return $this->_role_involvement;
    }

    public function getMotivation()
    {
        return $this->_personal_motivation;
    }

    public function getFit()
    {
        return $this->_personal_fit;
    }

    public function getSpeaking()
    {
        return $this->_speaking;
    }

    public function getComments()
    {
        return $this->_comments;
    }
}
