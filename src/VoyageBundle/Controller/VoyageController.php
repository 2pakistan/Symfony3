<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Count;
use VoyageBundle\Form\CreateStepType;
use VoyageBundle\Form\CreateTripType;
use VoyageBundle\Entity\Voyages;
use VoyageBundle\Entity\Etapes;
use VoyageBundle\Entity\Cities;
use VoyageBundle\Entity\States;
use VoyageBundle\Entity\Countries;

class VoyageController extends Controller
{

    /**
     * @Route("/voyage/create", name="createTrip")
     */
    public function creerVoyageAction(Request $request)
    {
        if( null ===  $this->getUser() ){
            return $this->redirectToRoute('fos_user_security_login');
        }

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
            return $this->redirect($this->generateUrl('memberHp', array('id' => $user->getId())));
        }

        return $this->render('VoyageBundle:Default/membre/layout:creerVoyage.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/voyage/{idVoyage}", name="voyagePage", requirements={"idVoyage": "\d+"})"
     */
    public function consultVoyageAction($idVoyage)
    {
        $em = $this->getDoctrine()->getManager();
        $trip = $em->getRepository('VoyageBundle:Voyages')
            ->find($idVoyage);

        if (null === $idVoyage || false === $trip instanceof Voyages) {
            return $this->redirectToRoute('homePage');//TODO-404
        }

        //get the traveller of this trip
        $traveller = $trip->getVoyageur()[0];

        //Increments number of views of this trip
            $trip->setViews($trip->getViews()+1);
            $em->persist($trip);
            $em->flush();

        $steps = $em->getRepository('VoyageBundle:Etapes')
            ->findBy(array('trip' => $idVoyage));

        // MAPS
        $this->get('app.js_vars')->userId = $traveller->getId();

        //Renders an array of countries visited with number of steps in each countries
        $dataCountries = array(['countries', 'nombre d\'etapes']);
        $countries = $traveller->getCountriesVisited();
        foreach ($countries as $country) {
            $countSteps = $em->getRepository('VoyageBundle:Etapes')
                ->getNbStepsByCountry($country, $trip);
            if ($countSteps > 0) {
                $dataCountries[] = array($country->getName(), intval($countSteps[0]));
            }
        }

        $this->get('app.js_vars')->dataCountries = $dataCountries;

        //map with all steps markers
        $stepMarkers = array();
        $stepData = array();
        $stepMedias = array();

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

        $this->get('app.js_vars')->stepMarkers = $stepMarkers;
        $this->get('app.js_vars')->stepData = $stepData;
        $this->get('app.js_vars')->stepMedias = $stepMedias;


        return $this->render('VoyageBundle:Default/membre/layout:consultVoyage.html.twig', array(
            'trip' => $trip,
            'traveller' => $traveller,
            'steps' => $steps));
    }

    /**
     * @Route("/voyage/{idVoyage}/edit", name="createStep")
     */
    public function createStepAction($idVoyage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $trip = $em->getRepository('VoyageBundle:Voyages')
            ->find($idVoyage);
        //if the requested trip doesnt exist or the user that tries to create a step is not the trip owner
        if(  null === $trip || ($trip->getVoyageur()[0]->getId() !== $this->getUser()->getId()) ){
            return $this->redirectToRoute('fos_user_security_login');
        }
        $step = new Etapes();
        $form = $this->createForm(CreateStepType::class, $step);
        $form->handleRequest($request);

        $step->setTrip($trip);

        if ($form->isSubmitted() && $form->isValid()) {

            $medias = $step->getMedias();

            foreach ($medias as $media) {
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

            if ($country instanceof Countries) {

                //if city already exists in DB and user choosed a city //TODO : OPTIMISER TOUT CE BLOC DE MERDE , CREER PROTECTED FONCTION POUR LA CREATION DE VILLES / ETATS
                if ($city instanceof Cities) {
                    $step->setCities($city);

                    //if state already exists
                    if ($state instanceof States) {
                        $step->setState($state);
                    } else {
                        $newState = new States();
                        $newState->setName($stateName);
                        $newState->setCountry($country);
                        $step->setState($newState);
                    }
                } else {
                    //user choosed a city but it's not in DB
                    if ($cityName !== 'undefined') {
                        $newCity = new Cities();
                        $newCity->setName($cityName);
                        $step->setCities($newCity);
                        if ($state instanceof States) {
                            $step->setState($state);
                            $newCity->setState($state);
                        } else {
                            $newState = new States();
                            $newState->setName($stateName);
                            $newState->setCountry($country);
                            $step->setState($newState);
                            $newCity->setState($newState);
                        }
                    } else {
                        $step->setCities(null);

                        //if state alerady exists
                        if ($state instanceof States) {
                            $step->setState($state);
                        } else {
                            //if user choosed a state
                            if ($stateName !== 'undefined') {
                                $newState = new States();
                                $newState->setName($stateName);
                                $newState->setCountry($country);
                                $step->setState($newState);
                            } else {
                                $step->setState(null);
                            }
                        }
                    }
                }
            }

            if ($country instanceof Countries) {
                $step->setCountry($country);

                $user = $this->getUser();
                $countriesVisited = $user->getCountriesVisited()->toArray();

                //if the user have never been in this country yet
                if (!in_array($country, $countriesVisited)) {
                    $user->addCountryVisited($country);
                }
            } else {
                $step->setCountry(null);
                $step->setCities(null);
                $step->setState(null);
            }

            $em->persist($step);
            $em->flush();
            return $this->redirectToRoute('voyagePage', array('idVoyage' => $idVoyage));
        }
        return $this->render('VoyageBundle:Default/membre/layout:createStep.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/deleteTrip", name="deleteTrip" , options={"expose"=true})
     */
    public function deleteTripAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $user = $this->getUser();
            $nbCountries = 0;
            $idTrip = $request->request->get('trip');

            $trip = $em->getRepository('VoyageBundle:Voyages')
                ->find($idTrip);

            $tripSteps = $em->getRepository('VoyageBundle:Etapes')
                ->findBy(array('trip' => $trip));
            $nbSteps = count($tripSteps);
            if (!empty($tripSteps)) {

                foreach ($tripSteps as $step) {
                    $country = $step->getCountry();
                    $nbCountries += 1;
                    //TODO :CONTROL IF THE DELETED STEP IS THE ONLY ONE IN THIS COUNTRY FOR THIS USER
                    //TODO : IF SO REMOVE THIS COUNTRY FROM USER VISITED COUNTRIES
                    $user->removeCountryVisited($country);
                    $em->remove($step);
                }
            }

            $user->removeVoyages($trip);
            $em->persist($user);
            $em->remove($trip);
            $em->flush();

            $response = new JsonResponse();
            return $response->setData(array('success' => true, 'nbCountries' => $nbCountries, 'nbSteps' => $nbSteps));

        } else {
            // TODO : REDIRECT
            $rep = false;
            return $rep;
        }

    }
}
