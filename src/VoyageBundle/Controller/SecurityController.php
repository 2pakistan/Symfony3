<?php

namespace VoyageBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;



class SecurityController extends BaseController
{

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function loginAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if( null !== $this->getUser()){
            return $this->redirectToRoute('homepage');
        }

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        //custom parameter
        $currentRoute = $request->get('_route');

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
            'current_route' => $currentRoute
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return Response
     */
    protected function renderLogin(array $data)
    {
        $route = $data['current_route'];


        if($route === 'fos_user_security_login'){
            $template = '@FOSUser/Security/login.html.twig';
        }else{
            $template = '@FOSUser/Security/login_embed.html.twig';
        }
        return $this->render($template, $data);
    }

    /**
     * @Route("/checkUsername", name="checkUsername" , options={"expose"=true})
     */
    public function checkUsernameAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $username = $request->request->get('username');
            $user = $em->getRepository('VoyageBundle:Utilisateurs')
                ->findOneBy(array('username' => $username));

            if(null === $user){
                $available = true;
            }else{
                $available = false;
            }
            $response = new JsonResponse();
            return $response->setData(array('available' => $available));

        } else {
            // TODO : REDIRECT
            $rep = false;
            return $rep;
        }

    }


    /**
     * @Route("/checkMail", name="checkMail" , options={"expose"=true})
     */
    public function checkMail(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $email = $request->request->get('email');
            $user = $em->getRepository('VoyageBundle:Utilisateurs')
                ->findOneBy(array('email' => $email));

            if(null === $user){
                $available = true;
            }else{
                $available = false;
            }
            $response = new JsonResponse();
            return $response->setData(array('available' => $available));

        } else {
            // TODO : REDIRECT
            $rep = false;
            return $rep;
        }

    }
}
