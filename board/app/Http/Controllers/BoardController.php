<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Request;
use DB;
use App\Board;

class BoardController extends BaseController
{
	/**
	 * [index description]
	 * @param  Request $request [description]
	 * @return [type]           [array]
	 */
	public function index(Request $request)
	{
		$message =  DB::table('board')->where('reId',0)->get()->toArray();
		$message = array_map(function ($value) {
				    return (array)$value;
				}, $message);
		foreach ($message as $key => $value) {
			$message[$key]['reMsg'] =array();
			$message[$key]['reMsg'] =  DB::table('board')->where('reId',$value['id'])->get()->toArray();
			$message[$key]['reMsg'] = array_map(function ($value) {
				    return (array)$value;
				}, $message[$key]['reMsg']);
		}
	    return view('index')
	    ->with('message', $message);
	}

	public function add(Request $request)
	{
		$data = $request::all();
		DB::table('board')->insert([
		    'name' => $data['name'],
		    'content' => $data['content'],
		    'addtime' => date("Y-m-d H:i:s"),
		]);
	    return redirect('/');
	}

	public function update(Request $request,$id)
	{
		$data =  DB::table('board')
			->where('id',$id)
			->get()
			->toArray();
	    return view('update')
	    ->with('message', $data[0]);
	}

	public function updatePost(Request $request)
	{
		$data = $request::all();
		DB::table('board')
            ->where('id', $data['id'])
            ->update([
			'name' => $data['name'],
			'content' => $data['content'],
			'updated_at' => date("Y-m-d H:i:s"),
			]);
	    return redirect('/');
	}

	public function reMsg(Request $request,$id)
	{
		$data =  DB::table('board')
			->where('id',$id)
			->get()
			->toArray();
	    return view('reMsg')
	    ->with('message', $data[0]);
	}

	public function reMsgPost(Request $request)
	{
		$data = $request::all();
		DB::table('board')
            ->insert([
			'name' => $data['name'],
			'content' => $data['content'],
			'addtime' => date("Y-m-d H:i:s"),
			'reId' => $data['reId'],
			]);
	    return redirect('/');
	}

	public function delete(Request $request)
	{
		$id = $request::all();
		DB::table('board')
			->where('id', $id)
			->delete();
	    return array('type'=>'success');
	}
}
