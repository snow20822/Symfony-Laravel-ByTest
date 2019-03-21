<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;
use App\Board;

class BoardController extends BaseController
{
    /**
     * [index 首頁]
     * @return [array] [留言&回覆內容]
     */
    public function index()
    {
        $message = Board::where('reId', 0)->get()->toArray();

        foreach ($message as $key => $value) {
            $message[$key]['reMsg'] = [];
            $message[$key]['reMsg'] = Board::where('reId', $value['id'])->get()->toArray();
        }

        return view('index')->with('message', $message);
    }

    /**
     * [add 新增留言]
     * @param Request $request [接收新增資料]
     */
    public function add(Request $request)
    {
        $data = $request::all();
        Board::insert([
            'name' => $data['name'],
            'content' => $data['content'],
            'addtime' => date("Y-m-d H:i:s")
        ]);

        return redirect('/');
    }

    /**
     * [update 修改留言]
     * @param Request $request [接收要修改的資料id]
     * @param [integer] $id [要修改資料的id]
     * @return [array] [顯示將要修改的原始資料]
     */
    public function update(Request $request, $id)
    {
        $data = Board::where('id', $id)->get()->toArray();

        return view('update')->with('message', $data[0]);
    }

    /**
     * [updatePost 送出修改]
     * @param Request $request [接收修改資料]
     */
    public function updatePost(Request $request)
    {
        $data = $request::all();
        Board::where('id', $data['id'])
            ->update([
                'name' => $data['name'],
                'content' => $data['content'],
                'updated_at' => date("Y-m-d H:i:s")
            ]);

        return redirect('/');
    }

    /**
     * [reMsg 回覆留言]
     * @param Request $request [接收被回覆的留言資料]
     * @param [integer] $id [被回覆的留言id]
     * @return [array] [被回覆的留言資料]
     */
    public function reMsg(Request $request, $id)
    {
        $data = Board::where('id', $id)->get()->toArray();

        return view('reMsg')->with('message', $data[0]);
    }

    /**
     * [reMsgPost 回覆留言送出]
     * @param Request $request [接收要回覆的留言資料]
     */
    public function reMsgPost(Request $request)
    {
        $data = $request::all();
        Board::insert([
            'name' => $data['name'],
            'content' => $data['content'],
            'addtime' => date("Y-m-d H:i:s"),
            'reId' => $data['reId']
        ]);

        return redirect('/');
    }

    /**
     * [delete 刪除留言]
     * @param Request $request [接收要刪除的留言id]
     * @return [array] [回傳刪除狀態]
     */
    public function delete(Request $request)
    {
        $id = $request::all();
        Board::where('id', $id)->orWhere('reId', $id)->delete();

        return array('type' => 'success');
    }
}
