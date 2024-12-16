<?php

namespace App\Http\Controllers;
use OpenApi\Annotations\SecurityScheme;
use OpenApi\Attributes as OA;

#[
    OA\Info(version: "1.0.0", description: "Azubi Commerce API documentation", title: "Azubi Commerce API"),
    OA\Server(url: 'http://127.0.0.1:8000/api', description: 'local server'),
    OA\Server(url: 'https://example.com', description: 'production server'),
    OA\SecurityScheme(securityScheme: 'brearerAuth', type: 'http', name: 'Authorization', in: 'header', scheme: 'header'),
]


/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"id", "name", "price", "category"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Sample Product"),
 *     @OA\Property(property="price", type="number", format="float", example=29.99),
 *     @OA\Property(property="category", type="string", example="Electronics"),
 *     @OA\Property(property="thumbnail", type="string", example="thumbnail.jpg"),
 *     @OA\Property(property="mobile", type="string", example="mobile.jpg"),
 *     @OA\Property(property="desktop", type="string", example="desktop.jpg"),
 *     @OA\Property(property="tablet", type="string", example="tablet.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-16T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-16T12:34:56Z")
 * ),
 * @OA\Schema(
 *     schema="Carts",
 *     type="object",
 *     required={"id", "quantity", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="quantity", type="integer", example="1"),
 *     @OA\Property(property="status", type="enum", format="enum", example="1"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-16T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-16T12:34:56Z")
 * ),
 * 
 * @OA\Schema(
 *     schema="Users",
 *     type="object",
 *     required={"id", "name", "email", "password"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Malvin Johnson"),
 *     @OA\Property(property="email", type="email", format="email", example="johnson@gmail.com"),
 *     @OA\Property(property="password", type="password", format="secret", example="xxxx"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-16T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-16T12:34:56Z")
 * )
 */






abstract class Controller
{
    //
}