<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(schema="LoginCreds", required={"email", "password"},
 *     @OA\Property(property="email", format="string", type="string", example="test@email.com"),
 *     @OA\Property(property="password", format="string", type="string", example="secret")
 * )
 */

/**
 * @OA\Schema(schema="RegisterCreds", required={"email", "password"},
 *     @OA\Property(property="email", format="string", type="string", example="test@email.com"),
 *     @OA\Property(property="password", format="string", type="string", example="secret"),
 *     @OA\Property(property="name", format="string", type="string", example="Tom Sand"),
 * )
 */

/**
 * @OA\Schema(schema="UserApiKey", required={"id", "api_key"},
 *     @OA\Property(property="id", format="int64", type="integer", example=1),
 *     @OA\Property(property="api_key", format="string", type="string", example="em1JbEVqSnZlR0lPMlozenZ5YmpPUWdKSURiVGpnZmg="),
 * )
 */

/**
 * #OA\Schema(schema="NewUser", required={"name"},
 *     #OA\Property(property="name", format="string", type="string"),
 * )
 */

/**
 * @OA\Schema(
 *    schema="User",
 *    required={"id"},
 *    @OA\Property(property="id", format="int64", type="integer", example=1),
 *    @OA\Property(property="admin", format="boolean", type="boolean", example=false),
 *    @OA\Property(property="name", format="string", type="string", example="User Name"),
 *    @OA\Property(property="status", format="string", type="string", example="thebest"),
 *    @OA\Property(property="options", format="array", type="object", example="{}",
 *      @OA\Items(
 *          type="array",
 *          example="{}",
 *          @OA\Items()
 *      ),
 *    ),
 *    @OA\Property(property="created_at", format="string", type="string", example="2019-01-26 20:00:00"),
 *    @OA\Property(property="updated_at", format="string", type="string", example="2019-01-26 20:00:57"),
 *    )
 * )
*/

class User extends Model
{
    protected $casts = [
        'admin' => 'boolean',
        'options' => 'object'
    ];

    protected $fillable = [
        'admin',
        'name',
        'email',
        'password',
        'api_key',
        'status',
        'options'
    ];

    protected $visible = [
        'id',
        'admin',
        'name',
        'status',
        'created_at',
        'updated_at',
        'options',
        'items'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (null === $model->api_key) {
                $model->api_key = self::generationApiKey();
            }
        });
    }

    /**
     * @param string $pass
     */
    public function setPasswordAttribute($pass)
    {
        $this->attributes['password'] = Hash::make($pass);
    }

    /**
     * Generate new api key
     * @return string
     */
    protected static function generationApiKey()
    {
        return substr(base64_encode(str_random(64)), 0, 32);
    }

    /**
     * Generate new api key
     * @return string
     */
    public function resetApiKey()
    {
        $this->api_key = self::generationApiKey();
    }

    /**
     * The roles that belong to the user.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'users_follow', 'follow_user_id', 'user_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'users_follow', 'user_id', 'follow_user_id');
    }

    /**
     * The roles that belong to the user.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'users_items');
    }
}
