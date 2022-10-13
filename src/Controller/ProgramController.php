<?php

namespace App\Controller;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\GeneratorInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Statement;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Absence;
use App\Entity\Contact;

use App\StudentExtraTest;
use App\Club as Club;
use App\Relative as Relative;

//use App\Programs;
use App\Entity\Programs;
use App\Entity\Questionnaire;
use App\Entity\Period;
use App\Form\ProgramFormType;

use App\Entity\Student;
//use App\Form\StudentFormTypeFormType;


use App\Entity\Course;
//use App\Form\CourseFormType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\DriverManager;

//blibiotecas para mailer
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
//blibiotecas para la creacion de pdf
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Bundle\SnappyBundle\Snappy\Generator;
use Knp\Snappy\Pdf;



class ProgramController extends AbstractController
{
    public function programShowAction(EntityManagerInterface $em,$id)
    {
      //  $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Programs")->find($id);
        $program = new \App\Entity\Programs();
        $form   = $this->createForm(new \App\Form\ProgramFormType(), $program);
        //$form = $this->createFormBuilder($program);

        return $this->render('App:Program/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 3));
    }

    /**
     * @Route("/program/create", name="_expediente_sysadmin_program_create")
     */
    public function programCreateAction()
    {
        $entity = new Programs();
        //$form   = $this->createForm(new \App\Form\ProgramFormType(), $entity);

        $form = $this->createForm('App\Form\ProgramFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_program_create'),
            'method' => 'POST'
        ));

        return $this->render('SuperAdmin/Programs/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 3));
    }

