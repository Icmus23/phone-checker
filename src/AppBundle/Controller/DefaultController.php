<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $host = 'https://mi.ua';
        $uri = '/mi-phones/smartfon-xiaomi-redmi-4-dark-gray-332-gb-ukrainska-versiya/';
        // client
        $client = new Client([
            'base_uri' => $host,
            'timeout'  => 2.0,
        ]);
        // response
        $response = $client->request('GET', $uri);
        // page content
        $pageContent = (string) $response->getBody();

        $pattern = "/<div class=\"product-stock\"><i .*><\/i>(.*)<\/div>/";
        preg_match($pattern, $pageContent, $matches);

        $result = isset($matches[1]) ? $matches[1] : '';

        return new Response($result.' <a href="'.$host.$uri.'">В магазин</a>');
    }
}
