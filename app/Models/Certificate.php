<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $certificate_id
 * @property string $name
 * @property array<array-key, mixed> $fields
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereFields($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Certificate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Certificate extends Model
{

    protected $primaryKey = 'certificate_id';

    protected $guarded = ['certificate_id'];

    protected $casts = [
        'fields' => 'array',
    ];
}
