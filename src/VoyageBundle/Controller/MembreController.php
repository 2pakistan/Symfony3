<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;
use VoyageBundle\Entity\Utilisateurs;
use VoyageBundle\Entity\Countries;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class MembreController extends Controller
{
    /**
     * @Route("/membre/{id}", name="memberHp", options={"expose"=true},  requirements={"id": "\d+"})
     */
    public function indexAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $membre = $em->getRepository('VoyageBundle:Utilisateurs')
            ->find($id);

        if ($membre === null) {
            return $this->redirectToRoute('homePage');//TODO-404
        }

        $form = $this->createFormBuilder()
            ->setMethod('post')
            ->add('imagefilecover', VichImageType::class, array(
                'required' => true,
            ))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverPic = $form->getData()['imagefilecover'];
            $membre->setImagefilecover($coverPic);
            $em->persist($membre);
            $em->flush();
            return $this->redirectToRoute('memberHp', array('id' => $membre->getId()));
        }

        $nbCountriesVisited = $em->getRepository('VoyageBundle:Etapes')
            ->countCountriesVisitedByUser($membre);

        $this->get('app.js_vars')->userId = $id;

        //Renders an array of countries visited with number of steps in each countries
        $dataCountries = array(['countries', 'nombre d\'etapes']);
        $countries = $em->getRepository('VoyageBundle:Etapes')
            ->getCountriesVisitedByUser($membre);

        foreach ($countries as $country) {
            $countSteps = $em->getRepository('VoyageBundle:Etapes')
                ->getAllNbStepsByCountry($membre,$country);
                if ($countSteps > 0) {
                    $dataCountries[] = array($country->getName(), intval($countSteps));
                }
        }

        $this->get('app.js_vars')->dataCountries = $dataCountries;
        return $this->render('VoyageBundle:Default:membre/layout/membre.html.twig', array(
            'membre' => $membre,
            'nbCountriesVisited' => $nbCountriesVisited,
            'form' => $form->createView(),
        ));
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
