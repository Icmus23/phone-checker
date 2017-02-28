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
    const MI_HOST = 'https://mi.ua';
    const MI_URI = '/mi-phones/smartfon-xiaomi-redmi-4-dark-gray-332-gb-ukrainska-versiya/';

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $response = $this->sendRequest();

        $result = $this->parseResponse($response);

        $text = $this->buildEmailText($result);

        if ($this->isPhoneAvailableForSale($result)) {
            $this->sendEmail($text, 'icmus.mail@gmail.com');
        }

        echo $text;

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

    private function isPhoneAvailableForSale($result)
    {
        if ($result != 'Немає в наявності') {
            return true;
        } else {
            return false;
        }
    }

    private function parseResponse($response)
    {
        $pageContent = (string) $response->getBody();

        $pattern = "/<div class=\"product-stock\"><i .*><\/i>(.*)<\/div>/";
        preg_match($pattern, $pageContent, $matches);

        return isset($matches[1]) ? $matches[1] : '';
    }

    private function sendRequest()
    {
        $client = new Client([
            'base_uri' => self::MI_HOST,
            'timeout'  => 2.0,
        ]);

        return $client->request('GET', self::MI_URI);
    }

    private function buildEmailText($result)
    {
        return '<p>'.$result.' <a href="'.self::MI_HOST.self::MI_URI.'">В магазин</a></p>';
    }
}
