<?php

namespace Diver\Field\Observers;

use Diver\Field\File;
use Illuminate\Http\UploadedFile;

class FileObserver
{
    /**
     * Saving
     *
     * @param \Diver\Field\File $file
     */
    public function saving(File $file)
    {
        $file->disk = $file->getDisk();

        if ($file->file) {
            $this->moveUploadedFile($file);
        }

        if ($file->path) {
            $this->processFile($file);
            $this->populateDirname($file);
            $this->populateFilename($file);
            $this->populateExtension($file);
        }

        unset($file->file);
    }

    /**
     * Move uploaded file to storage folder
     *
     * @param \Diver\Field\File $file
     */
    protected function moveUploadedFile(File $file)
    {
        if ($file->file instanceof UploadedFile) {
            $file->path = $file->file->store($file->dirname, $file->getDisk());
        }
    }

    /**
     * Process file if
     *
     * @param $file
     */
    protected function processFile(File $file)
    {
        if ($file->path) {
            $file->processFile();
        }
    }

    /**
     * Populate dirname
     *
     * @param \Diver\Field\File $file
     */
    protected function populateDirname(File $file)
    {
        if ($file->isClean('path')) {
            return;
        }

        $file->dirname = pathinfo($file->path, PATHINFO_DIRNAME);
    }

    /**
     * Populate filename
     *
     * @param \Diver\Field\File $file
     */
    protected function populateFilename(File $file)
    {
        if ($file->isClean('path')) {
            return;
        }

        $file->filename = pathinfo($file->path, PATHINFO_FILENAME);
    }

    /**
     * Populate filename
     *
     * @param \Diver\Field\File $file
     */
    protected function populateExtension(File $file)
    {
        if ($file->isClean('path')) {
            return;
        }

        $file->extension = pathinfo($file->path, PATHINFO_EXTENSION);
    }
}
