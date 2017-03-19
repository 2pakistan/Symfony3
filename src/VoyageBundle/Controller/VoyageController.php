<?php

namespace VoyageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use VoyageBundle\Entity\Etapes;
use VoyageBundle\Form\CreateStepType;
use VoyageBundle\Form\CreateTripType;
use VoyageBundle\Entity\Voyages;


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

            $this->addFlash('valid_voyage','Votre voyage a bien été enregistré !');
            return $this->redirect($this->generateUrl('memberVoyages' , array('id' => $user->getId())));
        }

        return $this->render('VoyageBundle:Default/membre/layout:creerVoyage.html.twig',array('form' => $form->createView()));
    }

    /**
     * @Route("/voyage/{idVoyage}", name="voyagePage", requirements={"id": "\d+"})"
     */
    public function consultVoyageAction($idVoyage)
    {
        $em = $this->getDoctrine()->getManager();

        $trip = $em->getRepository('VoyageBundle:Voyages')
            ->find($idVoyage);
        $travellers = $em->getRepository('VoyageBundle:Utilisateurs')
            ->findTravellersByVoyage($idVoyage);
        $steps = $em->getRepository('VoyageBundle:Etapes')
            ->findBy(array('idvoyage' => $idVoyage));

        return $this->render('VoyageBundle:Default/membre/layout:consultVoyage.html.twig',array(
                                                                                'trip' => $trip,
                                                                                'travellers' => $travellers,
                                                                                'steps' => $steps));
    }

    /**
     * @Route("/voyage/{idVoyage}/edit", name="createStep")
     */
    public function createStepAction(Request $request){
        $step = new Etapes();
        $form = $this->createForm(CreateStepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On recupere les donnees du form
            /*
            $em = $this->getDoctrine()->getManager();
            $em->persist($step);
            $em->flush();
            */
        }
    return $this->render('VoyageBundle:Default/membre/layout:createStep.html.twig',array('form' => $form->createView()));
    }
}
