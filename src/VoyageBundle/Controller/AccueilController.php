<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use VoyageBundle\Entity\Countries;
use VoyageBundle\Form\ReviewType;

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

        $membreReviews = $em->getRepository('VoyageBundle:Utilisateurs')
            ->findLastReviews();

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('searchPlace'))
            ->setMethod('post')
            ->add('nomDestination', TextType::class, array('label' => '',
                'required' => false,
                'attr' => array('class' => 'form-control',
                    'id' => 'form-search',
                    'placeholder' => 'Search for countries, states or cities')
            ))
            ->getForm();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $lastLogin = $this->getUser()->getLastLogin();
            $nbStepsUnseen = $this->getStepsPublishedSince($lastLogin,$this->getUser());
        }else{
            $nbStepsUnseen = null;
        }

        return $this->render('VoyageBundle:Default:index.html.twig', array('form' => $form->createView(),
            'membres' => $membres,
            'voyages' => $voyages,
            'membreReviews' => $membreReviews,
            'stepsUnseen' => $nbStepsUnseen
        ));
    }

    protected function getStepsPublishedSince($datetime,$user)
    {
        $em = $this->getDoctrine()->getManager();

        $nbSteps = $em->getRepository('VoyageBundle:Etapes')
            ->getNbStepsUnseenSince($datetime,$user);

        return $nbSteps;

    }

    /**
     * @Route("/search", options={"expose"=true}, name="searchPlace")
     * @Method("POST")
     */
    public function searchDestinationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {

            $string = $request->request->get('string');
            /*

$stepsInCountries =  $em->getRepository('VoyageBundle:Etapes')
    ->getStepsByCountryString($string);
$stepsInCities =  $em->getRepository('VoyageBundle:Etapes')
    ->getStepsByCityString($string);
$stepsInStates =  $em->getRepository('VoyageBundle:Etapes')
    ->getStepsByStateString($string);

$placesNames = array();
if(!empty($stepsInCountries)){
    foreach ($stepsInCountries as $step){
        $placesNames[] = $step->getCountry()->getName();
    }
}
if(!empty($stepsInCities)){
    foreach ($stepsInCountries as $step){
        $placesNames[] = $step->getCities()->getName();
    }
}
if(!empty($stepsInStates)){
    foreach ($stepsInCountries as $step){
        $placesNames[] = $step->getState()->getName();
    }
}

*/
            $countries = $em->getRepository('VoyageBundle:Countries')
                ->getCountriesByString($string);
            $cities = $em->getRepository('VoyageBundle:Cities')
                ->getCitiesByString($string);
            $states = $em->getRepository('VoyageBundle:States')
                ->getStatesByString($string);
            $places = array();
            if (!empty($countries)) {
                foreach ($countries as $country => $vals) {
                    $places[]['name'] = $countries[$country]->getName();
                    $places[$country]['type'] = 'country';
                }
            }

            if (!empty($cities)) {
                foreach ($cities as $city => $vals) {
                    $places[]['name'] = $cities[$city]->getName();
                    $places[$city]['type'] = 'city';
                }
            }

            if (!empty($states)) {
                foreach ($states as $state => $vals) {
                    $places[]['name'] = $states[$state]->getName();
                    $places[$state]['type'] = 'state';
                }
            }
            $response = new JsonResponse();
            return $response->setData($places);
        } else {
            $placeName = $request->request->get('form')['nomDestination'];
            $steps = $em->getRepository('VoyageBundle:Etapes')
                ->getStepsByPlaceString($placeName);
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig', array('steps' => $steps, 'placename' => $placeName));
        }
    }

    /**
     *
     * @Route("/review", name="userReview")
     */
    public function reviewAction(Request $request)
    {
        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review = $form->getData()['review'];

            $user = $this->getUser();
            $user->setReview($review);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('VoyageBundle:Default/membre/layout:userReview.html.twig', array('form' => $form->createView()));
    }
}
