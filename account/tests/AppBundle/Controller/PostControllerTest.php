<?php 
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostControllerTest extends WebTestCase
{
    public function testHtmlPost()
    {
        $client = static::createClient( 
        array(),
        array(
            'HTTP_HOST' => 'account.com', //dependent on server        
        ));

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Send')->form();

        // 设置一些值
        $form['name'] = '123';
        $form['in_out'] = 1;
        $form['description'] = 'test';
        // 提交表单
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testSymfonyFormPost()
    {
        $client = static::createClient( 
        array(),
        array(
            'HTTP_HOST' => 'account.com', //dependent on server        
        ));

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('form[save]')->form();

        // 设置一些值
        $form['form[name]'] = 1;
        $form['form[in_out]'] = 1;
        $form['form[description]'] = 'Hey there!';
        // 提交表单
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
    }
}