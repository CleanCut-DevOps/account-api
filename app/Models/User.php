<?php

namespace App\Models;

use App\Traits\UUID;
use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $contact
 * @property string $password
 * @property string|null $avatar
 * @property int|null $deleted_at
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static UserFactory factory(...$parameters)
 * @method static EloquentBuilder|User newModelQuery()
 * @method static EloquentBuilder|User newQuery()
 * @method static QueryBuilder|User onlyTrashed()
 * @method static EloquentBuilder|User query()
 * @method static EloquentBuilder|User whereAvatar($value)
 * @method static EloquentBuilder|User whereContact($value)
 * @method static EloquentBuilder|User whereCreatedAt($value)
 * @method static EloquentBuilder|User whereDeletedAt($value)
 * @method static EloquentBuilder|User whereEmail($value)
 * @method static EloquentBuilder|User whereId($value)
 * @method static EloquentBuilder|User wherePassword($value)
 * @method static EloquentBuilder|User whereUpdatedAt($value)
 * @method static EloquentBuilder|User whereUsername($value)
 * @method static QueryBuilder|User withTrashed()
 * @method static QueryBuilder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, Notifiable, UUID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'contact',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deleted_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
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
