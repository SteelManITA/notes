<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use App\Note;

class NoteController extends Controller
{
	private $fields = [];

	public function __construct() {
		$this->fields = Schema::getColumnListing('notes');
	}

	public function index() {
		return Note::where('user_id', Auth::user()->id)->orderBy('updated_at')->get();
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
