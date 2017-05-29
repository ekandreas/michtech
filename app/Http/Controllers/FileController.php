<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function syncFiles()
    {
        $folders = Folder::all();
        if($folders) {
            foreach ($folders as $folder) {
                $path = "folder-{$folder->id}";
                $this->addFolders($path, $folder->id);
            }
        }

    }

    private function addFolders($path, $folderId)
    {
        $subDirectories = [];
        $directories = Storage::disk('s3')->directories($path);

        if($directories) {
            foreach ($directories as $directory) {
                $subDirectories[] = $this->addFolders($directory, $folderId);
            }
        }

        $file = File::where('path', $path)->first();

        if(!$file) {
            $file = new File([
                'path' => $path,
                'type' => 'folder',
                'size' => 0,
                'parent' => null,
                'folder' => $folderId,
            ]);
            $file->save();
        }

        echo "{$file->id} => {$path}<br/>";

        $files = Storage::disk('s3')->files($path);
        if($files) {
            foreach ($files as $f) {
                $this->addFile($f, $file->id, $folderId);
            }
        }

        if($subDirectories) {
            foreach ($subDirectories as $subDirectoryId) {
                $f = File::where('id', $subDirectoryId)->first();
                $f->parent = $file->id;
                $f->save();
            }
        }

        return $file->id;

    }

    private function addFile($path, $parent, $folderId) {
        $file = File::where('path', $path)->first();
        if(!$file) {
            $size = Storage::disk('s3')->size($path);
            $mime = Storage::disk('s3')->mimeType($path);
            $file = new File([
                'path' => $path,
                'type' => 'file',
                'size' => $size ? round($size/1024) : null,
                'mime' => $mime ?: null,
                'parent' => $parent,
                'folder' => $folderId,
            ]);
            $file->save();
        }
        echo "{$file->id} => {$path}<br/>";
    }

}
