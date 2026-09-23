<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileUpload extends Model
{
    protected $table = 'file_uploads';
    protected $fillable = ['filename', 'uploaded_by'];
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $casts = ['created_at' => 'datetime'];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDisplayFilenameAttribute(): string
    {
        $moduleUploadName = \App\Models\UploadedFile::where('storage_path', $this->filename)
            ->value('original_filename');

        if ($moduleUploadName) {
            return $moduleUploadName;
        }

        return preg_replace('/^[a-f0-9]{32}-/', '', basename($this->filename)) ?: basename($this->filename);
    }
}
