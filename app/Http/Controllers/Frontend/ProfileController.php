<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CommonController;
use App\Models\User;
use App\Models\Newsfeed;
use App\Models\Follower;
use Auth;
use DB;
use Illuminate\Http\Request;

class ProfileController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($id=null) {
		$common  = new CommonController;
		if($id != null){
			$user_id = $common->decrypt($id);
		}else{
			if(!Auth::guard('users')->check()){
				return redirect()->route('front.login');
			}else{
				$user_id  = Auth::guard('users')->user()->id;
			}
		}
		$followers_count = $common->profileFollowerCount($user_id);
		$feeds = $this->listFeeds($user_id);
		return view('frontend.profile.profile', compact('feeds', 'followers_count'));
	}

	public function listFeeds($user_id)
	{
		$feed = Newsfeed::with('user.user_meta')->withCount('user_following')->withCount('likes')->where('user_id', $user_id);
		if (!Auth::guard('users')->check()) {
			$feeds = $feed->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		} else {
			$feeds = $feed->with('user_like')->with('user.followers')->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		}
		return $feeds;
	}

	public function getUserNewsFeedIds(Request $request, $id) {
		$common  = new CommonController;
		if(!$id){
			return response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
		}
		$user_id = $common->decrypt($id);
		if ($request->ids == null) {
			return response()->json(array('data' => null, 'error' => 'No data found.', 'status' => 0), 400);
		}
		$feed = Newsfeed::whereNotIn('id',$request->ids)->with('user.user_meta')->withCount('user_following')->withCount('likes')->where('user_id', $user_id);
		if (!Auth::guard('users')->check()) {
			$feeds = $feed->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		} else {
			$feeds = $feed->with('user_like')->with('user.followers')->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		}
		if(count($feeds) > 0){
			$returnHTML = view('frontend.profile.list-feed', compact('feeds'))->render();
			return response()->json(array('data' => $returnHTML, 'success' => 'News feed list.', 'status' => 1), 200);
		}
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}
