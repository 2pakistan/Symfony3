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

        if($request->isXmlHttpRequest()) { // pour vérifier la présence d'une requete Ajax
            $string = $request->request->get('string');

            $countries = $em->getRepository('VoyageBundle:Destination')
                ->getCountriesByString($string);
            $places = $em->getRepository('VoyageBundle:Destination')
                ->getPlacesByString($string);

            if (!empty($countries)) {
                $countriesFound = array();
                foreach ($countries as $country) {
                    $countriesFound[] = $country->getPays();
                }
            } else {
                $countriesFound = null;
            }
            if (!empty($places)) {
                $placesFound = array();
                foreach ($places as $place) {
                    $placesFound[] = $place->getNomDestination();
                }
            } else {
                $placesFound = null;
            }

            $response = new JsonResponse();
            return $response->setData(array('countries' => $countriesFound ,
                                            'places' => $placesFound));
        }else{
            $placeName = $request->request->get('form')['nomDestination'];
            if($placeName !== ''){
                $place = $em->getRepository('VoyageBundle:Destination')
                    ->findOneBy(array('nomdestination' => $placeName));
                if($place == null){
                    $place = $em->getRepository('VoyageBundle:Destination')
                        ->findOneBy(array('pays' => $placeName));
                }
                $idPlace = $place->getIddestination();
                $voyages = $em->getRepository('VoyageBundle:Etapes')
                    ->findBy(array('iddestination' => $idPlace));
            }
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig' ,array('place' => $placeName ,
                'voyages' => $voyages));
        }
    }

}
