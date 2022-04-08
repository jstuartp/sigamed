<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login_med.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/login_check", name="_expediente_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/logout", name="_expediente_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
    /**
     * @Route("/access/", name="_expediente_access")
     */
    public function accessPageAction(){

        $em = $this->getDoctrine()->getEntityManager();
        $dql = "SELECT users FROM TecnotekExpedienteBundle:User users JOIN users.roles r WHERE r.role = 'ROLE_COORDINADOR' order by users.firstname, users.lastname";
        $query = $em->createQuery($dql);
        $users = $query->getResult();

        $dql = "SELECT e FROM TecnotekExpedienteBundle:ActionMenu e WHERE e.parent is null order by e.sortOrder";
        $query = $em->createQuery($dql);
        $permisos = $query->getResult();

        $institutions = $em->getRepository("TecnotekExpedienteBundle:Institution")->findAll();

        return $this->render('TecnotekExpedienteBundle:SuperAdmin:Users/access.html.twig', array('menuIndex' => 1,
            'users' => $users, 'permisos' => $permisos, 'institutions' => $institutions));
    }

    public function saveAccessAction(){
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                $request = $this->get('request')->request;
                $userId = $request->get('userId');
                $access = $request->get('access');
                $institutions = $request->get('institutions');
                $translator = $this->get("translator");

                if( isset($userId) && isset($access) ) {

                    $em = $this->getDoctrine()->getEntityManager();

                    $user = $em->getRepository("TecnotekExpedienteBundle:User")->find($userId);

                    $currentInstitutions = $user->getInstitutions();
                    if($institutions == ""){
                        $newInstitutions = array();
                    } else {
                        $newInstitutions = explode(",", $institutions);
                    }

                    $institutionsToRemove = array();
                    foreach($currentInstitutions as $currentInstitution){
                        if( !in_array($currentInstitution->getId(), $newInstitutions)){
                            array_push($institutionsToRemove, $currentInstitution );
                        }
                    }

                    foreach($institutionsToRemove as $institution){
                        $user->removeInstitution($institution, $logger);
                    }

                    $found = false;
                    foreach($newInstitutions as $newInst){
                        $found = false;
                        foreach($currentInstitutions as $currentInst){
                            if($currentInst->getId() == $newInst){
                                $found = true;
                                break;
                            }
                        }
                        if(!$found){
                            $newEntityInstitution = $em->getRepository("TecnotekExpedienteBundle:Institution")
                                ->find($newInst);
                            $user->addInstitution($newEntityInstitution);
                        }
                    }

                    $em->persist($user);

                    /* Set privileges */
                    $currentPrivileges = $user->getPrivileges();
                    $newPrivileges = explode(",", $access);

                    $tempNew = array();

                    $found = false;
                    //Remove already saved
                    foreach( $newPrivileges as $privilege )
                    {
                        $found = false;
                        foreach( $currentPrivileges as $currentPrivilege )
                        {
                            if($currentPrivilege->getActionMenu()->getId() == $privilege){
                                $found = true;
                                break;
                            }
                        }
                        if($found == false){
                            array_push($tempNew, $privilege);
                        }
                    }

                    $actionMenuRepository = $em->getRepository("TecnotekExpedienteBundle:ActionMenu");
                    $privilegeRepository = $em->getRepository("TecnotekExpedienteBundle:UserPrivilege");
                    foreach( $tempNew as $temp )
                    {
                        $newPrivilege = new \Tecnotek\ExpedienteBundle\Entity\UserPrivilege();
                        $newPrivilege->setUser($user);
                        $newPrivilege->setActionMenu($actionMenuRepository->find($temp));
                        $em->persist($newPrivilege);
                    }


                    $tempToDelete = array();
                    //To delete
                    foreach( $currentPrivileges as $currentPrivilege )
                    {
                        $found = false;
                        foreach( $newPrivileges as $privilege )
                        {
                            if($currentPrivilege->getActionMenu()->getId() == $privilege){
                                $found = true;
                                break;
                            }
                        }
                        if($found == false){
                            array_push($tempToDelete, $currentPrivilege);
                        }
                    }

                    foreach( $tempToDelete as $temp )
                    {                    ;
                        $em->remove($temp);
                    }

                    $em->flush();

                    return new Response(json_encode(array('error' => false)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::createEntryAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }

    public function loadPrivilegesAction(){
        $logger = $this->get('logger');
        if ($this->get('request')->isXmlHttpRequest())// Is the request an ajax one?
        {
            try {
                $request = $this->get('request')->request;
                $userId = $request->get('userId');

                $translator = $this->get("translator");

                if( isset($userId) ) {

                    $em = $this->getDoctrine()->getEntityManager();

                    $user = $em->getRepository("TecnotekExpedienteBundle:User")->find($userId);

                    $currentPrivileges = $user->getPrivileges();

                    $privileges = array();

                    foreach( $currentPrivileges as $privilege )
                    {
                        if( sizeof($privilege->getActionMenu()->getChildrens()) == 0)
                            array_push($privileges, $privilege->getActionMenu()->getId());
                    }

                    $institutions = $user->getInstitutions();
                    $institutionsId = array();
                    foreach( $institutions as $institution )
                    {
                        array_push($institutionsId, $institution->getId());
                    }

                    return new Response(
                        json_encode(array('error' => false, 'privileges' => $privileges,
                            'institutions' => $institutionsId)));
                } else {
                    return new Response(json_encode(array('error' => true, 'message' =>$translator->trans("error.paramateres.missing"))));
                }
            }
            catch (Exception $e) {
                $info = toString($e);
                $logger->err('SuperAdmin::createEntryAction [' . $info . "]");
                return new Response(json_encode(array('error' => true, 'message' => $info)));
            }
        }// endif this is an ajax request
        else
        {
            return new Response("<b>Not an ajax call!!!" . "</b>");
        }
    }


    public function showMenuAction(){
        $logger = $this->get('logger');
        $user= $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();

        /// Get Allowed Menu Actions of the User
        $sql = 'SELECT p.action_menu_id'
            . ' FROM tek_users_privileges p'
            . ' WHERE p.user_id = ' . $user->getId();

        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $privileges = $stmt->fetchAll();
        $allowed = array();

        foreach($privileges as $privilege){
            array_push($allowed, $privilege[0]);
        }

        //if(!in_array(strtolower($contactEmail), $emailsFinal)){        }

        $dql = "SELECT e FROM App:ActionMenu e WHERE e.parent is null order by e.sortOrder";
        $query = $em->createQuery($dql);
        $headers = $query->getResult();

        $html = '';
        $menu = '';
        $html .= '<li class="divider"></li>';
        foreach($headers as $header){
            $menu = '';
            foreach($header->getChildrens() as $children){
                $submenuHtml = '';
                if(sizeof($children->getChildrens()) > 0){
                    foreach($children->getChildrens() as $submenu){
                        if(in_array($submenu->getId(), $allowed)){
                            $submenuHtml .= '<li class=""><a href="' .
                                ($submenu->getRoute() == "#"? "#":$this->generateUrl($submenu->getRoute())) . '">'
                                . $submenu->getLabel() . '</a>';
                            $submenuHtml .= '</li>';
                        }
                    }

                    if($submenuHtml != ''){
                        $menu .= '<li class="has-submenu"><a href="' .
                            ($children->getRoute() == "#"? "#":$this->generateUrl($children->getRoute())) . '">'
                            . $children->getLabel() . '</a>';

                        $submenuHtml = '<ul class="dropdown">' . $submenuHtml . '</ul>';

                        $menu .= $submenuHtml . '</li>';
                    }

                } else { // The children (second level) do not has a submenu
                    if(in_array($children->getId(), $allowed)){
                        $menu .= '<li><a href="' .
                            ($children->getRoute() == "#"? "#":$this->generateUrl($children->getRoute())) . '">'
                            . $children->getLabel() . '</a></li>';
                    }
                }
            }//End of childrens

            if($menu != ''){
                $menu = '<ul class="dropdown">' . $menu . '</ul>';
                $html .= '<li class="has-dropdown"><a href="' .
                    ($header->getRoute() == "#"? "#":$this->generateUrl($header->getRoute())) . '">' . $header->getLabel()
                    . '</a>';
                $html .= $menu;
                $html .= '</li>';
            }
        }
        //Get Current User Privileges
        /*$sql = 'SELECT m.label, m.route, m.parent_id, f.label as "father_label", f.route as "father_route"'
                . ' FROM tek_users_privileges p'
                . ' JOIN tek_actions_menu m ON m.id = p.action_menu_id'
                . ' JOIN tek_actions_menu f ON f.id = m.parent_id'
                . ' WHERE p.user_id = ' . $user->getId() . ' AND m.parent_id is not null'
                . ' order by f.sort_order, m.sort_order;';


        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        $privileges = $stmt->fetchAll();

        $html = "";
        $parentId = 0;

        foreach($privileges as $privilege){
            if( $privilege['parent_id'] == $parentId) {
                    $html .= '      <li><a href="' . ($privilege["route"] == "#"? "#":$this->generateUrl($privilege['route'])) . '">' . $privilege['label'] . '</a></li>';
            } else {
                if($parentId == 0){//First Menu
                    $html .= '<li class="divider"></li>';
                    $html .= '<li class="has-dropdown"><a href="' . ($privilege["father_route"] == "#"? "#":$this->generateUrl($privilege['father_route'])) . '">' . $privilege['father_label'] . '</a>';
                    $html .= '  <ul class="dropdown">';
                    $html .= '      <li><a href="' . ($privilege["route"] == "#"? "#":$this->generateUrl($privilege['route'])) . '">' . $privilege['label'] . '</a></li>';
                } else {
                    $html .= '  </ul>';
                    $html .= '</li>';
                    $html .= '<li class="divider"></li>';
                    $html .= '<li class="has-dropdown"><a href="' . ($privilege["father_route"] == "#"? "#":$this->generateUrl($privilege['father_route'])) . '">' . $privilege['father_label'] . '</a>';
                    $html .= '  <ul class="dropdown">';
                    $html .= '      <li><a  href="' . ($privilege["route"] == "#"? "#":$this->generateUrl($privilege['route'])) . '">' . $privilege['label'] . '</a></li>';
                }
            }
            $parentId = $privilege['parent_id'];
        }

        if($html != ""){
            $html .= '  </ul>';
            $html .= '</li>';
        }*/

        return new Response($html);
    }
}