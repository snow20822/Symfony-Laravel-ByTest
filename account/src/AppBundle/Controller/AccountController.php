<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User;
use AppBundle\Entity\Record;

class AccountController extends Controller
{
    /**
     * @Route("/", name="index")
     * [index 首頁]
     * @param Request $request [頁數]
     * @param Session $session [當前頁碼]
     * @return [array] [帳單紀錄+每頁筆數(#用)]
     */
    public function index(Request $request, Session $session)
    {
        $pageNum = $session->get('pageNum', 1);
        $page = $request->query->getInt('page', $pageNum);
        $em = $this->getDoctrine()->getManager();
        $Record = $em->getRepository(Record::class)->getIndexPageData();
        //parameters.yml
        $singlePageNum = $this->container->getParameter('singlePageNum');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $Record,
            $page,
            $singlePageNum
        );

        if (($page-1)*$singlePageNum >= $pagination->getTotalItemCount()) {
            $this->get('session')->set('pageNum', $page-1);

            return $this->redirectToRoute('index', ['page' => $page-1]);
        } else {
            $this->get('session')->set('pageNum', $page);
        }

        return $this->render('Account/index.html.twig', ['pagination' => $pagination, 'singlePageNum' => $singlePageNum]);
    }

    /**
     * @Route("/add", name="add", methods={"Post"})
     * [add 新增帳務資訊]
     * @param Request $request [帳務資料]
     * @param Session $session [當前頁碼]
     */
    public function add(Request $request, Session $session)
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
        $serial = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);

        $Record = new Record();
        $Record->setInOut($data['in_out']);
        $Record->setDescription($data['discription']);
        $Record->setUser($User);
        $Record->setCreatedAt(new \DateTime());
        $Record->setUpdatedAt(new \DateTime());
        $Record->setAfterMoney($finallyMoney);
        $Record->setSerial($serial);

        $User->setMoney($finallyMoney);

        $entityManager->persist($Record);
        $entityManager->flush();

        return $this->redirectToRoute('index', ['page' => 1]);
    }

}