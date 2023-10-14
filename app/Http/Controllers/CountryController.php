<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Shop;
use App\Models\Country;
use App\Models\Continent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\FileService;

class CountryController extends Controller
{

    public function __construct(protected FileService $fileService)
    {
    }

    public function index(Request $request)
    {
        $lists = Country::query();
        $keyword = $request->keyword ?? '';
        $page_numbers = $request->page_numbers;
        $page = $request->page;
        $count = $lists->count();

        if ($request->filled('keyword')) {
            $lists->where('country_name', 'like', "%{$keyword}%")
                ->orwhere('country_en_name', 'like', "%{$keyword}%")
                ->orwhere('sort', 'like', "%{$keyword}%")
                ->orWhereHas('Continent', function ($query) use ($keyword) {
                    $query->where('continent_tw', 'like', "%{$keyword}%")
                        ->orwhere('continent_en', 'like', "%{$keyword}%");
                });
        }

        if ($page_numbers == null) {
            $page_numbers = 10;
        }

        if ($page == null) {
            $page = 1;
        }
        $lists->orderBy('sort', 'asc');
        $lists = $lists->paginate($page_numbers);
        $lists->appends(compact('lists', 'keyword', 'page_numbers'));
        return view('backend.country.index', compact('lists', 'keyword', 'page_numbers', 'page', 'count'));
    }

    public function create()
    {
        $continents = Continent::get();
        return view('backend.country.create', compact('continents'));
    }

    public function store(Request $request)
    {
        $country = Country::create([
            'continent_id' => $request->continent_id,
            'country_name' => $request->country_name,
            'country_en_name' => $request->country_en_name,
            'link' => $request->link,
            'fb_link' => $request->fb_link,
            'ig_link' => $request->ig_link,
            'weibo_link' => $request->weibo_link,
            'sort' => $request->sort,
        ]);

        $img = $this->fileService->imgUpload($request->file('country_photo'), 'country-img');
        $country->update([
            'country_photo' => $img,
        ]);

        return redirect(route('back.country.index'))->with('message', '新增成功!');
    }

    public function edit($id)
    {
        $info = Country::find($id);
        $continents = Continent::get();
        return view('backend.country.edit', compact('info', 'id', 'continents'));
    }

    public function update(Request $request, $id)
    {
        $country = Country::find($id);
        $country->update([
            'continent_id' => $request->continent_id,
            'country_name' => $request->country_name,
            'country_en_name' => $request->country_en_name,
            'link' => $request->link,
            'fb_link' => $request->fb_link,
            'ig_link' => $request->ig_link,
            'weibo_link' => $request->weibo_link,
            'sort' => $request->sort,
        ]);

        if ($request->hasFile('country_photo')) {
            $img = $this->fileService->imgUpload($request->file('country_photo'), 'country-img');
            $country->update([
                'country_photo' => $img,
            ]);
        }
        return redirect(route('back.country.index'))->with('message', '更新成功!');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $Country = Country::find($id);

        $hasType = City::where('country_id', $id)->count();
        if ($hasType)
            return '';
        else {
            $this->fileService->deleteUpload($Country->country_photo);
            $Country->delete();
            return 'success';
        }
    }
}
