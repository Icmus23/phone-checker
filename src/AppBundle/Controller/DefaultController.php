<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $phoneCheckerService = $this->container->get('phone_checker');

        $response = $phoneCheckerService->check();

        $result = $phoneCheckerService->parseResponse($response);

        $text = $phoneCheckerService->buildEmailText($result);

        if ($phoneCheckerService->isPhoneAvailableForSale($result)) {
            $this->container->get('app')->sendEmail(
                'icmus.mail@gmail.com',
                'icmus.mail@gmail.com',
                'Phone is here',
                $text
            );
        }

        return $this->render(
            'default/index.html.twig'
        );
    }
}
