<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    // Aşağıdaki security kısmı swagger panelinin en üstüne token ile login olmamızı sağlayacak alanı ekliyor.
    /**
     * @OA\SecurityScheme(
     *     type="http",
     *     description="Login with email and password to get the authentication token",
     *     name="Token based Based",
     *     in="header",
     *     scheme="bearer",
     *     securityScheme="bearerAuth",
     * )
     */
    /**
     * @OA\Post(
     *      path="/api/order",
     *      tags={"ORDER"},
     *      summary="Add New Order.",
     *      operationId="addOrder",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="customerId", type="integer"),
     *               @OA\Property(property="items", type="array",
     *                  @OA\Items(
     *                      @OA\Property(property="productId", type="integer"),
     *                      @OA\Property(property="quantity", type="integer", default="0")
     *                  )
     *               ),
     *               example={"customerId": "integer", "items":{{"productId":"integer","quantity":"integer"},{"productId":"integer","quantity":"integer"}}}
     *            )
     *        )
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
     *         response=419,
     *         description="token mismatch."
     *      ),
     *      @OA\Response(
     *         response=500,
     *         description="Not successful operation"
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response    = [];
        $total       = 0;
        $order_items = [];
        try{
            // Order data alınıyor.
            $data  = $request->json()->all();

            $validator = Validator::make($data, [
                'customerId' => 'required|integer'
            ]);

            if ($validator->fails()){
                return response([
                    'message' => ['customerId mandatory.']
                ], 500);
            }

            $user = User::find($data['customerId']);

            if(!$user){
                return response([
                    'message' => ['The customer is not registered.']
                ], 500);
            }

            $order = Order::create([
                'user_id' => $data['customerId'],
                'total'   => 0
            ]);

            $response['order_id']   = $order->id;
            $response['customerId'] = $order->user_id;

            if($order){
                foreach ($data['items'] as $product){
                    $validator = Validator::make($product, [
                        'quantity' => 'required|integer',
                        'productId'=> 'required|integer',
                    ]);

                    if ($validator->fails()){
                        $product['reason']      = 'Quantity and productId are mandatory.';
                        $response['notAdded'][] = $product; continue;
                    }

                    $item   = Product::find($product['productId']);

                    if(!$item){
                        $product['reason']      = 'productId is wrong. It was not found at products table.';
                        $response['notAdded'][] = $product; continue;
                    }

                    if($item->stock>=$product['quantity']){
                        $create_data = [
                            'order_id'   => $order->id,
                            'product_id' => $product['productId'],
                            'category'   => $item->category,
                            'unit_price' => $item->price,
                            'quantity'   => $product['quantity'],
                            'total'      => $item->price*$product['quantity'],
                        ];
                        $total += $item->price*$product['quantity'];
                        $order_item = OrderItem::create($create_data);

                        $item->decrement('stock', $product['quantity']);
                        $response['added'][] = $create_data;

                        $order_items[] = $create_data;
                    } else{
                        $product['reason']     = 'no stock enough for this product';
                        $response['noStock'][] = $product;
                    }
                }
            }

            $order_id = $order->id;
            if($total>0){
                $message = "Order was added.";
                $order->update(['total'=>$total]);
            } else{
                $order_id = "Order was not added.";
                $message  = "Order was not added.";
                $order->delete();
            }

            return response(['message'=>$message,'data'=>$response,'order_id' => $order_id,'total'=>$total?$total:'Order was not added.'], 200);

        } catch (\Exception $e){
            return response(['message'=>'Operation is not successfull','error'=>$e->getMessage()." - ".$e->getLine()], 500);
        }

    }

    /**
     * @OA\Get(
     *      path="/api/order/{id}",
     *      tags={"ORDER"},
     *      summary="Get Order.",
     *      operationId="getOrder",
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
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function show($id)
    {
        try{
            $order       = Order::find($id);
            $order_items = OrderItem::where('order_id',$id)->get();
            return response(['order_id'=>$id,'customerId'=>$order->user_id,'items'=>$order_items], 200);
        } catch (\Exception $e){
            return response(['message'=>'not success.'.$e->getMessage()], 500);
        }
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
        //
    }

    /**
     * @OA\Delete(
     *      path="/api/order/{id}",
     *      tags={"ORDER"},
     *      summary="Delete Order.",
     *      operationId="deleteOrder",
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
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        try {
            $order = Order::where('id',$id)->delete();
            OrderItem::where('order_id',$id)->delete();
            if(!$order){
                return response(["message"=>"Order was not found."],400);
            }
            return response(['message'=>'Operation is successful.','deleted_order_id' => $id], 200);
        } catch (\Exception $e){
            return response(['message'=>'Operation is not successful.','error'=>$e->getMessage()." - ".$e->getLine()], 500);
        }
    }
}
