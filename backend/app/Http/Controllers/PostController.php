<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    const LOCAL_STORAGE_FOLDER = 'public/images/';

    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    // index()
    public function index()
    {
        $all_posts = $this->post->latest()->get();

        return view('posts.index')
            ->with('all_posts', $all_posts);
    }

    // create() - view/open the create post page
    public function create()
    {
        return view('posts.create');
    }

    // store()
    public function store(Request $request)
    {
        #validate the request
        $request->validate([
            'title' => 'required|min:1|max:50', 
            'body' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:1048'
        ]);
        // mimes - multipurpose internet mail extensions

        # Save the request to db
        $this->post->user_id = Auth::user()->id;
        // Auth::user()->id - is the id of logged in user
        $this->post->title = $request->title;
        $this->post->body = $request->body;
        $this->post->image = $this->saveImage($request);

        $this->post->save();

        # Back to homepage
        return redirect()->route('index');
    }

    // saveImage()
    public function saveImage($request)
    {
        // Change the name of the image to CURRENT TIME to avoid overwriting.
        $image_name = time() . "." . $request->image->extension();
        // $image_name = '1690173847.jpg';

        // save the image inside of local storage (storage/app/public/images)
        $request->image->storeAs(self::LOCAL_STORAGE_FOLDER, $image_name);

        return $image_name;
    }

    // show() - open/view the page show and display the details of the post
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('posts.show')
            ->with('post', $post);
    }

    // edit() - open/view the edit page and display the details of the post
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        return view('posts.edit')
            ->with('post', $post);
    }

    // update() - save changes of the post
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:1|max:50', 
            'body' => 'required|min:1|max:1000',
            'image' => 'mimes:jpg,jpeg,png,gif|max:1048'
        ]);

        $post = $this->post->findOrFail($id);
        $post->title = $request->title;
        $post->body = $request->body;

        # If there is a new image, 
        if($request->image){
            # Delete the previous image from the local storage
            $this->deleteImage($post->image);

            # Move the new image to local storage
            $post->image = $this->saveImage($request);
            // $post-image = 11111.jpg'
        }
        $post->save();

        return redirect()->route('post.show', $id);
    }

    // deleteImage()
    public function deleteImage($image_name)
    {
        $image_path = self::LOCAL_STORAGE_FOLDER . $image_name;
        // $image_path = 'public/images/111111.jpg';

        if(Storage::disk('local')->exists($image_path)){
            // storage/app/public/images/111111.jpg;
            Storage::disk('local')->delete($image_path);
        }
    }

    // destroy()
    public function destroy($id)
    {
        $post = $this->post->findOrFail($id);
        $this->deleteImage($post->image);
        $this->post->destroy($id);
    
        return redirect()->back();
    }
}
