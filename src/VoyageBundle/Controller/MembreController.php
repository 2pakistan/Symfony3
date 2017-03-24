<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoyageBundle\Entity\Membres;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MembreController extends Controller
{
    /**
     * @Route("/membre/{id}", name="memberHp", requirements={"id": "\d+"})
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.js_vars')->userId = $id;

        //on recupere les données du membre dont l'id est $id
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);
        if ($membre === null) {
            return $this->redirectToRoute('homePage');//TODO-404
        }

        return $this->render('VoyageBundle:Default:membre/layout/membre.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/membre/{id}/voyages", name="memberVoyages", requirements={"id": "\d+"})
     */
    public function listeVoyagesAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.js_vars')->userId = $id;

        //on recupere les données du membre dont l'id est $id
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);
        if ($membre === null) {
            return $this->redirectToRoute('homePage');//TODO-404
        }

        return $this->render('VoyageBundle:Default:membre/layout/membreVoyages.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/membre/{id}/visited", name="memberMap", requirements={"id": "\d+"})
     */
    public function cartePaysAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.js_vars')->userId = $id;

        //on recupere les données du membre dont l'id est $id
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);

        return $this->render('VoyageBundle:Default:membre/layout/membreCartePays.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/membre/{id}/followed", name="usersFollowed", requirements={"id": "\d+"})
     */
    public function followedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.js_vars')->userId = $id;

        //on recupere les données du membre dont l'id est $id
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);

        return $this->render('VoyageBundle:Default:membre/layout/membreFollowed.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/membre/{id}/followers", name="usersFollowers", requirements={"id": "\d+"})
     */
    public function followersAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $this->get('app.js_vars')->userId = $id;

        //on recupere les données du membre dont l'id est $id
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);

        return $this->render('VoyageBundle:Default:membre/layout/membreFollowers.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/follow", options={"expose"=true}, name="followUser")
     * @Method("POST")
     */
    public function followAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $idToFollow = $request->request->get('idFollowed');

            $usrFollowed = $em->getRepository('VoyageBundle:Utilisateurs')
                ->find($idToFollow);
            $user = $this->getUser();
            $user->addFollowed($usrFollowed);
            $em->persist($user);
            $em->flush();

            $response = new JsonResponse();
            return $response->setData(array('status' => 1));
        } else {
            $this->redirectToRoute('homePage'); //TODO : 404 ?
        }
    }

    /**
     * @Route("/unfollow", options={"expose"=true}, name="unfollowUser")
     * @Method("POST")
     */
    public function unfollowAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $idToFollow = $request->request->get('idFollowed');
            $usrFollowed = $em->getRepository('VoyageBundle:Utilisateurs')
                ->find($idToFollow);

            $user = $this->getUser();
            $user->removeFollowed($usrFollowed);
            $em->persist($user);
            $em->flush();

            $response = new JsonResponse();
            return $response->setData(array('status' => 1));
        } else {
            $this->redirectToRoute('homePage'); //TODO : 404 ?
        }
    }

}
