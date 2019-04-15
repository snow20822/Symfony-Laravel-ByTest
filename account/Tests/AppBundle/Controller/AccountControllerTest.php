<?php 
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends WebTestCase
{
    /**
     * [testIndex 測試首頁頁碼小於1]
     */
    public function testIndexPageNum()
    {
        $client = static::createClient( 
        [],
        ['HTTP_HOST' => 'account.com', //dependent on server        
        ]);

        $crawler = $client->request('GET', '/?page=0');

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

}