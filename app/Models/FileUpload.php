<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $table = 'file_uploads';
    protected $fillable = ['filename'];
    public $timestamps = true;
    const UPDATED_AT = null;

    protected $casts = ['created_at' => 'datetime'];

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
