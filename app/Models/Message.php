<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Message",
 *     type="object",
 *     title="訊息",
 *     required={"user_id", "role", "content"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="role", type="string", example="user"),
 *     @OA\Property(property="content", type="string", example="你好"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-04-09T15:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-04-09T15:00:00Z")
 * )
 */
class Message extends Model
{
    protected $fillable = ['user_id', 'role', 'content'];
}
