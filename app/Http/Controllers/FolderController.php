<?php

namespace App\Http\Controllers;

use App\Folder;
use App\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function index()
    {
        $result = [];
        foreach (Folder::all() as $folder) {
            $result[] = [
                'id' => $folder->id,
            ];
        }
        return $result;
    }

    public function show($id)
    {
        $folder = Folder::findOrFail($id);

        $documents = Storage::disk('s3')->allFiles("folder-{$folder->id}/documents");
        $uploads = Storage::disk('s3')->allFiles("folder-{$folder->id}/uploads");
        return [
            'name' => $folder->name,
            'documents' => $documents,
            'uploads' => $uploads,
        ];
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
        $folder = Folder::findOrFail($id);

        $result = $request->file('file')->storeAs(
            "folder-{$folder->id}/uploads",
            $request->file('file')->getClientOriginalName(),
            "s3"
        );

        return $result;
    }

    public function download(Request $request, $id, $itemId)
    {

        $item = Item::findOrFail($itemId);

        $pathToFile=storage_path()."/app/".$item->path;
        return response()->download($pathToFile, $item->name);

        $file = Storage::disk("local")->get($item->path);

//        return Storage::url($item->path);

        return response()->download(
            $file,
            $item->name
//            [
//                "Content-Type: {$item->mime}",
//                "Content-Disposition: attachment; filename='{$item->name}'"
//            ]
        );

    }

}
