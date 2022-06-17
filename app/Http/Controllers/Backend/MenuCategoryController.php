<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Auth;
use Datatables;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\MenuCategoryRequest;

class MenuCategoryController extends Controller {
	public function index(Request $request) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		if ($request->ajax()) {
			$menu_category = MenuCategory::latest()->get();
			return Datatables::of($menu_category)
				->addIndexColumn()
				->addColumn('created_at', function ($row) {
					$date = '';
					$date = isset($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('jS F, Y') : '-';
					return $date;
				})
				->addColumn('action', function ($row) {
					$btn = '';
					$btn .= '<a href="javascript:void(0)" onclick="return CategoryEditFunction(' . $row->id . ')" class="list-icons-item text-primary" data-popup="tooltip" title="" data-container="body" data-original-title="Edit"><i class="icon-pencil7 edt-icn"></i></a>';
					$btn .= '<a href="javascript:void(0)" onclick="return deleteCategory(' . $row->id . ',this)" class="list-icons-item text-danger ml-5" data-popup="tooltip" title="" data-container="body" data-original-title="Delete"><i class="icon-bin trsh-icn"></i></a>';
					return $btn;
				})
				->rawColumns(['action', 'created_at'])
				->make(true);
		}
		return view('backend.category.list');
	}

	public function saveCategory(MenuCategoryRequest $request) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		$menu_category = new MenuCategory;
		$menu_category->name = $request->category_name;
		$menu_category->save();
		return response()->json(['success' => 'Menu category added successfully.', 'status' => 1], 200);
	}

	public function editCategory($id) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		if ($id) {
			$menu_category = MenuCategory::find($id);
			return response()->json(['data' => $menu_category, 'success' => 'Menu category details.', 'status' => 1], 200);
		} else {
			return response()->json(['data' => null, 'error' => 'Category Not Found.', 'status' => 0], 400);
		}
	}

	public function updateCategory(MenuCategoryRequest $request) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		$menu_category = MenuCategory::find($request->cat_id);
		$menu_category->name = $request->category_name;
		$menu_category->save();
		return response()->json(['success' => 'Menu category updated successfully.', 'status' => 1], 200);
	}

	public function deleteCategory($id) {
		if (!Auth::guard('admin')->user()) {
			return redirect()->route('admin.login');
		}
		if ($id) {
			$menu_category = MenuCategory::find($id);
			$menu_category->delete();
			return response()->json(['success' => 'Menu category deleted successfully.', 'status' => 1], 200);
		} else {
			return redirect()->route('admin.manage-category');
		}
	}
}
