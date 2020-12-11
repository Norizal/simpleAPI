<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Validator;
use App\Item;


class ItemController extends Controller
{
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error'=> true, 'error_message' => $validator->errors()], 401);
        }else{
            $name = $request->input('name');
            $amount = $request->input('amount');

            $user = Auth::user();
            $userId = $user->id;
           
            $dataItem = new Item;
            $dataItem->name = $name;
            $dataItem->amount = $amount;
            $dataItem->userId = $userId;
            if($dataItem->save()){
                $success['name'] = $dataItem->name;
                $success['amount'] = $dataItem->amount;
                $success['name_user'] = $user->name;
                return response()->json(['error'=>false, "message"=> $success], 200);

            }else{
                return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);
            }        
                

        }

        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);

        
    }


    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error'=> true, 'error_message' => $validator->errors()], 401);
        }else{
            $name = $request->input('name');
            $amount = $request->input('amount');

            $user = Auth::user();
            $userId = $user->id;
           
            $itemUser = Item::where('userId', $userId)->first();

            if($itemUser){
                Item::where('id', $id)->update(array(
                    'name' => $name,
                    'amount' => $amount
                ));
                return response()->json(['error'=>false, "message"=> "Berjaya update!"], 200);
            }else{
                return response()->json(['error'=> true, 'error_message' => "Tiada item milik user!"], 401);

            }

           
                

        }

        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);

        
    }

    public function getItemById($id){
        $user = Auth::user();
        $userId = $user->id;
        
        $itemUser = Item::where('userId', $userId)->first();

        if($itemUser){
            $itemById = Item::where('id', $id)->get();
            return response()->json(['error'=>false, "message"=> $itemById], 200);
        }else{
            return response()->json(['error'=> true, 'error_message' => "Tiada item milik user!"], 401);

        }
        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);
        
    }


    public function getItem(){
        
            $items = Item::all();
            if($items){
                return response()->json($items, 200);

            }else{
                return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);

            }
            
        
       
        
    }

    public function getItemTest(){
       
            $items = Item::all();
            if($items){

                return response()->json(['error'=>false, "message"=> $items], 200);
        }else{
            return response()->json(['error'=> true, 'error_message' => "Tiada item milik user!"], 401);

        }
        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);
        
    }



    public function deleteItem($id){
        $user = Auth::user();
        $userId = $user->id;
        
        $itemUser = Item::where('userId', $userId)->first();

        if($itemUser){
            Item::where('id', $id)->delete();
            return response()->json(['error'=>false, "message"=> "Item delete!"], 200);
        }else{
            return response()->json(['error'=> true, 'error_message' => "Tiada item milik user!"], 401);

        }
        return response()->json(['error' => true, 'error_message' => 'Internal Server Error'], 500);
        
    }

}
