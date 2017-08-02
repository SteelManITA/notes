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

	public function get() {
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

		return $note;
	}

	public function edit(Request $request, $note_id) {
		$input = $request->intersect($this->fields);

		$note = Note::find($note_id);

		foreach ($input as $key => $value) {
			$note->$key = $value;
		}

		$note->save();

		return $note;
	}
	
	public function delete(Request $request, $note_id) {
		$note = Note::find($note_id);
		$note->delete();
		return $note;
	}

}
