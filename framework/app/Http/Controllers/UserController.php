<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/customer/list",
     *      tags={"CUSTOMER"},
     *      summary="Get Customer List.",
     *      operationId="getCustomerList",
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
    public function index(Request $request)
    {
        try{
            return response(User::where('role',1)->get()->toArray(), 200);
        } catch (\Exception $e){
            return response(['message'=>'not success.'.$e->getMessage()], 500);
        }
    }
}
