<?php

class SectionTable
{

    protected $_section_ID, $_name, $description, $_weight, $_assessment_type_ID; //SectionTable Table

    /**
     * Constructor of the SectionTable class
     */
    public function __construct($dbRow)
    {
        $this->_section_ID = $dbRow['section_ID'] ?? null;
        $this->_name = $dbRow['name'] ?? null;
        $this->_weight = $dbRow['weight'] ?? null;
        $this->_assessment_type_ID = $dbRow['assessment_type_ID'] ?? null;
    }

    public function getSectionID()
    {
        return $this->_section_ID;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function getWeight()
    {
        return $this->_weight;
    }

    public function getAssessmentTypeID()
    {
        return $this->_assessment_type_ID;
    }

}
