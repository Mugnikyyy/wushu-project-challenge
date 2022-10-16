<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RequestStoreOrUpdateImage;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::orderByDesc('id');
        $images = $images->paginate(50);

        return view('dashboard.images.index', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Image::whereNull('parent_id')->get(['id','title']);
        return view('dashboard.images.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestStoreOrUpdateImage $request)
    {
        $validated = $request->all() + [
            'created_at' => now(),
        ];

        if($request->hasFile('media')){
            $fileName = time() . '.' . $request->media->extension();
            $validated['media'] = $fileName;
            // move file
            $request->media->move(public_path('uploads/images'), $fileName);
        }

        $images = Image::create($validated);

        return redirect(route('galeries.index'))->with('success', 'Image berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Image::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        $parents = Image::whereNull('parent_id')->get(['id','title']);
        return view('dashboard.images.edit', compact('image', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestStoreOrUpdateImage $request, $id)
    {
        $validated = $request->all() + [
            'updated_at' => now(),
        ];

        $images = Image::findOrFail($id);

        $validated['media'] = $images->media;

        if($request->hasFile('media')){
            $fileName = time() . '.' . $request->media->extension();
            $validated['media'] = $fileName;

            // move file
            $request->media->move(public_path('uploads/images'), $fileName);
            
            // delete old file
            $oldPath = public_path('/uploads/images/'.$images->media);
            if($images->media && file_exists($oldPath) && $images->media != 'default.png'){
                unlink($oldPath);
            }
        }

        $images->update($validated);

        return redirect(route('galeries.index'))->with('success', 'Image berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $images = Image::findOrFail($id);

        if(is_null($images->parent_id)){
            $childImages = Image::whereParentId($images->id)->get();
            foreach ($childImages as $key => $image) {
                // delete old file
                $oldPath = public_path('/uploads/images/'.$image->media);
                if($image->media && file_exists($oldPath) && $image->media != 'default.png'){
                    unlink($oldPath);
                }
                $image->delete();
            }
        }

        // delete old file
        $oldPath = public_path('/uploads/images/'.$images->media);
        if($images->media && file_exists($oldPath) && $images->media != 'default.png'){
            unlink($oldPath);
        }
        $images->delete();

        return redirect(route('galeries.index'))->with('success', 'Image berhasil dihapus.');
    }
}
