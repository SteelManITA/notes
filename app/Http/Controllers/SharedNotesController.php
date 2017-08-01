<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Note;
use App\User;
use App\SharedNotes;

class SharedNotesController extends Controller
{
    public function share(Request $request, $id) {
		$input = $request->intersect(['email']);

		$userId = User::where('email', $input['email'])->first()->id;

		$sharedNote = new SharedNotes;
		$sharedNote->user_id = $userId;
		$sharedNote->note_id = $id;

		$sharedNote->save();

		return json_encode($sharedNote);
	}

}
