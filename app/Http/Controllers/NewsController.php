<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RequestStoreOrUpdateNews;
use App\Models\News;
use Illuminate\Support\Facades\Hash;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::orderByDesc('id');
        $news = $news->paginate(50);

        return view('dashboard.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.news.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestStoreOrUpdateNews $request)
    {
        $validated = $request->validated() + [
            'created_at' => now(),
        ];

        $validated['image'] = 'default.png';
        if($request->hasFile('image')){
            $fileName = time() . '.' . $request->image->extension();
            $validated['image'] = $fileName;
            // move file
            $request->image->move(public_path('uploads/images'), $fileName);
        }

        $news = News::create($validated);

        return redirect(route('news.index'))->with('success', 'News berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return News::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('dashboard.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RequestStoreOrUpdateNews $request, $id)
    {
        $validated = $request->validated() + [
            'updated_at' => now(),
        ];

        $news = News::findOrFail($id);

        $validated['image'] = $news->image;

        if($request->hasFile('avatar')){
            $fileName = time() . '.' . $request->image->extension();
            $validated['image'] = $fileName;

            // move file
            $request->image->move(public_path('uploads/images'), $fileName);
            
            // delete old file
            $oldPath = public_path('/uploads/images/'.$news->image);
            if($news->image && file_exists($oldPath) && $news->image != 'default.png'){
                unlink($oldPath);
            }
        }

        $news->update($validated);

        return redirect(route('news.index'))->with('success', 'News berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        // delete old file
        $oldPath = public_path('/uploads/images/'.$news->image);
        if($news->image && file_exists($oldPath) && $news->image != 'default.png'){
            unlink($oldPath);
        }
        $news->delete();

        return redirect(route('news.index'))->with('success', 'News berhasil dihapus.');
    }
}
