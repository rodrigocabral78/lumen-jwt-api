<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable;
    use Authorizable;
    use HasFactory;
    use SoftDeletes;

    /**
     * Undocumented function.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(
            function (User $user) {
                $user->uuid = (string) Str::uuid();
                $user->created_by ? 1 : Auth::id();
                $user->updated_by ? 1 : Auth::id();
            }
        );
        static::updating(
            function (User $user) {
                $user->updated_by = Auth::id();
            }
        );
    }

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The table users for the model.
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
        // 'created_by',
        // 'updated_by',
        // 'created_at',
        // 'updated_at',
        // 'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
        'is_admin'   => 'boolean',
        'is_active'  => 'boolean',
    ];

    /**
     * Interact with the user's password.
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Hash::make($value),
        );
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [
            'email' => $this->email,
        ];
    }

    /**
     * Returns true if user is administrator.
     *
     * @return $this
     */
    public function isAdmin()
    {
        return true === $this->is_admin;
    }

    /**
     * Returns true if user is active.
     *
     * @return $this
     */
    public function isActive()
    {
        return true === $this->is_active;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', '=', 1);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', '=', 1);
    }
}
