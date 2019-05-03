<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\DBALException;
use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class AccountController extends Controller
{
    /**
     * @Route("/", name = "index", methods = {"GET"})
     * [index 首頁]
     * @param Request $request [頁數]
     * @param Session $session [當前頁碼]
     * @return [array] [帳單紀錄+每頁筆數]
     */
    public function index(Request $request, Session $session)
    {
        $pageNum = $session->get('pageNum', 1);
        $page = $request->query->getInt('page', $pageNum);
        $em = $this->getDoctrine()->getManager();
        $Record = $em->getRepository(Record::class)->selectByArray([]);
        $singlePageNum = $this->container->getParameter('singlePageNum');
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $Record,
            $page,
            $singlePageNum
        );

        $maxPage = ceil($pagination->getTotalItemCount()/$singlePageNum);

        if ($page > $maxPage and $maxPage >= 1) {
            $this->get('session')->set('pageNum', $maxPage);

            return $this->redirectToRoute('index', ['page' => $maxPage]);
        } else if($page < 1) {
            $this->get('session')->set('pageNum', 1);

            return $this->redirectToRoute('index', ['page' => 1]);
        } else {
            $this->get('session')->set('pageNum', $page);
        }

        $record = new Record();
        $form = $this->createFormBuilder($record)
            ->add('name', TextType::class, ['label' => '姓名'])
            ->add('in_out', IntegerType::class, ['label' => '存提款金額'])
            ->add('description', TextType::class, ['label' => '描述','required'=> false])
            ->add('save', SubmitType::class, ['label' => '送出'])
            ->getForm();

        $response = new JsonResponse($pagination->getItems());

        return $this->render('Account/index.html.twig', [
            'response' => $response,
            'pagination' => $pagination,
            'singlePageNum' => $singlePageNum,
            'form' => $form->createView()
       ]);
    }

    /**
     * @Route("/add", name = "add", methods = {"Post"})
     * [add 新增帳務資訊]
     * @param Request $request [帳務資料]
     */
    public function add(Request $request)
    {
        $data = $request->request->all();
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->getConnection()->beginTransaction();
        try {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['name']]);

            if (!$user) {
                $user = new User();
                $user->setName($data['name']);
                $user->setMoney(0);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            $finallyMoney = $user->getMoney();
            $finallyMoney += $data['in_out'];

            if ($finallyMoney < 0) {
                return $this->redirectToRoute('index', ['page' => 1]);
            }

            if (isset($data['serial'])) {
                $serial = $data['serial'];
            } else {
                $serial = date('Y').strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
            }

            $date = new \DateTime('now');
            $record = new Record();
            $record->setInOut($data['in_out']);
            $record->setDescription($data['description']);
            $record->setUser($user);
            $record->setCreatedAt($date);
            $record->setUpdatedAt($date);
            $record->setAfterMoney($finallyMoney);
            $record->setSerial($serial);
            $entityManager->persist($record);

            $user->setMoney($finallyMoney);

            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (DBALException $e) {
            $entityManager->getConnection()->rollback();
        }

        return $this->redirectToRoute('index', ['page' => 1]);
    }

    /**
     * @Route("/addByForm", name = "addByForm", methods = {"Post"})
     * [addByForm 新增帳務資訊]
     * @param Request $request [帳務資料]
     */
    public function addByForm(Request $request)
    {
        $getFormData = $request->request->all();
        $data = $getFormData['form'];
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->getConnection()->beginTransaction();

        try {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['name']]);

            if (!$user) {
                $user = new User();
                $user->setName($data['name']);
                $user->setMoney(0);
                $entityManager->persist($user);
                $entityManager->flush();
            }

            $finallyMoney = $user->getMoney();
            $finallyMoney += $data['in_out'];

            if ($finallyMoney < 0) {
                return $this->redirectToRoute('index', ['page' => 1]);
            }

            if (isset($data['serial'])) {
                $serial = $data['serial'];
            } else {
                $serial = date('Y').strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(0, 99));
            }

            $date = new \DateTime('now');
            $record = new Record();
            $record->setInOut($data['in_out']);
            $record->setDescription($data['description']);
            $record->setUser($user);
            $record->setCreatedAt($date);
            $record->setUpdatedAt($date);
            $record->setAfterMoney($finallyMoney);
            $record->setSerial($serial);
            $entityManager->persist($record);

            $user->setMoney($finallyMoney);

            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (DBALException $e) {
            $entityManager->getConnection()->rollback();
        }

        return $this->redirectToRoute('index', ['page' => 1]);
    }

    /**
     * @Route("/addByRedis", name = "addByRedis", methods = {"Post"})
     * [addByRedis 新增帳務資訊ByRedis]
     * @param Request $request [帳務資料]
     */
    public function addByRedis(Request $request)
    {
        $client = $this->get('snc_redis.default');
        $data = $request->request->all();
        $date = date("Y-m-d H:i:s");
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['name' => $data['name']]);
        $id = $user->getId();

        $userData = 'userData' . $id;
        $updateList = 'updateList' . $id;
        $client->hSetNx($userData, 'id', $id);
        $client->hSetNx($userData, 'version', $user->getVersion());
        $client->hSetNx($userData, 'money', $user->getMoney());

        if (($client->hGet($userData, 'money') + $data['in_out']) < 0) {
            return new Response("Insufficient balance");
        } else {
            $serial = date('Y') . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
            $client->multi();
            $client->hIncrBy($userData, 'money', $data['in_out']);
            $client->hIncrBy($userData, 'version', 1);
            $client->exec();

            $updateArray = [
                'user_id' => $id,
                'in_out' => $data['in_out'],
                'description' => $data['description'],
                'after_money' => $client->hGet($userData, 'money'),
                'serial' => $serial,
                'created_at' => $date,
                'updated_at' => $date,
                'version' => $client->hGet($userData, 'version')
            ];

            $updateJson = json_encode($updateArray);

            $client->rPush($updateList, $updateJson);

            return new Response("addByRedis success");
        }
    }
}