<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $membreReviews = $membreRepo->findLastReviews();
        $voyageRepo = $em->getRepository('VoyageBundle:Voyages');
        $voyages = $voyageRepo->findLastTrips();

        foreach($membres as $membre){
            $nbCountries = $em->getRepository('VoyageBundle:Etapes')
                ->countCountriesVisitedByUser($membre);
            $membre->setNbCountries($nbCountries);
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
                                                                           'membreReviews' => $membreReviews,
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

            $countries = $em->getRepository('VoyageBundle:Countries')
                ->getCountriesByString($string);
            $states = $em->getRepository('VoyageBundle:States')
                ->getStatesByString($string);
            $places = $em->getRepository('VoyageBundle:Cities')
                ->getCitiesByString($string);

            if (!empty($countries)) {
                $countriesFound = array();
                foreach ($countries as $country) {
                    $countriesFound[] = $country->getName();
                }
            } else {
                $countriesFound = null;
            }
            if (!empty($places)) {
                $placesFound = array();
                foreach ($places as $place) {
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
                                            'places' => $placesFound ,
                                            'states' => $statesFound));
        }else{
            $placeName = $request->request->get('form')['nomDestination'];
            if($placeName !== ''){
                $destination = $em->getRepository('VoyageBundle:Destination')
                    ->findOneBy(array('nomdestination' => $placeName));
                if($destination == null){
                    $destination = $em->getRepository('VoyageBundle:Destination')
                        ->findOneBy(array('pays' => $placeName));
                }
                $idPlace = $destination->getIddestination();
                $voyages = $em->getRepository('VoyageBundle:Etapes')
                    ->findBy(array('iddestination' => $idPlace));
            }
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig' ,array('place' => $placeName ,
                'voyages' => $voyages));
        }
    }

    /**
     * @Route("/review", name="userReview")
     */
    public function userReview(Request $request){

        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $review = $form->getData()['review'];
            $rating = $form->getData()['rating'];

            //On recupere les donnees du form
            $user = $this->getUser();
            $user->setReview($review);
            $user->setRating($rating);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('VoyageBundle:Default/membre/layout:userReview.html.twig' ,array(
            'form' => $form->createView(),
        ));

    }

}
