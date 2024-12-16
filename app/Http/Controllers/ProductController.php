<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Helpers\Engine;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Monolog\ErrorHandler;
use App\Models\Helpers\MyErrorHandler;

use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/products",
     *     tags={"products"},
     *     summary="Returns all products",
     *     description="Returns a all products",
     *     operationId="getInventory",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     
     *     ),
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of product",
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
        $products = Product::with('images')->get();
        if ($products->count() > 0) {
            $data = [
                "status" => 200,
                "results" => $products
            ];
            return response()->json($data, 200);
        } else {
            return response()->json(["status" => 404, "message" => 'No record found'], 404);
        }
    }

    /**
     * @OA\Get(
     *     path="/search",
     *     tags={"products"},
     *     summary="Returns searched product",
     *     description="Returns a product by name",
     *     
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *        
     *     ),
     *      @OA\Parameter(
     *         name="name",
     *         in="path",
     *         description="name of product",
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

    public function search(Request $request)
    {
        $product = Product::where('name', 'LIKE', $request->name)->get();

        if ($product->count() > 0) {
            return response()->json(['status' => true, 'results' => $product]);
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

    /**
     * @OA\POST(
     *     path="/products",
     *     tags={"products"},
     *     summary="Save product information",
     *     description="Saves products to the database",
     *     
     *      @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Product name",
     *         required=true,
     *         @OA\Schema(type="string", example="Product Name")
     *     ),
     *     @OA\Parameter(
     *         name="price",
     *         in="query",
     *         description="Product price",
     *         required=true,
     *         @OA\Schema(type="number", format="float", example=29.99)
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Product category",
     *         required=true,
     *         @OA\Schema(type="string", example="Category Name")
     *     ),
     *     @OA\Parameter(
     *         name="thumbnail",
     *         in="query",
     *         description="Thumbnail image URL",
     *         required=true,
     *         @OA\Schema(type="string", example="image_thumbnail.jpg")
     *     ),
     *     @OA\Parameter(
     *         name="mobile",
     *         in="query",
     *         description="Mobile image URL",
     *         required=true,
     *         @OA\Schema(type="string", example="image_mobile.jpg")
     *     ),
     *     @OA\Parameter(
     *         name="desktop",
     *         in="query",
     *         description="Desktop image URL",
     *         required=true,
     *         @OA\Schema(type="string", example="image_desktop.jpg")
     *     ),
     *     @OA\Parameter(
     *         name="tablet",
     *         in="query",
     *         description="Tablet image URL",
     *         required=true,
     *         @OA\Schema(type="string", example="image_tablet.jpg")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="results", type="string", example="Product created successfully with respective images")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=422),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        //
        //
        $validateData = Validator::make($request->all(), [
            'name' => "required",
            'price' => "required|numeric",
            'category' => "required|string",
            //validate images
            'thumbnail' => 'required|string',
            'mobile' => 'required|string',
            'desktop' => 'required|string',
            'tablet' => 'required|string'
        ]);

        if ($validateData->fails()) {
            return response()->json(["status" => 422, "errors" => $validateData->messages()], 422);

        } else {
            $ucode = Engine::generate_ucode("PRO");

            $products = Product::create([
                'code' => $ucode,
                'name' => $request->name,
                'price' => $request->price,
                'category' => $request->category
            ]);

            //insert into images
            $images = Image::create([
                'code' => Engine::generate_ucode('IMG'),
                'productid' => $products->id,
                'thumbnail' => $request->thumbnail,
                'desktop' => $request->desktop,
                'tablet' => $request->tablet,
                'mobile' => $request->mobile
            ]);


            if ($products) {
                if ($images) {
                    return response()->json(["status" => 200, "results" => 'Product created successfully with respective images'], 200);
                }
                // return response()->json(["status" => 200, "results" =>'Produt created successfully'], 200);

            } else {
                return MyErrorHandler::errorMsg(500);
            }
        }
    }

    /**
     * Display the specified resource.
     */

    /** @OA\Get( 
     * path="/products/{id}", 
     * operationId="getProductById", 
     * tags={"products"}, 
     * summary="Get a product by its ID", 
     * @OA\Parameter( 
     * name="id", 
     * in="path", 
     * description="ID of the product to retrieve", 
     * required=true, 
     * @OA\Schema(type="integer") * ), 
     * @OA\Response( * response=200, 
     * description="Successful operation", 
     * @OA\JsonContent( 
     * @OA\Property(property="status", type="integer", example=200), 
     * @OA\Property(property="results", type="object", example="successfully fetched") * )
     * ), 
     * @OA\Response( * response=404, * description="Product not found", 
     * @OA\JsonContent( 
     * @OA\Property(property="status", type="integer", example=404), 
     * @OA\Property(property="message", type="string", example="Product not found") * ) * ) * ) */

    public function show(Product $product)
    {
        //
        if ($product) {
            return response()->json(["status" => 200, "results" => $product], 200);
        } else
            return MyErrorHandler::errorMsg(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
/** @OA\Put(path="/products/{id}", operationId="updateProduct", tags={"products"}, summary="Update an existing product", 
 * @OA\Parameter( name="id", in="path", description="ID of the product to update", required=true, @OA\Schema(type="integer") * ), * @OA\RequestBody( * required=true, * @OA\JsonContent( 
 * @OA\Property(property="name", type="string", example="Updated Product Name"), 
 * @OA\Property(property="price", type="number", format="float", example=39.99),
 *  @OA\Property(property="category", type="string", example="Updated Category Name"), 
 * @OA\Property(property="thumbnail", type="string", example="updated_thumbnail.jpg"),
 *  @OA\Property(property="mobile", type="string", example="updated_mobile.jpg"), 
 *  @OA\Property(property="desktop", type="string", example="updated_desktop.jpg"), 
 * @OA\Property(property="tablet", type="string", example="updated_tablet.jpg") * ) * ), * @OA\Response( * response=200, * description="Product updated successfully",
 *  @OA\JsonContent( * @OA\Property(property="status", type="integer", example=200), 
 * @OA\Property(property="results", type="object", example="Updated Successfully") * ) * ), * @OA\Response( * response=422, * description="Validation error",
 *  @OA\JsonContent(
 *  @OA\Property(property="status", type="integer", example=422),
 *  @OA\Property(property="errors", type="object") * ) * ),
 *  @OA\Response( * response=404, * description="Product not found",
 *  @OA\JsonContent( * @OA\Property(property="status", type="integer", example=404),
 *  @OA\Property(property="message", type="string", example="Product not found") * ) * ) * ) */
    public function update(Request $request, Product $product)
    {
        //
        //
        $validateData = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'price' => 'nullable|numeric',
            'category' => 'nullable|numeric'
        ]);

        if ($validateData->fails()) {
            return response()->json(['status' => 422, 'error' => $validateData->messages()], 422);
        } else {
            if ($product) {
                $product->update([
                    'name' => $request->name ?? $product->name,
                    'price' => $request->price ?? $product->price,
                    'category' => $request->category ?? $product->category,
                ]);

                return response()->json(new ProductResource($product), 200);
            } else {
                return MyErrorHandler::errorMsg(404);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */

     /** * @OA\Delete( * path="/products/{id}", * operationId="deleteProduct", * tags={"products"}, * summary="Delete a product by its ID", * @OA\Parameter( * name="id", * in="path", * description="ID of the product to delete", * required=true, * @OA\Schema(type="integer") * ), * @OA\Response( * response=200, * description="Product deleted successfully", * @OA\JsonContent( * @OA\Property(property="status", type="boolean", example=true), * @OA\Property(property="message", type="string", example="Product deleted successfully") * ) * ), * @OA\Response( * response=404, * description="Product not found", * @OA\JsonContent( * @OA\Property(property="status", type="boolean", example=false), * @OA\Property(property="message", type="string", example="Product not found") * ) * ) * ) */

    public function destroy(Product $product)
    {
        //
        //
        if ($product) {
            $product->delete();

            return response()->json(['status' => true, 'message' => 'product deleted successfully'], 200);
        } else {
            return MyErrorHandler::errorMsg(404);
        }
    }
}