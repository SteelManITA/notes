<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Note;
use App\SharedNotes;

class SharedNotesController extends Controller
{
	public function get(Request $request, $note_id) {
		$collaboratorIds = (array) DB::table('shared-notes')->where('note_id', $note_id)->pluck('user_id');
		$collaboratorIds = reset($collaboratorIds);
		$collaborators = User::find($collaboratorIds);



		$availableUsers = (array) User::get();
		$availableUsers = reset($availableUsers);
		array_splice($availableUsers, array_search(User::find(Auth::user()->id), $availableUsers), 1);

		foreach ($collaborators as $collaborator) {
			array_splice($availableUsers, array_search($collaborator, $availableUsers), 1);
		}

		return [
			'owner' => User::find(Note::where('id', $note_id)->value('user_id')),
			'collaborators' => $collaborators,
			'availableUsers' => $availableUsers,
		];
	}

    public function add(Request $request, $note_id) {
		$input = $request->intersect(['email']);

		$userId = User::where('email', $input['email'])->firstOrFail()->id;

		$alreadyExists = DB::table('shared-notes')->where('note_id', $note_id)->where('user_id', $userId)->count();

		if ($alreadyExists == 0 && $userId != Auth::user()->id) {
			$sharedNote = new SharedNotes;
			$sharedNote->user_id = $userId;
			$sharedNote->note_id = $note_id;

			$sharedNote->save();

			return $sharedNote;
		}

		return [
			'error' => 'Si sta tentando di aggiungere un collaboratore già presente o non registrato o se stessi.'
		];
	}

	public function delete(Request $request, $note_id, $collaborator_id) {
		$collaborator = SharedNotes::where('note_id', $note_id)->where('user_id', $collaborator_id);
		$collaborator->delete();
		return json_encode($collaborator);
	}

}
