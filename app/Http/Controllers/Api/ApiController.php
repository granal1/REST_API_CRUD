<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\ApiAddItem;
use App\Http\Requests\ApiEditItem;

use App\Models\Item;

class ApiController extends Controller
{
    public function getAllItems()
    {
        return response()->json(Item::get(), 200);
    }

    public function getItemById(int $id)
    {
        $item = Item::find($id);
        if (is_null($item)) {
            return response()->json(['error' => true, 'message' => 'Item not found'], 404);
        }
        $item['history'] = json_decode($item['history'], true);
        return response()->json($item, 200);
    }

    public function addItem(ApiAddItem $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $item = new Item();
            $item->name = $data['name'];
            $item->phone = $data['phone'];
            $item->key = $data['key'];
            $item->save();
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
        return response()->json($item, 201);
    }

    public function editItem(ApiEditItem $request, $id)
    {
        $data = $request->validated();
        $item = Item::find($id);
        if (is_null($item)) {
            return response()->json(['error' => true, 'message' => 'Item not found'], 404);
        }

        $history = json_decode($item['history'], true);
        if(empty($history)){
            $history = [];
        }

        $current = array(
            'name' => $item['name'], 
            'phone' => $item['phone'], 
            'key' => $item['key'], 
            'updated_at' => $item['updated_at']
        );

        array_push($history, $current);
        $data['history'] = json_encode($history);

        try {
            DB::beginTransaction();
            $item->update($data);
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
        $item['history'] = json_decode($item['history'], true);
        return response()->json($item, 200);
    }

    public function deleteItem(Request $request, $id)
    {
        $item = Item::find($id);
        if (is_null($item)) {
            return response()->json(['error' => true, 'message' => 'Item not found'], 404);
        }
        $item->delete();
        return response()->json('', 204);
    }
}
