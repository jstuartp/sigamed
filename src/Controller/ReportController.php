<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Absence;
use App\Entity\Contact;

use App\Entity\Programs;
use App\Form\ProgramFormType;

use App\Entity\Student;


use App\Entity\Course;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;


class ReportController extends AbstractController
{

    /**
     * @Route("/reports/Courses/", name="_expediente_sysadmin_academic")
     */
    public function reportCoursesAcademicAction(){
        $logger = $this->get("logger");
        $em = $this->getDoctrine()->getEntityManager();



        $request = $this->get('request_stack')->getCurrentRequest();

        $groups = null;
        $grades = null;
        $institutions = null;

        $groupsT = null;
        $gradesT = null;
        $institutionsT = null;

/*        $html = "<table align='center'>";
        $html .= "<tr><td rowspan='3' span='3' width='250'><img src='../images/logoucr.png'>/td><td>ESCUELA DE CIENCIAS POLITICAS</td><td rowspan='3' width='250'><img src='../images/ecp.png'></td></tr>";
        $html .= "<tr><td>HORARIO DE CURSOS</td></tr>";
        $html .= "<tr><td>I CICLO LECTIVO 2018</td></tr>";
        $html .= "</table>";*/

        $html = "</br>";
        /*$dql = "SELECT c FROM App:Course c order by c.section, c.code";
        $query = $em->createQuery($dql);
        $courses = $query->getResult();*/
        //$courses = $em->getRepository("App:Course");
        $html .= "<table width='900' border='1'>
                        <tr>
                            <td>SIGLA</td>
                            <td>NOMBRE-CURSO</td>
                            <td>GRUPO</td>
                            <td>CRED</td>
                            <td>CUPO</td>
                            <td>HORARIO</td>
                            <td>AULA</td>
                            <td>PROFESOR(A)</td>
                        </tr>";
        /*foreach($courses as $course){
            $html .= "<TR>";
            $html .= "<td>".$course->getCode()."</td>
                        <td>".$course->getName()."</td>
                        <td>".$course->getGroupnumber()."</td>
                        <td>".$course->getCredit()."</td>
                        <td>".$course->getRoom()."</td>
                        <td>".$course->getSchedule()."</td>
                        <td>".$course->getClassroom()."</td>
                        <td>".$course->getUser()."</td>";
            $html .= "</TR>";
        }*/

        for($i = 0; $i < 10; $i++){

            $dql = "SELECT c FROM App:Course c where c.section = ". $i ."order by c.section, c.code, c.groupnumber";
            $query = $em->createQuery($dql);
            $courses = $query->getResult();

            switch ($i){
                case 1:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Repertorios</span></td></tr>';
                    break;
                case 2:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Cursos de servicio</span></td></tr>';
                    break;
                case 3:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Primer A??o</span></td></tr>';
                    break;
                case 4:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Segundo A??o</span></td></tr>';
                    break;
                case 5:     $html .= '</table><div class="pageBreak"> </div>';
                            $html .= '<table width=\'900\' border=\'1\'>';
                            $html .= "<table width='900' border='1'>
                                <tr>
                                    <td>SIGLA</td>
                                    <td>NOMBRE-CURSO</td>
                                    <td>GRUPO</td>
                                    <td>CRED</td>
                                    <td>CUPO</td>
                                    <td>HORARIO</td>
                                    <td>AULA</td>
                                    <td>PROFESOR(A)</td>
                                </tr>";
                            $html .= '<tr style="background: #4d4d4d"><td colspan="7" align="center"><span style="color: #ffffff">Tercer A??o</span></div>';
                    break;
                case 6:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Cursos Optativos Tercer A??o</span></td></tr>';
                    break;
                case 7:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Cuarto A??o</span></td></tr>';
                    break;
                case 8:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Quinto A??o</span></td></tr>';
                    break;
                case 9:     $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Trabajos Finales</span></td></tr>';
                    break;
                default:    $html .= '<tr style="background: #4d4d4d"><td colspan="8" align="center"><span style="color: #ffffff">Repertorios</span></td></tr>';
                    break;

            }

            foreach($courses as $course){



                $html .= "<TR>";
                $html .= "<td>".$course->getCode()."</td>
                        <td>".$course->getName()."</td>
                        <td>".$course->getGroupnumber()."</td>
                        <td>".$course->getCredit()."</td>
                        <td>".$course->getRoom()."</td>
                        <td>".$course->getSchedule()."</td>
                        <td>".$course->getClassroom()."</td>";

                //$teacher =$course->getUser();

                $currentPeriod = $em->getRepository("App:Period")->findOneBy(array('isActual' => true));
                $period = $currentPeriod->getId();

                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_courses` t, tek_courses c, tek_users u  
                                    where t.user_id = u.id and t.course_id = c.id and t.course_id = "'.$course->getId() .'" and t.period_id = "'.$period.'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $html .= "<td>";
                foreach( $entity as $entry ){
                    $html .=  $entry['lastname'] .' '.$entry['firstname']  .' ';
                }
                $html .= "</td>";



                $html .= "</TR>";
            }

        }


        $html .= "</table>";


        return $this->render('SuperAdmin/Reports/academic.html.twig', array('menuIndex' => 4,
            'hmtl' => $html, 'institutionsT' => $institutionsT,'gradesT' => $gradesT
        ));
    }

    /**
     * @Route("/reports/Academic/", name="_expediente_sysadmin_charges")
     */
    public function reportCoursesChargesAction(){
        $logger = $this->get("logger");
        $em = $this->getDoctrine()->getEntityManager();



        $request = $this->get('request_stack')->getCurrentRequest();

        $groups = null;
        $grades = null;
        $institutions = null;

        $groupsT = null;
        $gradesT = null;
        $institutionsT = null;

        /*        $html = "<table align='center'>";
                $html .= "<tr><td rowspan='3' span='3' width='250'><img src='../images/logoucr.png'>/td><td>ESCUELA DE CIENCIAS POLITICAS</td><td rowspan='3' width='250'><img src='../images/ecp.png'></td></tr>";
                $html .= "<tr><td>HORARIO DE CURSOS</td></tr>";
                $html .= "<tr><td>I CICLO LECTIVO 2018</td></tr>";
                $html .= "</table>";*/

        $html = "</br>";
        /*$dql = "SELECT c FROM App:Course c order by c.section, c.code";
        $query = $em->createQuery($dql);
        $courses = $query->getResult();*/
        //$courses = $em->getRepository("App:Course");



            $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' or r.role = 'ROLE_COORDINATOR' ORDER BY users.firstname";
            $query = $em->createQuery($dql);
            $users = $query->getResult();



            foreach($users as $user){



                $html .= "<div class='listContainerTeacher charge_" .$user->getId()."'>";
                $html .= "<div>".$user->getFirstname()."-".$user->getLastName()."</div><br>";



                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as course, c.name as name, c.groupnumber as groupnumber, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_courses` t, tek_courses c, tek_users u  
                                    where t.user_id = u.id and t.course_id = c.id and t.user_id = "'.$user->getId() .'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();

                $html .= "<label>Cursos:</label>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' G-'.$entry['groupnumber']  .'</label><br> ';
                }




                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, p.id as project, p.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_projects` t, tek_projects p, tek_users u  
                                    where t.user_id = u.id and t.project_id = p.id and t.user_id = "'.$user->getId() .'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();
                $html .= "<br>";
                $html .= "<label>Proyectos:</label>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['project']  .'</label><br> ';
                }

                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, c.id as commission, c.name as name, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_commissions` t, tek_commissions c, tek_users u  
                                    where t.user_id = u.id and t.commission_id = c.id and t.user_id = "'.$user->getId() .'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();
                $html .= "<br>";
                $html .= "<label>Comisiones:</label>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['commission']  .'</label><br> ';
                }


                $stmt = $this->getDoctrine()->getEntityManager()
                    ->getConnection()
                    ->prepare('SELECT t.id as id, t.name as name, t.weight as charge, u.lastname as lastname, u.firstname as firstname
                                    FROM `tek_assigned_other` t, tek_users u  
                                    where t.user_id = u.id and t.user_id = "'.$user->getId() .'"');
                $stmt->execute();
                $entity = $stmt->fetchAll();
                $html .= "<br>";
                $html .= "<label>Otros:</label>";
                foreach( $entity as $entry ){
                    $html .=  '<label>'. $entry['name'] .' I-'.$entry['charge']  .'</label><br> ';
                }
                $html .= "<hr></div>";

            }






        $dql = "SELECT users FROM App:User users JOIN users.roles r WHERE r.role = 'ROLE_PROFESOR' ORDER BY users.firstname";
        $query = $em->createQuery($dql);
        $teachers = $query->getResult();


        return $this->render('SuperAdmin/Reports/charges.html.twig', array('menuIndex' => 4,
            'hmtl' => $html, 'institutionsT' => $institutionsT,'gradesT' => $gradesT, 'teachers' => $teachers
        ));
    }
}