<?php

namespace Diver\Field;

use Diver\Field\Observers\FileObserver;
use Illuminate\Support\Facades\Storage;

abstract class File extends Field
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fields_file';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'disk',
        'path',
        'dirname',
        'filename',
        'extension',
    ];

    /**
     * Field name
     *
     * @var string
     */
    protected $field = 'file';

    /**
     * Storage disk name
     *
     * @var string
     */
    protected $disk = 'public';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::observe(FileObserver::class);
    }

    /**
     * Get storage disk
     *
     * @return string
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * Get full path attribute
     *
     * @return string
     */
    public function getFullPathAttribute()
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    /**
     * Get file
     */
    public function getFileAttribute()
    {
        return array_get($this->attributes, 'file', function () {
            return Storage::disk($this->disk)->get($this->path);
        });
    }

    /**
     * Get dirname attribute
     *
     * @return string
     */
    public function getDirnameAttribute()
    {
        if (empty($this->attributes['dirname'])) {
            $this->dirname = $this->generateDirname();
        }

        return $this->attributes['dirname'];
    }

    /**
     * Generate dirname
     *
     * @return string
     */
    protected function generateDirname()
    {
        return $this->getTable();
    }

    /**
     * Process file
     */
    public function processFile()
    {
    }
}
