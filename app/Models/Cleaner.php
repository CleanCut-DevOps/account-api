<?php

namespace App\Models;

use App\Traits\UUID;
use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\Cleaner
 *
 * @property string $id
 * @property string $name
 * @property string $phone
 * @property string $password
 * @property string $email
 * @property string $preferred_contact
 * @property string $employment_type
 * @property float|null $base_salary
 * @property float|null $commission_cut
 * @property Carbon $hired_at
 * @property Carbon $updated_at
 * @property Carbon|null $terminated_at
 * @method static Builder|Cleaner newModelQuery()
 * @method static Builder|Cleaner newQuery()
 * @method static Builder|Cleaner query()
 * @method static Builder|Cleaner whereBaseSalary($value)
 * @method static Builder|Cleaner whereCommissionCut($value)
 * @method static Builder|Cleaner whereEmail($value)
 * @method static Builder|Cleaner whereEmploymentType($value)
 * @method static Builder|Cleaner whereHiredAt($value)
 * @method static Builder|Cleaner whereId($value)
 * @method static Builder|Cleaner whereName($value)
 * @method static Builder|Cleaner wherePassword($value)
 * @method static Builder|Cleaner wherePhone($value)
 * @method static Builder|Cleaner wherePreferredContact($value)
 * @method static Builder|Cleaner whereTerminatedAt($value)
 * @method static Builder|Cleaner whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Cleaner extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use UUID;

    const CREATED_AT = null;

    public $timestamps = true;
    public $incrementing = false;

    protected $table = 'cleaners';
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
        'hired_at',
        'base_salary',
        'terminated_at',
        'commission_cut',
        'employment_type',
        'preferred_contact',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hired_at' => 'datetime',
        'updated_at' => 'datetime',
        'terminated_at' => 'datetime'
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
