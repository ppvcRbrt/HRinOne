<?php

class assessorViewFunctions
{
    function getIndicatorForQuestion($multiDimArray, $currentQuestionID, $sectionNo, $questionNo)
    {
        $indicatorQuery = new IndicatorsQueries();
        $descriptions = array();
        $indCount = 0;
        $_SESSION["allIndicatorNames"] = array();
        for($x = 0; $x < count($multiDimArray); $x++)
        {
            $indicatorID = array_search($currentQuestionID, $multiDimArray);
            unset($multiDimArray[$indicatorID]);
            $indicatorScore = $indicatorQuery->getIndicatorScoreByID($indicatorID);
            $indicatorDesc = $indicatorQuery->getIndicatorDescByID($indicatorID);
            if(isset($indicatorDesc))
            {
                foreach($indicatorDesc as $currentDesc)
                {
                    array_push($descriptions, $currentDesc->getDescription());
                }
            }
            if(isset($indicatorScore[0]))
            {
                if(isset($_SESSION["indicatorIDForSection"]))
                {
                    $indAdded = false;
                    foreach($_SESSION["indicatorIDForSection"] as $currentIndicatorID)
                    {
                        if($currentIndicatorID === $indicatorID)
                        {
                            echo'<label class="btn btn-secondary active" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                             <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'" checked>'.$indicatorScore[0].'
                             </label>';
                            $indCount++;
                            $indAdded = true;
                        }
                    }
                    if(!$indAdded)
                    {
                        echo'<label class="btn btn-secondary active disabled" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                             <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'">'.$indicatorScore[0].'
                             </label>';
                        $indCount++;
                    }
                }
                else
                {
                    echo'<label class="btn btn-secondary active" data-toggle="collapse" data-target="#descriptionInd'.$indCount.'q'.$questionNo.'sec'.$sectionNo.'">
                         <input type="radio" name="indicatorValueQ'.$questionNo.'" autocomplete="off" value = "'.$indicatorID.'">'.$indicatorScore[0].'
                         </label>';
                    $indCount++;
                }

            }

               // echo '<p>Indicator Score: ' . $indicatorScore[0] . '</p>';
        }
        return $descriptions;
    }

    function getQuestionForSection($multiDimArray, $currentSectionID, $sectionNo)
    {
        $questionQuery = new QuestionQueries();
        $allIndicators = $this->getAllIndicatorsWithQuestions();
        $currentMultiDimCount = count($multiDimArray);
        $questionCount = 0;
        for($x = 0 ; $x < $currentMultiDimCount ; $x++)
        {
            $questionID = array_search($currentSectionID, $multiDimArray);
            unset($multiDimArray[$questionID]);
            $currentMultiDimCount = count($multiDimArray);
            $questionName = $questionQuery->getQuestionName($questionID);
            if(isset($questionName[0]))
            {
                if($questionID)
                {
                    echo '<p>Question Name: '. $questionName[0] . '</p>';
                    echo '<div id = "question'.$questionCount.'sec'.$sectionNo.'">';
                    echo '<div class = "row justify-content-center">';
                    echo '<div class="btn-group btn-group-toggle" data-toggle="buttons" id = "buttonsForQ'.$questionCount.'Sec'.$sectionNo.'">';
                    $indDesc = $this->getIndicatorForQuestion($allIndicators, $questionID, $sectionNo, $questionCount);
                    $indCount = 0;
                    echo '</div>';
                    echo '</div>';
                    foreach($indDesc as $currentDescription)
                    {
                        echo '<div class="row justify-content-center collapse" id="descriptionInd'.$indCount.'q'.$x.'sec'.$sectionNo.'" data-parent="#question'.$x.'sec'.$sectionNo.'">
                                    <div class="card card-body col-4">
                                        '.$currentDescription.'
                                    </div>
                            </div>';
                        $indCount++;
                    }
                    echo '</div>';

                    $questionCount++;
                }
            }

        }
    }
    function getAllQuestionsWithSections()
    {
        $questionsPerSections['questionID']['sectionID'] = array();
        $questionID = array();
        $sectionID = array();
        for($x = 0; $x < count($_SESSION["questionIDs"]['qID']); $x++)
        {
            array_push($questionID, $_SESSION["questionIDs"]['qID'][$x]);
            array_push($sectionID, $_SESSION["questionIDs"]['secID'][$x]);
        }

        $multiDimArray = array_combine($questionID, $sectionID);
        return $multiDimArray;
    }

    function getAllSectionsCount()
    {
        return count($_SESSION["sectionIDs"]);
    }

    function getAllIndicatorsWithQuestions()
    {
        $indicatorID = array();
        $questionID = array();
        for($x = 0; $x < count($_SESSION["indicatorIDs"]['indID']); $x++)
        {
            array_push($indicatorID, $_SESSION["indicatorIDs"]['indID'][$x]);
            array_push($questionID, $_SESSION["indicatorIDs"]['qID'][$x]);
        }
        $multiDimArray = array_combine($indicatorID, $questionID);
        return $multiDimArray;
    }
}