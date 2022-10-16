<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\News;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.index');
    }

    public function wushuHome()
    {
        return view('wushu-project.home');
    }

    public function wushuNews()
    {
        $news = News::latest()->paginate(4);
        $relatedNews = News::latest()->paginate(3);
        return view('wushu-project.news', compact('news', 'relatedNews'));
    }

    public function wushuNewsDetail($newsId)
    {
        $news = News::findOrFail($newsId);
        $relatedNews = News::latest()->paginate(3);
        return view('wushu-project.news-detail', compact('news', 'relatedNews'));
    }

    public function wushuGaleries()
    {
        $galeries = Image::whereNull('parent_id')->latest()->paginate(6);
        return view('wushu-project.galeries', compact('galeries'));
    }

    public function wushuGaleriesDetail($galeryId)
    {
        $detailGalery = Image::findOrFail($galeryId);
        $galeries = Image::whereNotNull('parent_id')->whereParentId($galeryId)->paginate(12);
        return view('wushu-project.galeries-detail', compact('galeries', 'detailGalery'));   
    }

    public function wushuAbout($who)
    {
        $view = 'about-';
        if($who == str()->lower('8thWJWC')){
            $view .= str()->lower('8thWJWC');
        }elseif($who == str()->lower('IWUF')){
            $view .= str()->lower('IWUF');
        }else{
            $view .= 'tangerang';
        }

        return view('wushu-project.' . $view);
    }

    public function wushuCompetition($what)
    {
        $view = 'competition-';
        if($what == 'numbers'){
            $view .= 'numbers';
        }elseif($what == 'schedules'){
            $view .= 'schedules';
        }else{
            $view .= 'medals';
        }

        return view('wushu-project.' . $view);
    }
}
