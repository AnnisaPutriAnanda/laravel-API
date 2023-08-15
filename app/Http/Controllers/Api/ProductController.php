<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Validator;
use Exception;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = product::paginate(10);
        return response()->json([
            'msg' => 'Data show', 'data' => $product
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    { 

        $rules=array(
            'username' => 'required|unique:customer',
            'number' => 'required|numeric',
            'name' => 'required|unique:products',
            'desc' => 'required',
            'user_id' => 'required|numeric',
        );
        $messages=array(

            'username.required' => 'Cannot create data, please add field for username: field_value.',
            'username.unique' => 'Customer name should be unique.',
            'number.required' => 'Cannot create data, Please add field for number: field_value.',


            'name.required' => 'Cannot create data, please add field for name: field_value.',
            'name.unique' => 'Product name should be unique.',
            'desc.required' => 'Cannot create data, Please add field for desc: field_value.',
            'user_id.required' => 'Cannot create data, Please add field for user_id: field_value.',
        );
 
    try{

        $validator=Validator::make($request->all(),$rules,$messages);
        if($validator->fails())
        {
            $messages=$validator->messages();
            return response()->json(["messages"=>$messages], 422);
        }

            DB::beginTransaction();

            $customer = new Customer();
            $customer->username = $request->username;
            $customer->number = $request->number;
            $customer->save();
            
            $product = new product();
            $product->name = $request->name;
            $product->desc = $request->desc;
            $product->user_id = $request->user_id;
            $product->save();

            DB::commit();
         }
         catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $e->getMessage(),
            ], 500);
            
          }


        // try {
           
            // $rules=array(
            //     'name' => 'required|unique:products',
            //     'desc' => 'required',
            // );
            // $messages=array(
            //     'name.required' => 'Cannot create data, please add field for name: field_value.',
            //     'name.unique' => 'Product name should be unique.',
            //     'desc.required' => 'Cannot create data, Please add field for desc: field_value.',
            // );
            // $validator=Validator::make($request->all(),$rules,$messages);
            // if($validator->fails())
            // {
            //     $messages=$validator->messages();
            //     return response()->json(["messages"=>$messages], 422);
            // }
            // $product = product::create([
            //     'name'   => $request->get('name'),
            //     'desc'    => $request->get('desc'),
            //     ]
            // );
        //     return response()->json([
        //         'status' => 'success',
        //         'msg'    => 'Okay',
        //         'data'   => $product,
                
        //     ], 201);
    
        // } catch (Exception $e) {

        //         return response()->json([
        //             'status' => 'error',
        //             'msg'    => 'Error',
        //             'errors' => $e->getMessage(),
        //         ], 500);
        // }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        return response()->json([
            'msg' => 'Data show', 'data' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {

        try{

            $rules=array(
                'name' => 'required|unique:products',
                'desc' => 'required',
            );
            $messages=array(
                'name.required' => 'Cannot create data, please add field for name: field_value.',
                'name.unique' => 'Product name should be unique.',
                'desc.required' => 'Cannot create data, Please add field for desc: field_value.',
            );
            $validator=Validator::make($request->all(),$rules,$messages);
            if($validator->fails())
            {
                $messages=$validator->messages();
                return response()->json(["messages"=>$messages], 422);
            }

        $product->name = $request->name;
        $product->desc = $request->desc;
        $product->save();
        return response()->json([
            'msg' => 'Data updated', 'data' => $product
        ]);
    } catch (Exception $e) {

        return response()->json([
            'status' => 'error',
            'msg'    => 'Error',
            'errors' => $e->getMessage(),
        ], 500);
}

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        $product->delete();
        return response()->json([
            'msg' => 'Data deleted'
        ]);
    }
}
