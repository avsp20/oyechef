<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\CommonController;
use App\Models\Follower;
use App\Models\Comment;
use App\Models\Newsfeed;
use App\Models\User;
use App\Models\NewsFeedLikes;
use App\Models\UserFollower;
use App\Models\UserFollowing;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Frontend\NewsFeedRequest;

class NewsFeedController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$feeds = $this->listFeeds();
		return view('frontend.news_feed.news_feed', compact('feeds'));
	}

	public function listFeeds()
	{
		$feed = Newsfeed::with('user.user_meta')->withCount('user_following')->withCount('likes');
		if (!Auth::guard('users')->check()) {
			$feeds = $feed->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		} else {
			$feeds = $feed->with('user_like')->with('user.followers')->withCount('post_comments')->with('post_comments')->orderBy('created_at', 'DESC')->take(12)->get();
		}
		return $feeds;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	public function loadFeedsOnScroll(Request $request, $page)
	{
		$feeds = array();
		if($page > 1){
			$feeds = $this->listFeeds();
		}
		if ($request->ajax()) {
			return view('frontend.profile.list-feed', compact('feeds'));
		}
	}

	public function getNewsFeedIds(Request $request) {
		if ($request->ids == null) {
			return response()->json(array('data' => null, 'error' => 'No data found.', 'status' => 0), 400);
		}
		$feed = Newsfeed::whereNotIn('id',$request->ids)->with('user.user_meta')->withCount('user_following')->withCount('likes');
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

	public function likeUserFeed($id) {
		if ($id) {
			$common  = new CommonController;
			$feed_id = $common->decrypt($id);
			if (!Auth::guard('users')->check()) {
				return response()->json(['error' => 'You need to do login first.', 'status' => 0], 400);
			}
			if (!$feed_id) {
				return response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
			}
			$feeds      = NewsFeedLikes::where('user_id', Auth::guard('users')->user()->id)->where('post_id', $feed_id);
			$news_feeds = $feeds->first();

			// News feed count
			$news_feed_count = NewsFeedLikes::where('post_id', $feed_id)->count();

			if ($news_feeds) {
				$news_feeds->delete();
				$data = [
					'feed_id'    => $feed_id,
					'feed_count' => $news_feed_count - 1,
				];
				return response()->json(['data' => $data, 'success' => 'News feed liked.', 'status' => 0], 200);
			} else {
				NewsFeedLikes::create([
					'user_id' => Auth::guard('users')->user()->id,
					'post_id' => $feed_id,
				]);
				$data = [
					'feed_id'    => $feed_id,
					'feed_count' => $news_feed_count + 1,
				];
				return response()->json(['data' => $data, 'success' => 'News feed liked.', 'status' => 1], 200);
			}
		} else {
			return response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
		}
	}

	public function followUserFeed($id) {
		if ($id) {
			$common  = new CommonController;
			$user_id = $common->decrypt($id);
			if (!$user_id) {
				return redirect()->back()->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
			}
			$feeds      = Follower::where('follower_id', Auth::guard('users')->user()->id)->where('following_id', $user_id);
			$news_feeds = $feeds->first();

			// News feed count
			$news_feed_count = Follower::where('following_id', $user_id)->count();

			if ($news_feeds) {
				$news_feeds->delete();
				$data = [
					'follower_id' => $user_id,
					'feed_count'  => $news_feed_count - 1,
				];
				return redirect()->back()->with(['content' => 'User unfollowed successfully.', 'level' => 'success']);
			} else {
				Follower::create([
					'follower_id'  => Auth::guard('users')->user()->id,
					'following_id' => $user_id,
				]);
				$data = [
					'follower_id' => $user_id,
					'feed_count'  => $news_feed_count + 1,
				];
				return redirect()->back()->with(['content' => 'User followed successfully.', 'level' => 'success']);
			}
		} else {
			return redirect()->back()->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function displayComments($post_id)
	{
		$common  = new CommonController;
		$response = response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
		if (!$post_id) {
			return $response;
		}
		$post_id = $common->decrypt($post_id);
		$post_comments = Newsfeed::with('post_comments.user')->where('id',$post_id)->first();
		return view('frontend.profile.post-comments',['post_comments' => $post_comments])->render();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(NewsFeedRequest $request) {
		if(!Auth::guard('users')->check()){
			return redirect()->route('front.login');
		}
		$output = array();
		$news_feed          = new Newsfeed();
		$news_feed->content = $request->content;
		$news_feed->user_id = Auth::guard('users')->user()->id;
		if ($request->file('file')) {
			$mime      = $request->file('file');
			$file_type = $mime->getClientMimeType();
			if (str_contains($file_type, 'video/')) {
				$news_feed->file_type = $mime->getClientMimeType();
			} elseif (str_contains($file_type, 'image/')) {
				$news_feed->file_type = $mime->getClientMimeType();
			}
			$imageName = time() . '.' . $request->file->extension();
			$request->file->move(public_path('/frontend/img/feeds/'), $imageName);
			$news_feed->file = $imageName;
		}
		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $request->content, $match);
		if(!empty($match)){ 
			$string = $request->content;
			$regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
		    $url = current($match[0]);
		    $resp = [];
		    $ch = curl_init();
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		    $data = curl_exec($ch);
		    curl_close($ch);
		    // Load HTML to DOM Object
		    $dom = new \DOMDocument();
		    @$dom->loadHTML($data);

		    // Parse DOM to get Title
		    $nodes = $dom->getElementsByTagName('title');
		    $title = '';
		    if($nodes->length > 0){
		        $title = $nodes->item(0)->nodeValue;
		    }
		    // Parse DOM to get Meta Description
		    $metas = $dom->getElementsByTagName('meta');
		    $body = "";
		    $imageURL = "";
		    for ($i = 0; $i < $metas->length; $i ++) {
		        $meta = $metas->item($i);
		        if ($meta->getAttribute('name') == 'Description' || $meta->getAttribute('name') == 'description' || $meta->getAttribute('name') == 'twitter:description') {
		            $body = $meta->getAttribute('content');
		        }
		        if ($meta->getAttribute('property') == 'og:image:url') {
		            $imageURL = $meta->getAttribute('content');
		        }elseif($meta->getAttribute('property') == 'og:image'){
		            $imageURL = $meta->getAttribute('content');
		        }
		    }
		    $images = $dom->getElementsByTagName('img');

		    if(!isset($imageURL) && $images->length > 0){
		        $imageURL = $images->item(0)->getAttribute('src');
		    }

		    $output = array(
		        'title' => $title,
		        'image_url' => $imageURL,
		        'body' => preg_replace('/[^\00-\255]+/u', '', $body)
		    );
		    $news_feed->content = preg_replace($regex, ' ', $string);
		    $news_feed->meta_title = $output['title'];
		    $news_feed->meta_description = $output['body'];
		    $news_feed->meta_image = $output['image_url'];
		    $news_feed->content_url = current($match[0]);
	    }
		if ($news_feed->save()) {
			return redirect()->route('news-feed.index')->with(['content' => 'Your content shared successfully.', 'level' => 'success']);
		} else {
			return redirect()->route('news-feed.index')->with(['content' => 'Something went wrong, Please try again later.', 'level' => 'error']);
		}
	}

	public function removeFollowing(Request $request) {
		$response = response()->json(array('data' => 'error', 'responseMessage' => 'Something went wrong, try again later.'), 400);
		if (!$request->id) {
			return $response;
		}
		$common  = new CommonController;
		$user_id = $common->decrypt($request->id);
		Follower::where('id', $user_id)->delete();
		return response()->json(array('data' => 'success', 'responseMessage' => 'You have unfollowed user successfully.'), 200);
	}

	public function addComments(Request $request)
	{
		$common  = new CommonController;
		$post_id = $common->decrypt($request->feed);
		if (!Auth::guard('users')->check()) {
			return response()->json(['error' => 'You need to do login first.', 'status' => 0], 400);
		}
		if (!$post_id) {
			return response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
		}
		$comment = new Comment();
		$comment->post_id = $post_id;
		$comment->user_id = Auth::guard('users')->user()->id;
		$comment->comment = $request->comment;
		if($comment->save()){
			$comments = Comment::with('user.user_meta')->where('id',$comment->id)->first();
			return response()->json(['data' => $comments, 'success' => 'Comment added successfully.', 'status' => 1], 200);
		}else{
			return response()->json(['error' => 'Something went wrong, try again later.', 'status' => 0], 400);
		}
	}

	public function getUserFollowers(Request $request)
	{
		$common  = new CommonController;
		$followers_arr = array();
		$text = $request->term;
		if($text != null){
			if(Auth::guard('users')->check()){
				$followers = User::with('user_meta')->with('followers')->whereHas('user_meta', function($query) use($text) {
					$query->where('role_id',2);
					$query->where('lname', 'like', '%' .$text. '%');
					$query->orWhere('username', 'like', '%' .$text. '%');
				})->orWhere('name', 'like', '%' .$text. '%')->get();
			}else{
				$followers = User::with('user_meta')->whereHas('user_meta', function($query) use($text) {
					$query->where('role_id',2);
					$query->where('lname', 'like', '%' .$text. '%');
					$query->orWhere('username', 'like', '%' .$text. '%');
				})->orWhere('name', 'like', '%' .$text. '%')->get();
			}
			if(count($followers) > 0){
				foreach ($followers as $key => $value) {
					$followers_arr[] = $value;
					$followers_arr[$key]['user_id'] = $common->encrypt($value->id);
				}
				return response()->json(['data' => $followers, 'success' => 'Search followers.', 'status' => 1], 200);
			}else{
				return response()->json(['data' => null, 'error' => 'No users found as per your search.', 'status' => 0], 400);
			}
		}else{
			if(!Auth::guard('users')->check()){
				return response()->json(['data' => null, 'error' => 'Please login to search your followers.', 'status' => 0], 400);
			}
			$followers = User::with('user_meta')->whereHas('user_meta', function($query){
					$query->where('role_id',2);
				})->with('followers')->has('followers')->get();
			if(count($followers) > 0){
				foreach ($followers as $key => $value) {
					$followers_arr[] = $value;
					$followers_arr[$key]['user_id'] = $common->encrypt($value->id);
				}
				return response()->json(['data' => $followers, 'success' => 'Search followers.', 'status' => 1], 200);
			}else{
				return response()->json(['data' => null, 'error' => 'No users found.', 'status' => 0], 400);
			}
		}
	}

	public function deleteComments(Request $request)
	{
		$common  = new CommonController;
		$comment_id = $common->decrypt($request->comment_id);
		$comment = Comment::findOrFail($comment_id);
		if(!$comment){
			return response()->json(['error' => 'Comment Not Found.', 'status' => 0], 400);
		}
		$comment->delete();
		return response()->json(['data' => $comment, 'success' => 'Comment deleted successfully.', 'status' => 1], 200);
	}

	public function editComments(Request $request)
	{
		$comment = Comment::findOrFail($request->comment_id);
		if(!$comment){
			return response()->json(['error' => 'Comment Not Found.', 'status' => 0], 400);
		}
		Comment::where('id',$request->comment_id)->update([
			'comment' => $request->comment
		]);
		$comments = Comment::findOrFail($request->comment_id);
		return response()->json(['data' => $comments, 'success' => 'Comment updated successfully.', 'status' => 1], 200);
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
