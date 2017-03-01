<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @Route("/api/1.0")
 */
class ApiV1Controller extends Controller
{
    /**
     * @Route("/get_phone_status")
     * @Method("GET")
     */
    public function getPhoneStatusAction(Request $request)
    {
        $phoneCheckerService = $this->container->get('phone_checker');

        $response = $phoneCheckerService->check();

        $result = $phoneCheckerService->parseResponse($response);

        $this->container->get('monolog.logger.api')->info($result);

        if ($phoneCheckerService->isPhoneAvailableForSale($result)) {
            $text = $phoneCheckerService->buildEmailText($result);
            $this->container->get('app')->sendEmail(
                'icmus.mail@gmail.com',
                'icmus.mail@gmail.com',
                'Phone is here',
                $text
            );
        }

        $response = new JsonResponse();
        $response->setData(array(
            'status' => $result
        ));

        return $response;
    }
}
