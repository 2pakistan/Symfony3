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
            $places = array();

            $countries = $em->getRepository('VoyageBundle:Countries')
                ->getCountriesByString($string);
            $cities = $em->getRepository('VoyageBundle:Cities')
                ->getCitiesByString($string);
            $states = $em->getRepository('VoyageBundle:States')
                ->getStatesByString($string);


            if (!empty($countries)) {
                foreach($countries as $country => $vals){
                    $places[]['name'] = $countries[$country]->getName();
                    $places[$country]['type'] = 'country';
                }
            }

            if (!empty($cities)) {
                foreach($cities as $city => $vals){
                    $places[]['name'] = $cities[$city]->getName();
                    $places[$city]['type'] = 'city';
                }
            }

            if (!empty($states)) {
                foreach($states as $state => $vals){
                    $places[]['name'] = $states[$state]->getName();
                    $places[$state]['type'] = 'state';
                }
            }
            $response = new JsonResponse();
            return $response->setData($places);
        }else{
            $placeName = $request->request->get('form')['nomDestination'];
            //TODO : SEARCH CLASSIC
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig' ,array('place' => $placeName));
        }
    }

}
