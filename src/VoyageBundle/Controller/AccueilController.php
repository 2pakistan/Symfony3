<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class AccueilController extends Controller
{
    /**
     * @Route("/", name="homePage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        // On recupere les 3 derniers inscrits
        $membreRepo = $em->getRepository('VoyageBundle:Utilisateurs');
        $membres = $membreRepo->findLastRegistered();

        $voyageRepo = $em->getRepository('VoyageBundle:Voyages');
        $voyages = $voyageRepo->findLastTrips();

        foreach ($voyages as $voyage){
            $travellers = $membreRepo->findTravellersByVoyage($voyage);
            foreach ($travellers as $traveller){
                $voyage->addVoyageur($traveller);
            }
        }
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('searchPlace'))
            ->setMethod('post')
            ->add('nomDestination',TextType::class, array('label'=>'',
                                                          'required' => false ,
                                                          'attr' => array( 'class' => 'form-control',
                                                                         'id' => 'form-search',
                                                                         'placeholder' => 'Search for countries, cities or a people')
            ))
            ->getForm();

        return $this->render('VoyageBundle:Default:index.html.twig' ,array('form' => $form->createView(),
                                                                           'membres' => $membres,
                                                                           'voyages'=> $voyages));
    }

    /**
     * @Route("/search", options={"expose"=true}, name="searchPlace")
     * @Method("POST")
     */
    public function searchDestinationAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        if($request->isXmlHttpRequest()) {

            $string = $request->request->get('string');

            $countries = $em->getRepository('VoyageBundle:Countries')
                ->getCountriesByString($string);
            $cities = $em->getRepository('VoyageBundle:Cities')
                ->getCitiesByString($string);
            $states = $em->getRepository('VoyageBundle:States')
                ->getStatesByString($string);

            if (!empty($countries)) {
                $countriesFound = array();
                foreach ($countries as $country) {
                    $countriesFound[] = $country->getName();
                }
            } else {
                $countriesFound = null;
            }
            if (!empty($cities)) {
                $placesFound = array();
                foreach ($cities as $place) {
                    $placesFound[] = $place->getName();
                }
            } else {
                $placesFound = null;
            }
            if (!empty($states)) {
                $statesFound = array();
                foreach ($states as $state) {
                    $statesFound[] = $state->getName();
                }
            } else {
                $statesFound = null;
            }

            $response = new JsonResponse();
            return $response->setData(array('countries' => $countriesFound ,
                                            'places' => $placesFound,
                                            'states' => $statesFound));
        }else{
            $placeName = $request->request->get('form')['nomDestination'];
            //TODO : SEARCH CLASSIC
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig' ,array('place' => $placeName));
        }
    }

}
