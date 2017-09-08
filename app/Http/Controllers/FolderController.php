<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Item;
use App\Jobs\IndexFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function index()
    {
        $result = [];
        foreach (Folder::all()->sortBy("prio") as $folder) {
            $result[] = [
                'id' => $folder->id,
                'showDocuments' => $folder->documents,
                'showUploads' => $folder->uploads,
            ];
        }
        return $result;
    }

    public function show($id)
    {
        $folder = Folder::findOrFail($id);
        return [
            'name' => $folder->name,
            'documents' => [],
            'uploads' => [],
            'showDocuments' => $folder->documents,
            'showUploads' => $folder->uploads,
        ];
    }

    public function documents($id)
    {
        $folder = Folder::findOrFail($id);

        Storage::disk('s3')->makeDirectory("folder-{$folder->id}");
        Storage::disk('s3')->makeDirectory("folder-{$folder->id}/documents");

        $fileFolder = File::where('path', "folder-{$folder->id}/documents")->first();
        $result = File::where('parent', $fileFolder->id)->get();
        return $result;
    }

    public function folder($id, $fileId)
    {
        $result = [];
        $fileFolder = File::find($fileId);

        $parent = File::find($fileFolder->parent);

        if ($parent->parent) {
            $parent->type = 'back';
            $result[] = $parent;
        }

        foreach (File::where('parent', $fileFolder->id)->get() as $file) {
            $result[] = $file;
        }
        return $result;
    }

    public function uploads($id)
    {
        $folder = Folder::findOrFail($id);

        Storage::disk('s3')->makeDirectory("folder-{$folder->id}");
        Storage::disk('s3')->makeDirectory("folder-{$folder->id}/uploads");

        $fileFolder = File::where('path', "folder-{$folder->id}/uploads")->first();
        $result = File::where('parent', $fileFolder->id)->get();
        return $result;
    }

    public function passcode(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        return new Response(
            $folder->passcode == $request->passcode ? 'success' : 'not-valid',
            200
        );
    }

    public function upload(Request $request, $id)
    {
        set_time_limit(0);
        $fileName = $request->file('file')->getClientOriginalName();
        $path = "folder-{$id}/uploads/{$fileName}";

        $result = $request->file('file')->storeAs(
            "folder-{$id}/uploads",
            $fileName,
            "s3"
        );

        $base = File::where('path', "folder-{$id}/uploads")->first();

        $size = Storage::disk('s3')->size($path);
        $mime = Storage::disk('s3')->mimeType($path);
        $file = new File([
            'path' => $path,
            'type' => 'file',
            'size' => $size ? round($size / 1024) : null,
            'mime' => $mime ?: null,
            'parent' => $base->id,
            'folder' => $id,
        ]);
        $file->save();

        $this->dispatch(new IndexFiles());

        return $result;
    }

    public function download(Request $request, $id, $itemId)
    {
        $file = File::find($itemId);

        $path = $file->path;
        $base = basename($file->path);
        Storage::disk('s3')->setVisibility($path, 'public');
        $url = Storage::disk('s3')->url($path);

        $headers = [
            'Content-Type' => $file->mime,
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => "attachment; filename={$base}",
            'filename' => $base,
        ];

        return response()->redirectTo($url, 302, $headers);
        //return redirect()->redurecaway($url);

    }

}
