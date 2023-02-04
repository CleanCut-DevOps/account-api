<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Admin
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Admin newModelQuery()
 * @method static Builder|Admin newQuery()
 * @method static Builder|Admin query()
 * @method static Builder|Admin whereCreatedAt($value)
 * @method static Builder|Admin whereId($value)
 * @method static Builder|Admin whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Admin extends Model
{
    use HasFactory;
}
