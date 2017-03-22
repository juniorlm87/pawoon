<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use App\Http\Requests;

class UserproController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		$users = DB::select('select * from user');
		return response()->json(['data' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$msg=array(
			'error'=>'1',
			'message'=>'Invalid Request'
			);
		if ($request->isMethod('post')){ 
			$uuid=uniqid(); 
			$nama=$request->input("nama"); 
			$alamat=$request->input("alamat");
			$ctn=0;
			try{
			$check=DB::select("select count(1) as total from user where lower(nama)=:nama",['nama'=>$nama]);
			foreach($check as $rs){
				$ctn=(int)$rs->total;
			}
			}catch(Exception $erc){
			}
			if($ctn>0){
				$msg=array(
					'error'=>'1',
					'message'=>'Nama has been used by another user'
					);
			}else{
				try{
					
					$insert_users = DB::insert('insert into `user`(`uuid`, `nama`, `alamat`) values(?,?,?)',[$uuid,$nama,$alamat]);
					
					$msg=array(
					'error'=>'0',
					'message'=>'Success insert new data'
					);
				}catch(Exception $er){
					$msg=array(
					'error'=>'1',
					'message'=>'Failed insert new data,error:'.$er->getMessage()
					);
				}
			}
		}
		return response()->json(['data' => $msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$users = DB::select('select * from user where uuid=:id',['id'=>$id]);
		return response()->json(['data' => $users]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
		$msg=array(
			'error'=>'1',
			'message'=>'Invalid Request'
			);
		if ($request->isMethod('post')){ 
			$uuid=$request->input("uuid"); 
			$nama=$request->input("nama"); 
			$alamat=$request->input("alamat");
			$ctn=0;
			try{
			$check=DB::select("select count(1) as total from user where lower(nama)=? and uuid!=?",[$nama,$uuid]);
			foreach($check as $rs){
				$ctn=(int)$rs->total;
			}
			}catch(Exception $erc){
			}
			if($ctn>0){
				$msg=array(
					'error'=>'1',
					'message'=>'Nama has been used by another user'
					);
			}else{
				try{
					
					$insert_users = DB::update('update `user` set `nama`=?, `alamat`=? where uuid=?',[$nama,$alamat,$uuid]);
					
					$msg=array(
					'error'=>'0',
					'message'=>'Success update data'
					);
				}catch(Exception $er){
					$msg=array(
					'error'=>'1',
					'message'=>'Failed update data,error:'.$er->getMessage()
					);
				}
			}
		}
		return response()->json(['data' => $msg]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		$msg=array();
		try{
			$delete=DB::delete("delete from user where uuid=:id",['id'=>$id]);
			$msg=array('error'=>'0','message'=>'success delete');
		}catch(Exception $er){
			$msg=array('error'=>'1','message'=>'failed delete,error :'.$er->getMessage());
		}
		return response()->json(['data' => $msg]);
    }
}
