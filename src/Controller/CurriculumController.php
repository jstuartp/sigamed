<?php

namespace App\Controller;

use App\Entity\Curriculumdata;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Absence;
use App\Entity\Contact;

use App\Entity\Programs;
use App\Entity\Degree;
use App\Entity\Wexperience;
use App\Entity\Aexperience;
use App\Entity\Oexperience;
use App\Entity\Publication;
use App\Entity\Investigation;
use App\Entity\Apresentation;
use App\Entity\Improve;

use App\Form\ProgramFormType;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\RequestStack;



class CurriculumController extends AbstractController
{
    /**
     * @Route("/curriculum/adminGeneral", name="_expediente_sysadmin_curriculum")
     */
    public function adminCurriculumAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();

        $entity = $em->getRepository("App:User")->find($user_id);

        $sql = "SELECT *"
            . " FROM tek_degree "
            . " WHERE user_id = ".$user_id;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $degrees = $stmt->fetchAll();

        $sql = "SELECT *"
            . " FROM tek_publication "
            . " WHERE user_id = ".$user_id;

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $publications = $stmt->fetchAll();


        return $this->render('SuperAdmin/Curriculum/admin.html.twig', array('degrees' => $degrees,'publications' => $publications,'entity' => $entity,
            'menuIndex' => 5));
    }

    public function loadDegreesByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                //$teacherId = $request->get('teacherId');
                $user = $this->container->get('security.token_storage')->getToken()->getUser();

                $teacherId = $user->getId();

                $translator = $this->get("translator");

                if( isset($teacherId)) {
                    $em = $this->getDoctrine()->getEntityManager();


                    $stmt = $this->getDoctrine()->getEntityManager()
                        ->getConnection()
                        ->prepare('SELECT d.id as id, d.name as name, d.year as year, d.place as place
                                    FROM `tek_degree` d
                                    where d.user_id = "'.$teacherId.'"');
                    $stmt->execute();
                    $entity = $stmt->fetchAll();

                    $colors = array(
                        "one" => "#38255c",
                        "two" => "#04D0E6"
                    );
                    $html = "";
                    $groupOptions = "";

                    foreach( $entity as $entry ){
                        $html .= '<div id="degreeRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                        $html .= '    <div id="entryNameField_' . $entry['name'] . '" name="entryNameField_' . $entry['name'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['name'],0,35) . '</div>';
                        $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                        $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                        $html .= '    <div class="right imageButton deleteButton deleteDegree" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                        $html .= '    <div class="right imageButton editButton editDegree" style="height: 16px;" title="Editar"  rel="' . $entry['id'] . '"></div>';
                        $html .= '    <div class="clear"></div>';
                        $html .= '</div>';

                    }

                    return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Curriculum::loadDegreeAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadDegreesfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.name as name, d.year as year, d.place as place, d.country as country, d.equated as equated, d.equatedtx as equatedtx, 
                                    d.inprogress as inprogress, d.eedition as eedition
                                    FROM `tek_degree` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Nombre: ' . $entry['name'] . '</div>';
                    $html .= '    <div>Año de graduación: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div>País: ' . $entry['country'] . '</div>';
                    if($entry['eedition'] == 1){
                        $html .= '    <div>Tipo: Presencial</div>';
                    }else{
                        if($entry['eedition'] == 2){
                            $html .= '    <div>Tipo: No presencial</div>';
                        }else{
                            $html .= '    <div>Tipo: Virtual</div>';
                        }
                    }
                    if($entry['inprogress'] == 1){
                        $html .= '    <div>En curso: No</div>';
                    }else{
                        $html .= '    <div>En curso: Si</div>';
                    }
                    if($entry['equated'] == 1){
                        $html .= '    <div>Equiparado: No</div>';
                    }else{
                        $html .= '    <div>Equiparado: Si, '.$entry['equatedtx'].'</div>';
                    }
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadDegreeAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeDegreeAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $degreeId = $request->get('degreeId');
                $translator = $this->get("translator");

                if( isset($degreeId) ) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $entity = $em->getRepository("App:Degree")->find( $degreeId );
                    if ( isset($entity) ) {
                        $em->remove($entity);
                        $em->flush();
                    }
                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createDegreeAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();

                //$teacherId = $request->get('teacherId');
                $user = $this->get('security.token_storage')->getToken()->getUser();
                $userId = $user->getId();

                $name = $request->get('name');
                $place = $request->get('place');
                $year = $request->get('year');
                $country = $request->get('country');
                $eedition = $request->get('eedition');
                $equated = $request->get('equated');
                $equatedtx = $request->get('equatedtx');
                $inprogress = $request->get('inprogress');

                $translator = $this->get("translator");

                if( isset($userId)) {
                    $em = $this->getDoctrine()->getEntityManager();

                    $degree = new Degree();
                    $degree->setName($name);
                    $degree->setPlace($place);
                    $degree->setYear($year);
                    $degree->setCountry($country);
                    $degree->setEedition($eedition);
                    $degree->setEquated($equated);
                    $degree->setEquatedtx($equatedtx);
                    $degree->setInprogress($inprogress);
                    $degree->setUser($user);


                    $em->persist($degree);
                    $em->flush();

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Curriculum::createDegreeAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function getInfoDegreeDetailAction(){
        $logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $degreeId = $request->get('degreeId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $degree = $em->getRepository("App:Degree")->find($degreeId);





            if ( isset($degree) ) {
                $idDegree = $degree->getId();
                $name = $degree->getName();
                $place = $degree->getPlace();
                $year = $degree->getYear();
                $country = $degree->getCountry();
                $eedition = $degree->getEedition();
                $equated = $degree->getEquated();
                $equatedtx = $degree->getEquatedtx();
                $inprogress = $degree->getInprogress();


                return new Response(json_encode(array('error' => false, 'id' => $idDegree, 'name' => $name, 'place' => $place, 'year' => $year, 'country' => $country,
                    'eedition' => $eedition, 'equated' => $equated, 'equatedtx' => $equatedtx, 'inprogress' => $inprogress)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::getInfoCourseFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function updateDegreeAction(){ //2018-13-03
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $degreeId = $request->get('degreeId');
            $name = $request->get('name');
            $place = $request->get('place');
            $year = $request->get('year');
            $country = $request->get('country');
            $eedition = $request->get('eedition');
            $equated = $request->get('equated');
            $equatedtx = $request->get('equatedtx');
            $inprogress = $request->get('inprogress');


            $translator = $this->get("translator");

            if( $degreeId != '') {
                $em = $this->getDoctrine()->getEntityManager();

                $degree = new Degree();
                $degree = $em->getRepository("App:Degree")->find($degreeId);

                $degree->setName($name);
                $degree->setPlace($place);
                $degree->setYear($year);
                $degree->setCountry($country);
                $degree->setEedition($eedition);
                $degree->setEquated($equated);
                $degree->setEquatedtx($equatedtx);
                $degree->setInprogress($inprogress);


                $entityUser = $em->getRepository("App:User")->find($user);
                if( isset($entityUser)){
                    $degree->setUser($entityUser);
                }


                $em->persist($degree);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Degree::updateDegreeAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadWexperiencesByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.position as position, d.year as year, d.place as place
                                    FROM `tek_wexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="wexperienceRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryPositionField_' . $entry['position'] . '" name="entryPositionField_' . $entry['position'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['position'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                    $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteWexperience" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadWexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadWexperiencesfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.position as position, d.year as year, d.place as place, d.job as job
                                    FROM `tek_wexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Puesto: ' . $entry['position'] . '</div>';
                    $html .= '    <div>Fecha: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div>Funciones que desempeñaba: ' . $entry['job'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadWexperienceFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeWexperienceAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $wexperienceId = $request->get('wexperienceId');
            $translator = $this->get("translator");

            if( isset($wexperienceId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Wexperience")->find( $wexperienceId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createWexperienceAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $position = $request->get('position');
            $place = $request->get('place');
            $year = $request->get('year');
            $job = $request->get('job');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $wexperience = new Wexperience();
                $wexperience->setPosition($position);
                $wexperience->setJob($job);
                $wexperience->setPlace($place);
                $wexperience->setYear($year);
                $wexperience->setUser($user);


                $em->persist($wexperience);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createWexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadAexperiencesByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place, d.performance as performance
                                    FROM `tek_aexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="aexperienceRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entrySubjectField_' . $entry['subject'] . '" name="entrySubjectField_' . $entry['subject'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['subject'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                    $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteAexperience" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadAexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadAexperiencesfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place, d.performance as performance
                                    FROM `tek_aexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Curso: ' . $entry['subject'] . '</div>';
                    $html .= '    <div>Periodo: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div>Resultado desempeño de docente: ' . $entry['performance'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadAexperienceFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeAexperienceAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $aexperienceId = $request->get('aexperienceId');
            $translator = $this->get("translator");

            if( isset($aexperienceId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Aexperience")->find( $aexperienceId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createAexperienceAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $subject = $request->get('subject');
            $place = $request->get('place');
            $year = $request->get('year');
            $performance = $request->get('performance');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $aexperience = new Aexperience();
                $aexperience->setSubject($subject);
                $aexperience->setPlace($place);
                $aexperience->setYear($year);
                $aexperience->setPerformance($performance);
                $aexperience->setUser($user);


                $em->persist($aexperience);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createAexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadOexperiencesByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place
                                    FROM `tek_oexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="oexperienceRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entrySubjectField_' . $entry['subject'] . '" name="entrySubjectField_' . $entry['subject'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['subject'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                    $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteOexperience" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadOexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadOexperiencesfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place
                                    FROM `tek_oexperience` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Puesto: ' . $entry['subject'] . '</div>';
                    $html .= '    <div>Periodo: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadOexperienceFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeOexperienceAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $oexperienceId = $request->get('oexperienceId');
            $translator = $this->get("translator");

            if( isset($oexperienceId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Oexperience")->find( $oexperienceId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createOexperienceAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $subject = $request->get('subject');
            $place = $request->get('place');
            $year = $request->get('year');
            $performance = $request->get('performance');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $oexperience = new Oexperience();
                $oexperience->setSubject($subject);
                $oexperience->setPlace($place);
                $oexperience->setYear($year);
                $oexperience->setUser($user);


                $em->persist($oexperience);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createOexperienceAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadPublicationsByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place
                                    FROM `tek_publication` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="publicationRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entrySubjectField_' . $entry['subject'] . '" name="entrySubjectField_' . $entry['subject'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['subject'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                    $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                    //$html .= '    <div class="right imageButton editButton editPublication"  title="Editar" style=" width: 22px;"  rel="' . $entry['id'] . '" ></div>';
                    $html .= '    <div class="right imageButton deleteButton deletePublication" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadPublicationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadPublicationsfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place, d.authorship as authorship
                                    FROM `tek_publication` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Titulo: ' . $entry['subject'] . '</div>';
                    $html .= '    <div>Fecha: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    if($entry['authorship'] == 1){
                        $html .= '    <div>Autoría: Personal</div>';
                    }else{
                        $html .= '    <div>Autoría: Compartida</div>';
                    }
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadPublicationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removePublicationAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $publicationId = $request->get('publicationId');
            $translator = $this->get("translator");

            if( isset($publicationId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Publication")->find( $publicationId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createPublicationAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $subject = $request->get('subject');
            $place = $request->get('place');
            $year = $request->get('year');
            $authorship = $request->get('authorship');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $publication = new Publication();
                $publication->setSubject($subject);
                $publication->setPlace($place);
                $publication->setYear($year);
                $publication->setAuthorship($authorship);
                $publication->setUser($user);


                $em->persist($publication);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createPublicationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadPublicationSaveAction(){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $publicationId = $request->get('publicationId');
            $year = $request->get('year');
            $subject = $request->get('subject');
            $place = $request->get('place');
            $authorship = $request->get('authorship');

            $translator = $this->get("translator");

            if( isset($gender) && isset($degree) && isset($pension)) {
                $em = $this->getDoctrine()->getEntityManager();

                if($publicationId == "0"){
                    $publication = new Publication();
                } else {
                    $publication = $em->getRepository("App:Publication")->find($publicationId);
                }
                $publication->setYear($year);
                $publication->setSubject($subject);
                $publication->setPlace($place);
                $publication->setAuthorship($authorship);
                $em->persist($publication);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Publication::loadPublicationEditAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadPublicationEditAction(){ //2016 - 41
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $publicationId = $request->get('publicationId');
            $translator = $this->get("translator");

            //if( $publicationId != "") {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.place as place, d.subject as subject, d.year as year, d.authorship as authorship
                                    FROM `tek_publication` d
                                    where d.id = "'.$publicationId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                foreach( $entity as $entry ) {
                    $place = $entry['place'];
                    $subject = $entry['subject'];
                    $year = $entry['year'];
                    if($entry['authorship'] == 2){
                        $authorship = "Compartida";
                    }else{
                        $authorship = "Personal";
                    }
                }

                return new Response(json_encode(array('error' => false, 'entity' => $entity, 'place' => $place, 'subject' => $subject, 'year' => $year, 'authorship' => $authorship)));
            /*} else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }*/
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadCurriculumDataAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadApresentationsByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place
                                    FROM `tek_apresentation` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="apresentationRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entrySubjectField_' . $entry['subject'] . '" name="entrySubjectField_' . $entry['subject'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['subject'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['place'],0,35) . '</div>';
                    $html .= '    <div id="entryYearField_' . $entry['year'] . '" name="entryYearField_' . $entry['year'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['year'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteApresentation" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadApresentationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadApresentationsfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.subject as subject, d.year as year, d.place as place
                                    FROM `tek_apresentation` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Titulo: ' . $entry['subject'] . '</div>';
                    $html .= '    <div>Año: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadApresentationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeApresentationAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $apresentationId = $request->get('apresentationId');
            $translator = $this->get("translator");

            if( isset($apresentationId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Apresentation")->find( $apresentationId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createApresentationAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $subject = $request->get('subject');
            $place = $request->get('place');
            $year = $request->get('year');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $apresentation = new Apresentation();
                $apresentation->setSubject($subject);
                $apresentation->setPlace($place);
                $apresentation->setYear($year);
                $apresentation->setUser($user);


                $em->persist($apresentation);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createApresentationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }



    public function loadInvestigationsByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.name as name, d.validity as validity, d.place as place, d.status as status, d.type as type, d.descriptors as descriptors
                                    FROM `tek_investigation` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="investigationRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryNameField_' . $entry['name'] . '" name="entryNameField_' . $entry['name'] . '" class="option_width" style="float: left; width: 340px;">' . substr($entry['name'],0,40) . '</div>';
                    if($entry['type'] == 1){
                            $html .= '    <div id="entryTypeField_' . $entry['type'] . '" name="entryTypeField_' . $entry['type'] . '" class="option_width" style="float: left; width: 140px;">Basica</div>';
                    }else {
                        $html .= '    <div id="entryTypeField_' . $entry['type'] . '" name="entryTypeField_' . $entry['type'] . '" class="option_width" style="float: left; width: 140px;">Aplicada</div>';
                    }


                    $html .= '    <div id="entryValidityField_' . $entry['validity'] . '" name="entryValidityField_' . $entry['validity'] . '" class="option_width" style="float: left; width: 140px;">' . $entry['validity'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteInvestigation" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';

                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadInvestigationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadInvestigationsfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.name as name, d.validity as validity, d.place as place, d.status as status, d.type as type, d.descriptors as descriptors
                                    FROM `tek_investigation` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Título: ' . $entry['name'] . '</div>';
                    $html .= '    <div>Tipo: ' . $entry['type'] . '</div>';
                    if($entry['type'] == 1){
                        $html .= '    <div>Estado: Basica</div>';
                    }else{
                        $html .= '    <div>Estado: Aplicada</div>';
                    }
                    $html .= '    <div>Estado: ' . $entry['status'] . '</div>';
                    if($entry['status'] == 1){
                        $html .= '    <div>Estado: Finalizada</div>';
                    }else{
                        if($entry['status'] == 2){
                                $html .= '    <div>Estado: Vigente</div>';
                        }
                        else{
                            $html .= '    <div>Estado: Suspendida</div>';
                        }

                    }
                    $html .= '    <div>Ubicación: ' . $entry['place'] . '</div>';
                    $html .= '    <div>Vigencia: ' . $entry['validity'] . '</div>';
                    $html .= '    <div>Descriptores: ' . $entry['descriptors'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadInvestigationFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeInvestigationAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $investigationId = $request->get('investigationId');
            $translator = $this->get("translator");

            if( isset($investigationId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Investigation")->find( $investigationId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeInvestigationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createInvestigationAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $type = $request->get('type');
            $status = $request->get('status');
            $validity = $request->get('validity');
            $descriptors = $request->get('descriptors');
            $place = $request->get('place');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $investigation = new Investigation();
                $investigation->setName($name);
                $investigation->setType($type);
                $investigation->setStatus($status);
                $investigation->setValidity($validity);
                $investigation->setDescriptors($descriptors);
                $investigation->setPlace($place);
                $investigation->setUser($user);


                $em->persist($investigation);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createInvestigationAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadImprovesByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.name as name, d.year as year, d.place as place, d.subject as subject
                                    FROM `tek_improve` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div id="improveRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryNameField_' . $entry['name'] . '" name="entryNameField_' . $entry['name'] . '" class="option_width" style="float: left; width: 240px;">' . substr($entry['name'],0,35) . '</div>';
                    $html .= '    <div id="entrySubjectField_' . $entry['subject'] . '" name="entrySubjectField_' . $entry['subject'] . '" class="option_width" style="float: left; width: 250px;">' . substr($entry['subject'],0,35) . '</div>';
                    $html .= '    <div id="entryPlaceField_' . $entry['place'] . '" name="entryPlaceField_' . $entry['place'] . '" class="option_width" style="float: left; width: 250px;">' . $entry['place'] . '</div>';
                    $html .= '    <div class="right imageButton deleteButton deleteImprove" style="height: 16px;" title="Eliminar"  rel="' . $entry['id'] . '"></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                }

                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadImproveAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadImprovesfullByTeacherAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.name as name, d.year as year, d.place as place, d.subject as subject
                                    FROM `tek_improve` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";

                foreach( $entity as $entry ){
                    $html .= '<div>';
                    $html .= '    <div>Nombre: ' . $entry['name'] . '</div>';
                    $html .= '    <div>Lugar: ' . $entry['place'] . '</div>';
                    $html .= '    <div>Fecha: ' . $entry['year'] . '</div>';
                    $html .= '    <div>Tema: ' . $entry['subject'] . '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

                return new Response(json_encode(array('error' => false, 'fullhtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadImproveFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeImproveAction(){    /// 2016 - 4

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $improveId = $request->get('improveId');
            $translator = $this->get("translator");

            if( isset($improveId) ) {
                $em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:Improve")->find( $improveId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('SuperAdmin::removeImproveAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function createImproveAction(){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $subject = $request->get('subject');
            $year = $request->get('year');
            $place = $request->get('place');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $improve = new Improve();
                $improve->setName($name);
                $improve->setSubject($subject);
                $improve->setYear($year);
                $improve->setPlace($place);
                $improve->setUser($user);


                $em->persist($improve);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createImproveAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadCurriculumdataAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.pension as pension, d.gender as gender, d.email as email, d.birthday as birthday, d.phone as phone, d.regimen as regimen, d.regimentx as regimentx, d.degree as degree
                                    FROM `tek_curriculumdata` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";
                foreach( $entity as $entry ) {
                    $html .= '<div>';
                    $html .= '    <div>Pension: ' . $entry['pension'] . '</div>';
                    if ($entry['gender'] == 1) {
                        $html .= '    <div>Sexo: Hombre</div>';
                        $html .= '<input value="Masculino" hidden  type="text" id="curriculumdataGenderLabel" name="curriculumdataGenderLabel" style="width: 250px">';
                    } else if ($entry['gender'] == 2) {
                        $html .= '    <div>Sexo: Mujer</div>';
                        $html .= '<input value="Femenino"  hidden type="text" id="curriculumdataGenderLabel" name="curriculumdataGenderLabel" style="width: 250px">';
                    } else {
                        $html .= '    <div>Sexo: </div>';
                    }
                    if ($entry['degree'] == 1) {
                        $html .= '    <div>Grado Académico: Bachiller</div>';
                        $html .= '<input value="Bachiller"  hidden type="text" id="curriculumdataDegreeLabel" name="curriculumdataDegreeLabel" style="width: 250px">';
                    }
                    if ($entry['degree'] == 2) {
                        $html .= '    <div>Grado Académico: Licenciatura</div>';
                        $html .= '<input value="Licenciatura"  hidden type="text" id="curriculumdataDegreeLabel" name="curriculumdataDegreeLabel" style="width: 250px">';
                    }
                    if($entry['degree'] == 3){
                        $html .= '    <div>Grado Académico: Maestría Académica</div>';
                        $html .= '<input value="Maestría académica"  hidden type="text" id="curriculumdataDegreeLabel" name="curriculumdataDegreeLabel" style="width: 250px">';
                    }
                    if($entry['degree'] == 4){
                        $html .= '    <div>Grado Académico: Maestría Profesional</div>';
                        $html .= '<input value="Maestría Profesional"  hidden type="text" id="curriculumdataDegreeLabel" name="curriculumdataDegreeLabel" style="width: 250px">';
                    }

                    if($entry['degree'] == 5){
                        $html .= '    <div>Grado Académico: Doctorado</div>';
                        $html .= '<input value="Doctorado"  hidden type="text" id="curriculumdataDegreeLabel" name="curriculumdataDegreeLabel" style="width: 250px">';
                    }

                    $html .= '    <div>Fecha de Nacimiento: ' . $entry['birthday'] . '</div>';
                    $html .= '    <div>Correo Electrónico: ' . $entry['email'] . '</div>';
                    $html .= '    <div>Teléfono: ' . $entry['phone'] . '</div>';
                    if ($entry['regimen'] == 1) {
                        $html .= '    <div>Regimen: No</div>';
                        $html .= '<input value="No" hidden  type="text" id="curriculumdataRegimenLabel" name="curriculumdataRegimenLabel" style="width: 250px">';
                    } else if ($entry['regimen'] == 2) {
                        $html .= '    <div>Regimen: Si</div>';
                        $html .= '<input value="Si"  hidden type="text" id="curriculumdataRegimenLabel" name="curriculumdataRegimenLabel" style="width: 250px">';
                    } else {
                        $html .= '    <div>Regimen: </div>';
                    }
                    $html .= '    <div>Tipo: ' . $entry['regimentx'] . '</div>';




                    $html .= '<input value="'.$entry['id'].'" hidden type="text" id="curriculumdataId" name="curriculumdataId" style="width: 250px">';

                    $html .= '<div align="right"><a id="openCurriculumdataForm" class="modalbox button success-darker tiny" href="#curriculumdataFormContainer">Editar</a></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }

              /*  if($html ==''){
                    $html .= '<div>';
                    $html .= '    <div>Pension: </div>';

                        $html .= '    <div>Sexo: </div>';


                    $html .= '    <div>Grado Académico: </div>';


                    $html .= '    <div>Fecha de Nacimiento: </div>';
                    $html .= '    <div>Correo Electrónico: </div>';
                    $html .= '    <div>Teléfono: </div>';
                    $html .= '    <div>Regimen: </div>';
                    $html .= '    <div>Tipo: </div>';




                    //$html .= '<input value="'.$entry['id'].'" hidden type="text" id="curriculumdataId" name="curriculumdataId" style="width: 250px">';

                    $html .= '<div align="right"><a id="openCurriculumdataForm" class="modalbox button success-darker tiny" href="#curriculumdataFormContainer">Editar</a></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                    $html .= '<hr>';

                }*/


                return new Response(json_encode(array('error' => false, 'entriesHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadCurriculumDataAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadCurriculumdataformsaveAction(){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();

                $curriculumId = $request->get('curriculumId');
                $gender = $request->get('gender');
                $pension = $request->get('pension');
                $degree = $request->get('degree');
                $email = $request->get('email');
                $phone = $request->get('phone');
                $regimen = $request->get('regimen');
                $regimentx = $request->get('regimentx');
                $birthday = $request->get('birthday');

                $translator = $this->get("translator");

                if( isset($gender) && isset($degree) && isset($pension)) {
                    $em = $this->getDoctrine()->getEntityManager();

                    if($curriculumId == "0"){
                        $curriculumdata = new Curriculumdata();
                    } else {
                        $curriculumdata = $em->getRepository("App:Curriculumdata")->find($curriculumId);
                    }
                    $curriculumdata->setGender($gender);
                    $curriculumdata->setDegree($degree);
                    $curriculumdata->setPension($pension);
                    $curriculumdata->setEmail($email);
                    $curriculumdata->setPhone($phone);
                    $curriculumdata->setRegimen($regimen);
                    $curriculumdata->setRegimentx($regimentx);
                    $curriculumdata->setBirthday($birthday);
                    $em->persist($curriculumdata);
                    $em->flush();

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Curriculumdata::loadCurriculumdataformsaveAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function loadCurriculumdataformAction(){ //2016 - 4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            //$teacherId = $request->get('teacherId');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $teacherId = $user->getId();

            $translator = $this->get("translator");

            if( isset($teacherId)) {
                $em = $this->getDoctrine()->getEntityManager();


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT d.id as id, d.pension as pension, d.gender as gender, d.email as email, d.birthday as birthday, d.phone as phone, d.regimen as regimen, d.regimentx as regimentx, d.degree as degree
                                    FROM `tek_curriculumdata` d
                                    where d.user_id = "'.$teacherId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = '';
                $groupOptions = "";
                $pension = "";
                $gender = "";
                $degree = "";
                $email = "";
                $birthday = "";
                $phone = "";
                $regimen = "";
                $regimentx = "";
                foreach( $entity as $entry ) {
                    $pension = $entry['pension'];
                    $gender = $entry['gender'];
                    $degree = $entry['degree'];
                    $email = $entry['email'];
                    $birthday = $entry['birthday'];
                    $phone = $entry['phone'];
                    if($entry['regimen'] == 2){
                        $regimen = "Si";
                    }else{
                        $regimen = "No";
                    }

                    $regimentx = $entry['regimentx'];
                }

                return new Response(json_encode(array('error' => false, 'entity' => $entity, 'pension' => $pension, 'gender' => $gender, 'degree' => $degree,
                    'email' => $email, 'birthday' => $birthday, 'phone' => $phone, 'regimen' => $regimen, 'regimentx' => $regimentx)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::loadCurriculumDataAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
}
