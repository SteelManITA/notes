<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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

	public function getUsersWithNote(Request $request, $id) {
		$userIds = (array) DB::table('shared-notes')->where('note_id', $id)->pluck('user_id');
		$userIds = reset($userIds);

		$users = User::find($userIds);

		return json_encode($users);
	}

}
