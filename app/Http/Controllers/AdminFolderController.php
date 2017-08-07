<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;

class AdminFolderController extends Controller
{
    public function index()
    {
        return Folder::all();
    }

    public function update(Request $request, $id) {

        $folder = Folder::findOrFail($id);
        $folder->name = $request->name;
        $folder->passcode = $request->passcode;
        $folder->prio = $request->prio;
        $folder->documents = (bool)$request->documents;
        $folder->uploads = (bool)$request->uploads;
        $folder->save();

    }

    public function destroy($id) {
        $folder = Folder::findOrFail($id);
        $folder->delete();
    }

    public function store(Request $request) {
        $folder = new Folder();
        $folder->name = $request->name;
        $folder->passcode = $request->passcode;
        $folder->prio = $request->prio;
        $folder->documents = $request->documents;
        $folder->uploads = $request->uploads;
        $folder->save();
        return $folder;
    }

}
