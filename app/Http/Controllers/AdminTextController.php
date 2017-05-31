<?php

namespace App\Http\Controllers;

use App\Text;
use Illuminate\Http\Request;

class AdminTextController extends Controller
{
    public function index()
    {
        return Text::all();
    }

    public function update(Request $request, $id) {

        $text = Text::findOrFail($id);
        $text->headline = $request->headline;
        $text->body = $request->body;
        $text->prio = $request->prio;
        $text->save();

    }

    public function destroy($id) {
        $text = Text::findOrFail($id);
        $text->delete();
    }

    public function store(Request $request) {
        $text = new Text();
        $text->headline = $request->headline;
        $text->body = $request->body;
        $text->prio = $request->prio;
        $text->save();
        return $text;
    }
}