    /**
     * @Route("/program/save", name="_expediente_sysadmin_program_save")
     */
    public function programSaveAction(){
        $entity  = new Programs();
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm(ProgramFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_program_save'),
            'method' => 'PUT',
        ));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_program',
                array('id' => $entity->getId(), 'menuIndex' => 5)));
        } else {
            return $this->render('SuperAdmin/Programs/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(), 'menuIndex' => 5
            ));
        }
    }
    /**
     * @Route("/program/search", name="_expediente_sysadmin_program_search")
     * @Method({"GET", "POST"})
     */
    public function searchProgramsAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger, $rowsPerPage = 30): Response {
        //$logger = $this->lo;
       // $em = $this->getDoctrine()->getEntityManager();
        $role = 1;
      /*  if ($request->isXmlHttpRequest())// Is the request an ajax one?
        {*/
            try {
                $request = $this->get('request_stack')->getCurrentRequest();

                $text = $request->get('text');
                $sortBy = $request->get('sortBy');
                $order = $request->get('order');
                $page = $request->get('page');
                $offset = ($page - 1) * $rowsPerPage;
                // $em = $this->getDoctrine()->getEntityManager();
                $words = explode(" ", trim($text));
                $where = "";
                $where2 = "";
                $where3 = "";
                $from2 = "";
                $exprograms = "";
                $periods = $em->getRepository("App:Period")->findBy(array("isActual" => 1));
                foreach ($periods as $period) {
                    $currentPeriod = $period->getId();
                }
               // $currentPeriod = 4;

                //Codigo agregado por STUART para pintar el search en lugar del list
                $courses = $em->getRepository("App:Course")->findBy(array(), array('code' => 'ASC', 'groupnumber' => 'ASC', ));
                $teachers = $em->getRepository("App:User")->findBy(array(), array('firstname' => 'ASC'));
                $types = $em->getRepository("App:QuestionnaireGroup")->findAll();
                //Fin de lo agregado por STUART


                ///filtrar por usuario y tipo
                //$user = $this->container->get('security.context')->getToken()->getUser();
                $user = $this->container->get('security.token_storage')->getToken()->getUser();
                $roles = $user->getIdRoles();
                $role = $roles[0];
                /*Areglar esta consulta, para que jale los roles correspondientes
                ATENCION STUART Y ERICK

                 */
                //$role = $roles[0]->getId();
                //$role = 1;

                if ($role != 1) {
                    if ($role == 4) {
                        $where2 = "and pr.user_id = " . $user->getId();
                    }
                    if ($role == 3) {
                        $from2 = ", tek_course_class cc";
                        $where3 = "and (pr.course_id = cc.course_id and cc.user_id = " . $user->getId() . " and cc.period_id = " . $currentPeriod . ")";
                        $where2 = "and pr.user_id = " . $user->getId();
                    }
                }

/*
                foreach ($words as $word) {
                    $where .= $where == "" ? "" : " AND ";
                    $where .= "(pr.detail like '%" . $word . "%' OR pr.date like '%" . $word . "%')";
                }
                $sql = "SELECT SUM($where) as filtered,"
                    . " COUNT(*) as total FROM tek_programs pr  where pr.period_id = " . $currentPeriod . ";";
*/
                $sql="";
                $em->clear();
                $em->getRepository(Programs::class);



                $sql = "SELECT DISTINCT pr.id, pr.detail, concat(u.lastname,' ',u.firstname) as name, pr.status, pr.period_id" .
                    " FROM tek_programs pr, tek_users u" . $from2 .
                    " WHERE u.id = pr.user_id and"  .
                    "  pr.period_id = " . $currentPeriod . " " . $where2 .
                    " ORDER BY pr.period_id";
                $stmt2 = $em->getConnection()->prepare($sql);
                $programs = $stmt2->executeQuery();
                $programsM = $programs->fetchAllAssociative();
                // $stmt2->fetchAl();
                $exprogramsM = "";

                if ($role == 3) {
                    $em->clear();
                    $em->getRepository(Programs::class);
                    /*$sql2 = "SELECT DISTINCT pr.id, pr.detail, concat(u.lastname,' ',u.firstname) as name, pr.status, pr.period_id"
                        . " FROM tek_programs pr, tek_users u "
                        . " WHERE u.id = pr.user_id "
                        . " and pr.period_id = ". $currentPeriod. " "
                        .  $where3;*/
                    $sql2 = "SELECT DISTINCT pr.id, pr.detail, concat(u.lastname,' ',u.firstname) as name, pr.status, pr.period_id" .
                        " FROM tek_programs pr, tek_users u" . $from2 .
                        " WHERE u.id = pr.user_id and"  .
                        "  pr.period_id = " . $currentPeriod . " " . $where3 .
                        " ORDER BY pr.period_id";
                    $stmt3 = $em->getConnection()->prepare($sql2);
                    $exprograms = $stmt3->executeQuery();
                    $exprogramsM = $exprograms->fetchAllAssociative();
                    //$stmt3->->fetchAll();
                }

                return $this->render('SuperAdmin/Programs/list.html.twig', array(
                    'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role,
                    'courses' => $courses, 'teachers' => $teachers, 'types' => $types,'programsM' => $programsM, 'exprogramsM' => $exprogramsM
                ));

         /*       return new Response(json_encode(array(
                  //  'error' => false,
                  //  'filtered' => $filtered,
                   // 'total' => $total,
                    'programs' => $programsM
            //        'exprograms' => $exprogramsM
            )));*/
            } catch (Exception $e) {
                $info = $e->getTraceAsString();
                $logger->alert('Program::searchProgramsAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $sql2)));
            }/*
        }
        // endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/program/hsearch", name="_expediente_sysadmin_program_hsearch")
     */
    public function searchHProgramsAction(EntityManagerInterface $em, LoggerInterface $logger, $rowsPerPage = 30) {
        //$logger = $this->get('logger');
        //$em = $this->getDoctrine()->getEntityManager();
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
           // $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $from2 = "";
            $periods = $em->getRepository("App:Period")->findBy(array("isActual" => 1));
            foreach ($periods as $period) {
                $currentPeriod = $period->getId();
            }


            $periods = $em->getRepository("App:Period")->findBy(array("isActual" => 1));
            foreach ($periods as $period) {
                $currentPeriod = $period->getId();
            }

            ///filtrar por usuario y tipo
            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];

            if($role != 1){
                if($role == 4) {
                    $where2 = "and pr.user_id = ".$user->getId();
                }
                if($role == 3){
                    $from2 = ", tek_course_class cc";
                    $where2 = "and (pr.course_id = cc.course_id and cc.user_id = ".$user->getId().") and pr.period_id = ".$currentPeriod;
                    $where3 = "and pr.user_id = ".$user->getId()." and pr.period_id = ".$currentPeriod;
                }
            }

            $sql = "SELECT pr.id, pr.detail, concat(u.lastname,' ',u.firstname) as name, pr.status"
                . " FROM tek_programs pr, tek_users u"
                . " $from2"
                . " WHERE u.id = pr.user_id "
                . " and pr.status = 6"
                //. " and pr.period_id != $currentPeriod"
                . " $where2";
               // . " ORDER BY pr.$sortBy $order"
               // . " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);
            $programs = $stmt2->executeQuery();
            $programs = $programs->fetchAllAssociative();


            return $this->render('SuperAdmin/Programs/historic.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'programs' => $programs
            ));
           /*
            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'programs' => $programs)));*/
        } catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::searchProgramsActionH [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
            //return new Response("<b>Not an ajax call!!!" . "</b>");
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/program", name="_expediente_sysadmin_program")
     *
     */
    public function programListAction(Request $request, EntityManagerInterface $em, $rowsPerPage = 30)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        //$roles = $user->getRoles();

        /*****Nueva forma de revisar los roles ***/
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $courses = $em->getRepository("App:Course")->findBy(array(), array('code' => 'ASC', 'groupnumber' => 'ASC', ));
        $teachers = $em->getRepository("App:User")->findBy(array(), array('firstname' => 'ASC'));
        $types = $em->getRepository("App:QuestionnaireGroup")->findAll();

        return $this->render('SuperAdmin/Programs/list.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role,
            'courses' => $courses, 'teachers' => $teachers, 'types' => $types
        ));
    }
    /**
     * @Route("programH", name="_expediente_sysadmin_programh")
     */
    public function programHListAction(Request $request, EntityManagerInterface $em, $rowsPerPage = 30)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $session = $this->get('session');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        return $this->render('SuperAdmin/Programs/historic.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role
        ));
    }

    /**
     * @Route("/program/programQ/{id}", name="_expediente_sysadmin_program_questions")
     */
    public function programQuestionsAction($id, EntityManagerInterface $em)
    {
        return $this->programQuestionsGroupAction($id, 0, $em);
    }

    /**
     * @Route("/program/programQ/", name="_expediente_sysadmin_program_questions_simple")
     */
    public function programQuestions($id, EntityManagerInterface $em)
    {
        return $this->programQuestionsGroupAction($id, 0, $em);
    }

    public function programQuestionsGroupAction($id, $groupId, EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Programs")->find($id);
        $groups = $em->getRepository("App:QuestionnaireGroup")->findAll();
        $group = null;
        if($groupId == 0){ //Get the first Group
            $group = $groups[0];
        } else {
            $group = $em->getRepository("App:QuestionnaireGroup")->find($groupId);
        }
        $forms = $em->getRepository("App:Questionnaire")->findProgramQuestionnairesOfGroup($group,
            false, $entity);

        $answersResult = $em->getRepository("App:Questionnaire")
            ->findProgramQuestionnairesAnswersOfProgramByGroup($id, $group);
        $answers = array();
        foreach ($answersResult as $answer) {
            $answers[$answer->getQuestion()->getId()] = $answer;
        }

        $answerscheckResult = $em->getRepository("App:Questionnaire")
            ->findProgramQuestionnairesAnswersCheckOfProgramByGroup($id, $group);
        $answerschecks = array();
        foreach ($answerscheckResult as $answerscheck) {
            $answerschecks[$answerscheck->getQuestion()->getId()] = $answerscheck;
        }

        return $this->render('SuperAdmin/Programs/program.html.twig',
            array('entity' => $entity,'forms'   => $forms, 'menuIndex' => 3, 'answers' => $answers, 'answerscheck' => $answerschecks,
                'groups' => $groups, 'currentGroup' => $groupId));
    }

    /**
     * @Route("/student/programDataSave", name="_expediente_sysadmin_save_program_data")
     * @Method({"GET", "POST"})
     */
    public function saveProgramFormAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        //$translator = $this->get("translator");
        try {

            $request = $this->get('request_stack')->getCurrentRequest();
            $programId = $request->get('programId');


            if( isset($programId) ){
                //$program = $em->getRepository("App:Programs")->find($programId);

                $program = $em->getRepository(Programs::class)->find($programId);

                $parameters = $request->request->all();
                foreach ($parameters as $key => $value) {
                    //$logger->err("Parameter-> " . $key . ":" . $value);
                }
                foreach ($parameters as $key => $value) {
                    if( $this->startswith($key, 'q-') ){
                        $objs = explode('-', $key); //0: q, 1: questionId, 2: type
                        $answer = new \App\Entity\QuestionnaireAnswer();
                        //$answer = $em->getRepository("App:Questionnaire")
                        //    ->findProgramQuestion($programId,$objs[1]);

                        $answer = $em->getRepository(Questionnaire::class)->findProgramQuestion($programId,$objs[1]);

                        $answer->setProgram($program);
                        switch($objs[2]){
                            //SimpleInput, DateInput, TextAreaInput and YesNoSelectionSimple, Just save the answer
                            case 1:
                            case 2:
                            case 5:
                            case 6:
                            case 10:
                                $answer->setMainText($value);
                                $answer->setSecondText("");
                                break;
                            case 3://YesNoSelectionWithExplain: Must get the explanation also
                                $answer->setMainText($value);
                                $answer->setSecondText($request->get('qaux-' . $objs[1]));
                                break;
                            case 4://Must get all the
                                break;
                            case 8://Three Columns Table
                                $answer->setMainText($request->get('qaux1-' . $objs[1]) . '-*-' .
                                    $request->get('qaux2-' . $objs[1]) . '-*-'
                                    . $request->get('qaux3-' . $objs[1]) . '-*-'
                                    . $request->get('qaux4-' . $objs[1]) . '-*-'
                                    . $request->get('qaux5-' . $objs[1]));
                                $answer->setSecondText("");
                                break;
                            default:
                                return new Response(json_encode(array('error' => true,
                                    'message' => 'Tipo Incorrecto: ' . $objs[2])));
                                break;
                        }
                        $em->persist($answer);
                    } else {
                        //$logger->err("---> Omitiendo porque no es una pregunta... " . $key);
                    }
                }
                $em->flush();
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true)));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Program::saveProgramFormAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    private function startswith($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }


    /**
     * @Route("/program/programQCheck/{id}", name="_expediente_sysadmin_program_questions_check")
     */
    public function programCheckQuestions($id, EntityManagerInterface $em)
    {
        return $this->programCheckQuestionsGroupAction($id, 0, $em);
    }

    /**
     * @Route("/program/programQCheck/", name="_expediente_sysadmin_program_questions_check_simple")
     */
    public function programCheckQuestionsAction($id, EntityManagerInterface $em)
    {
        return $this->programCheckQuestionsGroupAction($id, 0, $em);
    }

    public function programCheckQuestionsGroupAction($id, $groupId, EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Programs")->find($id);
        $groups = $em->getRepository("App:QuestionnaireGroup")->findAll();
        $group = null;
        if($groupId == 0){ //Get the first Group
            $group = $groups[0];
        } else {
            $group = $em->getRepository("App:QuestionnaireGroup")->find($groupId);
        }
        $forms = $em->getRepository("App:Questionnaire")->findProgramQuestionnairesOfGroup($group,
            false, $entity);

        $answersResult = $em->getRepository("App:Questionnaire")
            ->findProgramQuestionnairesAnswersOfProgramByGroup($id, $group);
        $answers = array();
        foreach ($answersResult as $answer) {
            $answers[$answer->getQuestion()->getId()] = $answer;
        }

        $answerscheckResult = $em->getRepository("App:Questionnaire")
            ->findProgramQuestionnairesAnswersCheckOfProgramByGroup($id, $group);
        $answerschecks = array();
        foreach ($answerscheckResult as $answerscheck) {
            $answerschecks[$answerscheck->getQuestion()->getId()] = $answerscheck;
        }

        return $this->render('SuperAdmin/Programs/programCheck.html.twig',
            array('entity' => $entity,'forms'   => $forms, 'menuIndex' => 3, 'answers' => $answers, 'answerscheck' => $answerschecks,
                'groups' => $groups, 'currentGroup' => $groupId));
    }

    /**
     * @Route("/student/programDataSaveCheck", name="_expediente_sysadmin_save_program_check_data")
     * @Method({"GET", "POST"})
     */
    public function saveProgramCheckFormAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger): Response {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        //$translator = $this->get("translator");
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $programId = $request->get('programId');

            //$em = $this->getDoctrine()->getEntityManager();

            if( isset($programId) ){
                //$program = $em->getRepository("App:Programs")->find($programId);
                $program = $em->getRepository(Programs::class)->find($programId);

                $parameters = $request->request->all();
                foreach ($parameters as $key => $value) {
                    //$logger->err("Parameter-> " . $key . ":" . $value);
                }
                $parametersc = 0;
                foreach ($parameters as $key => $value) {
                    $parametersc++;
                    if( $this->startswith($key, 'q-') ){
                        $objs = explode('-', $key); //0: q, 1: questionId, 2: type
                        $answercheck = new \App\Entity\QuestionnaireAnswerCheck();
                        //$answercheck = $em->getRepository("App:Questionnaire")
                        //   ->findProgramCheckQuestion($programId,$objs[1]);
                        $answercheck = $em->getRepository(Questionnaire::class)->findProgramCheckQuestion($programId,$objs[1]);

                        $answercheckid = $answercheck->getId();
                        $answercheck->setProgram($program);
                        switch($objs[2]){
                            //SimpleInput, DateInput, TextAreaInput and YesNoSelectionSimple, Just save the answer
                            case 1:
                            case 2:
                            case 5:
                            case 6:
                            case 10:
                                $answercheck->setMainText($value);
                                $answercheck->setSecondText("");
                                break;
                            case 3://YesNoSelectionWithExplain: Must get the explanation also
                                $answercheck->setMainText($value);
                                $answercheck->setSecondText("");
                                break;
                            case 4://Must get all the
                                break;
                            case 8://Three Columns Table
                                $answercheck->setMainText($request->get('qaux1-' . $objs[1]) . '-*-' .
                                    $request->get('qaux2-' . $objs[1]) . '-*-'
                                    . $request->get('qaux3-' . $objs[1]) . '-*-'
                                    . $request->get('qaux4-' . $objs[1]) . '-*-'
                                    . $request->get('qaux5-' . $objs[1]));
                                $answercheck->setSecondText("");
                                break;
                            default:
                                return new Response(json_encode(array('error' => true,
                                    'message' => 'Tipo Incorrecto: ' . $objs[2])));
                                break;
                        }
                        $em->persist($answercheck);
                    } else {
                        //$logger->err("---> Omitiendo porque no es una pregunta... " . $key);
                    }
                }
                $em->flush();
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true)));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Program::saveProgramFormAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/program/edit", name="_expediente_sysadmin_program_edit_simple")
     */
    public function programEditAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Programs")->find($id);
