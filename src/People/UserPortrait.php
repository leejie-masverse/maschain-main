<?php

namespace Src\People;

use Diver\Field\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\Size;

class UserPortrait extends File
{
    CONST SIZE = 256;

    /**
     * Field name
     *
     * @var string
     */
    protected $field = 'portrait';

    /**
     * Get url attribute
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if ($this->path) {
            return Storage::disk($this->disk)->url($this->path);
        }

        return "https://ui-avatars.com/api?" . http_build_query([
            'name' => $this->entity->profile->full_name,
            'size' => self::SIZE,
            'font-size' => '0.33'
        ]);
    }

    /**
     * Generate dirname
     *
     * @return string
     */
    protected function generateDirname()
    {
        if ($this->entity_id) {
            return "users/{$this->entity_id}/portrait/";
        }

        return parent::generateDirname();
    }

    /**
     * Process file
     */
    public function processFile()
    {
        $portrait = Image::make($this->full_path);
        $portrait->fit(self::SIZE);
        $portrait->save();
    }
}
