<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/product/list",
     *      tags={"PRODUCT"},
     *      summary="Get Product List.",
     *      operationId="getProductList",
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
    public function index()
    {
        try{
            // Order data alınıyor.
            return response(Product::all()->toArray(), 200);
        } catch (\Exception $e){
            return response(['message'=>'not success.'.$e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/product/update/{id}",
     *      tags={"PRODUCT"},
     *      summary="Update Product.",
     *      operationId="updateProduct",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *               type="object",
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="stock", type="integer"),
     *               example={"name": "string", "stock":"integer"}
     *            )
     *        )
     *      ),
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
    public function update(Request $request,$id){
        try{
            // Order data alınıyor.
            $data  = $request->json()->all();
            Product::where('id',$id)->update($data);
            return response(['message'=>'success.','data'=>Product::find($id)], 200);
        } catch (\Exception $e){
            return response(['message'=>'not success.'.$e->getMessage()], 500);
        }
    }
}
