<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;

class CommonController extends Controller {
	public function getStates($id) {
		$states = State::where("country_id", $id)->pluck("name", "id");
		return response()->json($states);
	}

	//For fetching cities
	public function getCities($id) {
		$cities = City::where("state_id", $id)->pluck("name", "id");
		return response()->json($cities);
	}
}
