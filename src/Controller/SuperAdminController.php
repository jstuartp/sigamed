<?php

namespace App\Controller;

use App\Entity\CategoryItem;
use App\Entity\User;
use App\Entity\Period;
use App\Entity\Course;
use App\Entity\Charges;
use App\Entity\Commission;
use App\Entity\Project;
use App\Entity\CourseClass;
use App\Entity\Record;
use App\Entity\Ticket;

use App\Entity\TicketItem;
use App\Entity\Item;
use App\Entity\RecordAttachment;
use App\Entity\RecordUser;
use App\Entity\AssignedCourse;
use App\Entity\AssignedCommission;
use App\Entity\AssignedProject;
use App\Entity\AssignedOther;
use App\Entity\AssignedTeacher;
use App\Form\UserFormType;
use App\Form\CourseFormType;
use App\Form\PeriodFormType;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/*use Tecnotek\App\Entity\Route;
use Tecnotek\App\Entity\Buseta;
use Tecnotek\App\Entity\Grade;
use Tecnotek\App\Entity\CourseEntry;
use Tecnotek\App\Entity\CourseClass;
use Tecnotek\App\Entity\StudentQualification;
use Tecnotek\App\Entity\SubCourseEntry;
use Tecnotek\App\Entity\AssignedTeacher;
*/

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\RequestStack;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//use Symfony\Component\DateTime;
//use Symfony\Component\Validator\Constraints\DateTime;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Contracts\Translation\TranslatorInterface;


class SuperAdminController extends AbstractController
{
    
    public function indexAction($name = "John Doe")
    {
        return $this->render('index.html.twig', array('name' => $name));
    }

    /**
     * @Route("/changePassword", name="_change_admin_password")
     * @Method({"GET", "POST"})
     */
    public function changeUserPasswordAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');

        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $newPassword = $request->get('newPassword');
            $confirmPassword = $request->get('confirmPassword');
            $userId = $request->get('userId');;
            //$em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository("App:User")->find($userId);
            //$translator = $this->get("translator");
            if ( isset($user) ) {
                $defaultController = new DefaultController();
                $error = $defaultController->validateUserPassword($newPassword, $confirmPassword);
                if ( isset($error) ) {
                    return new Response(json_encode(array('error' => true, 'message' => $error)));
                } else {
                    $user->setPassword($newPassword);
                    $em->persist($user);
                    $em->flush();
                    return new Response(json_encode(array('error' => false, 'message' =>"Contraseña cambiada correctamente")));
                }
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error datos")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::changeUserPasswordAction [' . $info . "]");
            return new Response($info);
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    /**
     * @Route("/user/createUser/", name="_expediente_sysadmin_create_user")
     */
    public function createUserAction(){ //2020-03-03
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();


            $firstname = $request->get('firstname');
            $lastname = $request->get('lastname');
            $username = $request->get('username');
            $type = 4;
            $email = $request->get('email');



            $translator = $this->get("translator");

            if( isset($username)) {
                $em = $this->getDoctrine()->getEntityManager();

                $user = new User();
                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setActive(1);
                //$user->setType($type);
                $user->setPassword("ecp1");


                $em->persist($user);
                $em->flush();

                $user_id = $user->getId();
                $role = new Role();
                

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::createUserAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /* Metodos para CRUD de courses */
    /*public function courseListAction($rowsPerPage = 10)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT entity FROM App:Course entity";
        $query = $em->createQuery($dql);

        $request = $this->get('request_stack')->getCurrentRequest();

        $param = $request->query->get('rowsPerPage');
        if(isset($param) && $param != "")
            $rowsPerPage = $param;

        $dql2 = "SELECT count(entity) FROM App:Course entity";
        $page = $this->getPaginationPage($dql2, $request->query->get('page', 1), $rowsPerPage);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,
            $rowsPerPage
        );

        return $this->render('SuperAdmin/Course/list.html.twig', array(
            'pagination' => $pagination, 'rowsPerPage' => $rowsPerPage, 'menuIndex' => 5
        ));
    }*/
    /**
     * @Route("/course", name="_expediente_sysadmin_course")
     */
    public function courseListAction(EntityManagerInterface $em, $rowsPerPage = 30)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Course/list.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }


    public function getPaginationPage($dql, $currentPage, $rowsPerPage){
        if(isset($currentPage) == false || $currentPage <= 1){
            return 1;
        } else {
            $em = $this->getDoctrine()->getEntityManager();
            $query = $em->createQuery($dql);
            $total = $query->getSingleScalarResult();
            //Check if current page has Results
            if( $total > (($currentPage - 1) * $rowsPerPage)){//the page has results
                return $currentPage;
            } else {
                $x = intval($total / $rowsPerPage);
                if($x == 0){
                    return 1;
                } else {
                    if( $total % ($x * $rowsPerPage) > 0){
                        return $x+1;
                    } else {
                        return $x;
                    }
                }
            }
        }
    }


    /**
     * @Route("/course/search", name="_expediente_sysadmin_course_search")
     */
    public function searchCoursesAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger, $rowsPerPage = 30) {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
          //  $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";


//Agregado por Stuart
            $request = $this->get('request_stack')->getCurrentRequest();
            $text = $request->get('text');

            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];

            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $teachers = $query->getResult();

/*
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_courses c;";
            $stmt = $em->getConnection()->prepare($sql);
*/
            $filtered = 0;
            $total = 0;
  //          $result = $stmt->executeQuery();


            $sql = "SELECT c.id, c.name, c.code, c.type"
                . " FROM tek_courses c";
            //    . " WHERE $where";
            //    . " ORDER BY c.$sortBy $order"
            //    . " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);

            $courses = $stmt2->executeQuery();


            return $this->render('SuperAdmin/Course/list.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers, 'courses' => $courses->fetchAllAssociative()
            ));