//        $form   = $this->createForm(new \App\Form\ProgramFormType(), $entity);

        $form = $this->createForm(ProgramFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_program_edit_simple'),
            'method' => 'PUT',
        ));

        return $this->render('SuperAdmin/Programs/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 3));
    }

    public function programUpdateAction(){
        $em = $this->getDoctrine()->getEntityManager();
        //$request = $this->get('request')->request;
        $request = $this->get('request_stack')->getCurrentRequest();

        $entity = $em->getRepository("App:Programs")->find( $request->get('id'));

        if ( isset($entity) ) {
            $form = $this->createForm(ProgramFormType::class, $entity, array(
                'action' => $this->generateUrl('_expediente_sysadmin_program_edit_simple'),
                'method' => 'PUT',
            ));

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('_expediente_sysadmin_program'));
            } else {
                return $this->render('SuperAdmin/Programs/edit.html.twig', array(
                    'entity' => $entity, 'form'   => $form->createView(), 'menuIndex' => 3
                ));
            }
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_program'));
        }

    }

    /**
     * @Route("/course/updateProgram/", name="_expediente_sysadmin_update_program")
     */
    public function updateProgramAction(){ //2018-02-04
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $programId = $request->get('idProgram');
            $detail = $request->get('detailProgram');
            $date = $request->get('dateProgram');

            $translator = $this->get("translator");

            if( $programId != '') {
                $em = $this->getDoctrine()->getEntityManager();

                $program = new Programs();
                $program = $em->getRepository("App:Programs")->find($programId);

                $program->setDetail($detail);
                $program->setDate($date);

                /*$entityUser = $em->getRepository("App:User")->find($user);
                if( isset($entityUser)){
                    $course->setUser($entityUser);
                }*/


                $em->persist($program);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::updateProgramAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/course/getInfoProgramDetail", name="_expediente_sysadmin_get_info_program_detail")
     */
    public function getInfoProgramDetailAction(){
        $logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $programId = $request->get('programId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $program = $em->getRepository("App:Programs")->find($programId);


            if ( isset($program) ) {
                $idCourse = $program->getId();
                $detail = $program->getDetail();
                $date = $program->getDate();


                /*$user = $program->getUser();
                $userTeacher = 0;

                if( isset($user)){
                    $userTeacher = $user->getId();
                }*/

                return new Response(json_encode(array('error' => false, 'id' => $idCourse, 'detail' => $detail, 'date' => $date)));//, 'user' => $userTeacher)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::getInfoProgramFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/student/programSendCheck", name="_expediente_sysadmin_send_check_program_data")
     */
    public function sendCheckProgramFormAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
        //$logger = $this->get('logger');
        $name= "profesor";

        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
           // $translator = $this->get("translator");
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $programId = $request->get('programId');
                $userId = $request->get('programId');

               // $em = $this->getDoctrine()->getEntityManager();

                if( isset($programId) ){
                    $program = $em->getRepository("App:Programs")->find($programId);
                    $user = $em->getRepository("App:User")->find($userId);

                    $course = $program->getCourse();
                    $courseId = $course->getId();
                    $coordinatorId = "";

                    $program->setStatus(3);
                    $em->persist($program);
                    $em->flush();

                    $detail = $program->getDetail();
                    $teacher = $program->getTeacher();

                    /// obtener correo de coordinador

                    $sql = "SELECT cc.user_id as user_id"
                        . " FROM tek_programs pr, tek_course_class cc"
                        . " WHERE pr.course_id = cc.course_id and cc.course_id = ".$courseId;

                    $stmt = $em->getConnection()->prepare($sql);
                    //cambios stuart para el envio del correo
                    $result = $stmt->executeQuery()->fetchAllAssociative();
                    foreach($result as $row) {
                        $coordinatorId = $row['user_id'];
                    }

                    $coordinator = $em->getRepository("App:User")->find($coordinatorId);
                    $coordinatorEmail = $coordinator->getEmail();

                    /// enviar por correo
                    /*
                    $message = (new \Swift_Message('Solicitud de Revisión de Programa'))
                        ->setSubject('Solicitud de Revisión de Programa')
                        ->setFrom('ciencias.politicas@ucr.ac.cr')
                        ->setTo(['sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr', 'ciencias.politicas@ucr.ac.cr', $coordinatorEmail])
                        ->setBody(
                            'El usuario: '. $teacher->getFirstname(). ' '. $teacher->getLastname() . ' solicita la revisión del programa ' .$detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr'// .$coordinatorId.'id'
                        );*/

                    $email = (new Email())

                        ->from('nides.ti@ucr.ac.cr')
                        ->to('nides.ti@ucr.ac.cr')
                        ->addTo('erick.morajimenez@ucr.ac.cr')
                        ->addTo('jorgestwart.perez@ucr.ac.cr')

                        ->from('sistemas.ecp@ucr.ac.cr')
                        ->to('sistemas.ecp@ucr.ac.cr')
                        ->addTo('erick.morajimenez@ucr.ac.cr')

                        ->addTo($coordinatorEmail)
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('Solicitud de Revisión de Programa')
                        ->text('El usuario: '. $teacher->getFirstname(). ' '. $teacher->getLastname() . ' solicita la revisión del programa ' .$detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr/' .$coordinatorId.'id');
                      //  ->html('<p>See Twig integration for better HTML integration!</p>');


                 //   $mailer->send($email);

                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        // some error prevented the email sending; display an
                        // error message or try to resend the message
                        $info = $e->getTraceAsString();
                        $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                    }

                    return new Response(json_encode(array('error' => false)));

                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = $e->getTraceAsString();
                $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/student/programSendReCheck", name="_expediente_sysadmin_send_recheck_program_data")
     */
    public function sendReCheckProgramFormAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
       // $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        //    $translator = $this->get("translator");
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $programId = $request->get('programId');
                $userId = $request->get('userId');

               // $em = $this->getDoctrine()->getEntityManager();

                if( isset($programId) ){
                    $program = $em->getRepository("App:Programs")->find($programId);
                    $user = $em->getRepository("App:User")->find($userId);

                    $program->setStatus(4);
                    $em->persist($program);
                    $em->flush();

                    /// enviar por correo


                    $detail = $program->getDetail();

                    $teacher = $program->getTeacher();

                    $teacherEmail = $teacher->getEmail();

                    /// enviar por correo
                    /*
                    $message = (new \Swift_Message('Solicitud de Revisión de Programa'))
                        ->setSubject('Solicitud de Revisión de Programa')
                        ->setFrom('ciencias.politicas@ucr.ac.cr')
                        ->setTo(['sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr', 'ciencias.politicas@ucr.ac.cr',$teacherEmail])  ///$teacher->getEmail()
                        ->setBody(
                            'El coordinador '.$user->getFirstname(). ' '. $user->getLastname() .'  ha realizado la revisión de su programa de '. $detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr'
                        );

                    $mailer->send($message);
                    */
                    $email = (new Email())
                        ->from('sistemas.ecp@ucr.ac.cr')
                        ->to('sistemas.ecp@ucr.ac.cr')
                        ->addTo('erick.morajimenez@ucr.ac.cr')
                        ->addTo($teacherEmail)
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('Solicitud de Revisión de Programa')
                        ->text('El coordinador '.$user->getFirstname(). ' '. $user->getLastname() .'  ha realizado la revisión de su programa de '. $detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr');
                    //  ->html('<p>See Twig integration for better HTML integration!</p>');
                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        // some error prevented the email sending; display an
                        // error message or try to resend the message
                        $info = $e->getTraceAsString();
                        $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                    }


                    return new Response(json_encode(array('error' => false)));

                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = $e->getTraceAsString();
                $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/student/programSendPdfCheck", name="_expediente_sysadmin_send_pdfcheck_program_data")
     */
    public function sendCheckPdfProgramFormAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
      //  $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
       // $translator = $this->get("translator");
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $programId = $request->get('programId');
            $userId = $request->get('userId');

          //  $em = $this->getDoctrine()->getEntityManager();

            if( isset($programId) ){
                $program = $em->getRepository("App:Programs")->find($programId);
                $user = $em->getRepository("App:User")->find($userId);

                $program->setStatus(5);
                $em->persist($program);
                $em->flush();

                $detail = $program->getDetail();
                $teacher = $program->getTeacher();

                $teacherEmail = $teacher->getEmail();

                /// enviar por correo
                /*
                $message = (new \Swift_Message('Programa completado'))
                    ->setSubject('Programa completado')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo(['sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr', 'ciencias.politicas@ucr.ac.cr', $teacherEmail])  ///$teacher->getEmail()
                    ->setBody(
                        'El coordinador '.$user->getFirstname(). ' '. $user->getLastname() .'  ha concluido la revisión de su programa de '. $detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr para descargar el archivo'
                    );

                $mailer->send($message);
*/
                $email = (new Email())
                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($teacherEmail)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Programa completado')
                    ->text('El coordinador '.$user->getFirstname(). ' '. $user->getLastname() .'  ha concluido la revisión de su programa de '. $detail . ' accese el sitio del sistema programas.ecp.ucr.ac.cr para descargar el archivo');
                //  ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $info = $e->getTraceAsString();
                    $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                }
                return new Response(json_encode(array('error' => false)));

            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/student/programSendPdfCreateCheck", name="_expediente_sysadmin_send_pdfcreatecheck_program_data")
     * @Method({"GET", "POST"})
     *  @var Knp\Snappy\Pdf
     */
    public function sendCheckPdfCreateProgramFormAction(Pdf $knpSnappyPdf, LoggerInterface $logger, TranslatorInterface $translator, EntityManagerInterface $em){
       // $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
         //   $translator = $this->get("translator");
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $programId = $request->get('programId');
                //$programId = 1330;
                $userId = $request->get('userId');
                //$userId = 1;

              //  $em = $this->getDoctrine()->getEntityManager();

                if( isset($programId) ){
                    $program = $em->getRepository("App:Programs")->find($programId);
                    $user = $em->getRepository("App:User")->find($userId);

                    $program->setStatus(6);
                    $em->persist($program);
                    $em->flush();

                    /*$versionpt = $program->getVersionp();
                    $versionpt = $versionpt +1;
                    $program->setVersionp($versionpt);
                    $em->persist($program);
                    $em->flush();*/

                    $detail = $program->getDetail();
                    $teacher = $program->getTeacher();
                    $versionp = $program->getVersionp();
                    $couseProgram = $program->getCourse();
                    $couserId = $couseProgram->getId();


                    $teacher = $program->getTeacher();
                    $teacherName = $teacher->getFirstname().' ' . $teacher->getLastname();

                    // generar pdf
                    $course = $em->getRepository("App:Course")->find($program->getCourse());


                    $groups = $em->getRepository("App:QuestionnaireGroup")->findAll();
                    $group = null;
                    $group = $groups[0];


                    //Encabezado
                    $html = '</br>';
                    $html .= '<!DOCTYPE html>';
                    $html .= '<html lang="es" xml:lang="es">';

                    $html .= '<div class=WordSection1>';
                   // $html .= '<div><img src="medilab/assets/img/logo.png"  width="960" height="200" align="center"></div>';
                    $html .= '<table align="center">';
                    $html .= '<tr>';


                    $html .= '<td>';
                    $html .= '    <p class=Textbody align=center style=\'margin-bottom:2.85pt;text-align:center\'><a
                                    name=\'__RefHeading__2191_1055990389\'><b><span style=\'font-family:\'Arial",sans-serif;
                                    mso-bidi-font-family:Mangal\'>UNIVERSIDAD DE COSTA RICA</span></b></a><b></p>';
                    $html .= '<p class=Textbody align=center style=\'margin-bottom:2.85pt;text-align:center\'><a
                                    name="__RefHeading__2193_1055990389"><b><span style=\'font-family:"Arial",sans-serif;
                                    mso-bidi-font-family:Mangal\'>FACULTAD DE CIENCIAS SOCIALES</span></b></a><b><span
                                    style=\'font-family:"Arial",sans-serif;mso-bidi-font-family:Mangal\'><o:p></o:p></span></b></p>';
                    $html .= '<p class=Textbody align=center style=\'margin-bottom:2.85pt;text-align:center\'><a
                                    name="__RefHeading__2195_1055990389"><b><span style=\'font-family:"Arial",sans-serif;
                                    mso-bidi-font-family:Mangal\'>ESCUELA DE CIENCIAS POLÍTICAS</span></b></a><b><span
                                    style=\'font-family:"Arial",sans-serif;mso-bidi-font-family:Mangal\'><o:p></o:p></span></b></p>';
                    $html .= '<p class=Standard align=center style=\'text-align:center\'><span
                                    style=\'font-size:11.0pt;mso-bidi-font-size:12.0pt;font-family:"Calibri",sans-serif;
                                    mso-fareast-font-family:Calibri\'><o:p>&nbsp;</o:p></span></p>';
                    $html .= '<p class=Standard align=center style=\'text-align:center\'><b><span
                                    style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:Arial\'>'.$course->getName().'</span></b></p>';
                    $html .= '<p class=Standard align=center style=\'text-align:center\'><b><span
                                    style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:Arial\'>'.$course->getCode().'</span></b></p>';
                    $html .= '<p class=Standard><span style=\'font-size:11.0pt;mso-bidi-font-size:12.0pt;
                                    font-family:"Calibri",sans-serif;mso-fareast-font-family:Calibri\'><o:p>&nbsp;</o:p></span></p>';
                    $html .= '</td>';

                    $html .= '</tr>';
                    $html .= '</table>';
                    $html .= '</br>';
                    $html .= '<table class=MsoNormalTable border="0" cellspacing=0 cellpadding=0 width=960
                                 style=\'mso-padding-alt:0cm .5pt 0cm .5pt\'>
                                 <tr style=\' height: 16px mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes\'>';
                    $html .= '<td valign=top style=\'width:450pt;background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Requisitos: '.$course->getRequisit().'<o:p></o:p></span></p>
                               </td>';
                    $html .= ' <td colspan=2 valign=top style=\'width:166.2pt;mso-border-alt:solid black .25pt;
                                  background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Total de Créditos: '.$course->getCredit().'<o:p></o:p></span></p>
                                </td></tr>';
                    $html .= '<tr style="height: 16px"><td></td></tr>';
                    $html .= ' <tr style="height: 16px"><td colspan=3 valign=top style=\'width:960pt;mso-border-alt:solid black .25pt;
                                  background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Pertenece a: '.$course->getArea().'<o:p></o:p></span></p>
                                </td></tr>';
                    $html .= '<tr style="height: 16px"><td></td></tr>';
                    $html .= ' <tr style="height: 16px"><td valign=top style=\'width:450pt;mso-border-alt:solid black .25pt;
                                  background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Ciclo Lectivo:  II  -  2022<o:p></o:p></span></p>   
                                </td>';
                    $html .= '<td colspan=2 width=443 valign=top style=\'width:332.4pt;background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Profesor: '.$teacherName.'<o:p></o:p></span></p>
                               </td></tr>';
                    $html .= '<tr style="height: 16px"><td></td></tr>';
                    $html .= ' <tr style="height: 16px"><td width=222 valign=top style=\'width:450pt;mso-border-alt:solid black .25pt;
                                  background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Horario Curso: '.$course->getSchedule().'<o:p></o:p></span></p>
                                </td>';
                    $html .= '<td width=443 valign=top style=\'width:332.4pt;background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Grupo: '.$course->getGroupnumber().'<o:p></o:p></span></p>
                               </td>';
                    $html .= '<td width=443 valign=top style=\'width:332.4pt;background:white;padding:0cm 2.7pt 0cm 2.7pt\'>
                                  <p class=Standard><span style=\'font-family:"Arial",sans-serif;mso-fareast-font-family:
                                  Arial\'>Aula: '.$course->getClassroom().'<o:p></o:p></span></p>
                               </td></tr>';
                    $html .= '';
                    $html .= '</table>';
                    $html .= '</br>';
                    $html .= '</br>';

                    $forms = $em->getRepository("App:Questionnaire")->findProgramQuestionnairesOfGroup($group,
                        false, $program);
                    $answersResult = $em->getRepository("App:Questionnaire")
                        ->findProgramQuestionnairesAnswersOfProgramByGroup($programId, $group);
                    $answers = array();
                    foreach ($answersResult as $answer) {
                        $answers[$answer->getQuestion()->getId()] = $answer;
                    }

                    $secnumber = 0;


                    foreach ($forms as $form) {

                        foreach ($form->getQuestions() as $question) {
                            $value1 = "";
                            $value2 = "";
                            $value3 = "";


                            //inicia el ciclo
                            if(array_key_exists($question->getId(), $answers)){
                                $value1 = $answers[$question->getId()]->getMainText();
                                $value2 = $answers[$question->getId()]->getSecondText();
                            }


                            if($question->getId() == 2){ ///Horario de atención
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'. nl2br($string) . '</span></p></div>';
                                }
                            }
                            if($question->getId() == 3){ ///Correo
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><p align="justify"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 1079){ ///Correo Alternativo
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><p align="justify"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 4){ ///Informacion adicional
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><p align="justify"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 5){    ///Descripcion
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">1.' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 6){    ///objetivo general
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">2.' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 7){    ///Objetivos especificos
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">3.' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 8){    ///Descripcion de
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">4.' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 9){    ///Metodologia
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">5.' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 1077){    ///Metodologia adicional
                                if($value1 != ''){
                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;"></span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 28){    ///Observaciones
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 29){    ///Cronograma
                                $html .= '</br>';
                                    $html .= '<label style="font-family:Arial; width: 100%">7. ' . $question->getMainText() . '</label><div
                            class="clear"></div>';
                                    $texts = explode('-',$question->getSecondText());
                                    $html .= '<table width="910"  border="1" style="font-family:Arial; table-layout: fixed; width: 100%"><tr>' .
                                        '<td width="75"> ' . $texts[0] . '</td>' .
                                        '<td width="75"> ' . $texts[1] . '</td>' .
                                        '<td width="100"> ' . $texts[2] . '</td>' .
                                        '<td width="250"> ' . $texts[3] . '</td>' .
                                        '<td width="250"> ' . $texts[4] . '</td>' .
                                        '</tr>';
                                    $html .= '<div class="questions-group">';
                                    foreach ( $question->getChildren() as $q) {
                                        $value1 = "";
                                        if(array_key_exists($q->getId(), $answers)){
                                            $value1 = $answers[$q->getId()]->getMainText();
                                            $value2 = explode('-*-', $value1);
                                        }else {
                                            $value2 = array("","","","","");
                                        }
                                        $html .= '<input type="hidden" >';
                                        if( $value2[0] != '' ||  $value2[1] != '' ||  $value2[2] != '' ||  $value2[3] != '' || $value2[4] != ''){
                                        $html .= '<tr>' .
                                            '<td width="75" align="center"> ' . $value2[0] . '</br></br></td>' .
                                            '<td width="75"> ' . $value2[1] . '</br></br></td>' .
                                            '<td width="100" align="center" style="word-wrap: break-word;" > '. $value2[2] . '</br></br></td>' .
                                            '<td width="250" style="word-wrap: break-word"> '. $value2[3] . '</br></br></td>' .
                                            '<td width="250" style="word-wrap: break-word"> '. $value2[4] . '</br></br></td>' .
                                            '</tr>';
                                        }
                                    }
                                    $html .= '</table>';

                            }

                            if($question->getId() == 35){    ///Bibliografia
                                if($value1 != ''){

                                    $html .= '</br>';
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; word-wrap: break-word; margin-left: 10px; margin-right: 10px;">8. ' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 54){    ///info adicional a bibliografia
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; word-wrap: break-word; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 1078){    ///info adicional a bibliografia complementaria
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><span style=" font-family:Arial; word-wrap: break-word; margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify"><span style="font-family:Arial;  margin-left: 10px; margin-right: 10px;">'.nl2br($string) . '</span></p></div>';
                                }
                            }

                            if($question->getId() == 46){    ///Evaluacion

                                $html .= '</br>';
                                $html .= '<label style="font-family:Arial; width: 100%">6.' . $question->getMainText() . '</label><div
                            class="clear"></div>';
                                $texts = explode('-',$question->getSecondText());
                                $html .= '<table width="910" style="font-family:Arial; table-layout: fixed; width: 100%" border="1">';
                                $html .= '<div class="questions-group">';
                                foreach ( $question->getChildren() as $q) {
                                    $value1 = "";
                                    if(array_key_exists($q->getId(), $answers)){
                                        $value1 = $answers[$q->getId()]->getMainText();
                                        $value2 = explode('-*-', $value1);
                                    }else {
                                        $value2 = array("","","","");
                                    }
                                    $html .= '<input type="hidden" >';
                                    if( $value2[0] != '' ||  $value2[1] != '' ||  $value2[2] != '' ||  $value2[3] != ''){
                                    $html .= '<tr>' .
                                        '<td width="50" align="center"> ' . $value2[0] . '</td>' .
                                        '<td width="150" align="left">' . $value2[1] . '</td>' .
                                        '<td width="50" align="center" > ' . $value2[2] . '</td>' .
                                        '<td width="660"  align="left" style="word-wrap: break-word"> '. $value2[3] . '</td></tr>' ;
                                    //if($value2[0] != ''){$html .= '<td><td colspan="2"> <hr size="2px" color="gray" /></td><td> </td></tr>';}
                                    }
                                }
                                $html .= '</table>';
                            }

                            if($question->getId() == 53){    ///Observaciones
                                if($value1 != ''){
                                    $html .= '<div style="width: 910px"><span style="  margin-left: 10px; margin-right: 10px;">' . $question->getMainText() . ': </span>';
                                    $string = htmlspecialchars($value1);
                                    $html .= '<p align="justify">'.nl2br($string) . '</p></div>';
                                }
                            }

                        }
                    }

                    $html .= '';
                    $html .= '';
                    $html .= '';
                    $html .= '    </div><br><br><br><br><br>';
                    $html .= '    <hr size="2px" color="black" />
                                   <div class="clear" style="font-family:Arial;">
                                   
                                   <strong>SOBRE EL PLAGIO </strong>
<p>De acuerdo con el REGLAMENTO DE ORDEN Y DISCIPLINA DE LOS ESTUDIANTES DE LA UNIVERSIDAD DE COSTA RICA (Reforma Integral aprobada en la sesión 4207-05, 21/08/1996, publicado en La Gaceta Universitaria 22-96 del 18/09/1996), la acción del PLAGIO es estipulada como falta muy grave según el  CAPÍTULO II DE LAS FALTAS ARTÍCULO 4, inciso j, el cual dice:  Plagiar, en todo o en parte, obras intelectuales de cualquier tipo.</p>

<p>Las sanciones correspondientes al PLAGIO se estipulan en el CAPÍTULO III  DE LAS SANCIONES, ARTÍCULO 9. Las faltas serán sancionadas  según la magnitud del hecho con las  siguientes medidas:</p>

<p>a) Las faltas muy graves, con suspensión de su condición de estudiante regular no menor de seis meses calendario, hasta  por seis años calendario.</p>

 <br><br>

<strong>SOBRE EL HOSTIGAMIENTO SEXUAL</strong> 

<p>CAPÍTULO III</p> 
<p>HOSTIGAMIENTO SEXUAL</p> 
<p>ARTÍCULO 5: DEFINICIÓN</p> 

<p>Se entiende por hostigamiento sexual toda conducta de naturaleza sexual indeseada por quien la recibe, reiterada, o bien que, habiendo ocurrido una sola vez, provoque efectos perjudiciales. El acoso sexual puede manifestarse por medio de los siguientes comportamientos: 1. Requerimientos de favores sexuales que impliquen: a. Promesa, implícita o expresa, de un trato preferencial, respecto de la situación, actual o futura, de empleo, estudio o cualquier otro propio del ámbito universitario. b. Amenazas, implícitas o expresas, físicas o morales, de daños o castigos referidos a la situación, actual o futura, de empleo o de estudio de quien las reciba. c. Exigencia de una conducta cuya sujeción o rechazo sea, en forma implícita o explícita, condición para el empleo o el estudio.</p>
<p>2. Uso de palabras de naturaleza sexual, escritas u orales, que resulten hostiles, humillantes u ofensivas para quien las reciba; piropos o gestos que resulten hostiles, humillantes u ofensivos para quien los reciba.</p> 
<p>3. Acercamientos corporales u otras conductas físicas de naturaleza sexual, indeseadas y ofensivas para quien los reciba. Estas conductas pueden tener las siguientes consecuencias:</p> 

<p>a. Condiciones materiales de trabajo: se refiere a todas aquellas acciones que suceden en el ámbito de las relaciones laborales, tales como modificaciones perjudiciales al salario, a los incentivos, rebajas de horas extras, modificación de funciones, comunicación no pertinente en tiempo, espacio, contenido, o violación de sus derechos laborales y cualquier otro trato discriminatorio en intención o resultado.</p> 
<p>b. Desempeño y cumplimiento laboral: son todas las acciones que afectan el desarrollo normal de las actividades laborales y que resultan en conductas tales como baja eficiencia, ausencias, incapacidades, desmotivación.</p>
<p>c. Condiciones materiales de estudio: se refiere a todas aquellas acciones que suceden en el ámbito académico, tales como cambio de horarios de lecciones o de atención de estudiantes, calificación injusta de trabajos, exámenes o promedios finales, afectación de la relación entre pares, comunicaciones no pertinentes en tiempo, espacio o contenido, y cualquier otro trato discriminatorio en intención o resultado.</p> 
<p>d. Desempeño y cumplimiento académico: son todas las acciones que afectan el desarrollo normal de las actividades académicas y que resultan en conductas tales como bajo rendimento académico, ausencias, incapacidades, no cumplimiento con los requisitos del curso, desmotivación y deserción.</p> 
<p>e. Estado general de bienestar personal: son todas aquellas acciones que afectan negativamente el estado general necesario para afrontar las actividades de la vida diaria.</p>

<p>SI UD SE ENCUENTRA EN UNA SITUACIÓN SIMILAR, PUEDE COMUNICARSE AL 25114898. </p>

<strong>SOBRE LA DISCRIMINACIÓN</strong>
<p>REGLAMENTO DE LA UNIVERSIDAD DE COSTA RICA EN CONTRA DE LA DISCRIMINACIÓN https://www.cea.ucr.ac.cr/images/asuntosadm/discriminacion.pdf </p>
<p>"ARTÍCULO 3.- Definiciones</p>
<p>Discriminación: Para efectos del presente reglamento, se entenderá por discriminación un acto u omisión que afecte, lesione o interrumpa, negativamente, las oportunidades o el ejercicio de derechos humanos, así como cualquier tratamiento injusto que afecte el estado general de bienestar de un grupo o una persona, origen étnico, nacionalidad, condición de salud, discapacidad, embarazo, estado civil, ciudadanía, cultura, condición migratoria, sexo, género o identidad de género, características genéticas, parentesco, razones de edad, religión, orientación sexual, opinión o participación política, afiliación gremial, origen social y situación económica, al igual que cualquier otra que socave el carácter y los propósitos de la Universidad de Costa Rica.”</p>
 <br><br>

<strong>SOBRE JUSTIFICACIONES DE ENTREGAS</strong>
<p>La Comisión de Orientación y Evaluación (COE) de la Escuela de Ciencias Políticas, comunica que como parte del mejoramiento de los procesos de enseñanza-aprendizaje-evaluación de la unidad académica, ante las consultas recibidas de algunas personas docentes en relación con justificaciones presentadas por el estudiantado cuya fundamentación es salud mental u otra condición de salud, la Comisión de Orientación y Evaluación les recuerda que para toda petición de parte del estudiantado:  </p>
<p>a) la persona estudiante debe seguir el debido proceso estipulado en el Reglamento de Régimen Estudiantil, </p>
<p>b) solo se admitirá como válida aquella justificación que se presente por escrito - una carta firmada-, debidamente acompañada de documentos probatorios tales como: diagnóstico médico, emitido por un centro de salud de la CCSS, un consultorio privado, constancia de apoyo psicológico del CASE de Ciencias Sociales, del CASED, de la Oficina de Bienestar y Salud, o del Programa Mishka de la UCR.</p>
<p>Cualquier otra comunicación personal no acompañada de un diagnóstico médico, psicológico o psiquiátrico no podrá tomarse como válido para justificar la no realización de actividades evaluativas en sus respectivos cursos. La Comisión de Orientación y Evaluación, sigue a su disposición para apoyarles en la evacuación de dudas y acompañamiento de procesos que estén dentro de las funciones que a ella le corresponden.</p>
 <br><br>

 
 </br></div>';

                    $html .= '    <div class="clear"></div>';
                    $html .= '    </div><br><br><br><br><br>';
                    $html .= '    <hr size="2px" color="black" />
                                   <div class="clear"><strong>Somos la Escuela de Ciencias Políticas - UCR</strong></br>
                                    Facultad de Ciencias Sociales, Ciudad Universitaria, San Pedro, Montes de Oca, San José, Costa Rica</br>
                                    Sexto piso Facultad de Ciencias Sociales. Teléfono 2511-6401. Fax 2511-6411</br>
                                    Página web: https://ecp.ucr.ac.cr / Dirección Electrónica: ciencias.politicas@ucr.ac.cr</br></div>';

                    $html .= '    <div class="clear"></div>';
                    $html .= '</html>';


                 //   $this->get('knp_snappy.pdf')->generateFromHtml(
                    $knpSnappyPdf->generateFromHtml(
                        $this->renderView(
                            'SuperAdmin/Programs/programaview.html.twig',array('html' => $html,'page-size' => "Letter"), true

                        ),
                        '../public/assets/images/programas/'.$programId.'v'.$versionp.'.pdf'
                        //'../web/images/programas/file4.pdf'
                    );

                    //$this->get('knp_snappy.pdf')->generate($html, '/web/images/programas/file4.pdf');

                    return new Response(json_encode(array('error' => false)));

                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info =  $e->getTraceAsString();
                $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/students/getInfoProgramFull", name="_expediente_sysadmin_get_info_program_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoProgramFullAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                //$request = $this->get('request')->request;
                $programId = $request->get('programId');

                //$em = $this->getDoctrine()->getEntityManager();
                //$student = new Student();
                $program = $em->getRepository("App:Programs")->find($programId);
                $course = $program->getCourse();




                if ( isset($program) ) {
                    $html  = '<div class="fieldRow"><label>Nombre:</label><span>' . $program->getDetail() . '</span></div><div style="float: right;"><p></div>';
                    $html .= '<div class="fieldRow"><label>Curso:</label><span>' . $course->getName() . '</span></div>';
                    $html .= '<div class="fieldRow"><label>Codigo:</label><span></span>' . $course->getCode() . '</div>';
                    $html .= '<div class="fieldRow"><label>Requisitos:</label><span>' . $course->getRequisit() . '</span></div>';
                    $html .= '<div class="fieldRow"><label>Co-Requisitos:</label><span></span>' . $course->getCorequisite() . '</div>';
                    $html .= '<div class="fieldRow"><label>Creditos:</label><span>' . $course->getCredit() . '</span></div>';
                    $html .= '<div class="fieldRow"><label>Área:</label><span></span>' . $course->getArea() . '</div>';
                    $html .= '<div class="fieldRow"><label>Horario:</label><span>' . $course->getSchedule() . '</span></div>';
                    $html .= '<div class="fieldRow"><label>Grupo:</label><span></span>' . $course->getGroupnumber() . '</div>';
                    $html .= '<div class="fieldRow"><label>Clase:</label><span></span>' . $course->getClassroom() . '</div>';
                    /*
                                        //$relative = new Relative();
                                        $relatives = $em->getRepository("App:Relative")->findByContact($contactId);
                                        $html .='<hr>';
                                        $html .= '<div><h3><label>Estudiantes Asociados:</label><span></h3></span></div>';
                                        foreach($relatives as $relative){
                                            $html .='<hr>';
                                            $html .= '<div class="fieldRow"><label>Nombre:</label><span>' . $relative->getStudent()->getFirstname() . '</span></div>';
                                            $html .= '<div class="fieldRow"><label>Grupo:</label><span>' . $relative->getStudent()->getGroupyear() . '</span></div>';
                                            $html .= '<div class="fieldRow"><label>Carne:</label><span>' . $relative->getStudent()->getCarne() . '</span></div>';
                                            $html .= '<div class="fieldRow"><label>Relaci&oacute;n:</label><span>' . $relative->getDescription() . '</span></div>';
                                        }*/

                    return new Response(json_encode(array('error' => false, 'html' => $html)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
                }


            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('Program::showProgramAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    /**
     * @Route("/students/getInfoQuestionnaireFull", name="_expediente_sysadmin_get_info_questionnaire_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoQuestionnaireFullAction(EntityManagerInterface $em, LoggerInterface $logger){
        //$logger = $this->get('logger');
        /*if ($this->get('request_stack')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {

            $request = $this->get('request_stack')->getCurrentRequest();

            //$request = $this->get('request')->request;
            //$questionnaireId = $request->get('questionnaireId');

            $programId = $request->get('questionnaireId');

            //$em = $this->getDoctrine()->getEntityManager();

            //$questionnaire = $em->getRepository("App:Questionnaire")->find($questionnaireId);

            $program = $em->getRepository("App:Programs")->find($programId);

            if ( isset($program) ) {

                $course = $program->getCourse();
                $html  = '<div class="fieldRow"><label>Ayuda:</label><span>' . $program->getDetail() . '</span></div><div style="float: right;"><p></div>';
                if ( isset($course) ) {
                    $html  .= '<div class="fieldRow"><label>Guia de programa:</label><span><a target="_blank" href="../../../assets/images/programas/' . $course->getCode() . '.pdf">Aca</a></span></div><div style="float: right;"><p></div>';
                }
                $html .= '<div><b>Información sobre programas generados</b></div>';
                $html .= '<div>- Se aclara que la plantilla del programa incluye de forma automática normativa sobre creditaje, plagio, hostigamiento sexual y discrimación.</div>';

                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Program::showQuestionnaireAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/program/programPDFCheck", name="_expediente_sysadmin_program_questions_pdf_check_simple")
     */
    public function programPdfCheckQuestionsAction(){
        $logger = $this->get('logger');



        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {

                $path = "D:/xampp-win32-1.7.5-VC9/xampp/htdocs/expediente2007/web/images/programas/";
                $file = $path.'4.pdf'; // Path to the file on the server

                /*$filetemp = new File($file);

                return $filetemp;*/


                /*
                                $fileContent = $file; // the generated file content
                                $response = new Response($fileContent);

                                $disposition = $response->headers->makeDisposition(
                                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                                    '4.pdf'
                                );

                                $response->headers->set('Content-Disposition', $disposition);

                                return $response;*/


                $response = new Response($file);

                $disposition = $response->headers->makeDisposition(
                    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    '4.pdf'
                );

                $response->headers->set('Content-Disposition', $disposition);

                return $response;

            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Program::showQuestionnaireAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }


    /**
     * @Route("/program/createProgram/", name="_expediente_sysadmin_create_program")
     * @Method({"GET", "POST"})
     */
    public function createProgramAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();


            //$request = $this->requestStack->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            /*$user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();*/

            $name = $request->get('name');
            $course = $request->get('course');
            $type = $request->get('type');
            $user = $request->get('user');



            //$translator = $this->get("translator");

            if( isset($user)) {

                $em->getRepository(Programs::class);


                $program = new Programs();
                $program->setDetail($name);
                $program->setStatus(1);
                $program->setDate();



                $entityCourse = $em->getRepository("App:Course")->findOneBy(array('id' => $course));
                if( isset($entityCourse)){
                    $program->setCourse($entityCourse);
                }

                $entityPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                if( isset($entityPeriod)){
                    $program->setPeriod($entityPeriod);
                }

                $entityUser = $em->getRepository("App:User")->findOneBy(array('id' => $user));
                if( isset($entityUser)){
                    $program->setTeacher($entityUser);
                }

                $entityCourse = $em->getRepository("App:Course")->findOneBy(array('id' => $course));
                if( isset($entityCourse)){
                    $program->setCourse($entityCourse);
                }

                $entityQuestionnaireGroup = $em->getRepository("App:QuestionnaireGroup")->findOneBy(array('id' => "1"));
                if( isset($entityQuestionnaireGroup)){
                    $program->setType($entityQuestionnaireGroup);
                }


                $em->persist($program);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                //return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                return new Response(json_encode(array('error' => true)));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::createProgramAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }



}
