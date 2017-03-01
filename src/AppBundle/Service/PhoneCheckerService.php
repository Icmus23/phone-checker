<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneCheckerService
{
    const NOT_AVAILABLE = 'Немає в наявності';

    const MI_HOST = 'https://mi.ua';
    const MI_URI = '/mi-phones/smartfon-xiaomi-redmi-4-dark-gray-332-gb-ukrainska-versiya/';

    private $appService;

    public function __construct(AppService $appService)
    {
        $this->appService = $appService;
    }

    public function isPhoneAvailableForSale($result)
    {
        if ($result != self::NOT_AVAILABLE) {
            return true;
        } else {
            return false;
        }
    }

    public function parseResponse($response)
    {
        $pageContent = (string) $response->getBody();

        $pattern = "/<div class=\"product-stock\"><i .*><\/i>(.*)<\/div>/";
        preg_match($pattern, $pageContent, $matches);

        return isset($matches[1]) ? $matches[1] : '';
    }

    public function buildEmailText($result)
    {
        return '<p>'.$result.' <a href="'.self::MI_HOST.self::MI_URI.'">В магазин</a></p>';
    }

    public function check()
    {
        return $this->appService->sendRequest('GET', self::MI_HOST, self::MI_URI);
    }
}
