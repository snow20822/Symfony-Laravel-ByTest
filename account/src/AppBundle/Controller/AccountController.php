<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Cache\Adapter\MemcachedAdapter;

use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class AccountController extends Controller
{
    /**
     * @Route("/", name="index", methods={"Get"})
     * [index 首頁]
     * @param Request $request [頁數]
     * @param Session $session [當前頁碼]
     * @return [array] [帳單紀錄+每頁筆數(#用)]
     */
    public function index(Request $request, Session $session)
    {
        /*
        //test Memcached
        $client = MemcachedAdapter::createConnection('memcached://localhost');
        $cache  = new MemcachedAdapter($client, $namespace = 'user', $defaultLifetime = 3600);
        $testMemcached = $cache->getItem('testMemcached');
        $testMemcached->set(123);
        $cache->save($testMemcached);
        */

        $pageNum = $session->get('pageNum', 1);
        $page = $request->query->getInt('page', $pageNum);
        $em = $this->getDoctrine()->getManager();
        $Record = $em->getRepository(Record::class)->selectByArray([]);
        //parameters.yml
        $singlePageNum = $this->container->getParameter('singlePageNum');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $Record,
            $page,
            $singlePageNum
        );
        $maxPage = ceil($pagination->getTotalItemCount()/$singlePageNum);
        if ($page > $maxPage and $maxPage >= 1){
            $this->get('session')->set('pageNum', $maxPage);

            return $this->redirectToRoute('index', ['page' => $maxPage]);
        }else if($page < 1){
            $this->get('session')->set('pageNum', 1);

            return $this->redirectToRoute('index', ['page' => 1]);
        }else{
            $this->get('session')->set('pageNum', $page);
        }

        // 创建一个record对象，赋一些例程中的假数据给它
        $record = new Record();
 
        $form = $this->createFormBuilder($record)
            ->add('name', TextType::class, array('label' => '姓名'))
            ->add('in_out', IntegerType::class, array('label' => '存提款金額'))
            ->add('description', TextType::class, array('label' => '描述','required'=> false))
            ->add('save', SubmitType::class, array('label' => '送出'))
            ->getForm();

        return $this->render('Account/index.html.twig', ['pagination' => $pagination, 'singlePageNum' => $singlePageNum, 'form' => $form->createView()]);
    }

    /**
     * @Route("/add", name="add", methods={"Post"})
     * [add 新增帳務資訊]
     * @param Request $request [帳務資料]
     */
    public function add(Request $request)
    {
        $data = $request->request->all();
        $entityManager = $this->getDoctrine()->getManager();
        $User = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['name']]);
        
        if (!$User) {
            $User = new User();
            $User->setName($data['name']);
            $User->setMoney(0);
            $entityManager->persist($User);
            $entityManager->flush();
        }
        $finallyMoney = $User->getMoney();
        $finallyMoney += $data['in_out'];
        $serial = date('Ymd').$User->getId().substr(implode(NULL, array_map('ord', str_split(substr(md5(uniqid()), 7, 13), 1))), 0, 4);
        //$entityManager->lock($User, LockMode::OPTIMISTIC);
        //$entityManager->lock($User, LockMode::PESSIMISTIC_READ);
        //$entityManager->lock($User, LockMode::PESSIMISTIC_WRIT);
        
        $Record = new Record();
        $Record->setInOut($data['in_out']);
        $Record->setDescription($data['description']);
        $Record->setUser($User);
        $Record->setCreatedAt(new \DateTime());
        $Record->setUpdatedAt(new \DateTime());
        $Record->setAfterMoney($finallyMoney);
        $Record->setSerial($serial);
        $entityManager->persist($Record);

        $User->setMoney($finallyMoney);
        $entityManager->flush();

        return $this->redirectToRoute('index', ['page' => 1]);
    }

    /**
     * @Route("/addByForm", name="addByForm", methods={"Post"})
     * [addByForm 新增帳務資訊]
     * @param Request $request [帳務資料]
     */
    public function addByForm(Request $request)
    {
        $getFormData = $request->request->all();
        $data = $getFormData['form'];
        $entityManager = $this->getDoctrine()->getManager();
        $User = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['name']]);
        
        if (!$User) {
            $User = new User();
            $User->setName($data['name']);
            $User->setMoney(0);
            $entityManager->persist($User);
            $entityManager->flush();
        }
        $finallyMoney = $User->getMoney();
        $finallyMoney += $data['in_out'];
        $serial = date('Ymd').$User->getId().substr(implode(NULL, array_map('ord', str_split(substr(md5(uniqid()), 7, 13), 1))), 0, 4);

        $Record = new Record();
        $Record->setInOut($data['in_out']);
        $Record->setDescription($data['description']);
        $Record->setUser($User);
        $Record->setCreatedAt(new \DateTime());
        $Record->setUpdatedAt(new \DateTime());
        $Record->setAfterMoney($finallyMoney);
        $Record->setSerial($serial);
        $entityManager->persist($Record);

        $User->setMoney($finallyMoney);

        $entityManager->flush();

        return $this->redirectToRoute('index', ['page' => 1]);
    }
}