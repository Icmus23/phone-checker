<?php

namespace AppBundle\Service;

use GuzzleHttp\Client;
use \Swift_Message;
use \Swift_MailTransport;
use \Swift_Mailer;
use AppBundle\Exception\WrongHttpMethodException;

class AppService
{
    private $httpMethods = ['POST', 'GET', 'PUT', 'DELETE', 'PATCH',];

    private $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendRequest($method, $host, $uri)
    {
        if (!in_array($method, $this->httpMethods)) {
            throw new WrongHttpMethodException($method);
        }
        $client = new Client([
            'base_uri' => $host,
        ]);

        return $client->request($method, $uri);
    }

    public function sendEmail($from, $to, $subject, $text)
    {
        $message = Swift_Message::newInstance()
            ->setContentType('text/html')
            ->setCharset('utf-8')
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($text);

        return $this->mailer->send($message);
    }
}
