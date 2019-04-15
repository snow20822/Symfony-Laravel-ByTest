<?php 
namespace Tests\AppBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountFunctionalTest extends WebTestCase
{
    /**
     * [testIndex 測試首頁]
     */
    public function testIndex()
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com', //dependent on server        
        ]);

        $crawler = $client->request('GET', '/?page=5000');

        $this->assertTrue($client->getResponse()->isRedirect());
    }

    /**
     * [testAdd 測試新增帳務資訊]
     * @param  [string] $name [姓名]
     * @param  [float] $in_out [變動金額]
     * @param  [text] $description [註解]
     * @dataProvider additionProvider
     */
    public function testAdd($name, $in_out, $description)
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com', //dependent on server        
        ]);

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Send')->form();

        // 设置一些值
        $form['name'] = $name;
        $form['in_out'] =$in_out;
        $form['description'] = $description;
        // 提交表单
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [additionProvider 測試新增所用參數]
     * @return [array] [測試用參數]
     */
    static function additionProvider()
    {
        return [
             [date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(md5(uniqid()), 7, 13), 1))), 0, 4), 3, 'testAdd']
        ];
    }

    /**
     * [testSymfonyFormPost 測試新增帳務資訊ByForm]
     * @param  [string] $name [姓名]
     * @param  [float] $in_out [變動金額]
     * @param  [text] $description [註解]
     * @dataProvider addByFormProvider
     */
    public function testSymfonyFormPost($name, $in_out, $description)
    {
        $client = static::createClient( 
        [],
        [
            'HTTP_HOST' => 'account.com', //dependent on server        
        ]);

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('form[save]')->form();

        // 设置一些值
        $form['form[name]'] = $name;
        $form['form[in_out]'] = $in_out;
        $form['form[description]'] = $description;
        // 提交表单
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect('/?page=1'));
    }

    /**
     * [addByFormProvider 測試新增帳務資訊ByForm所用參數]
     * @return [array] [測試新增帳務資訊ByForm用參數]
     */
    static function addByFormProvider()
    {
        return [
             [date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(md5(uniqid()), 7, 13), 1))), 0, 4), 3, 'testSymfonyFormPost']
        ];
    }
}