<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $document_id
 * @property string $file_name
 * @property string $file_path
 * @property string $certificate_template_name
 * @property string $hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCertificateTemplateName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Document extends Model
{
    protected $primaryKey = 'document_id';
    protected $guarded = ['document_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    #[Override]
    public function delete()
    {
        if (file_exists($this->file_path)) {
            unlink($this->file_path);
        }
        return parent::delete();
    }
}
