<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use \Swift_Message;
use \Swift_MailTransport;
use \Swift_Mailer;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $host = 'https://mi.ua';
        $uri = '/mi-phones/smartfon-xiaomi-redmi-4-dark-gray-332-gb-ukrainska-versiya/';

        $client = new Client([
            'base_uri' => $host,
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', $uri);

        $pageContent = (string) $response->getBody();

        $pattern = "/<div class=\"product-stock\"><i .*><\/i>(.*)<\/div>/";
        preg_match($pattern, $pageContent, $matches);

        $result = isset($matches[1]) ? $matches[1] : '';

        $response = '<p>'.$result.' <a href="'.$host.$uri.'">В магазин</a></p>';

        $this->sendEmail($response, 'icmus.mail@gmail.com');

        return new Response();
    }

    private function sendEmail($text, $to)
    {
        $message = Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setSubject('Phone is here')
            ->setFrom('icmus.mail@gmail.com')
            ->setTo($to)
            ->setBody($text);

        if ($this->get('mailer')->send($message)) {
            echo 'sended';
        } else {
            echo 'error';
        }
    }
}
