<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use ImageResize;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $serch = $request->serch;
        $show = $request->show;
        try {
            $data = Product::when($serch, function ($query, $serch) {
                $query->where('name', 'like', "%" . $serch . "%");
            })->paginate($show);
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $ex) {
            $result = [
                'status' => 400,
                'message' => $ex->getMessage(),
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
            'name' => 'required',
            'categoryId' => 'required',
            'price' => 'required|integer',
            'status' => 'required',
            'detail' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        try {
            $filename = "default.png";

            if ($request->image) {
                $file = $request->file('image');
                $filename = date('YmHi') . '-' . $file->getClientOriginalName();
                
                $file->move(public_path('public/upload/product/'), $filename);
            }

            $store = new Product();
            $store->name = $request->name;
            $store->categoryId = $request->categoryId;
            $store->price = $request->price;
            $store->status = $request->status;
            $store->detail = $request->detail;
            $store->image = $filename;
            $store->save();
            $result = [
                'status' => 200,
                'message' => 'success'
            ];
        } catch (Exception $ex) {
            $result = [
                'status' => 200,
                'message' => $ex->getMessage()
            ];
        }

        return response()->json([$result, $result['status']]);
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
            $data = Product::where('id', $id)->firstOrFail();
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
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
        //
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
            'name' => 'required',
            'categoryId' => 'required',
            'price' => 'required|integer',
            'status' => 'required',
            'detail' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        try 
        {
            $data = Product::find($id);
            $image = $request->file('image');
            if(isset($image))
            {
                $path = public_path('public\upload\product/'. $data->image);
                unlink($path);   
                $filename = date('YmHi') . '-' . $image->getClientOriginalName();
                $image->move(public_path('public/upload/product/'), $filename);
            }else {
                $filename = $data->image;
            }

            $data->name = $request->name;
            $data->categoryId = $request->categoryId;
            $data->price = $request->price;
            $data->status = $request->status;
            $data->detail = $request->detail;
            $data->image = $filename;
            $data->update();
            $result = [
                'status' => 200,
                'message' => 'success'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $data = Product::find($id);
            
            $path = public_path('public\upload\product/'. $data->image);
            unlink($path);

            $data->delete();
            
            $result = [
                'status' => 200,
                'message' => 'success delete'
            ];
        }catch(Exception $ex)
        {
            $result = [
                'status' => 400,
                'message' => $ex->getMessage()
            ];
        }

        return response()->json($result, $result['status']);
    }
}
