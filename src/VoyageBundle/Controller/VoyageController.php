<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use VoyageBundle\Form\CreateStepType;
use VoyageBundle\Form\CreateTripType;
use VoyageBundle\Entity\Voyages;
use VoyageBundle\Entity\Etapes;
use VoyageBundle\Entity\Medias;
use VoyageBundle\Entity\Cities;
use VoyageBundle\Entity\States;
use VoyageBundle\Form\MediasType;


class VoyageController extends Controller
{

    /**
     * @Route("/voyage/create", name="createTrip")
     */
    public function creerVoyageAction(Request $request)
    {
        $trip = new Voyages();
        $form = $this->createForm(CreateTripType::class, $trip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On recupere les donnees du form
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $user->addVoyages($trip);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('valid_voyage', 'Votre voyage a bien été enregistré !');
            return $this->redirect($this->generateUrl('memberVoyages', array('id' => $user->getId())));
        }

        return $this->render('VoyageBundle:Default/membre/layout:creerVoyage.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/voyage/{idVoyage}", name="voyagePage", requirements={"id": "\d+"})"
     */
    public function consultVoyageAction($idVoyage)
    {
        $em = $this->getDoctrine()->getManager();

        $trip = $em->getRepository('VoyageBundle:Voyages')
            ->find($idVoyage);

        if ($trip === null) {
            return $this->redirectToRoute('homePage');//TODO-404
        } else {
            $travellers = $em->getRepository('VoyageBundle:Utilisateurs')
                ->findTravellersByVoyage($idVoyage);
            $steps = $em->getRepository('VoyageBundle:Etapes')
                ->findBy(array('trip' => $idVoyage));
        }

        return $this->render('VoyageBundle:Default/membre/layout:consultVoyage.html.twig', array(
            'trip' => $trip,
            'travellers' => $travellers,
            'steps' => $steps));
    }

    /**
     * @Route("/voyage/{idVoyage}/edit", name="createStep")
     */
    public function createStepAction($idVoyage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $step = new Etapes();
        $form = $this->createForm(CreateStepType::class, $step);
        $form->handleRequest($request);

        $trip = $em->getRepository('VoyageBundle:Voyages')
            ->find($idVoyage);
        $nbSteps = $em->getRepository('VoyageBundle:Etapes')
            ->getNbStepsTrip($idVoyage);

        $step->setTrip($trip);
        $step->setIdetape($nbSteps + 1);

        if ($form->isSubmitted() && $form->isValid()) {

            $medias = $step->getMedias();
            foreach ($medias as $media){
                $media->setIdetape($step);
                $media->setIdvoyage($trip);
            }
            $codeCountry = $step->getCountry();
            $cityName = $step->getCities();
            $stateName = $step->getState();

            $country = $em->getRepository('VoyageBundle:Countries')
                ->findOneBy(array('shortname' => $codeCountry));
            $city = $em->getRepository('VoyageBundle:Cities')
                ->findOneBy(array('name' => $cityName));
            $state = $em->getRepository('VoyageBundle:States')
                ->findOneBy(array('name' => $stateName));

            //if city already exists in DB and user choosed a city
            if ($city instanceof Cities ) {
                $step->setCities($city);

                //if state already exists
                if($state instanceof States){
                    $step->setState($state);
                }else{
                    $newState = new States();
                    $newState->setName($stateName);
                    $newState->setCountry($country);
                    $step->setState($newState);
                }
            }else{
                //user choosed a city but it's not in DB
                if($cityName !== 'undefined'){
                    $newCity = new Cities();
                    $newCity->setName($cityName);
                    $step->setCities($newCity);
                    if($state instanceof States){
                        $step->setState($state);
                        $newCity->setState($state);
                    }else{
                        $newState = new States();
                        $newState->setName($stateName);
                        $newState->setCountry($country);
                        $step->setState($newState);
                        $newCity->setState($newState);
                    }
                }else{
                    $step->setCities(null);

                    //if state alerady exists
                    if($state instanceof States){
                        $step->setState($state);
                    }else{
                        //if user choosed a state
                        if($stateName !== 'undefined'){
                            $newState = new States();
                            $newState->setName($stateName);
                            $newState->setCountry($country);
                            $step->setState($newState);
                        }else{
                            $step->setState(null);
                        }
                    }
                }
            }
            $step->setCountry($country);

            $em->persist($step);
            $em->flush();
        }
        return $this->render('VoyageBundle:Default/membre/layout:createStep.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/test", name="test", requirements={"id": "\d+"})"
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();


        return $this->render('VoyageBundle:Default/membre/layout:vueTest.html.twig', array());
    }
}
