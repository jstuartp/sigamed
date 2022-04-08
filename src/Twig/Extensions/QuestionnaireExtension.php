<?php
namespace App\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;


use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class QuestionnaireExtension extends AbstractExtension
{
    private $em;
    private $conn;
    //private $translator;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        $this->conn = $em->getConnection();
        //$this->translator = $translator;
    }

    public function getFunctions()
    {
        return array(
            // 'include_raw' => new \Twig_Function_Method($this, 'twig_include_raw'),
            // 'print_package_box_raw' => new \Twig_Function_Method($this, 'print_package_box_raw'),
            new TwigFunction('include_raw', [$this, 'twig_include_raw']),
            new TwigFunction('print_package_box_raw', [$this, 'print_package_box_raw']),
        );
    }

    public function twig_include_raw($file) {
        return file_get_contents($file);
    }

    /**
     * This methods prints a html box with the package information
     *
     * @param $package Package Entity to be printed
     *
     * @return string raw html of the generated box
     */
    public function print_package_box_raw($package, $width = 'six') {
        $html = '<div class="' . $width . ' columns">';
        $html .= '<div class="side-ad clearfix">';
        $html .= '<h3 class="package">' . $package->getShortTitle() . '</h3>';
        $html .= '<h4 class="pricing">' . $this->formatDolars($package->getAmount()) . ' <br /><span>' .
            $package->getPaymentMode() . '</span><br /></h4>';
        $html .= '<ul class="checklist">';
        $html .= '<li>&nbsp;&nbsp;&nbsp;Unlimited Email Alerts</li>';
        $html .= '<li>&nbsp;&nbsp;&nbsp;Unlimited Text Alerts</li>';
        $html .= '</ul>';
        if( $width == "six"){
            $html .= '<div class="button darkgrey">';
            $html .= '<input class="btn-package" type="submit" rel="' . $package->getId() . '" value="' .
                $this->translator->trans('pentair.members.buy.plan.upgrade') .  '"/> ';
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }


    public function getFilters()
    {
        /*return array(
            'printQuestionnaireQuestion' => new \Twig_Filter_Method($this, 'printQuestionnaireQuestion'),
        );*/

        return [
            new TwigFilter('printQuestionnaireQuestion', [$this, 'printQuestionnaireQuestion']),
        ];
    }


    public function printQuestionnaireQuestion( \App\Entity\QuestionnaireQuestion $question,
                                                $answers, $answerscheck, $option)
    {
        if($option == 1){
            $value1 = "";
            $value2 = "";
            $value3 = "";

            if(array_key_exists($question->getId(), $answers)){
                $value1 = $answers[$question->getId()]->getMainText();
                $value2 = $answers[$question->getId()]->getSecondText();
            }

            if(array_key_exists($question->getId(), $answerscheck)){
                $value3 = $answerscheck[$question->getId()]->getMainText();
            }

            $labelStyle = ' style="  float: left; width: 350px;" ';
            $html = "";
            $html .= "<div class='questionnaireQuestion'>";
            switch($question->getType()){
                case 1: //Simple Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<input class="along-input"' . $this->getFieldId($question) .
                        ' type="text" value="' . $value1 . '">';
                    $html .= '<div class="clear"></div>';
                    break;
                case 2://YesNoSelectionSimple
                    $html .= 'Tipo desconocido: ' . $question->getType();
                    break;
                case 3: //YesNoSelectionWithExplain
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<select ' . $this->getFieldId($question) .
                        ' class="short" style="max-width: 50px; "><option value="0"' .
                        (($value1 == "0")? " selected":"") .
                        '>No</option><option value="1"' .
                        (($value1 == "1")? " selected":"") .
                        '>Si</option></select>';
                    $html .= '<span style="  margin-left: 10px; margin-right: 10px;">' . $question->getSecondText() .
                        '</span>';
                    $html .= '<textarea style="height: 80px;" rows="5" id="qaux-' . $question->getId() . '" name="qaux-' .
                        $question->getId() . '">' . $value2 . '</textarea>';
                    $html .= "<div class='clear'></div>";
                    break;
                case 4://Group Of Questions
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . '</label><div class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 373px;"> ' . $texts[0] . '</div>' .
                        '<div class="left"> ' . $texts[1] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        $value2 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = $answers[$q->getId()]->getSecondText();
                        }
                        $html .= "<label" . $labelStyle . ">" . $q->getMainText() . "</label>";
                        $html .= '<input ' . $this->getFieldId($q) .
                            ' type="text" value="' . $value1 . '">';
                        $html .= '<div class="clear"></div>';
                    }
                    $html .= '</div>';
                    break;
                case 5://Date Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<input class="date-input along-input" ' . $this->getFieldId($question) . ' type="text" value="'
                        . $value1 . '">';
                    break;
                case 6: // TextArea Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<textarea maxlength="10000"' .
                        'style="height: 195px; margin: 0px 0px 7px; width: 781px;"' .
                        ' class="along-input questionnaire-textarea" rows="12" ' . $this->getFieldId($question) . '>' .
                        $value1 .
                        '</textarea>';
                    $html .= '<div class="hiddenOnScreen" ' . $this->getRelatedFieldId($question, "printarea")
                        . ' style="font-size: 16px; color:#646464; margin-bottom:15px;">' . $value1 . '</div>';
                    $html .= '<textarea disabled ' .
                        'style="height: 105px; margin: 0px 0px 7px; width: 781px;"' .
                        ' class="along-input questionnaire-textarea" rows="6">' .
                        $value3 .
                        '</textarea>';
                    break;
                case 7: // 3 Columns Question
                    $html .= '<label style="width: 100%">' . $question->getMainText() . '</label><div
                    class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 150px;"> ' . $texts[0] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 300px;"> ' . $texts[1] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 150px;"> ' . $texts[2] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 200px;"> ' . $texts[3] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = explode('-*-', $value1);
                        }else {
                            $value2 = array("","","","");
                        }
                        $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                        $html .= '<div>' .
                            '<div class="left" style="width: 150px;"> '
                            . '<input type="text" id="qaux1-' . $q->getId() . '" name="qaux1-' . $q->getId() .
                            '" value="' . $value2[0] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 300px;"> '
                            . '<input type="text" id="qaux2-' . $q->getId() . '" name="qaux2-' . $q->getId()
                            . '" value="' . $value2[1] . '"></div>' .

                            '<div class="left" style="width: 75px;"> '
                            . '<input style="width: 50px;" type="text" id="qaux3-' . $q->getId() . '" name="qaux3-' .
                            $q->getId() . '" value="' . $value2[2] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 50px;"> '
                            . '<textarea style="width: 300px;" type="text" id="qaux4-' . $q->getId() . '" name="qaux4-' .
                            $q->getId() . '">' . $value2[3] . '</textarea>'
                            . '</div>' .
                            '<div class="clear"></div>' .
                            '</div>';
                    }
                    $html .= '</div>';
                    break;
                case 9:
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<table class="form-type-9">';
                    $html .= "<thead>";
                    $html .= "<tr><th>" . $question->getMainText() . "</th><th>" . $texts[0] . "</th><th>" . $texts[1] .
                        "</th><th>" . $texts[2] . "</th></tr>";
                    $html .= "</thead>";
                    $html .= "<tbody>";
                    foreach ( $question->getChildren() as $q) {
                        $html .= '<tr>';
                        $html .= '';
                        $value = 0;
                        if(array_key_exists($q->getId(), $answers)){
                            $value = $answers[$q->getId()]->getMainText();
                        }
                        $html .= '<td>' . $q->getMainText(). '</td>';
                        $html .= '<td><input type="radio" ' . $this->getFieldId($q) . ' value="1" ' .
                            (($value == 1)? 'checked="checked"':"") . '></td>';
                        $html .= '<td><input type="radio" ' . $this->getFieldId($q) . ' value="2" ' .
                            (($value == 2)? 'checked="checked"':"") . '></td>';
                        $html .= '<td><input type="radio" ' . $this->getFieldId($q) . ' value="3" ' .
                            (($value == 3)? 'checked="checked"':"") . '></td>';
                        $html .= '</tr>';
                        $html .= '';
                        /*
                                            $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                                            $html .= '<div>' .
                                                '<div class="left" style="width: 325px;"> '
                                                . '<input type="text" id="qaux1-' . $q->getId() . '" name="qaux1-' . $q->getId() .
                                                '" value="' . $value2[0] . '">'
                                                . '</div>' .
                                                '<div class="left" style="width: 300px;"> '
                                                . '<input type="text" id="qaux2-' . $q->getId() . '" name="qaux2-' . $q->getId()
                                                . '" value="' . $value2[1] . '"></div>' .
                                                '<div class="left"> '
                                                . '<input style="width: 75px;" type="text" id="qaux3-' . $q->getId() . '" name="qaux3-' .
                                                $q->getId() . '" value="' . $value2[2] . '">'
                                                . '</div>' .
                                                '<div class="clear"></div>' .
                                                '</div>';*/
                    }

                    $html .= "</tbody>";
                    $html .= "</table>";

                    break;
                case 10: // 5 Columns Question
                    $html .= '<label style="width: 100%">' . $question->getMainText() . '</label><div
                    class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[0] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[1] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[2] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 250px;"> ' . $texts[3] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 250px;"> ' . $texts[4] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = explode('-*-', $value1);
                        }else {
                            $value2 = array("","","","","");
                        }
                        $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                        $html .= '<div>' .
                            '<div class="left" style="width: 100px;"> '
                            . '<input style="width: 100px;" type="text" id="qaux1-' . $q->getId() . '" name="qaux1-' . $q->getId() .
                            '" value="' . $value2[0] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 100px;"> '
                            . '<input style="width: 100px;" type="text" id="qaux2-' . $q->getId() . '" name="qaux2-' . $q->getId()
                            . '" value="' . $value2[1] . '"></div>' .

                            '<div class="left" style="width: 100px;"> '
                            . '<input style="width: 100px;" type="text" id="qaux3-' . $q->getId() . '" name="qaux3-' .
                            $q->getId() . '" value="' . $value2[2] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 250px;"> '
                            . '<textarea style="width: 250px;" type="text" id="qaux4-' . $q->getId() . '" name="qaux4-' .
                            $q->getId() . '">' . $value2[3] . '</textarea>'
                            . '</div>' .
                            '<div class="left" style="width: 250px;"> '
                            . '<textarea style="width: 250px;" type="text" id="qaux5-' . $q->getId() . '" name="qaux5-' .
                            $q->getId() . '">' . $value2[4] . '</textarea>'
                            . '</div>' .
                            '<div class="clear"></div>' .
                            '</div>';
                    }
                    $html .= '</div>';
                    break;
                default:
                    $html .= 'Tipo desconocido: ' . $question->getType();
                    break;
            }
            $html .= "</div>";
            return $html;
        }
        if($option == 2){
            $value1 = "";
            $value2 = "";
            $value3 = "";
            if(array_key_exists($question->getId(), $answers)){
                $value1 = $answers[$question->getId()]->getMainText();
                $value2 = $answers[$question->getId()]->getSecondText();
            }

            if(array_key_exists($question->getId(), $answerscheck)){
                $value3 = $answerscheck[$question->getId()]->getMainText();
            }

            $labelStyle = ' style="  float: left; width: 350px;" ';
            $html = "";
            $html .= "<div class='questionnaireQuestion'>";
            switch($question->getType()){
                case 1: //Simple Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<input disabled class="along-input" type="text" value="' . $value1 . '">';
                    $html .= '<div class="clear"></div>';
                    break;
                case 2://YesNoSelectionSimple
                    $html .= 'Tipo desconocido: ' . $question->getType();
                    break;
                case 3: //YesNoSelectionWithExplain
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<select disabled ' . $this->getFieldId($question) .
                        ' class="short" style="max-width: 50px; "><option value="0"' .
                        (($value1 == "0")? " selected":"") .
                        '>No</option><option value="1"' .
                        (($value1 == "1")? " selected":"") .
                        '>Si</option></select>';
                    $html .= '<span style="  margin-left: 10px; margin-right: 10px;">' . $question->getSecondText() .
                        '</span>';
                    $html .= '<textarea disabled style="height: 80px;" rows="5" id="qaux-' . $question->getId() . '" name="qaux-' .
                        $question->getId() . '">' . $value2 . '</textarea>';
                    $html .= "<div class='clear'></div>";
                    break;
                case 4://Group Of Questions
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . '</label><div class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 373px;"> ' . $texts[0] . '</div>' .
                        '<div class="left"> ' . $texts[1] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        $value2 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = $answers[$q->getId()]->getSecondText();
                        }
                        $html .= "<label" . $labelStyle . ">" . $q->getMainText() . "</label>";
                        $html .= '<input ' . $this->getFieldId($q) .
                            ' type="text" value="' . $value1 . '">';
                        $html .= '<div class="clear"></div>';
                    }
                    $html .= '</div>';
                    break;
                case 5://Date Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<input disabled class="date-input along-input" type="text" value="'
                        . $value1 . '">';
                    break;
                case 6: // TextArea Input
                    $html .= "<label" . $labelStyle . ">" . $question->getMainText() . "</label>";
                    $html .= '<textarea disabled ' .
                        'style="height: 195px; margin: 0px 0px 7px; width: 781px;"' .
                        ' class="along-input questionnaire-textarea" rows="12">' .
                        $value1 .
                        '</textarea>';
                    $html .= '<textarea ' .
                        'style="height: 105px; margin: 0px 0px 7px; width: 781px;"' .
                        ' class="along-input questionnaire-textarea" rows="6" maxlength="5000" ' . $this->getFieldId($question) . '>' .
                        $value3 .
                        '</textarea>';
                    break;
                case 7: // 3 Columns Question
                    $html .= '<label style="width: 100%">' . $question->getMainText() . '</label><div
                    class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 150px;"> ' . $texts[0] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 300px;"> ' . $texts[1] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 150px;"> ' . $texts[2] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 200px;"> ' . $texts[3] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = explode('-*-', $value1);
                        }else {
                            $value2 = array("","","","");
                        }
                        $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                        $html .= '<div>' .
                            '<div class="left" style="width: 150px;"> '
                            . '<input disabled type="text" id="qaux1-' . $q->getId() . '" name="qaux1-' . $q->getId() .
                            '" value="' . $value2[0] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 300px;"> '
                            . '<input disabled type="text" id="qaux2-' . $q->getId() . '" name="qaux2-' . $q->getId()
                            . '" value="' . $value2[1] . '"></div>' .

                            '<div class="left" style="width: 75px;"> '
                            . '<input disabled style="width: 50px;" type="text" id="qaux3-' . $q->getId() . '" name="qaux3-' .
                            $q->getId() . '" value="' . $value2[2] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 50px;"> '
                            . '<textarea maxlength="5000" disabled style="width: 300px;" type="text" id="qaux4-' . $q->getId() . '" name="qaux4-' .
                            $q->getId() . '">' . $value2[3] . '</textarea>'
                            . '</div>' .
                            '<div class="clear"></div>' .
                            '</div>';
                    }
                    $html .= '</div>';
                    break;
                case 9:
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<table class="form-type-9">';
                    $html .= "<thead>";
                    $html .= "<tr><th>" . $question->getMainText() . "</th><th>" . $texts[0] . "</th><th>" . $texts[1] .
                        "</th><th>" . $texts[2] . "</th></tr>";
                    $html .= "</thead>";
                    $html .= "<tbody>";
                    foreach ( $question->getChildren() as $q) {
                        $html .= '<tr>';
                        $html .= '';
                        $value = 0;
                        if(array_key_exists($q->getId(), $answers)){
                            $value = $answers[$q->getId()]->getMainText();
                        }
                        $html .= '<td>' . $q->getMainText(). '</td>';
                        $html .= '<td><input disabled type="radio" ' . $this->getFieldId($q) . ' value="1" ' .
                            (($value == 1)? 'checked="checked"':"") . '></td>';
                        $html .= '<td><input disabled type="radio" ' . $this->getFieldId($q) . ' value="2" ' .
                            (($value == 2)? 'checked="checked"':"") . '></td>';
                        $html .= '<td><input disabled type="radio" ' . $this->getFieldId($q) . ' value="3" ' .
                            (($value == 3)? 'checked="checked"':"") . '></td>';
                        $html .= '</tr>';
                        $html .= '';
                        /*
                                            $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                                            $html .= '<div>' .
                                                '<div class="left" style="width: 325px;"> '
                                                . '<input type="text" id="qaux1-' . $q->getId() . '" name="qaux1-' . $q->getId() .
                                                '" value="' . $value2[0] . '">'
                                                . '</div>' .
                                                '<div class="left" style="width: 300px;"> '
                                                . '<input type="text" id="qaux2-' . $q->getId() . '" name="qaux2-' . $q->getId()
                                                . '" value="' . $value2[1] . '"></div>' .
                                                '<div class="left"> '
                                                . '<input style="width: 75px;" type="text" id="qaux3-' . $q->getId() . '" name="qaux3-' .
                                                $q->getId() . '" value="' . $value2[2] . '">'
                                                . '</div>' .
                                                '<div class="clear"></div>' .
                                                '</div>';*/
                    }

                    $html .= "</tbody>";
                    $html .= "</table>";

                    break;
                case 10: // 5 Columns Question
                    $html .= '<label style="width: 100%">' . $question->getMainText() . '</label><div
                    class="clear"></div>';
                    $texts = explode('-',$question->getSecondText());
                    $html .= '<div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[0] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[1] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 100px;"> ' . $texts[2] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 250px;"> ' . $texts[3] . '</div>' .
                        '<div class="left" style="padding-left: 10px; width: 250px;"> ' . $texts[4] . '</div>' .
                        '<div class="clear"></div>' .
                        '</div>';
                    $html .= '<div class="questions-group">';
                    foreach ( $question->getChildren() as $q) {
                        $value1 = "";
                        if(array_key_exists($q->getId(), $answers)){
                            $value1 = $answers[$q->getId()]->getMainText();
                            $value2 = explode('-*-', $value1);
                        }else {
                            $value2 = array("","","","","");
                        }
                        $html .= '<input type="hidden" ' . $this->getFieldId($q) . '>';
                        $html .= '<div>' .
                            '<div class="left" style="width: 100px;"> '
                            . '<input disabled style="width: 100px;" type="text" value="' . $value2[0] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 100px;"> '
                            . '<input disabled style="width: 100px;" type="text" value="' . $value2[1] . '"></div>' .

                            '<div class="left" style="width: 100px;"> '
                            . '<input disabled style="width: 100px;" type="text" value="' . $value2[2] . '">'
                            . '</div>' .
                            '<div class="left" style="width: 250px;"> '
                            . '<textarea disabled style="width: 250px;" type="text" maxlength="5000" >' . $value2[3] . '</textarea>'
                            . '</div>' .
                            '<div class="left" style="width: 250px;"> '
                            . '<textarea disabled style="width: 250px;" type="text" maxlength="5000" >' . $value2[4] . '</textarea>'
                            . '</div>' .
                            '<div class="clear"></div>' .
                            '</div>';
                    }
                    $html .= '</div>';
                    break;
                default:
                    $html .= 'Tipo desconocido: ' . $question->getType();
                    break;
            }
            $html .= "</div>";
            return $html;
        }
    }

    private function getFieldId(\App\Entity\QuestionnaireQuestion $question){
        $id = ' id="q-' . $question->getId() . "-" . $question->getType() . '" ' .
            ' name="q-' . $question->getId() . "-" . $question->getType() . '" ';
        return $id;
    }

    private function getRelatedFieldId(\App\Entity\QuestionnaireQuestion $question, $rel){
        $id = ' id="' . $rel . '-q-' . $question->getId() . "-" . $question->getType() . '" ' .
            ' name="' . $rel . '-q-' . $question->getId() . "-" . $question->getType() . '" ';
        return $id;
    }

    public function getName()
    {
        return 'questionnaire_twig_extension';
    }






}