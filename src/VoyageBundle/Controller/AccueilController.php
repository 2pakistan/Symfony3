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
                                                                         'placeholder' => 'Search for country or a city')
            ))
            ->getForm();

        $nbTripsTotal =  $em->getRepository('VoyageBundle:Voyages')
            ->countTrips();

        $nbStepsTotal =  $em->getRepository('VoyageBundle:Etapes')
            ->countSteps();

        $trips =  $em->getRepository('VoyageBundle:Voyages')
            ->findAll();


        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT COUNT(*) AS nbMembres FROM utilisateurs WHERE utilisateurs.id IN (select DISTINCT(id) from participer) ");
        $statement->execute();
        $results = $statement->fetchAll();
        $nbActiveMembers = $results[0]['nbMembres'];

        return $this->render('VoyageBundle:Default:index.html.twig' ,array('form' => $form->createView(),
                                                                           'membres' => $membres,
                                                                           'membreReviews' => $membreReviews,
                                                                           'voyages'=> $voyages,
                                                                           'nbTripsTotal'=> $nbTripsTotal,
                                                                           'nbStepsTotal'=> $nbStepsTotal,
                                                                           'nbActiveMembers'=> $nbActiveMembers
        ));
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

            $places = $em->getRepository('VoyageBundle:Cities')
                ->getCitiesByString($string);

            $results = [] ;
            if (!empty($countries)) {
                foreach ($countries as $country) {
                    $results[] = $country->getName();
                }
            }

            if (!empty($places)) {
                foreach ($places as $place) {
                    $results[] = $place->getName();
                }
            }

            $placesList = '<ul id="matchList" class="list-group">';
            foreach ($results as $result) {
                $matchStringBold = preg_replace('/(' . $string . ')/i', '<strong>$1</strong>', $result); // Replace text field input by bold one
                $placesList .= '<li class="list-group-item" id="' . $result . '">' . $matchStringBold . '</li>'; // Create the matching list - we put maching name in the ID too
            }
            $placesList .= '</ul>';
            return new JsonResponse(array('placesList' => $placesList));

        }else{
            $placeName = $request->request->get('form')['nomDestination'];
            if($placeName !== ''){
                $city = $em->getRepository('VoyageBundle:Cities')
                    ->findBy(array('name' => $placeName));

                if(empty($city)){
                    $country = $em->getRepository('VoyageBundle:Countries')
                        ->findBy(array('name' => $placeName));
                    $steps = $em->getRepository('VoyageBundle:Etapes')
                        ->findBy(array('country' => $country) , array('createDate' => 'DESC'));
                }else{
                    $steps = $em->getRepository('VoyageBundle:Etapes')
                        ->findBy(array('cities' => $city) , array('createDate' => 'DESC'));
                }
            }else{
                $steps = $em->getRepository('VoyageBundle:Etapes')
                    ->findAllByDate();
            }
            return $this->render('VoyageBundle:Default/membre/layout:searchVoyage.html.twig' ,array(
                'placename' => $placeName ,
                'steps' => $steps));
        }
    }

    /**
     * @Route("/review", name="userReview")
     */
    public function userReview(Request $request){

        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //On recupere les donnees du form
            $review = $form->getData()['review'];
            $rating = $form->getData()['rating'];

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
