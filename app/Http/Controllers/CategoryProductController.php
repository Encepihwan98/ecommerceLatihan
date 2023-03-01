<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\CategoryProduct;
use Illuminate\Support\Facades\Validator;

class CategoryProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $data = CategoryProduct::get();
            $result = [
                'status' => 200,
                'message' => 'getdata succes',
                'data' => $data
            ];
        }catch(Exception $ex){
            $result = [
                'status' => 500,
                'error' => $ex->getMessage(),
            ];
        }

        return response()->json($result, $result['status']);
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
        
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        // dd($request->name);
        try {
            $category = new CategoryProduct();
            $category->name = $request->name;
            $category->save();
            $result = [
                'status' => 200,
                'message' => 'getdata success',
            ];
        }catch(Exception $ex)
        {
            $result = [
                'status' => 500,
                'error' => $ex->getMessage(),
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $category = CategoryProduct::where('id', $id)->firstOrFail();
            // dd($category);
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $category
            ];
        }catch(Exception $ex)
        {
            $result = [
                'status' => 500,
                'message' => $ex->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(),[
            'name' => 'required'
        ]);
        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }
        
        try 
        {
            $category = CategoryProduct::find($request->id);
            $category->name = $request->name;
            $category->save();
            $result = [
                'status' => 200,
                'message' =>  'success update data',
                'data' => $category
            ];
        }catch(Exception $ex)
        {
            $result = [
                'status' => 400,
                'message' =>  $ex->getMessage(),
            ];
        }

        return response()->json($result, $result['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $category = CategoryProduct::where('id', $id)->delete();
            $result = [
                'status' => 200,
                'message' =>  'success delete data',
            ];
        }catch(Exception $ex)
        {
            $result = [
                'status' => 400,
                'message' =>  $ex->getMessage(),
            ];
        }

        return response()->json($result, $result['status']);
    }
}
