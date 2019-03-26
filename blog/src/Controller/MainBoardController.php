<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Knp\Component\Pager\PaginatorInterface;
use App\Entity\MainBoard;
use App\Entity\ReBoard;
use App\Repository\MainBoardRepository;

class MainBoardController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * [index 首頁]
     * @param Request $request [當前頁碼]
     * @param MainBoardRepository $repository [使用MainBoardRepository->getIndexPageData()]
     * @param PaginatorInterface $paginator [分頁]
     * @param [type] $singlePageNum [每頁顯示留言數量]
     * @return [array] [分頁顯示資料]
     */
    public function index(Request $request, MainBoardRepository $mainBoardRepository, PaginatorInterface $paginator, Session $session, $singlePageNum)
    {
        $pageNum = $session->get('pageNum', 1);
        $page = $request->query->getInt('page', $pageNum);
        $MainBoard = $mainBoardRepository->getIndexPageData();

        $pagination = $paginator->paginate(
            $MainBoard,
            $page,
            $singlePageNum
        );

        if (($page-1)*$singlePageNum >= $pagination->getTotalItemCount()) {
            $this->get('session')->set('pageNum', $page-1);

            return $this->redirectToRoute('index', ['page' => $page-1]);
        } else {
            $this->get('session')->set('pageNum', $page);
        }

        return $this->render('SymfonyBoard/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/add", name="add")
     * [add 新增主要留言]
     * @param Request $request [主要留言資料]
     */
    public function add(Request $request)
    {
        $data = $request->request->all();
        $nowDate = date("Y-m-d H:i:s");
        $entityManager = $this->getDoctrine()->getManager();

        $MainBoard = new MainBoard();
        $MainBoard->setName($data['name']);
        $MainBoard->setContent($data['content']);
        $MainBoard->setAddTime(new \DateTime($nowDate));
        $entityManager->persist($MainBoard);
        $entityManager->flush();

        return $this->redirectToRoute('index', ['page' => 1]);
    }

    /**
     * @Route("/update/{id}", name="update")
     * [update 修改留言]
     * @param [int] $id [要修改的留言ID]
     * @return [array] [要修改的留言資料]
     */
    public function update($id)
    {
        $message = $this->getDoctrine()->getRepository(MainBoard::class)->find($id);

        return $this->render('SymfonyBoard/update.html.twig', ['message' => $message]);
    }

    /**
     * @Route("/updatePost", name="update_post")
     * [updatePost 修改留言]
     * @param Request $request [抓取要修改留言資料]
     */
    public function updatePost(Request $request)
    {
        $data = $request->request->all();
        $nowDate = date("Y-m-d H:i:s");
        $entityManager = $this->getDoctrine()->getManager();
        $MainBoard = $entityManager->getRepository(MainBoard::class)->find($data['id']);
        $MainBoard->setName($data['name']);
        $MainBoard->setContent($data['content']);
        $MainBoard->setFixTime(new \DateTime($nowDate));
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/reMsg/{id}", name="remsg")
     * [reMsg 回覆留言]
     * @param [int] $id [要回覆留言的ID]
     * @return [array] [要回覆留言的資料]
     */
    public function reMsg($id)
    {
        $message = $this->getDoctrine()->getRepository(MainBoard::class)->find($id);

        return $this->render('SymfonyBoard/reMsg.html.twig', ['message' => $message]);
    }

    /**
     * @Route("/reMsgPost", name="remsg_post")
     * [reMsgPost 回覆留言送出]
     * @param Request $request [回覆留言送出資料]
     */
    public function reMsgPost(Request $request)
    {
        $nowDate = date("Y-m-d H:i:s");
        $data = $request->request->all();
        $MainBoard = $this->getDoctrine()->getRepository(MainBoard::class)->find($data['mainboard_id']);
        $entityManager = $this->getDoctrine()->getManager();
        $ReBoard = new ReBoard();
        $ReBoard->setMainboard($MainBoard);
        $ReBoard->setName($data['name']);
        $ReBoard->setContent($data['content']);
        $ReBoard->setAddTime(new \DateTime($nowDate));
        $entityManager->persist($ReBoard);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/updateReMsg/{id}", name="update_reMsg")
     * [updateReMsg 修改回覆留言]
     * @param [int] $id [要修改的回覆留言ID]
     */
    public function updateReMsg($id)
    {
        $message = $this->getDoctrine()->getRepository(ReBoard::class)->find($id);

        return $this->render('SymfonyBoard/updateReMsg.html.twig', ['message' => $message]);
    }

    /**
     * @Route("/updateReMsgPost", name="update_reMsg_post")
     * [updatePost 修改回覆留言]
     * @param Request $request [抓取要修改回覆留言資料]
     */
    public function updateReMsgPost(Request $request)
    {
        $data = $request->request->all();
        $nowDate = date("Y-m-d H:i:s");
        $entityManager = $this->getDoctrine()->getManager();
        $ReBoard = $entityManager->getRepository(ReBoard::class)->find($data['id']);
        $ReBoard->setName($data['name']);
        $ReBoard->setContent($data['content']);
        $ReBoard->setFixTime(new \DateTime($nowDate));
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * [delete 刪除留言]
     * @param [int] $id [要刪除的留言ID]
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $delete = $em->getRepository(MainBoard::class)->find($id);
        $em->remove($delete);
        $em->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/deleteReMsg/{id}", name="deleteReMsg")
     * [deleteReMsg 刪除回覆]
     * @param [int] $id [要刪除的回覆留言ID]
     */
    public function deleteReMsg($id)
    {
       $em = $this->getDoctrine()->getEntityManager();
       $deleteReMsg = $em->getRepository(ReBoard::class)->find($id);
       $em->remove($deleteReMsg);
       $em->flush();

        return $this->redirectToRoute('index');
    }
}