<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Cleaner
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Cleaner newModelQuery()
 * @method static Builder|Cleaner newQuery()
 * @method static Builder|Cleaner query()
 * @method static Builder|Cleaner whereCreatedAt($value)
 * @method static Builder|Cleaner whereId($value)
 * @method static Builder|Cleaner whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Cleaner extends Model
{
    use HasFactory;
}
