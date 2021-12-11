<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/order/discounts/{id}",
     *      tags={"DISCOUNT"},
     *      summary="Discount Order.",
     *      operationId="discountOrder",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *         )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *      ),
     *      @OA\Response(
     *         response=500,
     *         description="Not successful operation"
     *      )
     * )
     */
    public function index($id)
    {
        try {
            $products = \App\Models\OrderItem::where('order_id',$id)->get();
            $order    = \App\Models\Order::find($id);
            if(!$order){
                return response(["message"=>"Order was not found."],400);
            }
            $response = app()->make('discount')->calculate($products,$order->total,$order->id);
            return response($response,200);
        } catch (\Exception $e){
            return response(["message"=>$e->getMessage()],500);
        }

    }
}
