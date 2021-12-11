<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      tags={"TOKEN"},
     *      summary="Login",
     *      operationId="login",
     *      @OA\Parameter(
     *          name="email",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          in="query",
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
     *
     * Login  user.
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()){
                return response([
                    'message' => ['Mail and password are mandatory.']
                ], 400);
            }

            $user = User::where('email', $request->email)->first();

            if(!$user){
                return response([
                    'message' => ['The email is incorrect.']
                ], 400);
            }

            if($user->role == 1){
                return response([
                    'message' => ['User does not have authorization.']
                ], 401);
            }

            if(!Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['The password is incorrect.']
                ], 500);
            }

            if(!$user->token){
                $userToken = $user->createToken('api-token')->plainTextToken;
                $user = User::find($user->id);
                $user->token = $userToken;
                $user->save();
                return response(['token' => $userToken], 200);
            } else{
                return response(['token' => $user->token], 200);
            }
        } catch (\Exception $e){
            return response(['message'=>$e->getMessage()], 500);
        }

    }

    public function rlogin(){
        return response(['message'=>"token mismatch. "], 419);
    }
}