/*
            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'courses' => $courses->fetchAllAssociative())));*/
        } catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::searchCoursesAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/createCourse/", name="_expediente_sysadmin_create_course")
     * @Method({"GET", "POST"})
     */
    public function createCourseAction(EntityManagerInterface $em, Request $request){ //2018-13-03
     //   $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $requisit = $request->get('requisit');
            $corequisite = $request->get('corequisite');
            $credit = $request->get('credit');
            $area = $request->get('area');
            $schedule = $request->get('schedule');
            $groupnumber = $request->get('groupnumber');
            $classroom = $request->get('classroom');
            $room = $request->get('room');
            $section = $request->get('section');
            $status = $request->get('status');
            $user = $request->get('user');



          //  $translator = $this->get("translator");

            if( isset($userId)) {
               // $em = $this->getDoctrine()->getEntityManager();

                $course = new Course();
                $course->setName($name);
                $course->setCode($code);
                $course->setType($type);
                $course->setRequisit($requisit);
                $course->setCorequisite($corequisite);
                $course->setCredit($credit);
                $course->setArea($area);
                $course->setSchedule($schedule);
                $course->setGroupnumber($groupnumber);
                $course->setClassroom($classroom);
                $course->setRoom($room);
                $course->setSection($section);
                $course->setStatus($status);

                $entityUser = $em->getRepository("App:User")->find($user);
                if( isset($entityUser)){
                    $course->setUser($entityUser);
                }


                $em->persist($course);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"Error")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
           // $logger->err('Course::createCourseAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/updateCourse/", name="_expediente_sysadmin_update_course")
     * @Method({"GET", "POST"})
     */
    public function updateCourseAction(Request $request, EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $courseId = $request->get('courseId');
            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $requisit = $request->get('requisit');
            $corequisite = $request->get('corequisite');
            $credit = $request->get('credit');
            $area = $request->get('area');
            $schedule = $request->get('schedule');
            $groupnumber = $request->get('groupnumber');
            $classroom = $request->get('classroom');
            $room = $request->get('room');
            $section = $request->get('section');
            $status = $request->get('status');
            $user = $request->get('user');

            //$translator = $this->get("translator");

            if( $courseId != '') {
                //$em = $this->getDoctrine()->getEntityManager();

                $course = new Course();
                $course = $em->getRepository("App:Course")->find($courseId);

                $course->setName($name);
                $course->setCode($code);
                $course->setType($type);
                $course->setRequisit($requisit);
                $course->setCorequisite($corequisite);
                $course->setCredit($credit);
                $course->setArea($area);
                $course->setSchedule($schedule);
                $course->setGroupnumber($groupnumber);
                $course->setClassroom($classroom);
                $course->setRoom($room);
                $course->setSection($section);
                $course->setStatus($status);

                $entityUser = $em->getRepository("App:User")->find($user);
                if( isset($entityUser)){
                    $course->setUser($entityUser);
                }


                $em->persist($course);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::createCourseAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/create", name="_expediente_sysadmin_course_create")
     */

    public function courseCreateAction(Request $request)
    {
        /*$entity = new Course();
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\CourseFormType(), $entity);
        $form = $this->createForm('App\Form\CourseFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_course_create'),
            'method' => 'POST'
        ));
        return $this->render('SuperAdmin/Course/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));*/

        $entity = new Course();

        /*$form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();*/

        $form = $this->createForm('App\Form\CourseFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_course_create'),
            'method' => 'POST'
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $entity = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('_expediente_sysadmin_course', [
                'id' => $entity->getId()
            ]);

            return $this->redirectToRoute('task_success');
        }

        return $this->render('SuperAdmin/Course/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/course/show", name="_expediente_sysadmin_course_show_simple")
     */
    public function courseShowAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Course")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\CourseFormType(), $entity);
        $form = $this->createForm(CourseFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_course_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Course/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));
    }

    /**
     * @Route("/course/getInfoCourseFull", name="_expediente_sysadmin_get_info_course_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoCourseFullAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $courseId = $request->get('courseId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $course = $em->getRepository("App:Course")->find($courseId);





            if ( isset($course) ) {
                $html  = '<div class="fieldRow"><label>Nombre:</label><span>' . $course->getName() . '</span></div><div style="float: right;"><p></div>';
                $html .= '<div class="fieldRow"><label>Codigo:</label><span></span>' . $course->getCode() . '</div>';
                switch ($course->getType()){
                    case 1:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                                break;
                    case 2:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Verano</span></div>';
                                break;
                    case 3:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Tutoría</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                        break;

                }

                $html .= '<div class="fieldRow"><label>Requisitos:</label><span>' . $course->getRequisit() . '</span></div>';
                $html .= '<div class="fieldRow"><label>Co-Requisitos:</label><span></span>' . $course->getCorequisite() . '</div>';
                $html .= '<div class="fieldRow"><label>Creditos:</label><span>' . $course->getCredit() . '</span></div>';
                $html .= '<div class="fieldRow"><label>Área:</label><span></span>' . $course->getArea() . '</div>';
                $html .= '<div class="fieldRow"><label>Horario:</label><span>' . $course->getSchedule() . '</span></div>';
                $html .= '<div class="fieldRow"><label>Grupo:</label><span></span>' . $course->getGroupnumber() . '</div>';
                $html .= '<div class="fieldRow"><label>Clase:</label><span></span>' . $course->getClassroom() . '</div>';


                switch ($course->getSection()){
                    case 1:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Repertorios</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Cursos de servicio</span></div>';
                        break;
                    case 3:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Primer Año</span></div>';
                        break;
                    case 4:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Segundo Año</span></div>';
                        break;
                    case 5:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Tercer Año</span></div>';
                        break;
                    case 6:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Cursos Optativos Tercer Año</span></div>';
                        break;
                    case 7:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Cuarto Año</span></div>';
                        break;
                    case 8:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Quinto Año</span></div>';
                        break;
                    case 9:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Trabajos Finales</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Sección:</label><span>Repertorios</span></div>';
                        break;

                }
                $html .= '<div class="fieldRow"><label>Sección:</label><span>' . $course->getSection() . '</span></div>';


                $html .= '<div class="fieldRow"><label>Cupo:</label><span></span>' . $course->getRoom() . '</div>';
                $html .= '<div class="fieldRow"><label>Profesor:</label><span></span>' . $course->getUser() . '</div>';

                switch ($course->getStatus()){
                    case 1:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Inactivo</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;

                }

                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::getInfoCourseFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/getInfoCourseDetail", name="_expediente_sysadmin_get_info_course_detail")
     * @Method({"GET", "POST"})
     */
    public function getInfoCourseDetailAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $courseId = $request->get('courseId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $course = $em->getRepository("App:Course")->find($courseId);





            if ( isset($course) ) {
                $idCourse = $course->getId();
                $name = $course->getName();
                $code = $course->getCode();
                $requisit = $course->getRequisit();
                $corequisite = $course->getCorequisite();
                $credit = $course->getCredit();
                $type = $course->getType();
                $area = $course->getArea();
                $schedule = $course->getSchedule();
                $groupnumber = $course->getGroupnumber();
                $classroom = $course->getClassroom();
                $section = $course->getSection();
                $room = $course->getRoom();
                $status = $course->getStatus();

                $user = $course->getUser();
                $userTeacher = 0;

                if( isset($user)){
                    $userTeacher = $user->getId();
                }




                return new Response(json_encode(array('error' => false, 'id' => $idCourse, 'name' => $name, 'code' => $code, 'status' => $status, 'requisit' => $requisit, 'corequisite' => $corequisite, 'credit' => $credit, 'area' => $area,
                    'type' => $type, 'schedule' => $schedule, 'groupnumber' => $groupnumber, 'classroom' => $classroom, 'section' => $section, 'room' => $room, 'user' => $userTeacher)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::getInfoCourseFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/edit", name="_expediente_sysadmin_course_edit_simple")
     */
    public function courseEditAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Course")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\CourseFormType(), $entity);
        $form = $this->createForm(CourseFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_course_edit_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Course/edit.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));
    }

    public function courseSaveAction(){
        /*$entity  = new Course();
        //$request = $this->getRequest();
        $request = $this->get('request_stack')->getCurrentRequest();
        //$form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\CourseFormType(), $entity);
        $form = $this->createForm(CourseFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_course_save'),
            'method' => 'PUT',
        ));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_course',
                array('id' => $entity->getId(), 'menuIndex' => 5)));
        } else {
            return $this->render('SuperAdmin/Course/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(), 'menuIndex' => 5
            ));
        }*/

        $entity  = new Course();
        $request = $this->get('request_stack')->getCurrentRequest();
        $form = $this->createForm(CourseFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_course_save'),
            'method' => 'PUT',
        ));

//        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entity = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute('_expediente_sysadmin_course', [
                'id' => $entity->getId()
            ]);
        }

    }

    public function courseDeleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Course")->find( $id );
        if ( isset($entity) ) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('_expediente_sysadmin_course'));
    }

    public function courseUpdateAction(){
        $em = $this->getDoctrine()->getEntityManager();
        //$request = $this->getRequest();
        $request = $this->get('request_stack')->getCurrentRequest();
        $entity = $em->getRepository("App:Course")->find($request->get('id'));
        if ( isset($entity) ) {
            //$form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\CourseFormType(), $entity);
            $form = $this->createForm(CourseFormType::class, $entity, array(
                'action' => $this->generateUrl('_expediente_sysadmin_course_show_simple'),
                'method' => 'PUT',
            ));
            //$form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('_expediente_sysadmin_course_show_simple') . "/" . $entity->getId());
            } else {
                return $this->render('SuperAdmin/Course/edit.html.twig', array(
                    'entity' => $entity, 'form'   => $form->createView(), 'updateRejected' => true, 'menuIndex' => 5
                ));
            }
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_course'));
        }
    }
    /* Final de los metodos para CRUD de Courses */


    /* Metodos para CRUD de Profesor */
    /**
     * @Route("/profesor", name="_expediente_sysadmin_profesor")
     */
    public function profesorListAction(EntityManagerInterface  $em, $rowsPerPage = 10)
    {
      //  $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT u.id, u.username, u.firstname, u.lastname, u.email, u.is_active, user_id FROM tek_users u Join user_role r ON u.id=r.user_id where r.role_id = 4";
        $stmt = $em->getConnection()->prepare($dql);
        $profesor = $stmt->executeQuery();

        //$param = $this->get('request')->query->get('rowsPerPage');
        $param = 10;
        if(isset($param) && $param != "")
            $rowsPerPage = $param;

        $request = $this->get('request_stack')->getCurrentRequest();

        //$dql2 = "SELECT count(users) FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR'";
       // $page = $this->getPaginationPage($dql2, $request->query->get('page'), $rowsPerPage);
        //$page = 1;
/*
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page
            $rowsPerPage
        );*/

        return $this->render('SuperAdmin/Profesor/list.html.twig', array(
            'pagination' => $profesor->fetchAllAssociative(), 'isTeacher' => true, 'rowsPerPage' => $rowsPerPage
        ));
    }
    /**
     * @Route("/profesor/create", name="_expediente_sysadmin_profesor_create")
     */
    public function profesorCreateAction()
    {
        $entity = new User();
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm('App\Form\UserFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_create'),
            'method' => 'POST'
        ));
        return $this->render('SuperAdmin/Profesor/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }
    /**
     * @Route("/profesor/show", name="_expediente_sysadmin_profesor_show_simple")
     */
    public function profesorShowAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:User")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Profesor/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }


    /**
     * @Route("/profesor/show/{id}", name="_expediente_sysadmin_profesor_show")
     */
    public function profesorShowIdAction($id, EntityManagerInterface $em)
    {
       // $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:User")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Profesor/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }


    /**
     * @Route("/profesor/save", name="_expediente_sysadmin_profesor_save")
     */
    public function profesorSaveAction(Request $request, EntityManagerInterface $em){
        $entity  = new User();
        //$request = $this->getRequest();

        //$form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_save'),
            'method' => 'PUT',
        ));
        //$form->bindRequest($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
           // $em = $this->getDoctrine()->getEntityManager();
            $role = $em->getRepository('App:Role')->
            findOneBy(array('role' => 'ROLE_PROFESOR'));
            $entity->getUserRoles()->add($role);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_profesor',
                array('id' => $entity->getId())));
        } else {
            return $this->render('SuperAdmin/Profesor/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        }
    }
    /**
     * @Route("/profesor/delete/{id}", name="_delete_profesor")
     */
    public function profesorDeleteAction(EntityManagerInterface $em, $id){
        //$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("TecnotekExpedienteBundle:User")->find( $id );
        if ( isset($entity) ) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('_expediente_sysadmin_profesor'));
    }

    /**
     * @Route("/profesor/update", name="_expediente_sysadmin_profesor_update")
     */
    public function profesorUpdateAction(EntityManagerInterface $em){
        //$em = $this->getDoctrine()->getEntityManager();
        //$request = $this->get('request')->request;
        $request = $this->get('request_stack')->getCurrentRequest();
        $entity = $em->getRepository("App:User")->find( $request->get('userId'));

        if ( isset($entity) ) {
            $entity->setFirstname($request->get('firstname'));
            $entity->setLastname($request->get('lastname'));
            $entity->setUsername($request->get('username'));
            $entity->setEmail($request->get('email'));
            $entity->setActive(($request->get('active') == "on"));
            $em->persist($entity);
            $em->flush();
            //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
            $form = $this->createForm(UserFormType::class, $entity, array(
                'action' => $this->generateUrl('_expediente_sysadmin_profesor_show_simple'),
                'method' => 'PUT',
            ));
            return $this->render('SuperAdmin/Profesor/show.html.twig', array('entity' => $entity,
                'form'   => $form->createView()));
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_profesor'));
        }
    }
    /* Final de los metodos para CRUD de Profesor*/

    /* Metodos para CRUD de Coordinador */
    /**
     * @Route("/coordinador", name="_expediente_sysadmin_coordinador")
     */
    public function coordinadorListAction($rowsPerPage = 10)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_COORDINADOR'";
        $query = $em->createQuery($dql);

        $request = $this->get('request_stack')->getCurrentRequest();
        //$param = $this->get('request')->query->get('rowsPerPage');
        $param = 10;

        if(isset($param) && $param != "")
            $rowsPerPage = $param;

        $dql2 = "SELECT count(users) FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_COORDINADOR'";
        $page = $this->getPaginationPage($dql2, $request->query->get('page'), $rowsPerPage);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            $rowsPerPage/*limit per page*/
        );

        return $this->render('SuperAdmin/Coordinador/list.html.twig', array(
            'pagination' => $pagination, 'isTeacher' => true, 'rowsPerPage' => $rowsPerPage
        ));
    }

    public function coordinadorCreateAction()
    {
        $entity = new User();
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm('App\Form\UserFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_create'),
            'method' => 'POST'
        ));
        return $this->render('SuperAdmin/Coordinador/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }

    public function coordinadorShowAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:User")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Coordinador/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }

    public function coordinadorSaveAction(){
        $entity  = new User();
        $request = $this->getRequest();
        //$form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_profesor_save'),
            'method' => 'PUT',
        ));
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $role = $em->getRepository('App:Role')->
            findOneBy(array('role' => 'ROLE_COORDINADOR'));
            $entity->getUserRoles()->add($role);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_coordinador',
                array('id' => $entity->getId())));
        } else {
            return $this->render('SuperAdmin/Coordinador/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        }
    }

    public function coordinadorDeleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:User")->find( $id );
        if ( isset($entity) ) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('_expediente_sysadmin_coordinador'));
    }

    public function coordinadorUpdateAction(){
        $em = $this->getDoctrine()->getEntityManager();
        //$request = $this->get('request')->request;
        $request = $this->get('request_stack')->getCurrentRequest();
        $entity = $em->getRepository("App:User")->find( $request->get('userId'));

        if ( isset($entity) ) {
            $entity->setFirstname($request->get('firstname'));
            $entity->setLastname($request->get('lastname'));
            $entity->setUsername($request->get('username'));
            $entity->setEmail($request->get('email'));
            $entity->setActive(($request->get('active') == "on"));
            $em->persist($entity);
            $em->flush();
            //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
            $form = $this->createForm(UserFormType::class, $entity, array(
                'action' => $this->generateUrl('_expediente_sysadmin_profesor_show_simple'),
                'method' => 'PUT',
            ));
            return $this->render('SuperAdmin/Coordinador/show.html.twig', array('entity' => $entity,
                'form'   => $form->createView()));
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_coordinador'));
        }
    }
    /* Final de los metodos para CRUD de Coordinador*/

    /* Metodos para CRUD de Administrador */
    /**
     * @Route("/administrador", name="_expediente_sysadmin_administrador")
     */
    public function administradorListAction($rowsPerPage = 10)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_ADMIN'";
        $query = $em->createQuery($dql);

        $request = $this->get('request_stack')->getCurrentRequest();
        //$param = $this->get('request')->query->get('rowsPerPage');
        $param = 10;

        if(isset($param) && $param != "")
            $rowsPerPage = $param;

        $dql2 = "SELECT count(users) FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_ADMIN'";
        $page = $this->getPaginationPage($dql2, $request->query->get('page'), $rowsPerPage);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            $rowsPerPage/*limit per page*/
        );

        return $this->render('SuperAdmin/Administrador/list.html.twig', array(
            'pagination' => $pagination, 'isTeacher' => true, 'rowsPerPage' => $rowsPerPage
        ));
    }

    public function administradorCreateAction()
    {
        $entity = new User();
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm('App\Form\UserFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_admin_create'),
            'method' => 'POST'
        ));
        return $this->render('SuperAdmin/Administrador/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));
    }

    public function administradorShowAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:User")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form = $this->createForm(UserFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_admin_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Administrador/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView()));

    }

    public function administradorSaveAction(){
        $entity  = new User();
        $request = $this->getRequest();
        $form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $role = $em->getRepository('TecnotekExpedienteBundle:Role')->
            findOneBy(array('role' => 'ROLE_ADMIN'));
            $entity->getUserRoles()->add($role);
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_administrador',
                array('id' => $entity->getId())));
        } else {
            return $this->render('TecnotekExpedienteBundle:SuperAdmin:Administrador/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView()
            ));
        }
    }

    public function administradorDeleteAction($id){
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("TecnotekExpedienteBundle:User")->find( $id );
        if ( isset($entity) ) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('_expediente_sysadmin_administrador'));
    }

    public function administradorUpdateAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request')->request;
        $entity = $em->getRepository("TecnotekExpedienteBundle:User")->find( $request->get('userId'));

        if ( isset($entity) ) {
            $entity->setFirstname($request->get('firstname'));
            $entity->setLastname($request->get('lastname'));
            $entity->setUsername($request->get('username'));
            $entity->setEmail($request->get('email'));
            $entity->setActive(($request->get('active') == "on"));
            $em->persist($entity);
            $em->flush();
            $form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\UserFormType(), $entity);
            return $this->render('TecnotekExpedienteBundle:SuperAdmin:Administrador/show.html.twig', array('entity' => $entity,
                'form'   => $form->createView()));
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_administrador'));
        }
    }

    /* Final de los metodos para CRUD de Administrador*/

    /* Metodos para CRUD de periods */
    /**
     * @Route("/period", name="_expediente_sysadmin_period")
     */
    public function periodListAction(EntityManagerInterface $em, $rowsPerPage = 10)
    {
       // $em = $this->getDoctrine()->getEntityManager();
        $periods = $em->getRepository(Period::class)->findAll();
        $dql = "SELECT period FROM App:Period period";
//        $query = $em->createQuery($dql);
        $stmt = $em->getConnection()->prepare($dql);

        $request = $this->get('request_stack')->getCurrentRequest();
        //$param = $this->get('request')->query->get('rowsPerPage');
     //   if(isset($param) && $param != "")
     //       $rowsPerPage = $param;

    //    $dql2 = "SELECT count(periods) FROM App:Period periods";
    //    $page = $this->getPaginationPage($dql2, $request->query->get('page'), $rowsPerPage);

        $pagination = $periods;

     /*   $pagination = $paginator->paginate(
            $query,
            $page
            $rowsPerPage */
//        );

        return $this->render('SuperAdmin/Period/list.html.twig', array(
            'pagination' => $pagination, 'rowsPerPage' => $rowsPerPage, 'menuIndex' => 5
        ));
    }
    /**
     * @Route("/period/create", name="_expediente_sysadmin_period_create")
     */
    public function periodCreateAction()
    {
        $entity = new Period();
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\PeriodFormType(), $entity);
        $form = $this->createForm('App\Form\PeriodFormType',null,array(
            // To set the action use $this->generateUrl('route_identifier')
            'action' => $this->generateUrl('_expediente_sysadmin_period_create'),
            'method' => 'POST'
        ));
        return $this->render('SuperAdmin/Period/new.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));
    }

    /**
     * @Route("/period/createPeriod/", name="_expediente_sysadmin_create_period")
     */
    public function createPeriodAction(){ //2019-16-01
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            /*$user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();*/

            $name = $request->get('name');
            $year = $request->get('year');
            $order = $request->get('order');


            $translator = $this->get("translator");

            if( isset($name)) {
                $em = $this->getDoctrine()->getEntityManager();

                $period = new Period();
                $period->setYear($year);
                $period->setName($name);
                $period->setOrderInYear($order);
                $period->isActual(0);
                $period->isEditable(0);


                $em->persist($period);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::createPeriodAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/period/show", name="_expediente_sysadmin_period_show_simple")
     */
    public function periodShowAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Period")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\PeriodFormType(), $entity);
        $form = $this->createForm(PeriodFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_period_show_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Period/show.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));
    }
    /**
     * @Route("/period/edit", name="_expediente_sysadmin_period_edit_simple")
     */
    public function periodEditAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Period")->find($id);
        //$form   = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\PeriodFormType(), $entity);
        $form = $this->createForm(PeriodFormType::class, $entity, array(
            'action' => $this->generateUrl('_expediente_sysadmin_period_edit_simple'),
            'method' => 'PUT',
        ));
        return $this->render('SuperAdmin/Period/edit.html.twig', array('entity' => $entity,
            'form'   => $form->createView(), 'menuIndex' => 5));
    }

    public function periodSaveAction(){
        $entity  = new Period();
        //$request = $this->getRequest();
        $request = $this->get('request_stack')->getCurrentRequest();
        $form    = $this->createForm(new \Tecnotek\ExpedienteBundle\Form\PeriodFormType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('_expediente_sysadmin_period',
                array('id' => $entity->getId(), 'menuIndex' => 5)));
        } else {
            return $this->render('TecnotekExpedienteBundle:SuperAdmin:Period/new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(), 'menuIndex' => 5
            ));
        }
    }

    /**
     * @Route("/period/delete/{id}", name="_delete_period")
     */
    public function periodDeleteAction($id, EntityManagerInterface $em){
        //$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Period")->find( $id );
        if ( isset($entity) ) {
            $em->remove($entity);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('_expediente_sysadmin_period'));
    }

    public function periodUpdateAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request_stack')->getCurrentRequest();
        //$request = $this->getRequest();
        $entity = $em->getRepository("App:Period")->find($request->get('id'));
        if ( isset($entity) ) {
            $form    = $this->createForm(new \App\Form\PeriodFormType(), $entity);
            $form->bindRequest($request);

            if ($form->isValid()) {
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('_expediente_sysadmin_period_show_simple') . "/" . $entity->getId());
            } else {
                return $this->render('App:SuperAdmin:Period/edit.html.twig', array(
                    'entity' => $entity, 'form'   => $form->createView(), 'updateRejected' => true, 'menuIndex' => 5
                ));
            }
        } else {
            return $this->redirect($this->generateUrl('_expediente_sysadmin_period'));
        }
    }
    /* Final de los metodos para CRUD de periods */

    /**
     * @Route("/period/admin/{id}", name="_expediente_sysadmin_period_admin")
     */
    public function adminPeriodAction($id, EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App:Period")->find($id);
        $grades = $em->getRepository("App:Grade")->findAll();
        $institutions = $em->getRepository("App:Institution")->findAll();

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Period/admin.html.twig', array('entity' => $entity,
            'grades' => $grades, 'teachers' => $teachers, 'institutions' => $institutions,
            'menuIndex' => 5));
    }

    /**
     * @Route("/period/load", name="_expediente_sysadmin_load_period_info")
     * @Method({"GET", "POST"})
     */
    public function loadPeriodInfoAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger){
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
       // {
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $gradeId = $request->get('gradeId');

                //$translator = $this->get("translator");

                if( isset($gradeId) && isset($periodId)) {
                    //$em = $this->getDoctrine()->getEntityManager();
                    //Get Groups
                    $sql = "SELECT g.id, g.name, g.user_id as 'teacherId', CONCAT(u.firstname,' ',u.lastname) as 'teacherName', institution.name as 'institutionName', institution.id as 'institutionId'"
                        . " FROM tek_groups g"
                        . " JOIN tek_users u ON u.id = g.user_id"
                        . " LEFT JOIN tek_institutions institution ON institution.id = g.institution_id"
                        . " WHERE g.period_id = " . $periodId . " AND g.grade_id = " . $gradeId
                        . " ORDER BY g.name";
                    //$stmt = $em->getConnection()->prepare($sql);
                    //$stmt->execute();
                    //$groups = $stmt->fetchAll();


                    $em->clear();
                    $em->getRepository(Period::class);
                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->executeQuery();
                    $groups = $result->fetchAllAssociative();

                    //Get Courses
                    $sql = "SELECT cc.id, c.name, cc.user_id as 'teacherId', (CONCAT(u.firstname, ' ', u.lastname)) as 'teacherName', c.id as 'courseId' "
                        . " FROM tek_courses c, tek_course_class cc, tek_users u"
                        . " WHERE cc.period_id = " . $periodId . " AND cc.grade_id = " . $gradeId . " AND cc.course_id = c.id AND u.id = cc.user_id"
                        . " ORDER BY c.name";
                    /*$stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $courses = $stmt->fetchAll();*/

                    $em->clear();
                    $em->getRepository(Period::class);
                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->executeQuery();
                    $courses = $result->fetchAllAssociative();

                    return new Response(json_encode(array('error' => false, 'groups' => $groups, 'courses' => $courses)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::changeUserPasswordAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
      /*  }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/teachers/load", name="_expediente_sysadmin_load_courses_groups_by_teacher")
     * @Method({"GET", "POST"})
     */
    public function loadCoursesByTeacherAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                //$request = $this->get('request')->request;
                $periodId = $request->get('periodId');
                $teacherId = $request->get('teacherId');

                //$translator = $this->get("translator");

                if( isset($periodId) && isset($teacherId)) {
                    //$em = $this->getDoctrine()->getEntityManager();

                    /* $dql = "SELECT a FROM TecnotekExpedienteBundle:AssignedTeacher a WHERE a.period = $periodId AND a.user = $teacherId";
                     $query = $em->createQuery($dql);
                     $entries = $query->getResult();
 */

                    $sql='SELECT t.id as id, t.group_id, c.id as course, c.name as name, cc.id as courseclass, concat(g.grade_id,"-",g.name)  as groupname
                                    FROM `tek_assigned_teachers` t, tek_courses c, tek_course_class cc, tek_groups g
                                    where cc.course_id = c.id and t.course_class_id = cc.id and g.id = t.group_id and cc.period_id = "'.$periodId.'" and t.user_id = "'.$teacherId.'"';
                    /*$em = $this->getConnection()
                        ->prepare('SELECT t.id as id, t.group_id, c.id as course, c.name as name, cc.id as courseclass, concat(g.grade_id,"-",g.name)  as groupname
                                    FROM `tek_assigned_teachers` t, tek_courses c, tek_course_class cc, tek_groups g
                                    where cc.course_id = c.id and t.course_class_id = cc.id and g.id = t.group_id and cc.period_id = "'.$periodId.'" and t.user_id = "'.$teacherId.'"');*/
                    $em->clear();
                    $em->getRepository(Period::class);
                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->executeQuery();
                    $entity = $result->fetchAllAssociative();

                    $colors = array(
                        "one" => "#38255c",
                        "two" => "#04D0E6"
                    );
                    $html = "";
                    $groupOptions = "";

                    foreach( $entity as $entry ){
                        $html .= '<div id="courseTeacherRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                        $html .= '    <div id="entryNameField_' . $entry['courseclass'] . '" name="entryNameField_' . $entry['courseclass'] . '" class="option_width" style="float: left; width: 150px;">' . $entry['name'] . '</div>';
                        $html .= '    <div id="entryCodeField_' . $entry['group_id'] . '" name="entryCodeField_' . $entry['group_id'] . '" class="option_width" style="float: left; width: 100px;">' . $entry['groupname'] . '</div>';
                        $html .= '    <div class="right icon button imageButton deleteButton deleteTeacherAssigned" title="Eliminar"  rel="' . $entry['id'] . '"><i class="fas fa-trash"></i></div>';
                        $html .= '    <div class="clear"></div>';
                        $html .= '</div>';

                    }

                    $dql = "SELECT g FROM App:Group g WHERE g.period = $periodId";
                    $query = $em->createQuery($dql);
                    $results = $query->getResult();
                    $courseClassId = 0;
                    $groupOptions .= '<option value="0"></option>';
                    foreach( $results as $result ){
                        $groupOptions .= '<option value="' . $result->getId() . '-' . $result->getGrade()->getId() . '">'. $result->getGrade() . '-'. $result->getName() . '</option>';
                    }

                    return new Response(json_encode(array('error' => false, 'groupOptions' => $groupOptions, 'entriesHtml' => $html)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::loadCoursesByTeacherAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/coursesassigned/load/", name="_expediente_sysadmin_load_courses_assigned_by_teacher")
     * @Method({"GET", "POST"})
     */
    public function loadCoursesAssignedByTeacherAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');
            //$periodId=1;
            //$teacherId =218;

            //$translator = $this->get("translator");

            if( isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                /* $dql = "SELECT a FROM TecnotekExpedienteBundle:AssignedTeacher a WHERE a.period = $periodId AND a.user = $teacherId";
                 $query = $em->createQuery($dql);
                 $entries = $query->getResult();
*/

                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, c.code as code, c.schedule as schedule
                                    FROM `tek_assigned_courses` t, tek_courses c 
                                    where t.course_id = c.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql='SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, c.code as code, c.schedule as schedule
                                    FROM `tek_assigned_courses` t, tek_courses c 
                                    where t.course_id = c.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();


                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $courseOptions = '<option value="0"></option>';



                foreach( $entity as $entry ){
                    $html .= '<div id="courseAssginedRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryNameField_' . $entry['course'] . '" name="entryNameField_' . $entry['course'] . '" class="option_width" style="float: left; width: 800px;">' .$entry['groupnumber'].'-' .$entry['code'].' '. $entry['name'] . '</div>';
                    $html .= '    <div style="float: right; width: 50px;" class="right icon button imageButton deleteButton deleteCourseAssigned" title="Eliminar"  rel="' . $entry['id'] . '"><i class="fas fa-trash"></i></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';


                   }

                $dql = "SELECT c FROM App:Course c";
                $query = $em->createQuery($dql);
                $results = $query->getResult();

                foreach( $results as $result ){
                    $courseOptions .= '<option value="' . $result->getId() . '">'. $result->getGroupnumber() . '-'. $result->getCode() . ' '. $result->getName() .'('. $result->getSchedule() . ')d'. '</option>';
                }


                return new Response(json_encode(array('error' => false, 'courseOptions' => $courseOptions, 'entriesAssignedHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::loadCoursesByTeacherAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/loadCoursesByGrade", name="_expediente_sysadmin_load_courses_by_grade")
     * @Method({"GET", "POST"})
     */
    public function loadAvailableCoursesAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $gradeId = $request->get('gradeId');

                //$translator = $this->get("translator");

                if( isset($gradeId) && isset($periodId)) {
                    //$em = $this->getDoctrine()->getEntityManager();
                    //Get Courses
                    $sql = "SELECT c.id, c.name, c.groupnumber, c.code"
                        . " FROM tek_courses c"
                        . " WHERE c.id not in (select cc.course_id from tek_course_class cc where cc.period_id = " . $periodId . " AND cc.grade_id = " . $gradeId . ")"
                        . " ORDER BY c.name";
                    //$stmt = $em->getConnection()->prepare($sql);
                    //$stmt->execute();
                    //$courses = $stmt->fetchAll();
                    $em->clear();
                    $em->getRepository(Period::class);
                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->executeQuery();
                    $courses = $result->fetchAllAssociative();

                    return new Response(json_encode(array('error' => false, 'courses' => $courses)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::loadAvailableCoursesAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/associateCourse", name="_expediente_sysadmin_associate_course")
     * @Method({"GET", "POST"})
     */
    public function associateCourseAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $gradeId = $request->get('gradeId');
                $courseId = $request->get('courseId');
                $teacherId = $request->get('teacherId');

                //$translator = $this->get("translator");

                if( isset($gradeId) && isset($periodId) && isset($courseId) && isset($teacherId)) {

                    $courseClass = new CourseClass();
                    //$em = $this->getDoctrine()->getEntityManager();
                    $teacher = $em->getRepository("App:User")->find($teacherId);
                    //$courseClass->setPeriod($em->getRepository("App:Period")->find($periodId));

                    $entityPeriod = $em->getRepository("App:Period")->find($periodId);
                    if( isset($entityPeriod)){
                        $courseClass->setPeriod($entityPeriod);
                    }


                    $courseClass->setGrade($em->getRepository("App:Grade")->find($gradeId));
                    $courseClass->setTeacher($teacher);
                    $courseClass->setCourse($em->getRepository("App:Course")->find($courseId));
                    $em->persist($courseClass);

/*                    //Get groups of period-grade to assigned teacher
                    $dql = "SELECT g FROM TecnotekExpedienteBundle:Group g WHERE g.period = " . $periodId . " AND g.grade = " . $gradeId;
                    $query = $em->createQuery($dql);
                    $groups = $query->getResult();
                    foreach( $groups as $group )
                    {
                        $assignedTeacher =  new \Tecnotek\ExpedienteBundle\Entity\AssignedTeacher();
                        $assignedTeacher->setCourseClass($courseClass);
                        $assignedTeacher->setGroup($group);
                        $assignedTeacher->setTeacher($teacher);
                        $em->persist($assignedTeacher);
                    }
*/
                    $em->flush();

                    return new Response(json_encode(array('error' => false, 'courseClass' => $courseClass->getId())));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::loadAvailableCoursesAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/couseAssociationRemove", name="_expediente_sysadmin_course_association_remove")
     */
    public function courseAssociationRemoveAction(){

        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $associationId = $request->get('associationId');
                $translator = $this->get("translator");

                if( isset($associationId) ) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $entity = $em->getRepository("App:CourseClass")->find( $associationId );
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
                $logger->err('SuperAdmin::removeGroupAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/teachersCourses/load/", name="_expediente_sysadmin_load_course_class_by_group")
     */
    public function loadAvailableCourseClassAction(){   //2016 -4
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $groupId = $request->get('groupId');

                $keywords = preg_split("/[\s-]+/", $groupId);
                $groupId = $keywords[0];
                $gradeId = $keywords[1];

                $translator = $this->get("translator");

                if( isset($gradeId) && isset($periodId)) {
                    $em = $this->getDoctrine()->getEntityManager();
                    //Get Courses
                    $sql = "SELECT cc.id as courseclass, c.id as course, c.name as name, c.code as code, c.groupnumber as groupnumber"
                        . " FROM tek_courses c, tek_course_class cc"
                        . " WHERE cc.course_id = c.id and cc.period_id = " . $periodId . " AND cc.grade_id = " . $gradeId . " "
                        . " ORDER BY c.name";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    $courses = $stmt->fetchAll();
                    return new Response(json_encode(array('error' => false, 'courses' => $courses)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::loadAvailableCourseClassAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/period/teachers/create/", name="_expediente_sysadmin_create_teacher_assigned")
     * @Method({"GET", "POST"})
     */
    public function createTeacherAssignedAction(Request $request, EntityManagerInterface $em){ //2016 - 4 temp
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
       // {
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $teacherId = $request->get('teacherId');
                $courseClassId = $request->get('courseClassId');
                $groupId = $request->get('groupId');

                $keywords = preg_split("/[\s-]+/", $groupId);
                $groupId = $keywords[0];
                $gradeId = $keywords[1];

                //$translator = $this->get("translator");

                if( isset($courseClassId) && isset($groupId) && isset($teacherId)) {
                    //$em = $this->getDoctrine()->getEntityManager();

                    $assignedTeacher = new AssignedTeacher();
                    $assignedTeacher->setCourseClass($em->getRepository("App:CourseClass")->find($courseClassId));
                    $assignedTeacher->setGroup($em->getRepository("App:Group")->find($groupId));
                    $assignedTeacher->setTeacher($em->getRepository("App:User")->find($teacherId));

                    $em->persist($assignedTeacher);
                    $em->flush();

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::createTeacherAssignedAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
       /* }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/courses/createassigned/", name="_expediente_sysadmin_create_course_assigned")
     * @Method({"GET", "POST"})
     */
    public function createCourseAssignedAction(Request $request, EntityManagerInterface $em){ //2016 - 4 temp
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $periodId = $request->get('periodId');
                $teacherId = $request->get('teacherId');
                $courseId = $request->get('courseId');
                $weight = $request->get('weight');

                //$translator = $this->get("translator");

                if( isset($courseId) && isset($periodId) && isset($teacherId)) {
                    //$em = $this->getDoctrine()->getEntityManager();

                    $assignedCourse = new AssignedCourse();
                    $assignedCourse->setCourse($em->getRepository("App:Course")->find($courseId));
                    $assignedCourse->setPeriod($em->getRepository("App:Period")->find($periodId));
                    $assignedCourse->setUser($em->getRepository("App:User")->find($teacherId));
                    $assignedCourse->setWeight($weight);


                    $em->persist($assignedCourse);
                    $em->flush();

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::createTeacherAssignedAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
       /* }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/teachers/remove", name="_expediente_sysadmin_teacher_assigned_remove")
     * @Method({"GET", "POST"})
     */
    public function removeTeacherAssignedAction(Request $request, EntityManagerInterface $em){    /// 2016 - 4

        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $teacherAssignedId = $request->get('teacherAssignedId');
                //$translator = $this->get("translator");

                if( isset($teacherAssignedId) ) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $entity = $em->getRepository("App:AssignedTeacher")->find( $teacherAssignedId );
                    if ( isset($entity) ) {
                        $em->remove($entity);
                        $em->flush();
                    }
                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::removeTeacherAssignedAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/courses/removeassigned/", name="_expediente_sysadmin_course_assigned_remove")
     * @Method({"GET", "POST"})
     */
    public function removeCourseAssignedAction(Request $request, EntityManagerInterface $em){    /// 2016 - 4

        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                //$request = $this->get('request')->request;
                $request = $this->get('request_stack')->getCurrentRequest();
                $courseAssignedId = $request->get('courseAssignedId');
                //$translator = $this->get("translator");

                if( isset($courseAssignedId) ) {
                    //$em = $this->getDoctrine()->getEntityManager();
                    $entity = $em->getRepository("App:AssignedCourse")->find( $courseAssignedId );
                    if ( isset($entity) ) {
                        $em->remove($entity);
                        $em->flush();
                    }
                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                //$logger->err('SuperAdmin::removeCourseAssignedAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/commission/search", name="_expediente_sysadmin_commission_search")
     */
    public function searchCommissionsAction($rowsPerPage = 30, EntityManagerInterface $em, LoggerInterface $logger) {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
          //  $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";

            //Codigo de Stuart
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];

            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $teachers = $query->getResult();
            //fin codigo de stuart
/*
            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(c.name like '%" . $word . "%' OR c.code like '%" . $word . "%')";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_commissions c;";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $filtered = 0;
            $total = 0;
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }*/

            $sql = "SELECT c.id, c.name, c.code, c.type"
                . " FROM tek_commissions c" ;


            $stmt2 = $em->getConnection()->prepare($sql);
            $comm1=$stmt2->executeQuery();
            $commissions = $comm1->fetchAllAssociative();

            return $this->render('SuperAdmin/Commission/list.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers , 'commissions' => $commissions
            ));
/*
            return new Response(json_encode(array('error' => false, 'menuIndex' => 3, 'text' => $text, 'user' => $user,
                'role' => $role, 'teachers' => $teachers, 'commissions' => $commissions)));*/
        } catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::searchCommissionsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $sql)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    /**
     * @Route("/commission/createCommission/", name="_expediente_sysadmin_create_commission")
     * @Method({"GET", "POST"})
     */
    public function createCommissionAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $status = $request->get('status');

            //$translator = $this->get("translator");

            if( isset($userId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $commission = new Commission();
                $commission->setName($name);
                $commission->setCode($code);
                $commission->setType($type);
                $commission->setStatus($status);

                $em->persist($commission);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
           // $logger->err('Commission::createCommissionAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/commission/updateCommission/", name="_expediente_sysadmin_update_commission")
     * @Method({"GET", "POST"})
     */
    public function updateCommissionAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $commissionId = $request->get('commissionId');
            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $status = $request->get('status');

            //$translator = $this->get("translator");

            if( $commissionId != '') {
               // $em = $this->getDoctrine()->getEntityManager();

                $commission = new Commission();
                $commission = $em->getRepository("App:Commission")->find($commissionId);

                $commission->setName($name);
                $commission->setCode($code);
                $commission->setType($type);
                $commission->setStatus($status);

                $em->persist($commission);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Commission::createCommissionAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/commission/getInfoCommissionFull", name="_expediente_sysadmin_get_info_commission_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoCommissionFullAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $commissionId = $request->get('commissionId');

            //$em = $this->getDoctrine()->getEntityManager();
            $commission = $em->getRepository("App:Commission")->find($commissionId);





            if ( isset($commission) ) {
                $html  = '<div class="fieldRow"><label>Nombre:</label><span>' . $commission->getName() . '</span></div><div style="float: right;"><p></div>';
                $html .= '<div class="fieldRow"><label>Codigo:</label><span></span>' . $commission->getCode() . '</div>';
                switch ($commission->getType()){
                    case 1:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Otro</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                        break;

                }


                switch ($commission->getStatus()){
                    case 1:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Inactivo</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;

                }

                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Commission::getInfoCommissionFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/commission/getInfoCommissionDetail", name="_expediente_sysadmin_get_info_commission_detail")
     * @Method({"GET", "POST"})
     */
    public function getInfoCommissionDetailAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $commissionId = $request->get('commissionId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $commission = $em->getRepository("App:Commission")->find($commissionId);





            if ( isset($commission) ) {
                $idCourse = $commission->getId();
                $name = $commission->getName();
                $code = $commission->getCode();
                $type = $commission->getType();
                $status = $commission->getStatus();

                return new Response(json_encode(array('error' => false, 'id' => $idCourse, 'name' => $name, 'code' => $code, 'status' => $status, 'type' => $type)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::getInfoCommissionFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/commission", name="_expediente_sysadmin_commission")
     */
    public function commissionListAction($rowsPerPage = 30, EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Commission/list.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }

    /**
     * @Route("/commissions/removeassigned/", name="_expediente_sysadmin_commission_assigned_remove")
     * @Method({"GET", "POST"})
     */
    public function removeCommissionAssignedAction(Request $request, EntityManagerInterface $em, LoggerInterface $logger){    /// 2016 - 4

        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $commissionAssignedId = $request->get('commissionAssignedId');
            //$translator = $this->get("translator");

            if( isset($commissionAssignedId) ) {
                //$em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:AssignedCommission")->find( $commissionAssignedId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('SuperAdmin::removeCommissionAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/commissions/commissionassigned/", name="_expediente_sysadmin_create_commission_assigned")
     * @Method({"GET", "POST"})
     */
    public function createCommissionAssignedAction(Request $request, EntityManagerInterface $em){ //2016 - 4 temp
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');
            $commissionId = $request->get('commissionId');
            $type = $request->get('type');
            $weight = $request->get('weight');

            //$translator = $this->get("translator");

            if( isset($commissionId) && isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $assignedCommission = new AssignedCommission();
                $assignedCommission->setCommission($em->getRepository("App:Commission")->find($commissionId));
                $assignedCommission->setPeriod($em->getRepository("App:Period")->find($periodId));
                $assignedCommission->setUser($em->getRepository("App:User")->find($teacherId));
                $assignedCommission->setType($type);
                $assignedCommission->setWeight($weight);


                $em->persist($assignedCommission);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::createTeacherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /* }// endif this is an ajax request
         else
         {
             return new Response("<b>Not an ajax call!!!" . "</b>");
         }*/
    }


    /**
     * @Route("/period/commissionsassigned/load/", name="_expediente_sysadmin_load_commissions_assigned_by_teacher")
     * @Method({"GET", "POST"})
     */
    public function loadCommissionsAssignedByTeacherAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');

            //$translator = $this->get("translator");

            if( isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                /* $dql = "SELECT a FROM TecnotekExpedienteBundle:AssignedTeacher a WHERE a.period = $periodId AND a.user = $teacherId";
                 $query = $em->createQuery($dql);
                 $entries = $query->getResult();
*/
/*
                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as commission, c.name as name, t.type as type
                                    FROM `tek_assigned_commissions` t, tek_commissions c 
                                    where t.commission_id = c.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql='SELECT t.id as id, c.id as commission, c.name as name, t.type as type
                                    FROM `tek_assigned_commissions` t, tek_commissions c 
                                    where t.commission_id = c.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $commissionOptions = '<option value="0"></option>';



                foreach( $entity as $entry ){
                    if($entry['type']==1){
                        $typeC = "Coordinador";
                    }else{
                        $typeC = "Miembro";
                    }
                    $html .= '<div id="commissionAssginedRows_' . $entry['id'] . '">';
                    $html .= '    <div id="entryNameField_' . $entry['commission'] . '" name="entryNameField_' . $entry['commission'] . '" style="float: left; width: 450px;">' . $entry['name'] . '</div>';
                    $html .= '    <div  style="float: left; width: 50px;">' . $typeC . '</div>';
                    $html .= '    <div  style="float: right; width: 50px;" class="right icon button imageButton deleteButton deleteCommissionAssigned" title="Eliminar"  rel="' . $entry['id'] . '"><i class="fas fa-trash"></i></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';


                }

                $dql = "SELECT c FROM App:Commission c";
                $query = $em->createQuery($dql);
                $results = $query->getResult();

                foreach( $results as $result ){
                    $commissionOptions .= '<option value="' . $result->getId() . '">'. $result->getName() . '</option>';
                }


                return new Response(json_encode(array('error' => false, 'commissionOptions' => $commissionOptions, 'entriesAssignedHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::loadCommissionsByTeacherAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    /**
     * @Route("/projects/removeassigned/", name="_expediente_sysadmin_project_assigned_remove")
     * @Method({"GET", "POST"})
     */
    public function removeProjectAssignedAction(Request $request, EntityManagerInterface $em){    /// 2016 - 4

        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $projectAssignedId = $request->get('projectAssignedId');
            //$translator = $this->get("translator");

            if( isset($projectAssignedId) ) {
                //$em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:AssignedProject")->find( $projectAssignedId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::removeProjectAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/projects/projectassigned/", name="_expediente_sysadmin_create_project_assigned")
     * @Method({"GET", "POST"})
     */
    public function createProjectAssignedAction(Request $request, EntityManagerInterface $em){ //2016 - 4 temp
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');
            $projectId = $request->get('projectId');
            $weight = $request->get('weight');

            //$translator = $this->get("translator");

            if( isset($projectId) && isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $assignedProject = new AssignedProject();
                $assignedProject->setProject($em->getRepository("App:Project")->find($projectId));
                $assignedProject->setPeriod($em->getRepository("App:Period")->find($periodId));
                $assignedProject->setUser($em->getRepository("App:User")->find($teacherId));
                $assignedProject->setWeight($weight);


                $em->persist($assignedProject);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::createProjectAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /* }// endif this is an ajax request
         else
         {
             return new Response("<b>Not an ajax call!!!" . "</b>");
         }*/
    }


    /**
     * @Route("/period/projectsassigned/load/", name="_expediente_sysadmin_load_projects_assigned_by_teacher")
     * @Method({"GET", "POST"})
     */
    public function loadProjectsAssignedByTeacherAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');

            //$translator = $this->get("translator");

            if( isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                /* $dql = "SELECT a FROM TecnotekExpedienteBundle:AssignedTeacher a WHERE a.period = $periodId AND a.user = $teacherId";
                 $query = $em->createQuery($dql);
                 $entries = $query->getResult();
*/

                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, p.id as project, p.name as name
                                    FROM `tek_assigned_projects` t, tek_projects p 
                                    where t.project_id = p.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql = 'SELECT t.id as id, p.id as project, p.name as name
                                    FROM `tek_assigned_projects` t, tek_projects p 
                                    where t.project_id = p.id and t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $projectOptions = '<option value="0"></option>';



                foreach( $entity as $entry ){
                    $html .= '<div id="projectAssginedRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryNameField_' . $entry['project'] . '" name="entryNameField_' . $entry['project'] . '" class="option_width" style="float: left; width: 300px;">' . $entry['name'] . '</div>';
                    $html .= '    <div style="float: right; width: 50px;" class="right icon button imageButton deleteButton deleteProjectAssigned" title="Eliminar"  rel="' . $entry['id'] . '"><i class="fas fa-trash"></i></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';


                }

                $dql = "SELECT c FROM App:Project c";
                $query = $em->createQuery($dql);
                $results = $query->getResult();

                foreach( $results as $result ){
                    $projectOptions .= '<option value="' . $result->getId() . '">'. $result->getName() . '</option>';
                }


                return new Response(json_encode(array('error' => false, 'projectOptions' => $projectOptions, 'entriesAssignedHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::loadProjectsByTeacherAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/others/removeassigned/", name="_expediente_sysadmin_other_assigned_remove")
     * @Method({"GET", "POST"})
     */
    public function removeOtherAssignedAction(Request $request, EntityManagerInterface $em){    /// 2016 - 4

        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $otherAssignedId = $request->get('otherAssignedId');
            //$translator = $this->get("translator");

            if( isset($otherAssignedId) ) {
                //$em = $this->getDoctrine()->getEntityManager();
                $entity = $em->getRepository("App:AssignedOther")->find( $otherAssignedId );
                if ( isset($entity) ) {
                    $em->remove($entity);
                    $em->flush();
                }
                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::removeOtherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/others/otherassigned/", name="_expediente_sysadmin_create_other_assigned")
     * @Method({"GET", "POST"})
     */
    public function createOtherAssignedAction(Request $request, EntityManagerInterface $em){ //2016 - 4 temp
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');
            $name = $request->get('name');
            $weight = $request->get('weight');
            $type = $request->get('type');

            //$translator = $this->get("translator");

            if( isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $assignedOther = new AssignedOther();
                $assignedOther->setWeight($weight);
                $assignedOther->setType($type);
                $assignedOther->setName($name);
                $assignedOther->setPeriod($em->getRepository("App:Period")->find($periodId));
                $assignedOther->setUser($em->getRepository("App:User")->find($teacherId));


                $em->persist($assignedOther);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::createOtherAssignedAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /* }// endif this is an ajax request
         else
         {
             return new Response("<b>Not an ajax call!!!" . "</b>");
         }*/
    }


    /**
     * @Route("/period/othersassigned/load/", name="_expediente_sysadmin_load_others_assigned_by_teacher")
     * @Method({"GET", "POST"})
     */
    public function loadOthersAssignedByTeacherAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $periodId = $request->get('periodId');
            $teacherId = $request->get('teacherId');

            //$translator = $this->get("translator");

            if( isset($periodId) && isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                /* $dql = "SELECT a FROM TecnotekExpedienteBundle:AssignedTeacher a WHERE a.period = $periodId AND a.user = $teacherId";
                 $query = $em->createQuery($dql);
                 $entries = $query->getResult();
*/

                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, t.name as name
                                    FROM `tek_assigned_other` t
                                    where t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql= 'SELECT t.id as id, t.name as name
                                    FROM `tek_assigned_other` t
                                    where t.user_id = "'.$teacherId.'" and t.period_id = "'.$periodId.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $otherOptions = '<option value="0"></option>';



                foreach( $entity as $entry ){
                    $html .= '<div id="otherAssginedRows_' . $entry['id'] . '" class="row userRow tableRowOdd">';
                    $html .= '    <div id="entryNameField_' . $entry['id'] . '" name="entryNameField_' . $entry['id'] . '" class="option_width" style="float: left; width: 800px;">' . $entry['name'] . '</div>';
                    $html .= '    <div style="float: right; width: 50px;" class="right icon button imageButton deleteButton deleteOtherAssigned" title="Eliminar"  rel="' . $entry['id'] . '"><i class="fas fa-trash"></i></div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';


                }



                return new Response(json_encode(array('error' => false, 'entriesAssignedHtml' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::loadOthersByTeacherAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge", name="_expediente_sysadmin_charge")
     */
    public function chargeListAction(EntityManagerInterface $em, Request $request, $rowsPerPage = 30)
    {
       // $em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' or r.role = 'ROLE_SUPERADMIN' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Charges/list.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }

    /**
     * @Route("/charge/getInfoTeacherChargeStatus", name="_expediente_sysadmin_get_info_charge_teacher_status")
     * @Method({"GET", "POST"})
     */
    public function getInfoChargeTeacherStatusAction(Request $request, EntityManagerInterface $em){ //2016 - 4
        //$logger = $this->get('logger');

        $html = "";
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $teacherId = $request->get('teacherId');

            //$translator = $this->get("translator");

            if( isset($teacherId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                $periodId  = $currentPeriod->getId();

               /* $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT status as status, id as id, detailin as detailin
                                    FROM `tek_charges` c
                                    where c.user_id = "'.$teacherId.'" and c.period_id = "'.$periodId.'" order by id asc');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql='SELECT status as status, id as id, detailin as detailin
                                    FROM `tek_charges` c
                                    where c.user_id = "'.$teacherId.'" and c.period_id = "'.$periodId.'" order by id asc';
                $em->clear();
                //$em->getRepository(Program::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $colors = array(
                    "one" => "#38255c",
                    "two" => "#04D0E6"
                );
                $html = "";
                $color = "black";

                foreach( $entity as $entry ){
                    $html = '';
                    if ($entry['status'] == '1'){ $statusCharge = "Creada";$color = "black";}
                    if ($entry['status'] == '2'){ $statusCharge = "Espera de aprobación";$color = "black";}
                    if ($entry['status'] == '3'){ $statusCharge = "Aprobada";$color = "green";}
                    if ($entry['status'] == '4'){ $statusCharge = "Rechazada";$color = "red";}
                    if ($entry['status'] == '5'){ $statusCharge = "Cerrada y aprobada";$color = "green";}

                    $html .= '<div id="courseTeacherRows_"'.$entry['id'].'">';
                    $html .= '    <div  style="width: 600px;">Estado de la Carga: <p style="color:'.$color.'">' .$statusCharge . '</p></div>';
                    $html .= '    <div  style="width: 600px;">Detalles: ' .$entry['detailin']. '</div>';
                    $html .= '    <div class="clear"></div>';
                    $html .= '</div>';
                }


            return new Response(json_encode(array('error' => false, 'html' => $html)));
        } else {
            return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
        }

        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('SuperAdmin::loadCoursesByTeacherAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }
    /**
     * @Route("/charge/search", name="_expediente_sysadmin_charge_search")
     */
    public function searchChargesAction(Request $request, EntityManagerInterface $em, $rowsPerPage = 30, LoggerInterface $logger) {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
          //  $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";

            $periods = $em->getRepository("App:Period")->findBy(array('isActual'=>'1'));

            foreach($periods as $period ){
                $period_id = $period->getId();
            }

            ///filtrar por usuario y tipo
            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];

            if($role != 1){
                //if($role == 4) {
                    $where2 = "and pr.user_id = ".$user->getId();
                /*}
                if($role == 3){
                    $from2 = ", tek_course_class cc";
                    $where2 = "and (pr.course_id = cc.course_id and cc.user_id = ".$user->getId().")";
                    $where3 = "and pr.user_id = ".$user->getId();
                }*/
            }


            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(pr.detailin like '%" . $word . "%' )";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_charges pr where period_id = ".$period_id.";";
            $stmt = $em->getConnection()->prepare($sql);

            $filtered = 0;
            $total = 0;
            $result = $stmt->executeQuery();
            foreach($result->fetchAllAssociative() as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }

            $sql = "SELECT pr.id, pr.detailin, concat(u.lastname,' ',u.firstname) as user, pr.status as status"
                . " FROM tek_charges pr, tek_users u"
                . " $from2"
                . " WHERE u.id = pr.user_id and $where"
                . " and period_id = $period_id";
               // . " $where2"
               // . " ORDER BY pr.$sortBy $order"
               // . " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);

            $charges = $stmt2->executeQuery();

            //Agregado para cargar el tiwg directamente
            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' or r.role = 'ROLE_SUPERADMIN' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $teachers = $query->getResult();
            //fin de agregado

            return $this->render('SuperAdmin/Charges/list.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers, 'charges' => $charges->fetchAllAssociative()
            ));



/*
            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'charges' => $charges->fetchAllAssociative())));

  */
        } catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Charge::searchChargesAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/getInfoChargeDetail", name="_expediente_sysadmin_get_info_charge_detail")
     */
    public function getInfoChargeDetailAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $chargeId = $request->get('chargeId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $charge = $em->getRepository("App:Charges")->find($chargeId);





            if ( isset($charge) ) {
                $idCharge = $charge->getId();
                $detailin = $charge->getDetailin();
                $status = $charge->getStatus();

                $user = $charge->getUser();
                $userTeacher = 0;

                if( isset($user)){
                    $userTeacher = $user->getId();
                }




                return new Response(json_encode(array('error' => false, 'id' => $idCharge, 'detailin' => $detailin, 'status' => $status, 'user' => $userTeacher)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Charge::getInfoChargeFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/getInfoChargeFull", name="_expediente_sysadmin_get_info_charge_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoChargeFullAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $chargeId = $request->get('chargeId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $charge = $em->getRepository("App:Charges")->find($chargeId);


            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];


            if ( isset($charge) ) {
                $html  = '<div class="fieldRow"><label>Detalles:</label><span>' . $charge->getDetailin() . '</span></div><div style="float: right;"><p></div>';

                $html .= '<div class="fieldRow"><label>Profesor:</label><span></span>' . $charge->getUser() . '</div>';

                switch ($charge->getStatus()){
                    case 1:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Creada</span></div></br></br>';
                                if($role == 1){
                                    $html .= '<form id="'.$charge->getId().'"><div><label>Solicitar aceptación de carga:</label>
                                                    <input type="button" class="btnSendEmailCharge" value="Solicitar revisón" rel="'.$charge->getId().'">
                                              </div></form>';
                                }else {

                                }
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Espera de aprobación</span></div></br></br>';
                                if($role == 1){
                                    $html .= '<div><label>Reenviar solicitud de aceptación de carga:</label>
                                                            <input type="button" class="btnSendEmailCharge" value="Solicitar revisón" rel="'.$charge->getId().'">
                                                      </div>';
                                }else {
                                    $html .= '<div><label>Desea Aceptar carga:</label>
                                                            <input type="text" id="detailout" name="detailout">
                                                            <input type="button" class="btnSendYesCharge" value="Si" rel="'.$charge->getId().'">
                                                            <input type="button" class="btnSendYesCharge" value="No" rel="'.$charge->getId().'">
                                                      </div>';
                                }
                        break;
                    case 3:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Aprobada</span></div></br></br>';
                                if($role == 1){
                                    $html .= '<div><label>Cerrar carga:</label>
                                                            <input type="button" class="btnCloseEmailCharge" value="Cerrar" rel="'.$charge->getId().'">
                                                      </div>';
                                }else {

                                }
                                break;
                    case 4:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Rechazada</span></div></br></br>';
                                if($role == 1){
                                    $html .= '<div><label>Reenviar solicitud de aceptación de carga:</label>
                                                                    <input type="button" class="btnSendEmailCharge" value="Solicitar revisón" rel="'.$charge->getId().'">
                                                              </div>';
                                }else {

                                }
                                break;
                    case 5:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Cerrada</span></div></br></br>';

                                break;
                    default:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Error</span></div>';
                        break;

                }



                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Charge::getInfoChargeFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/getInfoTeacherChargeFull", name="_expediente_sysadmin_get_info_charge_teacher_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoChargeTeacherFullAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $teacherId = $request->get('teacherId');
            $period_id = $request->get('periodId'); //agregada 2020

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $charge = $em->getRepository("App:Charges")->findBy(array('teacher'=>$teacherId));
            //$user = $em->getRepository("App:User")->find($teacherId);



            if ( isset($charge) ) {

                $user_id = $teacherId;

                //$currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                //$period_id  = $currentPeriod->getId();

                $user = $em->getRepository("App:User")->find($user_id);
                /*$periods = $em->getRepository("App:Period")->findBy(array('isActual'=>'1'));

                foreach($periods as $period ){
                    $period_id = $period->getId();
                }*/
                if($period_id == ""){
                    $currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                    $period_id  = $currentPeriod->getId();
                }


                $html = "</br>";
                $html .= "<div>".$user->getFirstname()."-".$user->getLastName()."</div><br>";



                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_courses` t, tek_courses c, tek_users u  
                                    where t.user_id = u.id and t.course_id = c.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql='SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_courses` t, tek_courses c, tek_users u  
                                    where t.user_id = u.id and t.course_id = c.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $html .= "<label>Cursos:</label><br>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' G-'.$entry['groupnumber']  .'</label><br> ';
                }




                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, p.id as project, p.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_projects` t, tek_projects p, tek_users u  
                                    where t.user_id = u.id and t.project_id = p.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/

                $sql='SELECT t.id as id, p.id as project, p.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_projects` t, tek_projects p, tek_users u  
                                    where t.user_id = u.id and t.project_id = p.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();


                $html .= "<br>";
                $html .= "<label>Proyectos:</label><br>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['project']  .'</label><br> ';
                }

                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as commission, c.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_commissions` t, tek_commissions c, tek_users u  
                                    where t.user_id = u.id and t.commission_id = c.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/
                $sql='SELECT t.id as id, c.id as commission, c.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_commissions` t, tek_commissions c, tek_users u  
                                    where t.user_id = u.id and t.commission_id = c.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $html .= "<br>";
                $html .= "<label>Comisiones:</label><br>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['commission']  .'</label><br> ';
                }


                /*$stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, t.name as name, t.weight as charge, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_other` t, tek_users u  
                                    where t.user_id = u.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();*/
                $sql='SELECT t.id as id, t.name as name, t.weight as charge, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_other` t, tek_users u  
                                    where t.user_id = u.id and t.user_id = "'.$user->getId() .'" and t.period_id = "'.$period_id.'"';
                $em->clear();
                $em->getRepository(Period::class);
                $stmt = $em->getConnection()->prepare($sql);
                $result = $stmt->executeQuery();
                $entity = $result->fetchAllAssociative();

                $html .= "<br>";
                $html .= "<label>Otros:</label><br>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['charge']  .'</label><br> ';
                }
                $html .= "<hr></div>";



                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Charge::getInfoChargeFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/updateCharge/", name="_expediente_sysadmin_update_charge")
     * @Method({"GET", "POST"})
     */
    public function updateChargeAction(Request $request, EntityManagerInterface $em){
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $chargeId = $request->get('chargeId');
            $detailin = $request->get('detailin');
            //$status = $request->get('status');
            //$user = $request->get('user');

            $translator = $this->get("translator");

            if( $chargeId != '') {
                //$em = $this->getDoctrine()->getEntityManager();

                $charge = new Charges();
                $charge = $em->getRepository("App:Charges")->find($chargeId);

                $charge->setDetailin($detailin);
                //$charge->setStatus($status);

                /*$entityUser = $em->getRepository("App:User")->find($user);
                if( isset($entityUser)){
                    $charge->setUser($entityUser);
                }*/


                $em->persist($charge);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Charge::createChargeAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/createCharge/", name="_expediente_sysadmin_create_charge")
     * @Method({"GET", "POST"})
     */
    public function createChargeAction(Request $request, EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $detailin = $request->get('detailin');
            $status = 1;
            $teacher = $request->get('teacher');



            //$translator = $this->get("translator");

            if( isset($userId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $charge = new Charges();
                $charge->setDetailin($detailin);

                $charge->setStatus($status);

                $entityUser = $em->getRepository("App:User")->find($teacher);
                if( isset($entityUser)){
                    $charge->setUser($entityUser);
                }

                //$currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                //$period_id  = $currentPeriod->getId();

                //$entityPeriod = $em->getRepository("App:Period")->find(2);
                $periods = $em->getRepository("App:Period")->findBy(array('isActual'=>'1'));

                foreach($periods as $period ){
                    $entityPeriod = $period;
                }
                if( isset($entityPeriod)){
                    $charge->setPeriod($entityPeriod);
                }


                $em->persist($charge);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Charge::createChargeAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/createSendCharge/", name="_expediente_sysadmin_create_send_charge")
     */
    public function createSendChargeAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $detailin = $request->get('detailin');
            $status = 1;
            $teacher = $request->get('teacher');



           // $translator = $this->get("translator");

            if( isset($userId)) {
               // $em = $this->getDoctrine()->getEntityManager();

                $charge = new Charges();
                $charge->setDetailin($detailin);

                $charge->setStatus($status);

                $entityUser = $em->getRepository("App:User")->find($teacher);
                if( isset($entityUser)){
                    $charge->setUser($entityUser);
                }

                //$currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                //$period_id  = $currentPeriod->getId();

                //$entityPeriod = $em->getRepository("App:Period")->find(2);
                $periods = $em->getRepository("App:Period")->findBy(array('isActual'=>'1'));

                foreach($periods as $period ){
                    $entityPeriod = $period;
                }
                if( isset($entityPeriod)){
                    $charge->setPeriod($entityPeriod);
                }


                $em->persist($charge);
                $em->flush();


                $chargeId = $charge->getId();
                ///enviar cargar recien creada
                if($chargeId != ''){
                    $charge = $em->getRepository("App:Charges")->find($chargeId);

                    $charge->setStatus(2);
                    $em->persist($charge);
                    $em->flush();

                    $detailin = $charge->getDetailin();
                    $teacher = $charge->getUser();

                    /// enviar por correo
                    /*$message = (new \Swift_Message('Solicitud de revisión de carga académica'))
                        ->setSubject('Solicitud de revisión de carga académica')
                        ->setFrom('ciencias.politicas@ucr.ac.cr')
                        ->setTo('sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr',$teacher->getEmail())  ///$teacher->getEmail()
                        ->setBody(
                            'La escuela de Ciencias Polítcas solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.'
                        );

                    $mailer->send($message);*/

                    $teacherEmail=$teacher->getEmail();

                    $email = (new Email())

                        ->from('nides.ti@ucr.ac.cr')   /*Modificar para poner el corro de SALUD Publica*/
                        ->to('nides.ti@ucr.ac.cr')

                        ->from('sistemas.ecp@ucr.ac.cr')
                        ->to('sistemas.ecp@ucr.ac.cr')

                        ->addTo('erick.morajimenez@ucr.ac.cr')
                        ->addTo($teacherEmail)
                        //->cc('cc@example.com')
                        //->bcc('bcc@example.com')
                        //->replyTo('fabien@example.com')
                        //->priority(Email::PRIORITY_HIGH)
                        ->subject('Solicitud de revisión de carga académica')

                        ->text('La escuela de Salud Pública solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.');

                        ->text('La escuela de Ciencias Políticas solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.');

                    //  ->html('<p>See Twig integration for better HTML integration!</p>');

                    try {
                        $mailer->send($email);
                    } catch (TransportExceptionInterface $e) {
                        // some error prevented the email sending; display an
                        // error message or try to resend the message
                        $info = $e->getTraceAsString();
                        //$logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                    }
                }

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Charge::createSendChargeAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/charge/sendEmailCharge", name="_expediente_sysadmin_send_email_charge")
     * @Method({"GET", "POST"})
     */
    public function sendEmailChargeAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
        //$logger = $this->get('logger');
       // $translator = $this->get("translator");
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $chargeId = $request->get('chargeId');

          //  $em = $this->getDoctrine()->getEntityManager();

            if( isset($chargeId) ){
                $charge = $em->getRepository("App:Charges")->find($chargeId);

                $charge->setStatus(2);
                $em->persist($charge);
                $em->flush();

                $detailin = $charge->getDetailin();
                $teacher = $charge->getUser();


                /// enviar por correo
                /*$message = (new \Swift_Message('Solicitud de revisión de carga académica'))
                    ->setSubject('Solicitud de revisión de carga académica')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo('sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr',$teacher->getEmail())  ///$teacher->getEmail()
                    ->setBody(
                        'La escuela de Ciencias Polítcas solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.'
                    );

                $mailer->send($message);*/

                $teacherEmail=$teacher->getEmail();

                $email = (new Email())

                    ->from('nides.ti@ucr.ac.cr')     /*Modificar para poner correo de la escuela*/
                    ->to('nides.ti@ucr.ac.cr')

                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')

                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($teacherEmail)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Solicitud de revisión de carga académica')

                    ->text('La escuela de Salud Pública solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.');

                    ->text('La escuela de Ciencias Políticas solicita la revisión de las cargas académicas, '. $detailin . ', accese el sitio del sistema programas.ecp.ucr.ac.cr para verificarla.');

                //  ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $info = $e->getTraceAsString();
                    //$logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
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
    }

    /**
     * @Route("/charge/sendEmailCloseCharge", name="_expediente_sysadmin_send_email_close_charge")
     */
    public function sendEmailCloseChargeAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
        //$logger = $this->get('logger');
      //  $translator = $this->get("translator");
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $chargeId = $request->get('chargeId');

         //   $em = $this->getDoctrine()->getEntityManager();

            if( isset($chargeId) ){
                $charge = $em->getRepository("App:Charges")->find($chargeId);

                $charge->setStatus(5);
                $em->persist($charge);
                $em->flush();

                $detailin = $charge->getDetailin();
                $teacher = $charge->getUser();


                /// enviar por correo
                /*$message = (new \Swift_Message('Aviso de comprobación de carga académica'))
                    ->setSubject('Aviso de comprobación de carga académica')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo('sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr',$teacher->getEmail())  ///$teacher->getEmail()
                    ->setBody(
                        'La escuela de Ciencias Polítcas comunica que las carga académica, '. $detailin . ', ha sido aprobada y cerrada.'
                    );

                $mailer->send($message);*/
                $teacherEmail=$teacher->getEmail();

                $email = (new Email())

                    ->from('nides.ti@ucr.ac.cr')
                    ->to('nides.ti@ucr.ac.cr')

                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')

                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($teacherEmail)
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Aviso de comprobación de carga académica')

                    ->text('La escuela de Salud Pública comunica que las carga académica, '. $detailin . ', ha sido aprobada y cerrada.');

                    ->text('La escuela de Ciencias Políticas comunica que las carga académica, '. $detailin . ', ha sido aprobada y cerrada.');

                //  ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $info = $e->getTraceAsString();
                    //$logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
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
    }
    /**
     * @Route("/charge/mycharge", name="_expediente_sysadmin_mycharge")
     */
    public function userChargeAction(EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $user_id = $user->getId();

        $currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
        $period_id  = $currentPeriod->getId();

        $entity = $em->getRepository("App:User")->find($user_id);

        $sql = "SELECT *"
            . " FROM tek_charges c"
            . " WHERE c.user_id = ".$user_id." and c.period_id = ".$period_id." and c.status != 1";

        $stmt = $em->getConnection()->prepare($sql);

        $charges = $stmt->executeQuery()->fetchAllAssociative();


        $sql = "SELECT *"
            . " FROM tek_assigned_courses ac, tek_courses c "
            . " WHERE c.id = ac.course_id and ac.user_id = ".$user_id." and ac.period_id = ".$period_id;

        $stmt = $em->getConnection()->prepare($sql);

        $courses = $stmt->executeQuery()->fetchAllAssociative();

        $sql = "SELECT *"
            . " FROM tek_assigned_commissions ac, tek_commissions c"
            . " WHERE c.id = ac.commission_id and ac.user_id = ".$user_id." and ac.period_id = ".$period_id;

        $stmt = $em->getConnection()->prepare($sql);

        $commissions = $stmt->executeQuery()->fetchAllAssociative();

        $sql = "SELECT *"
            . " FROM tek_assigned_projects ap, tek_projects p"
            . " WHERE p.id = ap.project_id and ap.user_id = ".$user_id ." and ap.period_id = ".$period_id;

        $stmt = $em->getConnection()->prepare($sql);

        $projects = $stmt->executeQuery()->fetchAllAssociative();

        $sql = "SELECT *"
            . " FROM tek_assigned_other o"
            . " WHERE o.user_id = ".$user_id ." and o.period_id = ".$period_id;

        $stmt = $em->getConnection()->prepare($sql);

        $others = $stmt->executeQuery()->fetchAllAssociative();

        return $this->render('SuperAdmin/Charges/mycharge.html.twig', array('teacherId' => $user_id, 'charges' => $charges, 'commissions' => $commissions,'projects' => $projects,'courses' => $courses, 'others' => $others,
            'menuIndex' => 5));
    }

    /**
     * @Route("/charge/sendEmailChargeYes", name="_expediente_sysadmin_send_email_charge_yes")
     * @Method({"GET", "POST"})
     */
    public function sendEmailChargeYesAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){ //2018-18-06
        //$logger = $this->get('logger');
        //$translator = $this->get("translator");
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $chargeId = $request->get('chargeId');
            $detailout = $request->get('detailout');

           // $em = $this->getDoctrine()->getEntityManager();

            if( isset($chargeId) ){
                $charge = $em->getRepository("App:Charges")->find($chargeId);

                $charge->setStatus(3);
                $charge->setDetailout($detailout);
                $em->persist($charge);
                $em->flush();

                $teacher = $charge->getUser();

                /// enviar por correo
                /*
                $message = (new \Swift_Message('Aviso de aprobación de carga académica'))
                    ->setSubject('Aviso de aprobación de carga académica')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo(['sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr', 'ciencias.politicas@ucr.ac.cr'])  ///$teacher->getEmail()
                    ->setBody(
                        'Se informa que la carga académica del profesor: '. $teacher->getUsername() . ', '. $chargeId . ', ha sido aprobada.'
                    );

                $mailer->send($message);*/

                $email = (new Email())
                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')

                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Aviso de aprobación de carga académica')
                    ->text('Se informa que la carga académica del profesor: '. $teacher->getUsername() . ', '. $chargeId . ', ha sido aprobada.');
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
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }

    /**
     * @Route("/charge/sendEmailChargeNo", name="_expediente_sysadmin_send_email_charge_no")
     * @Method({"GET", "POST"})
     */
    public function sendEmailChargeNoAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){ //2018-18-06
      //  $logger = $this->get('logger');
      //  $translator = $this->get("translator");
        try {
            //$request = $this->get('request')->request;
            $request = $this->get('request_stack')->getCurrentRequest();
            $chargeId = $request->get('chargeId');
            $detailout = $request->get('detailout');

          //  $em = $this->getDoctrine()->getEntityManager();

            if( isset($chargeId) ){
                $charge = $em->getRepository("App:Charges")->find($chargeId);

                $charge->setStatus(4);
                $charge->setDetailout($detailout);
                $em->persist($charge);
                $em->flush();

                $teacher = $charge->getUser();

                /// enviar por correo
                /*
                $message = (new \Swift_Message('Aviso de rechazo de carga académica'))
                    ->setSubject('Aviso de rechazo de carga académica')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo(['sistemas.ecp@ucr.ac.cr', 'erick.morajimenez@ucr.ac.cr', 'ciencias.politicas@ucr.ac.cr'])
                    ->setBody(
                        'Se informa que la carga académica del profesor: '. $teacher->getUsername() . ', '. $detailout . ', ha sido rechazada.'
                    );

                $mailer->send($message);*/
                $email = (new Email())

                    ->from('nides.ti@ucr.ac.cr')
                    ->to('nides.ti@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo('jorgestwart.perez@ucr.ac.cr')

                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')



                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Aviso de rechazo de carga académica')
                    ->text('Se informa que la carga académica del profesor: '. $teacher->getUsername() . ', '. $chargeId . ', ha sido rechazada.');
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
    }



    //Actas
    /**
     * @Route("/record/search", name="_expediente_sysadmin_record_search")
     */
    public function searchRecordsAction($rowsPerPage = 30, EntityManagerInterface $em, LoggerInterface $logger) {
        //$logger = $this->get('logger');
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
            $where3 = "";
            $from2 = "";

            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];


            if($role != 1){
                /*if($role == 4) {
                    $where2 = "and pr.user_id = ".$user->getId();
                }*/
                if($role == 4){
                    $from2 = ", tek_record_user u";
                    $where2 = "and u.user_id = ".$user->getId()." and c.id = u.record_id ";
                }
            }

/*
            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(c.name like '%" . $word . "%' OR c.summary like '%" . $word . "%')";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_record c;";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $filtered = 0;
            $total = 0;
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }*/

            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $teachers = $query->getResult();
            $em->clear();

            $sql = "SELECT c.id, c.name, c.date, c.summary, c.status"
                . " FROM tek_record c"
                . " $from2"
                . " WHERE c.type = 1  " . $where
                . " $where2";
            $stmt2 = $em->getConnection()->prepare($sql);

            $records = $stmt2->executeQuery()->fetchAllAssociative();



            return $this->render('SuperAdmin/Documents/record.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers, "records"=>$records
            ));/*
            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'records' => $records)));*/
        } catch (Exception $e) {
            $info =$e->getTraceAsString();
            $logger->alert('Program::searchRecordsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $sql)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/updateRecord/", name="_expediente_sysadmin_update_record")
     */
    public function updateRecordAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $recordId = $request->get('recordId');
            $name = $request->get('name');
            $summary = $request->get('summary');
            $type = $request->get('type');
            $status = $request->get('status');

            //$translator = $this->get("translator");

            if( $recordId != '') {
                //$em = $this->getDoctrine()->getEntityManager();

                $record = new Record();
                $record = $em->getRepository("App:Record")->find($recordId);

                $record->setName($name);
                $record->setSummary($summary);
                $record->setType($type);
                //$record->setStatus($status);f

                $em->persist($record);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error parametros")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Commission::createSummaryAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => "error")));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/record", name="_expediente_sysadmin_record")
     */
    public function recordListAction(EntityManagerInterface $em, $rowsPerPage = 30)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Documents/record.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }
    /**
     * @Route("/record/getInfoRecordFull", name="_expediente_sysadmin_get_info_record_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoRecordFullAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $recordId = $request->get('recordId');
            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();

            $record = $em->getRepository(Record::class)->find($recordId);

            $dateRecord = "";
            //$dateRecord = $record->getDate();



            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];



            if ( isset($record) ) {
                $recordType = $record->getType();

                if($recordType == 1){
                    $html  = '<div><label>Nombre: ' . $record->getName() . '</label></div><div style="float: right;"><p></div>';
                    //$html .= '<div><label>Resumen:</label>' . $record->getSummary() . '</div>';


                    $html .= '<div style="width: 800px"><p align="justify">Resumen: ';
                    $string = htmlspecialchars($record->getSummary());
                    $html .= nl2br($string) . '</p></div>';

                    $html .= '<div><label>Fecha:</label>' . $dateRecord . '</div></br></br>';

                    $html .= '<table width="400">';

                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 1 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    $html .= '<td>Documentos:</td>';
                    $count = 1;
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<td width="100"><a title="'.$name.'" target="_blank" href="/assets/images/data/'.$filename.'">Doc_'.$count.'</a></br></td>&nbsp;';
                        $count++;
                    }

                    $html .= '</tr>';



                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 2 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    $html .= '<td>Audios:</td>';
                    $count = 1;
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<td width="100"><a target="_blank" href="/assets/images/data/'.$filename.'">Audio'.$count.'</a></br></br></td>';
                        $count++;
                    }

                    $html .= '</tr>';


                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 3 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    $html .= '<td>Acta:</td>';
                    $count = 1;
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<td width="100"><a target="_blank" href="/assets/images/data/'.$filename.'">Acta'.$count.'</a></br></br></td>';
                        $count++;
                    }


                    $html .= '</tr>';


                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 4 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    $html .= '<td>Ejecución de acuerdos:</td>';
                    $count = 1;
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<td width="100"><a target="_blank" href="/images/data/'.$filename.'">Doc_'.$count.'</a></br></br></td>';



                        $count++;
                    }

                    $html .= '</tr>';

                    $html .= '</table>';
                }

                if($recordType == 2){
                    $html  = '<div><label>Nombre: ' . $record->getName() . '</label></div><div style="float: right;"><p></div>';
                    //$html .= '<div><label>Resumen:</label>' . $record->getSummary() . '</div>';


                    $html .= '<div style="width: 800px"><p align="justify">Resumen: ';
                    $string = htmlspecialchars($record->getSummary());
                    $html .= nl2br($string) . '</p></div>';

                    $html .= '<div><label>Fecha:</label>' . $dateRecord . '</div></br></br>';

                    $html .= '<table width="600">';

                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 1 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    //$html .= '<td>Seguimiento al plan de estudios:</td>';
                    //$html .= '<td colspan="50"><a href="javascript:anzeigen(\'1\')"><div class="anzButton"><img width="25" height="25" src="./images/folder.png" id="pb1" /> Seguimiento al plan de estudios:</div></a></td>';
                    $html .= '<td colspan="50"><div><a href="#" id="openRecTba1" name="openRecTba1" rel="1"><span><img width="25" height="25" src="/images/folder.png" id="pb1" /> Seguimiento al plan de estudios:</span></a></div></td>';
                    $count = 1;
                    $html .= '<tr><td>';
                    $html .= '<div id="p1"  style="display:none;">';
                    $html .= '<p>Listado de archivos correspondiente al plan de estudios</p>';
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        //$html .= '<td width="100"><a title="'.$name.'" target="_blank" href="/ucr2/web/images/data/'.$filename.'">Doc'.$count.'</a></br></td>&nbsp;';

                        $html .= '<a title="'.$name.'" target="_blank" href="/images/data/'.$filename.'">Doc'.$count.'</a></br>&nbsp;';


                        $count++;
                    }
                    $html .= '</div>';
                    $html .= '</tr>';



                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 2 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    $html .= '<hr>';
                    //$html .= '<td>Informes comisión práctica profesional:</td>';
                    //$html .= '<td colspan="50"><a href="javascript:anzeigen(\'2\')"><div class="anzButton"><img width="25" height="25" src="./images/folder.png" id="pb2" /> Informes comisión práctica profesional:</div></a></td>';
                    $html .= '<td colspan="50"><div><a href="#" id="openRecTba2" name="openRecTba2" rel="2"><span><img width="25" height="25" src="/images/folder.png" id="pb2" /> Informes comisión práctica profesional:</span></a></div></td>';
                    $count = 1;
                    $html .= '<tr><td>';
                    $html .= '<div id="p2"  style="display:none;">';
                    $html .= '<p>Listado de archivos correspondiente a informes de comisión práctica profesional</p>';
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<a title="'.$name.'" target="_blank" href="/images/data/'.$filename.'">Doc'.$count.'</a></br>&nbsp;';
                        $count++;
                    }
                    $html .= '</div>';
                    $html .= '</tr>';


                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 3 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    //$html .= '<td>Informes comisión de docencia:</td>';
                    //$html .= '<td colspan="50"><a href="javascript:anzeigen(\'3\')"><div class="anzButton"><img width="25" height="25" src="./images/folder.png" id="pb3" /> Informes comisión de docencia:</div></a></td>';
                    $html .= '<td colspan="50"><div><a href="#" id="openRecTba3" name="openRecTba3" rel="3"><span><img width="25" height="25" src="/images/folder.png" id="pb3" /> Informes comisión de docencia:</span></a></div></td>';
                    $count = 1;
                    $html .= '<tr><td>';
                    $html .= '<div id="p3"  style="display:none;">';
                    $html .= '<p>Listado de archivos correspondiente a informes de comisión de docencia</p>';
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        $html .= '<a title="'.$name.'" target="_blank" href="/images/data/'.$filename.'">Doc'.$count.'</a></br>';
                        $count++;
                    }
                    $html .= '</div>';
                    $html .= '</tr>';


                    $html .= '<tr>';

                    $sql = "SELECT ra.id, ra.name, ra.filename, ra.type, ra.extension"
                        . " FROM tek_record_attachment ra"
                        . " WHERE ra.type = 4 and ra.record_id = $recordId";
                    $stmt = $em->getConnection()->prepare($sql);
                    $stmt->execute();
                    //$records = $stmt->fetchAll();
                    $records = $stmt->executeQuery()->fetchAllAssociative();

                    //$html .= '<td>Seguimiento de contratación de docentes interinos:</td>';
                    //$html .= '<div class="anzButton"><a id="openRecTba" rel="p4" href="#p4"><img width="25" height="25" src="./images/folder.png" id="pb1" /> Seguimiento de contratación de docentes interinos:</a></div>';
                    $html .= '<td colspan="50"><div><a href="#" id="openRecTba4" name="openRecTba4" rel="4"><span><img width="25" height="25" src="/images/folder.png" id="pb4" /> Seguimiento de contratación de docentes interinos:</span></a></div></td>';
                    $html .= '</tr>';
                    $count = 1;
                    $html .= '<tr><td>';
                    $html .= '<div id="p4"  style="display:none;">';
                    $html .= '<p>Listado de archivos correspondiente a seguimiento de contratación de docentes interinos</p>';

                    /*$html .= '</td></tr>';
                    $html .= '<tr>';*/
                    foreach($records as $row) {
                        $filename = $row['filename'];
                        $name = $row['name'];
                        //$html .= '<a title="'.$name.'" target="_blank" href="/images/data/'.$filename.'">CV'.$count.'</a>- '.$name.'</br>';
                        $html .= $name.'</br>';
                        $count2 = 0;
                        $filesA = explode(",", $filename);
                        foreach ($filesA as $fileA) {
                            $count2++;
                            $html .= '<a title="'.$name.'" target="_blank" href="/images/data/'.$fileA.'">Archivo'.$count2.'</a>-';
                        }
                        $html .= '</br>';



                        $count++;
                    }
                    $html .= '</div>';
                    $html .= '</td></tr>';

                    $html .= '</table>';
                }


                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            //$info = toString($e);
            //$logger->err('Record::getInfoRecordFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => "error")));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/createRecord/", name="_expediente_sysadmin_create_record")
     */
    public function createRecordAction(){ //2018-13-03
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            /*$user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();*/

            $name = $request->get('name');
            $summary = $request->get('summary');
            $type = $request->get('type');


            $translator = $this->get("translator");

            if( isset($name)) {
                $em = $this->getDoctrine()->getEntityManager();

                $record = new Record();
                $record->setSummary($summary);
                $record->setName($name);
                $record->setDate();
                $record->setStatus(1);
                $record->setType($type);

/*
                $entityCourse = $em->getRepository("App:Course")->findOneBy(array('id' => $course));
                if( isset($entityCourse)){
                    $program->setCourse($entityCourse);
                } */


                $em->persist($record);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::createRecordAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/course/getInfoRecordDetail", name="_expediente_sysadmin_get_info_record_detail")
     * @Method({"GET", "POST"})
     */
    public function getInfoRecordDetailAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $recordId = $request->get('recordId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $record = $em->getRepository("App:Record")->find($recordId);


            if ( isset($record) ) {
                $idRecord = $record->getId();
                $name = $record->getName();
                $type = $record->getType();
                $status = $record->getStatus();
                $summary = $record->getSummary();



                return new Response(json_encode(array('error' => false, 'id' => $idRecord, 'name' => $name, 'type' => $type, 'type' => $type, 'summary' => $summary)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::getInfoCourseFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => "error")));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    //Prestamos de equipo

    //Item
    /**
     * @Route("/item", name="_expediente_sysadmin_item")
     */
    public function itemListAction($rowsPerPage = 30)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getRoles();
        $role = $roles[0]->getId();

        $dql = "SELECT categories FROM App:CategoryItem categories ";
        $query = $em->createQuery($dql);
        $categories = $query->getResult();

        return $this->render('SuperAdmin/Items/item.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'categories' => $categories
        ));
    }

    public function searchItemAction($rowsPerPage = 30) {
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
            $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";



            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if($role != 1){
                //
                if($role == 4){
                    //$from2 = ", tek_record_user u";
                    //$where2 = "and u.user_id = ".$user->getId()." and c.id = u.record_id ";
                }
            }


            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(i.name like '%" . $word . "%' OR i.description like '%" . $word . "%')";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_item i;";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $filtered = 0;
            $total = 0;
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }

            $sql = "SELECT i.id, i.category_item_id as category, i.code as code, i.name, i.description, i.status"
                . " FROM tek_item i"
                . " $from2"
                . " WHERE $where"
                . " $where2";
            //. " ORDER BY i.$sortBy $order"
            //. " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);
            $stmt2->execute();
            $items = $stmt2->fetchAll();



            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'items' => $items)));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('Program::searchItemsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }

    }



    public function createItemAction(){ //2016 - 4 temp
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $categoryId = $request->get('category');
            $name = $request->get('name');
            $code = $request->get('code');
            $description = $request->get('description');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $item = new Item();
                $item->setName($name);
                $item->setCode($code);
                $item->setDescription($description);
                $item->setStatus(1);


                $entityCategory = $em->getRepository("App:CategoryItem")->find($categoryId);
                if( isset($entityCategory)){
                    $item->setCategory($entityCategory);
                }

                $em->persist($item);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createItemAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }


    public function getInfoItemDetailAction(){
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $itemId = $request->get('itemId');

            $em = $this->getDoctrine()->getEntityManager();
            $item = $em->getRepository("App:Item")->find($itemId);


            if ( isset($item) ) {
                $idTicket = $item->getId();
                $name = $item->getName();
                $code = $item->getCode();
                $description = $item->getDescription();
                $category = $item->getCategory();

                if( isset($category)){
                    $categoryItem = $category->getId();

                }else{
                    $categoryItem = 1;
                }




                return new Response(json_encode(array('error' => false, 'id' => $idTicket, 'name' => $name, 'code' => $code, 'description' => $description, 'category' => $categoryItem)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::getInfoItemFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }



    public function getInfoItemFullAction(){
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $itemId = $request->get('itemId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $item = $em->getRepository("App:Item")->find($itemId);

            //$dateRecord = "";
            //$dateRecord = $record->getDate();



            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if ( isset($item) ) {

                if($item->getStatus()=='1'){
                    $status="En bodega";
                }
                if($item->getStatus()=='2'){
                    $status="En préstamo";
                }
                if($item->getStatus()=='3'){
                    $status="Dañado";
                }
                $html  = '<div><label>Estado: ' . $status . '</label></div><div style="float: right;"><p></div><div>&nbsp</div>';


                $html .= '<div style="width: 800px"><p align="justify">Equipo Solicitado: </p></div>';
                //if($ticket->getId()){}
                if($item->getName() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Item: '.$item->getName().'</p></div>';
                }
                if($item->getCode() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Código: '.$item->getCode().'</p></div>';
                }
                if($item->getDescription() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Descripción: '.$item->getDescription().'</p></div>';
                }

                /* $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                 $string = htmlspecialchars($ticket->getComments());
                 $html .= nl2br($string) . '</p></div>';*/

                $html .= '</div>';



                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::getInfoRecordFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }

    public function updateItemAction(){ //2018-13-03
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $itemId = $request->get('itemId');
            $name = $request->get('name');
            $code = $request->get('code');
            $description = $request->get('description');
            $categoryId = $request->get('$category');

            $translator = $this->get("translator");

            if( $itemId != '') {
                $em = $this->getDoctrine()->getEntityManager();

                $item = new Ticket();
                $item = $em->getRepository("App:Item")->find($itemId);



                $item->setName($name);
                $item->setCode($code);
                $item->setDescription($description);

                $item->setStatus(1);

                $category = $em->getRepository("App:CategoryItem")->find($categoryId);
                if( isset($category)){
                    $item->setCategory($category);
                }


                $em->persist($item);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::editItemAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }

    //Category
    public function categoryListAction($rowsPerPage = 30)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getRoles();
        $role = $roles[0]->getId();

        return $this->render('SuperAdmin/Items/category.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role
        ));
    }

    public function searchCategoryAction($rowsPerPage = 30) {
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
            $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";



            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if($role != 1){
                //
                if($role == 4){
                    //$from2 = ", tek_record_user u";
                    //$where2 = "and u.user_id = ".$user->getId()." and c.id = u.record_id ";
                }
            }


            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(c.name like '%" . $word . "%' OR c.description like '%" . $word . "%')";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_category_item c;";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $filtered = 0;
            $total = 0;
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }

            $sql = "SELECT c.id, c.code as code, c.name as name, c.description as description"
                . " FROM tek_category_item c"
                . " $from2"
                . " WHERE $where"
                . " $where2";
                //. " ORDER BY i.$sortBy $order"
                //. " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);
            $stmt2->execute();
            $categories = $stmt2->fetchAll();



            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'categories' => $categories)));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('Program::searchItemsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }

    }



    public function createCategoryAction(){ //2016 - 4 temp
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $code = $request->get('code');
            $description = $request->get('description');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $category = new CategoryItem();
                $category->setName($name);
                $category->setCode($code);
                $category->setDescription($description);


                $em->persist($category);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Curriculum::createCategoryAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }


    public function getInfoCategoryDetailAction(){
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $categoryId = $request->get('categoryId');

            $em = $this->getDoctrine()->getEntityManager();
            $category = $em->getRepository("App:CategoryItem")->find($categoryId);


            if ( isset($category) ) {
                $idCategory = $category->getId();
                $name = $category->getName();
                $code = $category->getCode();
                $description = $category->getDescription();


                return new Response(json_encode(array('error' => false, 'id' => $idCategory, 'name' => $name, 'code' => $code, 'description' => $description)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::getInfoCategoryItemFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }



    public function getInfoCategoryFullAction(){
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $categoryId = $request->get('categoryId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $category = $em->getRepository("App:CategoryItem")->find($categoryId);

            //$dateRecord = "";
            //$dateRecord = $record->getDate();



            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if ( isset($category) ) {


                $html = '<div style="width: 800px"><p align="justify">Detalles: </p></div>';
                //if($ticket->getId()){}
                if($category->getName() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Item: '.$category->getName().'</p></div>';
                }
                if($category->getCode() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Código: '.$category->getCode().'</p></div>';
                }
                if($category->getDescription() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">Descripción: '.$category->getDescription().'</p></div>';
                }

                   /* $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                    $string = htmlspecialchars($ticket->getComments());
                    $html .= nl2br($string) . '</p></div>';*/

                    $html .= '</div>';



                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::getInfoCategoryFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }

    public function updateCategoryAction(){ //2018-13-03
        $logger = $this->get('logger');

        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $categoryId = $request->get('categoryId');
            $name = $request->get('name');
            $code = $request->get('code');
            $description = $request->get('description');

            $translator = $this->get("translator");

            if( $categoryId != '') {
                $em = $this->getDoctrine()->getEntityManager();

                $category = new CategoryItem();
                $category = $em->getRepository("App:CategoryItem")->find($categoryId);



                $category->setName($name);
                $category->setCode($code);
                $category->setDescription($description);


                $em->persist($category);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::editCategoryItemAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
    }


    //Tickets
    /**
     * @Route("/ticket", name="_expediente_sysadmin_ticket")
     */
    public function ticketListAction($rowsPerPage = 30)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getRoles();
        $role = $roles[0]->getId();

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINADOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Items/tickets.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }

    public function searchTicketAction($rowsPerPage = 30) {
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
            $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";



            //$user = $this->container->get('security.context')->getToken()->getUser();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if($role != 1){
                /*if($role == 4) {
                    $where2 = "and pr.user_id = ".$user->getId();
                }*/
                if($role == 4){
                    //$from2 = ", tek_record_user u";
                    //$where2 = "and u.user_id = ".$user->getId()." and c.id = u.record_id ";
                }
            }


            foreach ($words as $word) {
                $where .= $where == ""? "":" AND ";
                $where .= "(t.date_in like '%" . $word . "%' OR t.comments like '%" . $word . "%')";
            }
            $sql = "SELECT SUM($where) as filtered,"
                . " COUNT(*) as total FROM tek_ticket t;";
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $filtered = 0;
            $total = 0;
            $result = $stmt->fetchAll();
            foreach($result as $row) {
                $filtered = $row['filtered'];
                $total = $row['total'];
            }

            $sql = "SELECT t.id, t.comments, t.date_estimated as dateestimated, t.date_in, t.user_id, t.status"
                . " FROM tek_ticket t"
                . " $from2"
                . " WHERE $where"
                . " $where2"
                . " ORDER BY t.$sortBy $order"
                . " LIMIT $rowsPerPage OFFSET $offset";
            $stmt2 = $em->getConnection()->prepare($sql);
            $stmt2->execute();
            $records = $stmt2->fetchAll();



            return new Response(json_encode(array('error' => false,
                'filtered' => $filtered,
                'total' => $total,
                'tickets' => $records)));
        } catch (Exception $e) {
            $info = toString($e);
            $logger->err('Program::searchTicketsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }



    public function createTicketAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){ //2016 - 4 temp
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $comments = $request->get('comments');
            $authorized = $request->get('authorized');
            $computer = $request->get('computer');
            $ncomputer = $request->get('ncomputer');
            $videobeam = $request->get('videobeam');
            $nvideobeam = $request->get('nvideobeam');
            $camara = $request->get('camara');
            $ncamara = $request->get('ncamara');
            $control = $request->get('control');
            $hdmi = $request->get('hdmi');
            $cable = $request->get('cable');
            $recorder = $request->get('recorder');
            $tripod = $request->get('tripod');
            $speaker = $request->get('speaker');
            $outlet = $request->get('outlet');
            $presenter = $request->get('presenter');


            $ticketDate = $request->get('ticketDate');
            $ticketHour = $request->get('ticketHour');

            $translator = $this->get("translator");

            if( isset($userId)) {
                $em = $this->getDoctrine()->getEntityManager();

                $ticket = new Ticket();
                $ticket->setComments($comments);
                $ticket->setAuthorized($authorized);
                $ticket->setComputer($computer);
                $ticket->setNcomputer($ncomputer);
                $ticket->setVideobeam($videobeam);
                $ticket->setNvideobeam($nvideobeam);
                $ticket->setCamara($camara);
                $ticket->setNcamara($ncamara);
                $ticket->setControl($control);
                $ticket->setHdmi($hdmi);
                $ticket->setCable($cable);
                $ticket->setRecorder($recorder);
                $ticket->setTripod($tripod);
                $ticket->setSpeaker($speaker);
                $ticket->setOutlet($outlet);
                $ticket->setPresenter($presenter);
                $ticket->setStatus(1);


                $ticket->setTicketDate($ticketDate);
                $date = new \DateTime();
                $date = $ticketDate;
                $tempTicketDate = $date->format('d/m/Y');

                $ticket->setTicketHour($ticketHour);
                $hour = new \DateTime();
                $hour = $ticketHour;
                $tempTicketHour = $hour->format('h:m:s');


                $entityUser = $em->getRepository("App:User")->find($userId);
                if( isset($entityUser)){
                    $ticket->setUser($entityUser);
                }

                $em->persist($ticket);
                $em->flush();

                $html = "";


                if($ticket->getTicketDate() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Fecha: ('.$tempTicketDate.')</p></div>';
                }
                if($ticket->getTicketHour() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Hora: ('.$tempTicketHour.')</p></div>';
                }
                $html .= '<div style="width: 800px"><p align="justify">Equipo Solicitado: </p></div>';
                //if($ticket->getId()){}
                if($ticket->getComputer() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Computadora ('.$ticket->getNcomputer().')</p></div>';
                }
                if($ticket->getCamara() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Camara ('.$ticket->getNcamara().')</p></div>';
                }
                if($ticket->getVideobeam() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Videobeam ('.$ticket->getNvideobeam().')</p></div>';
                }
                if($ticket->getControl() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Control</p></div>';
                }
                if($ticket->getHdmi() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable Hdmi</p></div>';
                }
                if($ticket->getCable() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable de Red</p></div>';
                }
                if($ticket->getRecorder() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Grabador</p></div>';
                }
                if($ticket->getTripod() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Tripode</p></div>';
                }
                if($ticket->getSpeaker() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Parlantes</p></div>';
                }
                if($ticket->getOutlet() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Regleta</p></div>';
                }
                if($ticket->getPresenter() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Pasador</p></div>';
                }

                $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                $string = htmlspecialchars($ticket->getComments());
                $html .= nl2br($string) . '</p></div>';

                $html .= '<div style="width: 800px"><p align="justify">Autorizado a recoger el equipo: ';
                $string = htmlspecialchars($ticket->getAuthorized());
                $html .= nl2br($string) . '</p></div>';

                $html .= '</div>';

                /// enviar por correo
                /*$message = (new \Swift_Message('Solicitud de revisión de carga académica'))
                    ->setSubject('Solicitud de equipo')
                    ->setFrom('sistemas.ecp@ucr.ac.cr')
                    ->setTo(['erick.morajimenez@ucr.ac.cr','sistemas.ecp@ucr.ac.cr',$entityUser->getEmail()])  ///$teacher->getEmail()
                    ->setBody(
                        'El profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', ha solicitado equipo, solicitud número: '. $ticket->getId() .'.
                        '.$html.'
                        
                        -----------------------------------------------------------------------------------------
                        REGLAMENTO PARA LA ADMINISTRACIÓN Y CONTROL DE LOS BIENES INSTITUCIONALES DE LA UNIVERSIDAD DE COSTA RICA
                        ARTÍCULO 4. DEFINICIONES 
                        1. Bienes institucionales: Son todos  aquellos bienes relacionados con propiedad, planta y equipo, lo cual también incluye bienes intangibles, 
                        recursos bibliográficos, documentos de valor administrativo, legal, histórico y cultural, sujetos de registro que la Universidad de Costa Rica 
                        tiene para uso y funcionamiento en la operación normal y cuya vida útil supera un año.
                        
                        CAPÍTULO IV OBLIGACIONES DE LAS PERSONAS USUARIAS DE LOS BIENES INSTITUCIONALES
                            ARTÍCULO 13. OBLIGACIONES Son obligaciones de las personas usuarias de los bienes institucionales, las siguientes: 
                            a) Custodiar, conservar y utilizar adecuadamente los bienes que le son asignados para el cumplimiento de sus actividades institucionales. 
                            b) Comunicar, de forma inmediata, a la persona encargada del control de bienes, lo siguiente:
                                    i. Daños o desperfectos que sufra el bien, con el fin de que se hagan las gestiones correspondientes para su reparación, o cambio por garantía.
                                    ii. La pérdida, robo o hurto del bien a su cargo, con el fin de que se hagan las denuncias pertinentes ante las instancias correspondientes, según
                                        los procedimientos establecidos por la Vicerrectoría de Administración. 
                            c) Utilizar los bienes únicamente para los fines e intereses institucionales. 
                            d) Solicitar la autorización del superior jerárquico o de la superiora jerárquica para trasladar o prestar bienes bajo su cargo a terceras personas o a otra unidad.
                            e) Cumplir con otras obligaciones que se establezcan en este Reglamento y la normativa sobre esta materia.
                            
                         ARTÍCULO 50. RESPONSABILIDAD ADMINISTRATIVA
                            El personal docente o administrativo que incurra en alguna falta, según su gravedad, estarán obligados a reponer, en forma personal o solidaria, la pérdida o reparación
                            del bien asignado, de conformidad con lo dispuesto por la Vicerrectoría de Administración.'
                    );

                $mailer->send($message);*/

                $email = (new Email())
                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($entityUser->getEmail())


                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Aviso de aprobación de carga académica')
                    ->text('El profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', ha solicitado equipo, solicitud número: '. $ticket->getId() .'.
                        '.$html.'
                        
                        -----------------------------------------------------------------------------------------
                        REGLAMENTO PARA LA ADMINISTRACIÓN Y CONTROL DE LOS BIENES INSTITUCIONALES DE LA UNIVERSIDAD DE COSTA RICA
                        ARTÍCULO 4. DEFINICIONES 
                        1. Bienes institucionales: Son todos  aquellos bienes relacionados con propiedad, planta y equipo, lo cual también incluye bienes intangibles, 
                        recursos bibliográficos, documentos de valor administrativo, legal, histórico y cultural, sujetos de registro que la Universidad de Costa Rica 
                        tiene para uso y funcionamiento en la operación normal y cuya vida útil supera un año.
                        
                        CAPÍTULO IV OBLIGACIONES DE LAS PERSONAS USUARIAS DE LOS BIENES INSTITUCIONALES
                            ARTÍCULO 13. OBLIGACIONES Son obligaciones de las personas usuarias de los bienes institucionales, las siguientes: 
                            a) Custodiar, conservar y utilizar adecuadamente los bienes que le son asignados para el cumplimiento de sus actividades institucionales. 
                            b) Comunicar, de forma inmediata, a la persona encargada del control de bienes, lo siguiente:
                                    i. Daños o desperfectos que sufra el bien, con el fin de que se hagan las gestiones correspondientes para su reparación, o cambio por garantía.
                                    ii. La pérdida, robo o hurto del bien a su cargo, con el fin de que se hagan las denuncias pertinentes ante las instancias correspondientes, según
                                        los procedimientos establecidos por la Vicerrectoría de Administración. 
                            c) Utilizar los bienes únicamente para los fines e intereses institucionales. 
                            d) Solicitar la autorización del superior jerárquico o de la superiora jerárquica para trasladar o prestar bienes bajo su cargo a terceras personas o a otra unidad.
                            e) Cumplir con otras obligaciones que se establezcan en este Reglamento y la normativa sobre esta materia.
                            
                         ARTÍCULO 50. RESPONSABILIDAD ADMINISTRATIVA
                            El personal docente o administrativo que incurra en alguna falta, según su gravedad, estarán obligados a reponer, en forma personal o solidaria, la pérdida o reparación
                            del bien asignado, de conformidad con lo dispuesto por la Vicerrectoría de Administración.');
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


    public function getInfoTicketDetailAction(){
        $logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            $ticketId = $request->get('ticketId');

            $em = $this->getDoctrine()->getEntityManager();
            $ticket = $em->getRepository("App:Ticket")->find($ticketId);


            if ( isset($ticket) ) {
                $idTicket = $ticket->getId();
                $comments = $ticket->getComments();
                $authorized = $ticket->getAuthorized();
                $computer = $ticket->getComputer();
                $nComputer = $ticket->getNcomputer();
                $camara = $ticket->getCamara();
                $nCamara = $ticket->getNcamara();
                $videobeam = $ticket->getVideobeam();
                $nvideobeam = $ticket->getnVideobeam();
                $control = $ticket->getControl();
                $hdmi = $ticket->getHdmi();
                $cable = $ticket->getCable();
                $recorder = $ticket->getRecorder();
                $tripod = $ticket->getTripod();
                $speaker = $ticket->getSpeaker();
                $outlet = $ticket->getOutlet();
                $presenter = $ticket->getPresenter();

                //$dateTicket = $ticket->getTicketDate();

                $date = new \DateTime();
                $date = $ticket->getTicketDate();
                $dateTicket = $date->format('d/m/Y');

               // $hourTicket = $ticket->getTicketHour();

                $date2 = new \DateTime();
                $date2 = $ticket->getTicketHour();
                $hourTicket = $date2->format('h:m:s');

                return new Response(json_encode(array('error' => false, 'id' => $idTicket, 'comments' => $comments, 'authorized' => $authorized, 'computer' => $computer, 'ncomputer' => $nComputer, 'camara' => $camara,
                    'ncamara' => $nCamara, 'videobeam' => $videobeam, 'nvideobeam' => $nvideobeam, 'control' => $control, 'hdmi' => $hdmi, 'cable' => $cable, 'recorder' => $recorder,
                    'tripod' => $tripod, 'speaker' => $speaker, 'outlet' => $outlet, 'presenter' => $presenter, 'dateTicket' => $dateTicket, 'hourTicket' => $hourTicket)));
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



    public function getInfoTicketFullAction(){
        $logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $ticketId = $request->get('ticketId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $ticket = $em->getRepository("App:Ticket")->find($ticketId);

            //$dateRecord = "";
            //$dateRecord = $record->getDate();



            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if ( isset($ticket) ) {

                if($ticket->getStatus()=='1'){
                    $status="Solicitud enviada";
                }
                if($ticket->getStatus()=='2'){
                    $status="Solicitud recibida y procesada";
                }
                if($ticket->getStatus()=='3'){
                    $status="Prestamo activo";
                }
                if($ticket->getStatus()=='4'){
                    $status="Préstamos concluido";
                }
                if($ticket->getStatus()=='5'){
                    $status="Préstamo cancelado";
                }
                $html  = '<div><label>Estado: ' . $status . '</label></div><div style="float: right;"><p></div><div>&nbsp</div>';



                $date = new \DateTime();
                $date = $ticket->getTicketDate();
                $tempTicketDate = $date->format('d/m/Y');

                $date2 = new \DateTime();
                $date2 = $ticket->getTicketHour();
                $tempTicketHour = $date2->format('h:m:s');

                if($ticket->getTicketDate() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Fecha: '.$tempTicketDate.' '.$tempTicketHour.'</p></div>';
                }
                if($ticket->getTicketHour() != ""){
                  //  $html .= '<div style="width: 800px"><p align="justify">- Hora: '.$ticket->getTicketHour().'</p></div>';
                }
                $html .= '<div style="width: 800px"><p align="justify">Equipo Solicitado: </p></div>';
                //if($ticket->getId()){}
                if($ticket->getComputer() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Computadora ('.$ticket->getNcomputer().')</p></div>';
                }
                if($ticket->getCamara() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Camara ('.$ticket->getNcamara().')</p></div>';
                }
                if($ticket->getVideobeam() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Videobeam ('.$ticket->getNvideobeam().')</p></div>';
                }
                if($ticket->getControl() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Control</p></div>';
                }
                if($ticket->getHdmi() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable Hdmi</p></div>';
                }
                if($ticket->getCable() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable de Red</p></div>';
                }
                if($ticket->getRecorder() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Grabador</p></div>';
                }
                if($ticket->getTripod() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Tripode</p></div>';
                }
                if($ticket->getSpeaker() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Parlantes</p></div>';
                }
                if($ticket->getOutlet() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Regleta</p></div>';
                }
                if($ticket->getPresenter() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Pasador</p></div>';
                }

                $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                $string = htmlspecialchars($ticket->getComments());
                $html .= nl2br($string) . '</p></div>';

                $html .= '<div style="width: 800px"><p align="justify">Autorizado a recoger el equipo: ';
                $string = htmlspecialchars($ticket->getAuthorized());
                $html .= nl2br($string) . '</p></div>';

                $html .= '</div>';



                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::getInfoRecordFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function getListItemsOfTicketAction(){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $ticketId = $request->get('ticketId');

                $em = $this->getDoctrine()->getEntityManager();
                $ticket = $em->getRepository("App:Ticket")->find($ticketId);
                $detail = $ticket->getDetails();
                $authorized = $ticket->getAuthorized();

                $em = $this->getDoctrine()->getEntityManager();
                $sql = "SELECT ti.id, i.id as 'itemId', i.code, i.name "
                    . " FROM tek_ticket_item ti"
                    . " JOIN tek_item i ON i.id = ti.item_id"
                    . " WHERE ti.ticket_id = " . $ticketId
                    . " ORDER BY i.name, i.code";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll();

                if ( isset($items) ) {
                    return new Response(json_encode(array('error' => false, 'items' => $items, 'detail' => $detail, 'authorized' => $authorized)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' => "Data not found.")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Ticket::getListAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        //}// endif this is an ajax request
        /*else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function getListItemsForTicketAction(){
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getEntityManager();
       // if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
       // {
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $ticketId = $request->get('ticketId');
                $text = $request->get('text');

                $em = $this->getDoctrine()->getEntityManager();
                $sql = "SELECT i.id, i.name, i.code "
                    . " FROM tek_item i"
                    . " LEFT JOIN tek_ticket_item ti ON ti.item_id = i.id"
                    . " WHERE (i.name like '%" . $text . "%' OR i.code like '%" . $text . "%') "
                    . " AND (ti.id is null or ti.ticket_id <> " . $ticketId . " OR ti.ticket_id is null)"
                    . " GROUP BY i.id"
                    . " ORDER BY i.name, i.code";
                $stmt = $em->getConnection()->prepare($sql);
                $stmt->execute();
                $items = $stmt->fetchAll();

                if ( isset($items) ) {
                    return new Response(json_encode(array('error' => false, 'items' => $items)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' => "Data not found.")));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('Student::getListStudentsForGroupAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
       /* }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function addItemToTicketAction(){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $ticketId = $request->get('ticketId');
                $itemId = $request->get('itemId');

                $translator = $this->get("translator");

                if( isset($ticketId) && isset($itemId)) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $ticket = $em->getRepository("App:Ticket")->find($ticketId);

                    $item = $em->getRepository("App:Item")->find($itemId);

                    $ticketItem = new TicketItem();

                    $ticketItem->setComments("1");
                    if( isset($item)){
                        $ticketItem->setItem($item);
                    }
                    if( isset($ticket)){
                        $ticketItem->setTicket($ticket);
                    }

                    $em->persist($ticketItem);
                    $em->flush();

                    return new Response(json_encode(array('error' => false, 'id' => $ticketItem->getId())));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::addStudentToGroupAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function removeItemFromTicketAction(){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
            try {
                $request = $this->get('request_stack')->getCurrentRequest();
                $itemId = $request->get('itemId');
                $ticketId = $request->get('ticketId');

                $ticketItemId = $request->get('ticketItemId');

                $translator = $this->get("translator");

                //if( isset($itemId) && isset($ticketId)) {
                if( isset($ticketItemId) ) {
                    $em = $this->getDoctrine()->getEntityManager();


                    //$ticketItem = $em->getRepository("App:TicketItem")->findOneBy(array('item' => $itemId, 'ticket' => $ticketId));
                    $ticketItem = $em->getRepository("App:TicketItem")->find($ticketItemId);

                    if ( isset($ticketItem) ) {
                        $em->remove($ticketItem);
                        $em->flush();
                    }

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::removeItemFromTicketAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function getInfoTicketRealiceAction(){
        $logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $ticketId = $request->get('ticketId');

            $em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $ticket = $em->getRepository("App:Ticket")->find($ticketId);

            //$dateRecord = "";
            //$dateRecord = $record->getDate();



            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getRoles();
            $role = $roles[0]->getId();


            if ( isset($ticket) ) {


                if($ticket->getStatus()=='1'){
                    $status="Solicitud enviada";
                }
                if($ticket->getStatus()=='2'){
                    $status="Solicitud recibida y procesada";
                }
                if($ticket->getStatus()=='3'){
                    $status="Prestamo activo";
                }
                if($ticket->getStatus()=='4'){
                    $status="Préstamos concluido";
                }
                if($ticket->getStatus()=='5'){
                    $status="Préstamo cancelado";
                }
                $html  = '<div><label>Estado: ' . $status . '</label></div><div style="float: right;"><p></div><div>&nbsp</div>';


                $html .= '<div style="width: 800px"><p align="justify">Equipo Solicitado: </p></div>';
                //if($ticket->getId()){}
                if($ticket->getComputer() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Computadora ('.$ticket->getNcomputer().')</p></div>';
                }
                if($ticket->getCamara() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Camara ('.$ticket->getNcamara().')</p></div>';
                }
                if($ticket->getVideobeam() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Videobeam ('.$ticket->getNvideobeam().')</p></div>';
                }
                if($ticket->getControl() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Control</p></div>';
                }
                if($ticket->getHdmi() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable Hdmi</p></div>';
                }
                if($ticket->getCable() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable de Red</p></div>';
                }
                if($ticket->getRecorder() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Grabador</p></div>';
                }
                if($ticket->getTripod() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Tripode</p></div>';
                }
                if($ticket->getSpeaker() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Parlantes</p></div>';
                }
                if($ticket->getOutlet() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Regleta</p></div>';
                }
                if($ticket->getPresenter() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Pasador</p></div>';
                }

                $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                $string = htmlspecialchars($ticket->getComments());
                $html .= nl2br($string) . '</p></div>';

                $html .= '</div>';


                $html .= '<div style="width: 800px"><p align="justify">Se ha autorizado a: ';
                $string = htmlspecialchars($ticket->getAuthorized());
                $html .= nl2br($string) . '</p></div>';

                $html .= '</div>';


                /*$html .= '<hr>';
                $html .= '<p>Agregar items a la solicitud:</p>';
                $html .= '<hr>';
                $html .= '<div>';
                $html .= '<select id="itemList" name="itemList">';*/
                $sql = "SELECT i.id, i.name, i.code, i.status"
                    . " FROM tek_item i"
                    . " WHERE i.status != 3"
                    . " ORDER BY i.name";
                $stmt2 = $em->getConnection()->prepare($sql);
                $stmt2->execute();
                $items = $stmt2->fetchAll();
                /*foreach($items as $row) {
                    $name = $row['name'];
                    $id = $row['id'];
                    $html .= '<option value="'.$id.'">'.$name.'</option>';
                }
                $html .= '</select>';
                $html .= '</div>';*/



                return new Response(json_encode(array('error' => false, 'html' => $html, 'items' => $items)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Record::getInfoRecordFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function updateTicketAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){ //2018-13-03
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $ticketId = $request->get('ticketId');
            $comments = $request->get('comments');
            $authorized = $request->get('authorized');
            $computer = $request->get('computer');
            $ncomputer = $request->get('ncomputer');
            $videobeam = $request->get('videobeam');
            $nvideobeam = $request->get('nvideobeam');
            $camara = $request->get('camara');
            $ncamara = $request->get('ncamara');
            $control = $request->get('control');
            $hdmi = $request->get('hdmi');
            $cable = $request->get('cable');
            $recorder = $request->get('recorder');
            $tripod = $request->get('tripod');
            $speaker = $request->get('speaker');
            $outlet = $request->get('outlet');
            $presenter = $request->get('presenter');
            $user = $request->get('user');

            //$translator = $this->get("translator");

            if( $ticketId != '') {
                //$em = $this->getDoctrine()->getEntityManager();

                $ticket = new Ticket();
                $ticket = $em->getRepository("App:Ticket")->find($ticketId);



                $ticket->setComments($comments);
                $ticket->setAuthorized($authorized);
                $ticket->setComputer($computer);
                $ticket->setNcomputer($ncomputer);
                $ticket->setVideobeam($videobeam);
                $ticket->setNvideobeam($nvideobeam);
                $ticket->setCamara($camara);
                $ticket->setNcamara($ncamara);
                $ticket->setControl($control);
                $ticket->setHdmi($hdmi);
                $ticket->setCable($cable);
                $ticket->setRecorder($recorder);
                $ticket->setTripod($tripod);
                $ticket->setSpeaker($speaker);
                $ticket->setOutlet($outlet);
                $ticket->setPresenter($presenter);
                $ticket->setStatus(2);

                $entityUser = $em->getRepository("App:User")->find($userId);
                if( isset($entityUser)){
                    $ticket->setUserUpdate($entityUser);
                }

                $html = "";

                $html .= '<div style="width: 800px"><p align="justify">Equipo Solicitado: </p></div>';
                //if($ticket->getId()){}
                if($ticket->getComputer() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Computadora ('.$ticket->getNcomputer().')</p></div>';
                }
                if($ticket->getCamara() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Camara ('.$ticket->getNcamara().')</p></div>';
                }
                if($ticket->getVideobeam() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Videobeam ('.$ticket->getNvideobeam().')</p></div>';
                }
                if($ticket->getControl() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Control</p></div>';
                }
                if($ticket->getHdmi() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable Hdmi</p></div>';
                }
                if($ticket->getCable() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Cable de Red</p></div>';
                }
                if($ticket->getRecorder() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Grabador</p></div>';
                }
                if($ticket->getTripod() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Tripode</p></div>';
                }
                if($ticket->getSpeaker() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Parlantes</p></div>';
                }
                if($ticket->getOutlet() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Regleta</p></div>';
                }
                if($ticket->getPresenter() != ""){
                    $html .= '<div style="width: 800px"><p align="justify">- Pasador</p></div>';
                }

                $html .= '<div style="width: 800px"><p align="justify">Información Adicional: ';
                $string = htmlspecialchars($ticket->getComments());
                $html .= nl2br($string) . '</p></div>';

                $html .= '<div style="width: 800px"><p align="justify">Autorizado a recoger el equipo: ';
                $string = htmlspecialchars($ticket->getAuthorized());
                $html .= nl2br($string) . '</p></div>';

                $html .= '</div>';

                /// enviar por correo
                /*$message = (new \Swift_Message('Solicitud de revisión de carga académica'))
                    ->setSubject('Solicitud de equipo (Actualización)')
                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                    ->setTo(['erick.morajimenez@ucr.ac.cr','sistemas.ecp@ucr.ac.cr',$entityUser->getEmail()])  ///$teacher->getEmail()
                    ->setBody(
                        'Solicitud Actualizada, el profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', ha modificado la solicitud de equipo, se requiere revisar y procesar nuevamente.
                        -----------------------------------------------------------------------------------------
                        REGLAMENTO PARA LA ADMINISTRACIÓN Y CONTROL DE LOS BIENES INSTITUCIONALES DE LA UNIVERSIDAD DE COSTA RICA
                        ARTÍCULO 4. DEFINICIONES 
                        1. Bienes institucionales: Son todos  aquellos bienes relacionados con propiedad, planta y equipo, lo cual también incluye bienes intangibles, 
                        recursos bibliográficos, documentos de valor administrativo, legal, histórico y cultural, sujetos de registro que la Universidad de Costa Rica 
                        tiene para uso y funcionamiento en la operación normal y cuya vida útil supera un año.
                        
                        CAPÍTULO IV OBLIGACIONES DE LAS PERSONAS USUARIAS DE LOS BIENES INSTITUCIONALES
                            ARTÍCULO 13. OBLIGACIONES Son obligaciones de las personas usuarias de los bienes institucionales, las siguientes: 
                            a) Custodiar, conservar y utilizar adecuadamente los bienes que le son asignados para el cumplimiento de sus actividades institucionales. 
                            b) Comunicar, de forma inmediata, a la persona encargada del control de bienes, lo siguiente:
                                    i. Daños o desperfectos que sufra el bien, con el fin de que se hagan las gestiones correspondientes para su reparación, o cambio por garantía.
                                    ii. La pérdida, robo o hurto del bien a su cargo, con el fin de que se hagan las denuncias pertinentes ante las instancias correspondientes, según
                                        los procedimientos establecidos por la Vicerrectoría de Administración. 
                            c) Utilizar los bienes únicamente para los fines e intereses institucionales. 
                            d) Solicitar la autorización del superior jerárquico o de la superiora jerárquica para trasladar o prestar bienes bajo su cargo a terceras personas o a otra unidad.
                            e) Cumplir con otras obligaciones que se establezcan en este Reglamento y la normativa sobre esta materia.
                            
                         ARTÍCULO 50. RESPONSABILIDAD ADMINISTRATIVA
                            El personal docente o administrativo que incurra en alguna falta, según su gravedad, estarán obligados a reponer, en forma personal o solidaria, la pérdida o reparación
                            del bien asignado, de conformidad con lo dispuesto por la Vicerrectoría de Administración.'. $html

                    );

                $mailer->send($message);*/

                $email = (new Email())
                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($entityUser->getEmail())


                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Solicitud de equipo (Actualización)')
                    ->text('Solicitud Actualizada, el profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', ha modificado la solicitud de equipo, se requiere revisar y procesar nuevamente.
                        
                        -----------------------------------------------------------------------------------------
                        REGLAMENTO PARA LA ADMINISTRACIÓN Y CONTROL DE LOS BIENES INSTITUCIONALES DE LA UNIVERSIDAD DE COSTA RICA
                        ARTÍCULO 4. DEFINICIONES 
                        1. Bienes institucionales: Son todos  aquellos bienes relacionados con propiedad, planta y equipo, lo cual también incluye bienes intangibles, 
                        recursos bibliográficos, documentos de valor administrativo, legal, histórico y cultural, sujetos de registro que la Universidad de Costa Rica 
                        tiene para uso y funcionamiento en la operación normal y cuya vida útil supera un año.
                        
                        CAPÍTULO IV OBLIGACIONES DE LAS PERSONAS USUARIAS DE LOS BIENES INSTITUCIONALES
                            ARTÍCULO 13. OBLIGACIONES Son obligaciones de las personas usuarias de los bienes institucionales, las siguientes: 
                            a) Custodiar, conservar y utilizar adecuadamente los bienes que le son asignados para el cumplimiento de sus actividades institucionales. 
                            b) Comunicar, de forma inmediata, a la persona encargada del control de bienes, lo siguiente:
                                    i. Daños o desperfectos que sufra el bien, con el fin de que se hagan las gestiones correspondientes para su reparación, o cambio por garantía.
                                    ii. La pérdida, robo o hurto del bien a su cargo, con el fin de que se hagan las denuncias pertinentes ante las instancias correspondientes, según
                                        los procedimientos establecidos por la Vicerrectoría de Administración. 
                            c) Utilizar los bienes únicamente para los fines e intereses institucionales. 
                            d) Solicitar la autorización del superior jerárquico o de la superiora jerárquica para trasladar o prestar bienes bajo su cargo a terceras personas o a otra unidad.
                            e) Cumplir con otras obligaciones que se establezcan en este Reglamento y la normativa sobre esta materia.
                            
                         ARTÍCULO 50. RESPONSABILIDAD ADMINISTRATIVA
                            El personal docente o administrativo que incurra en alguna falta, según su gravedad, estarán obligados a reponer, en forma personal o solidaria, la pérdida o reparación
                            del bien asignado, de conformidad con lo dispuesto por la Vicerrectoría de Administración.');
                //  ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $info = $e->getTraceAsString();
                    $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                }

                $em->persist($ticket);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::createCourseAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    public function saveTicketMessageAction(MailerInterface $mailer, EntityManagerInterface $em, LoggerInterface $logger, TranslatorInterface $translator){
        $logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $ticketId = $request->get('ticketId');
            $details = $request->get('details');

            //$translator = $this->get("translator");

            if( $ticketId != '') {
                //$em = $this->getDoctrine()->getEntityManager();

                $ticket = new Ticket();
                $ticket = $em->getRepository("App:Ticket")->find($ticketId);



                $ticket->setDetails($details);
                $ticket->setStatus(3);

                $entityUser = $em->getRepository("App:User")->find($userId);
                if( isset($entityUser)){
                    $ticket->setUserUpdate($entityUser);
                }


                                /// enviar por correo
                                /*$message = (new \Swift_Message('Solicitud de revisión de carga académica'))
                                    ->setSubject('Solicitud de equipo (Actualización)')
                                    ->setFrom('ciencias.politicas@ucr.ac.cr')
                                    ->setTo(['erick.morajimenez@ucr.ac.cr','sistemas.ecp@ucr.ac.cr',$entityUser->getEmail()])  ///$teacher->getEmail()
                                    ->setBody(
                                        'Solicitud Procesada, para el profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', '. $details .'.'
                                    );

                                $mailer->send($message);*/

                $email = (new Email())
                    ->from('sistemas.ecp@ucr.ac.cr')
                    ->to('sistemas.ecp@ucr.ac.cr')
                    ->addTo('erick.morajimenez@ucr.ac.cr')
                    ->addTo($entityUser->getEmail())


                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Solicitud de equipo (Actualización)')
                    ->text('Solicitud Procesada, para el profesor: '. $entityUser->getFirstname(). ' '. $entityUser->getLastname() . ', '. $details .'.');
                //  ->html('<p>See Twig integration for better HTML integration!</p>');

                try {
                    $mailer->send($email);
                } catch (TransportExceptionInterface $e) {
                    // some error prevented the email sending; display an
                    // error message or try to resend the message
                    $info = $e->getTraceAsString();
                    $logger->alert('Program::sendCheckProgramFormAction [' . $info . "]");
                }


                $em->persist($ticket);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            $logger->err('Course::createCourseAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }


    //////Mantenimiento Proyectos

    /**
     * @Route("/project", name="_expediente_sysadmin_project")
     */
    public function projectListAction($rowsPerPage = 30, EntityManagerInterface $em)
    {
        //$em = $this->getDoctrine()->getEntityManager();
        //$text = $this->get('request')->query->get('text');
        $request = $this->get('request_stack')->getCurrentRequest();
        $text = $request->get('text');

        //$user = $this->container->get('security.context')->getToken()->getUser();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $roles = $user->getIdRoles();
        $role = $roles[0];

        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();

        return $this->render('SuperAdmin/Project/list.html.twig', array(
            'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers
        ));
    }

    /**
     * @Route("/project/search", name="_expediente_sysadmin_project_search")
     * @Method({"GET", "POST"})
     */
    public function searchProjectsAction($rowsPerPage = 30, EntityManagerInterface $em, LoggerInterface $logger) {
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            $text = $request->get('text');
            $sortBy = $request->get('sortBy');
            $order = $request->get('order');
            $page = $request->get('page');
            $offset = ($page-1) * $rowsPerPage;
            //  $em = $this->getDoctrine()->getEntityManager();
            $words = explode(" ", trim($text));
            $where = "";
            $where2 = "";
            $where3 = "";
            $from2 = "";

            $user = $this->get('security.token_storage')->getToken()->getUser();
            $roles = $user->getIdRoles();
            $role = $roles[0];

            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $teachers = $query->getResult();


            $sql = "SELECT c.id, c.name, c.code, c.type"
                . " FROM tek_projects c" ;


            $stmt2 = $em->getConnection()->prepare($sql);
            $comm1=$stmt2->executeQuery();
            $projects = $comm1->fetchAllAssociative();

            return $this->render('SuperAdmin/Project/list.html.twig', array(
                'menuIndex' => 3, 'text' => $text, 'user' => $user, 'role' => $role, 'teachers' => $teachers , 'projects' => $projects
            ));

        } catch (Exception $e) {
            $info = $e->getTraceAsString();
            $logger->alert('Program::searchProjectsAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $sql)));
        }

        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/project/createProject/", name="_expediente_sysadmin_create_project")
     * @Method({"GET", "POST"})
     */
    public function createProjectAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $status = $request->get('status');

            //$translator = $this->get("translator");

            if( isset($userId)) {
                //$em = $this->getDoctrine()->getEntityManager();

                $project = new Project();
                $project->setName($name);
                $project->setCode($code);
                $project->setType($type);
                $project->setStatus($status);

                $em->persist($project);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            // $logger->err('Project::createProjectAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }

    /**
     * @Route("/project/updateProject/", name="_expediente_sysadmin_update_project")
     * @Method({"GET", "POST"})
     */
    public function updateProjectAction(EntityManagerInterface $em){ //2018-13-03
        //$logger = $this->get('logger');
        //if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        //{
        try {
            $request = $this->get('request_stack')->getCurrentRequest();

            //$teacherId = $request->get('teacherId');
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();

            $projectId = $request->get('projectId');
            $name = $request->get('name');
            $code = $request->get('code');
            $type = $request->get('type');
            $status = $request->get('status');

            //$translator = $this->get("translator");

            if( $projectId != '') {
                // $em = $this->getDoctrine()->getEntityManager();

                $project = new Project();
                $project = $em->getRepository("App:Project")->find($projectId);

                $project->setName($name);
                $project->setCode($code);
                $project->setType($type);
                $project->setStatus($status);

                $em->persist($project);
                $em->flush();

                return new Response(json_encode(array('error' => false)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' =>"error.paramateres.missing")));
            }
        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Project::createProjectAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/project/getInfoProjectFull", name="_expediente_sysadmin_get_info_project_full")
     * @Method({"GET", "POST"})
     */
    public function getInfoProjectFullAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $projectId = $request->get('projectId');

            //$em = $this->getDoctrine()->getEntityManager();
            $project = $em->getRepository("App:Project")->find($projectId);





            if ( isset($project) ) {
                $html  = '<div class="fieldRow"><label>Nombre:</label><span>' . $project->getName() . '</span></div><div style="float: right;"><p></div>';
                $html .= '<div class="fieldRow"><label>Codigo:</label><span></span>' . $project->getCode() . '</div>';
                switch ($project->getType()){
                    case 1:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Otro</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Tipo:</label><span>Normal</span></div>';
                        break;

                }


                switch ($project->getStatus()){
                    case 1:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;
                    case 2:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Inactivo</span></div>';
                        break;
                    default:     $html .= '<div class="fieldRow"><label>Estado:</label><span>Activo</span></div>';
                        break;

                }

                return new Response(json_encode(array('error' => false, 'html' => $html)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Project::getInfoProjectFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
    /**
     * @Route("/project/getInfoProjectDetail", name="_expediente_sysadmin_get_info_project_detail")
     * @Method({"GET", "POST"})
     */
    public function getInfoProjectDetailAction(EntityManagerInterface $em){
        //$logger = $this->get('logger');
        /*if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {*/
        try {
            $request = $this->get('request_stack')->getCurrentRequest();
            //$request = $this->get('request')->request;
            $projectId = $request->get('projectId');

            //$em = $this->getDoctrine()->getEntityManager();
            //$student = new Student();
            $project = $em->getRepository("App:Project")->find($projectId);





            if ( isset($project) ) {
                $idCourse = $project->getId();
                $name = $project->getName();
                $code = $project->getCode();
                $type = $project->getType();
                $status = $project->getStatus();

                return new Response(json_encode(array('error' => false, 'id' => $idCourse, 'name' => $name, 'code' => $code, 'status' => $status, 'type' => $type)));
            } else {
                return new Response(json_encode(array('error' => true, 'message' => "No se encontro información.")));
            }


        }
        catch (Exception $e) {
            $info = toString($e);
            //$logger->err('Course::getInfoProjectFullAction [' . $info . "]");
            return new Response(json_encode(array('error' => true, 'message' => $info)));
        }
        /*}// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }*/
    }
}
