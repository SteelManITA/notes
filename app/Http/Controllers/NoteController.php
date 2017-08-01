<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Note;
use App\SharedNotes;

class NoteController extends Controller
{
	private $fields = [];

	public function __construct() {
		$this->fields = Schema::getColumnListing('notes');
	}

	public function index() {
		$userNotesId = (array) DB::table('notes')->where('user_id', Auth::user()->id)->pluck('id');
		$sharedWithUserNoteIds = (array) DB::table('shared-notes')->where('user_id', Auth::user()->id)->pluck('note_id');
		$noteIds = array_merge(reset($sharedWithUserNoteIds), reset($userNotesId));
		$notes = Note::find($noteIds);

		return $notes;
	}

	public function add(Request $request) {
		$input = $request->intersect($this->fields);
		$input['user_id'] = Auth::user()->id;

		$note = new Note;
		$note->fill($input);
		$note->save();

		return json_encode($note);
	}

	public function edit(Request $request, $id) {
		$input = $request->intersect($this->fields);

		$note = Note::find($id);

		foreach ($input as $key => $value) {
			$note->$key = $value;
		}

		$note->save();

		return json_encode($note);
	}
	
	public function delete(Request $request, $id) {
		$note = Note::find($id);
		$note->delete();
		return json_encode($note);
	}

}
