<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;

use Auth;
use Validator;
use App\Models\User;
use App\Models\Movie;
use App\Models\Author;
use App\Models\Genre;
use App\Models\MovieTag;
use App\Models\Rating;
use App\Models\Comment;

use DB;
use PDF;
use Storage;
use Carbon\Carbon;
use File;

use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    use ApiResponser;
    
    public function index() 
    {

        try {

            $authUser = Auth::user();
            $data = [];
            $movies = DB::table('movies')
                        ->leftjoin('authors', 'movies.author_id', '=', 'authors.id')
                        ->leftjoin('genres', 'movies.genre_id', '=', 'genres.id')
                        ->select(
                            'movies.id',
                            'movies.title',
                            'movies.summary',
                            'movies.cover_image',
                            'authors.name as author_name',
                            'genres.name as genre_name',
                        )
                        ->orderBy('movies.created_at', 'DESC')
                        ->paginate(5);
            foreach($movies as $movie){
                $data[] = [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'summary' => $movie->summary,
                    'cover_image' => ($movie->cover_image != null) ? asset('images/' . $movie->cover_image) : asset('images/default.jpg'),
                    'author' => $movie->author_name,
                    'genre' => $movie->genre_name,
                ];
            }
    
            return $this->paginateSuccessResponseWithArrayData($movies, $data, Response::HTTP_OK);

        } catch(\Exception $e) {

            Log::error("API Movie List Exception: $e");
            return $this->errorResponse('Something went wrong!', 500);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'summary' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'required|exists:genres,id',
        ];

        $messages = [
            'title.required' => 'The title field is required',
            'summary.required' => 'The summary field is required',
            'cover_image.required' => 'The image field is required',

            'author_id.required' => 'The author is required.',
            'author_id.exists' => 'The author is not found.',

            'genre_id.required' => 'The genre is required.',
            'genre_id.exists' => 'The genre is not found.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 400);
        }

        try {

            $authUser = Auth::user();

            if ($request->hasFile('cover_image')) {
                $coverImageName = time() . '.' . $request->cover_image->extension();
                $request->cover_image->move(public_path('images'), $coverImageName);
            }

            $data = [
                'title' => $request->title,
                'summary' => $request->summary, 
                'author_id' => $request->author_id,
                'user_id' => $authUser->id,
                'genre_id' => $request->genre_id,
            ];

            if (!empty($coverImageName)) {
                $data['cover_image'] = $coverImageName;
            }

            $movie = Movie::create($data);

            if (!empty($request->tag)) {
                $tag_ids = array_map(
                        function($value) { return (int)$value; },
                        $request->tag
                    );
                $movie->tags()->attach($tag_ids, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            DB::commit();

            return $this->successResponse([], Response::HTTP_OK, 'You have successfully store movie.');

        } catch(\Exception $e) {
            
            DB::rollback();
            Log::error("API Movie Store Exception: {$e}");
            return $this->errorResponse('Something went wrong!', 500);
        }

    }

    public function show($id)
    {
        try {

            $ratings = 0;
            $comments = [];
            $tags = [];
            $related_movies = [];
            $movie_ids_by_ratings = [];
            $movie_ids_by_tags = [];
            $movie_ids = [];
            $same_ratings = [];

            $movie = Movie::find($id);

            if(!isset($movie)){
                return $this->errorResponse('Movie Not Found!', 400);
            }
            
            $ratings = $movie->ratings()->sum('rating');
            $comments = $movie->comments()->select('id','comment')->get()->toArray();
            $tags = $movie->tags()->pluck('name')->toArray();

            $same_ratings = $movie->ratings()->pluck('rating')->toArray();
            $movie_ids_by_ratings = Rating::whereIn('rating', $same_ratings)->pluck('movie_id')->toArray();
            $movie_ids_by_tags = MovieTag::whereIn('tag_id', $tags)->pluck('movie_id')->toArray();

            $movie_ids = array_merge($movie_ids_by_ratings,$movie_ids_by_tags);

            $movies = Movie::Where('author_id',$movie->genre->id)->orWhere('author_id',$movie->author->id)->orWhereIn('id', $movie_ids)->take(7)->get();

            foreach($movies as $m){
                $related_movies[] = [
                    'title' => $m->title,
                    'summary' => $m->summary,
                    'cover_image' => ($m->cover_image != null) ? asset('images/' . $m->cover_image) : asset('images/default.jpg'),
                    'author' => $m->author->name,
                    'genre' => $m->genre->name,
                ];
            }

            $data = [
                'title' => $movie->title,
                'summary' => $movie->summary,
                'cover_image' => ($movie->cover_image != null) ? asset('images/' . $movie->cover_image) : asset('images/default.jpg'),
                'author' => $movie->author->name,
                'genre' => $movie->genre->name,
                'ratings' => $ratings,
                'tags' => $tags,
                'comments' => $comments,
                'related_movies' => $related_movies
            ];

            $filename = "movie_".$movie->id.'_'.Carbon::now()->toDateString();
            $path = storage_path('pdf');

            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0755, true, true);
            } 

            $pdf = PDF::loadView('myPDF', $data)->save(''.$path.'/'.$filename.'.pdf');

            $data['pdf'] = asset('/storage/pdf/'.$filename.'.pdf');

            return $this->successResponse($data, Response::HTTP_OK);

        } catch(\Exception $e) {

            Log::error("API Movie Show Exception: $e");
            return $this->errorResponse('Something went wrong!', 500);
        }

    }

    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'summary' => 'required',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_id' => 'required|exists:authors,id',
            'genre_id' => 'required|exists:genres,id',
        ];

        $messages = [
            'title.required' => 'The title field is required',
            'summary.required' => 'The summary field is required',
            'cover_image.required' => 'The image field is required',

            'author_id.required' => 'The author is required.',
            'author_id.exists' => 'The author is not found.',

            'genre_id.required' => 'The genre is required.',
            'genre_id.exists' => 'The genre is not found.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 400);
        }

        try {

            $authUser = Auth::user();

            if ($request->hasFile('cover_image')) {
                $coverImageName = time() . '.' . $request->cover_image->extension();
                $request->cover_image->move(public_path('images'), $coverImageName);
            }

            $movie = Movie::find($id);

            if(!isset($movie)){
                return $this->errorResponse('Movie Not Found!', 400);
            }

            $movie->title = $request->title;
            $movie->summary = $request->summary;
            $movie->cover_image = !empty($coverImageName) ? $coverImageName : $movie->cover_image;
            $movie->user_id = $authUser->id;
            $movie->author_id = $request->author_id;
            $movie->genre_id = $request->genre_id;
            $movie->save();


            if (!empty($request->tag)) {
                $tag_ids = array_map(
                        function($value) { return (int)$value; },
                        $request->tag
                    );
                $movie->tags()->sync($tag_ids, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            DB::commit();

            return $this->successResponse([], Response::HTTP_OK, 'You have successfully update movie.');

        } catch(\Exception $e) {
            
            DB::rollback();
            Log::error("API Movie Update Exception: {$e}");
            return $this->errorResponse('Something went wrong!', 500);
        }

    }

    public function destory(Request $request, $id)
    {
        try {

            $authUser = Auth::user();

            $movie = Movie::find($id);

            if(!isset($movie)){
                return $this->errorResponse('Movie Not Found!', 400);
            }

            if($authUser->id == $movie->user_id) {

                $result = $movie->delete();
                return $this->successResponse([], Response::HTTP_OK, 'You have successfully delete movie.');

            }

            return $this->errorResponse('You have not delete this video', 400);

        } catch (\Exception $e) {

            Log::error("API Movie Delete Exception: {$e}");
            return $this->errorResponse('Something went wrong!', 500);
        }
    }

    public function storeComment(Request $request, $movie_id)
    {
        $rules = [
            'comment' => 'required',
        ];

        $messages = [
            'comment.required' => 'The comment field is required',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 400);
        }

        try {

            $authUser = Auth::user();

            $data = [
                'comment' => $request->comment,
                'user_id' => $authUser->id,
                'movie_id' => $movie_id,
            ];

            $comment = Comment::create($data);

            DB::commit();

            return $this->successResponse([], Response::HTTP_OK, 'You have successfully create comment.');

        } catch(\Exception $e) {
            
            DB::rollback();
            Log::error("API Comment Exception: {$e}");
            return $this->errorResponse('Something went wrong!', 500);
        }
    }
}
