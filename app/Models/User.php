<?php

namespace App\Models;

use App\Traits\UUID;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


/**
 * App\Models\User
 *
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $password
 * @property string $email
 * @property string $preferred_contact
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static UserFactory factory(...$parameters)
 * @method static EloquentBuilder|User newModelQuery()
 * @method static EloquentBuilder|User newQuery()
 * @method static QueryBuilder|User onlyTrashed()
 * @method static EloquentBuilder|User query()
 * @method static EloquentBuilder|User whereCreatedAt($value)
 * @method static EloquentBuilder|User whereDeletedAt($value)
 * @method static EloquentBuilder|User whereEmail($value)
 * @method static EloquentBuilder|User whereEmailVerifiedAt($value)
 * @method static EloquentBuilder|User whereId($value)
 * @method static EloquentBuilder|User whereName($value)
 * @method static EloquentBuilder|User wherePassword($value)
 * @method static EloquentBuilder|User wherePhone($value)
 * @method static EloquentBuilder|User wherePreferredContact($value)
 * @method static EloquentBuilder|User whereUpdatedAt($value)
 * @method static QueryBuilder|User withTrashed()
 * @method static QueryBuilder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use UUID, SoftDeletes, Notifiable, HasFactory;

    public $timestamps = true;
    public $incrementing = false;

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'preferred_contact'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
