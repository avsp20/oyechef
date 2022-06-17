<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Datatables;
use Illuminate\Http\Request;

class UserController extends Controller {
	public function index(Request $request) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		if ($request->ajax()) {
			// $users = User::where('role_id', '!=', 1)->latest()->get();
			$users = User::with(['user_meta' => function ($query) {
				$query->where('role_id', 2);
			}])->latest()->get();
			return Datatables::of($users)
				->addIndexColumn()
				->addColumn('status', function ($row) {
					$status = '';
					if ($row->status == 1) {
						$status .= '<label class="switch"><input type="checkbox" onchange="activeUser(' . $row->id . ',0)" id="status_' . $row->id . '" checked=""><span class="slider round"></span></label>';
					} else {
						$status .= '<label class="switch"><input type="checkbox" onchange="activeUser(' . $row->id . ',1)" id="status_' . $row->id . '"><span class="slider round"></span></label>';
					}
					return $status;
				})
				->addColumn('name', function ($row) {
					$name = '';
					$name = ucfirst($row->name) . (($row->user_meta != null) ? ' ' . ucfirst($row->user_meta->lname) : ''); //. ' ' . ucwords($row->lname);
					return $name;
				})
				->addColumn('phone', function ($row) {
					$phone = ($row->user_meta != null) ? ' ' . $row->user_meta->phone : '';
					return $phone;
				})
				->addColumn('created_at', function ($row) {
					$date = '';
					$date = isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('jS F, Y') : '-';
					return $date;
				})
				->addColumn('user_image', function ($row) {
					$image = '';
					if ($row->user_meta != null) {
						if ($row->user_meta->user_image != null) {
							$image_url = asset('public/frontend/img/user_profiles/' . $row->user_meta->user_image);
							$image = '<img src="' . $image_url . '" style="width: 100%;">';
						}
					}
					return $image;
				})
				->addColumn('action', function ($row) {
					$btn = '';
					$btn .= '<a href="javascript:void(0)" onclick="return deleteUser(' . $row->id . ',this)" class="list-icons-item text-danger ml-5" data-popup="tooltip" title="" data-container="body" data-original-title="Delete"><i class="icon-bin trsh-icn"></i></a>';
					return $btn;
				})
				->rawColumns(['user_image', 'action', 'status', 'name', 'created_at', 'phone'])
				->make(true);
		}
		return view('backend.users.list');
	}

	public function deleteUser($id) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		if ($id) {
			$user = User::find($id);
			$user->delete();
			return response()->json(['success' => 'User deleted successfully.', 'status' => 1], 200);
		} else {
			return redirect()->route('admin.manage-users');
		}
	}

	public function userStatus($id, $status) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		$user = User::find($id);
		$user->status = $status;
		$user->save();
		return response()->json(['success' => 'User status updated successfully.', 'status' => 1], 200);
	}
}
