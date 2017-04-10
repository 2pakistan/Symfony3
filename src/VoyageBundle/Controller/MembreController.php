<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use VoyageBundle\Entity\Utilisateurs;
use VoyageBundle\Entity\Countries;
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
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);
        $this->get('app.js_vars')->userId = $id;

        //Renders an array of countries visited with number of steps in each countries
        $dataCountries = array(['countries', 'nombre d\'etapes']);
        $countries = $membre->getCountriesVisited();
        foreach ($countries as $country => $val) {
            $id = $val->getId();
            $countSteps = $em->getRepository('VoyageBundle:Etapes')
                ->getNbStepsByCountry($id);

            $dataCountries[$country + 1][0] = $val->getName();
            $dataCountries[$country + 1][1] = intval($countSteps[0]);
        }

        $this->get('app.js_vars')->dataCountries = $dataCountries;

        //map with all steps markers
        $trips = $membre->getVoyages();
        $stepMarkers = array();
        $stepData = array();
        $stepMedias = array();

        foreach ($trips as $trip) {
            $tripSteps = $em->getRepository('VoyageBundle:Etapes')
                ->findBy(array('trip' => $trip));

            foreach ($tripSteps as $step) {

                $paths = $em->getRepository('VoyageBundle:Medias')
                    ->findByStep($step);
                $stepMedias[] = $paths;
                $stepData[] = array($step->getDescriptionEtape());
                if ($step->getCountry() instanceof Countries) {
                    $stepMarkers[] = array($step->getCountry()->getName(), $step->getLatitude(), $step->getLongitude());
                } else {
                    $stepMarkers[] = array('Pays non précisé par l\'utilisateur', $step->getLatitude(), $step->getLongitude());
                }
            }
        }

        $this->get('app.js_vars')->stepMarkers = $stepMarkers;
        $this->get('app.js_vars')->stepData = $stepData;
        $this->get('app.js_vars')->stepMedias = $stepMedias;

        return $this->render('VoyageBundle:Default:membre/layout/membreCartePays.html.twig', array('membre' => $membre));
    }

    /**
     * @Route("/membre/{id}/followed", name="memberFollowed", requirements={"id": "\d+"})
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
     * @Route("/membre/{id}/followers", name="memberFollowers", requirements={"id": "\d+"})
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
            return $this->redirectToRoute('homePage'); //TODO : 404 ?
        }
    }

    /**
     * @Route("/trip-list", name="tripList")
     */
    public function tripListAction()
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('homePage');
        }

        $em = $this->getDoctrine()->getManager();
        $usersFollowed = $user->getFollowed();
        $tripsFollowed = array();
        foreach ($usersFollowed as $followed) {
            $userTrips = $followed->getVoyages();
            foreach ($userTrips as $trip) {
                $tripsFollowed[] = $trip;
            }
        }

        $recentSteps = $em->getRepository('VoyageBundle:Etapes')
            ->getRecentByTrips($tripsFollowed);

        return $this->render('VoyageBundle:Default:membre/layout/tripList.html.twig',
            array(
                'tripsFollowed' => $tripsFollowed,
                'recentSteps' => $recentSteps
            ));
    }

}
