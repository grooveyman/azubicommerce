<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Helpers\Engine;
use App\Models\Helpers\MyErrorHandler;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */

     /**
     * @OA\Get(
        *     path="/carts",
        *     tags={"carts"},
        *     summary="Returns all carts",
        *     description="Returns a all carts",
        *     operationId="getCarts",
        *     @OA\Response(
        *         response=200,
        *         description="successful operation",
        *     
        *     ),
        *      @OA\Parameter(
        *         name="id",
        *         in="path",
        *         description="Id of cart",
        *          required=true,
        *         @OA\Schema(
        *             type="integer",
        *             example="1"
        *         )
        *     ),
        *
        *     @OA\Response(
        *         response=500,
        *         description="An error occurred"
        *     )
        * )
        */
    public function index()
    {
        //
        $carts = Cart::all();
        if ($carts->count() > 0) {
            $data = [
                "status" => 200,
                "results" => $carts
            ];
            return response()->json($data, 200);
        } else {
            return MyErrorHandler::errorMsg(404);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

   /** @OA\Post( * path="/carts", * tags={"carts"}, * summary="Save cart information", * description="Saves carts to the database", * * @OA\Parameter( * name="productid", * in="query", * description="Product ID", * required=true, * @OA\Schema(type="integer", example=1) * ), * @OA\Parameter( * name="quantity", * in="query", * description="Quantity of the product", * required=true, * @OA\Schema(type="integer", example=2) * ), * @OA\Response( * response=200, * description="Cart created successfully", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=200), * @OA\Property(property="results", type="string", example="Cart created successfully") * ) * ), * @OA\Response( * response=422, * description="Validation error", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=422), * @OA\Property(property="errors", type="object") * ) * ) * ) */
    public function store(Request $request)
    {
        //

        $validateData = Validator::make($request->all(), [
            "productid" => "required|numeric",
            'quantity' => "required|numeric"
        ]);

        if ($validateData->fails()) {
            return response()->json(["status" => 422, "errors" => $validateData->messages()], 422);
        } else {
            $ucode = Engine::generate_ucode("CRT");
            $carts = Cart::create([
                'code' => $ucode,
                'quantity' => $request->quantity,
            ]);

            if ($carts) {
                return response()->json(new CartResource($carts), 200);
            } else {
                return MyErrorHandler::errorMsg(500);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    /** @OA\Get( * path="/carts/{id}", * operationId="getCartById", * tags={"carts"}, * summary="Get a cart by its ID", * @OA\Parameter( * name="id", * in="path", * description="ID of the cart to retrieve", * required=true, * @OA\Schema(type="integer") * ), * @OA\Response( * response=200, * description="Successful operation", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=200), * @OA\Property(property="results", type="object", * @OA\Property(property="id", type="integer", example=1), * @OA\Property(property="productid", type="integer", example=1), * @OA\Property(property="quantity", type="integer", example=2), * @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-16T12:34:56Z"), * @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-16T12:34:56Z") * ) * ) * ), * @OA\Response( * response=404, * description="Cart not found", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=404), * @OA\Property(property="message", type="string", example="Cart not found") * ) * ) * ) */
    public function show(Cart $cart)
    {
        //
        
        if ($cart) {
            return response()->json(["status" => 200, "results" => $cart], 200);
        } else
            return MyErrorHandler::errorMsg(404);
    }

    /**
     * Show the form for editing the specified resource.
     */

     /**  @OA\Put( * path="/carts/{id}", * operationId="updateCart", * tags={"carts"}, * summary="Update an existing cart", * @OA\Parameter( * name="id", * in="path", * description="ID of the cart to update", * required=true, * @OA\Schema(type="integer") * ), * @OA\RequestBody( * required=true, * @OA\JsonContent( * @OA\Property(property="quantity", type="integer", example=2) * ) * ), * @OA\Response( * response=200, * description="Cart updated successfully", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=200), * @OA\Property(property="results", type="object", * @OA\Property(property="id", type="integer", example=1), * @OA\Property(property="productid", type="integer", example=1), * @OA\Property(property="quantity", type="integer", example=2), * @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-16T12:34:56Z"), * @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-16T12:34:56Z") * ) * ) * ), * @OA\Response( * response=422, * description="Validation error", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=422), * @OA\Property(property="errors", type="object") * ) * ), * @OA\Response( * response=404, * description="Cart not found", * @OA\JsonContent( * @OA\Property(property="status", type="integer", example=404), * @OA\Property(property="message", type="string", example="Cart not found") * ) * ) * ) */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
        try {

            $validateData = Validator::make($request->all(), [
                'quantity' => 'nullable|numeric'
            ]);

            if ($validateData->fails()) {
                return response()->json(['status' => 422, 'error' => $validateData->messages()], 422);
            } else {
                if ($cart) {
                    $cart->update([
                        'quantity' => $request->quantity ?? $cart->quantity,
                    ]);

                    return response()->json(new CartResource($cart), 200);
                } else {
                    return MyErrorHandler::errorMsg(404);
                }
            }


        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */

     /** @OA\Delete( * path="/carts/{id}", * operationId="deleteCart", * tags={"carts"}, * summary="Delete a cart by its ID", * @OA\Parameter( * name="id", * in="path", * description="ID of the cart to delete", * required=true, * @OA\Schema(type="integer") * ), * @OA\Response( * response=200, * description="Cart deleted successfully", * @OA\JsonContent( * @OA\Property(property="status", type="boolean", example=true), * @OA\Property(property="message", type="string", example="Cart deleted successfully") * ) * ), * @OA\Response( * response=404, * description="Cart not found", * @OA\JsonContent( * @OA\Property(property="status", type="boolean", example=false), * @OA\Property(property="message", type="string", example="Cart not found") * ) * ) * ) */
    public function destroy(Cart $cart)
    {
        //
        if ($cart) {
            $cart->delete();

            return response()->json(['status' => true, 'message' => 'cart deleted successfully'], 200);
        } else {
            return MyErrorHandler::errorMsg(404);
        }
    }
}
