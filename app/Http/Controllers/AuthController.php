<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //

    /**
     * @OA\POST(
     *     path="/create",
     *     tags={"users"},
     *     summary="Save user information and generate token",
     *     description="Saves user to the database",
     *     
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User name",
     *         required=true,
     *         @OA\Schema(type="string", example="User Name")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *         @OA\Schema(type="email", format="string", example="johnson@gmail.com")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Password of User",
     *         required=true,
     *         @OA\Schema(type="string", example="dfs@.drL;s")
     *     ),
     *    
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User created Successfully"),
     *             @OA\Property(property="token", type="string", example="xxxxxxxxxxxxxxxxxxxxxx")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="errors", type="object", example={"name":"name field cannot be empty"})
     *         )
     *     )
     * )
     */

    public function create(Request $request)
    {

        try {

            //validated
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);


            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage(),
                ], 500);
            }


        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }


    }


     /**
     * @OA\POST(
     *     path="/login",
     *     tags={"users"},
     *     summary="Log user in",
     *     description="Saves user to the database",

     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email",
     *         required=true,
     *         @OA\Schema(type="email", format="string", example="johnson@gmail.com")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Password of User",
     *         required=true,
     *         @OA\Schema(type="string", example="dfs@.drL;s")
     *     ),
     *    
     *      @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in Successfully"),
     *             @OA\Property(property="token", type="string", example="xxxxxxxxxxxxxxxxxxxxxx")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="errors", type="object", example={"email":"email field cannot be empty"})
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {

        try {
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.'
                ], 401);

            }
            $user = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Logged In successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }

    }




    public function logout(Request $request) { $request->user()->tokens()->delete(); return response()->json(['message' => 'Logged out successfully']); }


}
