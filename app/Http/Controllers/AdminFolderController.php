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
        $folder->save();

    }

}
