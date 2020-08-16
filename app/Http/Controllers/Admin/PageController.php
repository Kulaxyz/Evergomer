<?php

namespace App\Http\Controllers\Admin;

use App\FrontendSetting;
use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function mainpage()
    {
        $blocks = Page::where('name', 'mainpage')->first()->blocks;
        $data = json_decode($blocks);

        return view('vendor.frontend.mainpage', compact('data'));
    }

    public function edit(string $page, Request $request)
    {
        $page = Page::where('name', $page)->first();

        $data = [];
        $adv_fields = Page::getAdvFields();

        $top_fields = Page::TOP_AND_MOB_FIELDS;
        $mobile_fields = Page::TOP_AND_MOB_FIELDS;

        $testim_fields = Page::TESTIM_FIELDS;

        $carousels = Page::CAROUSELS;

        foreach ($carousels as $carousel) {
            $fields = $carousel.'_fields';
            $data[$carousel.'_carousel'] = $this->manage_carousels($$fields, $request, $carousel.'-carousel-');
        }

        $mob_data = [];
        foreach ($mobile_fields as $field) {
            $mob_data[$field] = $request->input('mob-'.$field);
        }

        $data['mob_app'] = $mob_data;

        $page->blocks = json_encode($data);
        $page->save();

        return redirect()->back();
    }

    public function gallery()
    {
        $images = json_decode(FrontendSetting::get('gallery_images'));

        return view('vendor.frontend.gallery', compact('images'));
    }

    public function gallery_edit(Request $request)
    {
        $images = $request->gallery_img;
        FrontendSetting::set('gallery_images', $images);

        return redirect()->back();
    }

    private function manage_carousels(array $fields, Request $request, string $type = 'top-carousel-') : array
    {
        $inputs = [];

        //Fill input arrays with data, for ex.: input['title'] = $request->input('top-carousel-title')
        // * Request of carousels values contains arrays

        foreach ($fields as $field) {
            $inputs[$field] =  $request->input($type.$field);
        }

        $items = [];
        $len = count($inputs[$fields[0]]);

        //Fill the block of carousel with items of type array

        for ($i = 0; $i < $len; $i++) {
            foreach ($fields as $field) {
                $items[$i][$field] = $inputs[$field][$i];
            }
        }

        return $items;
    }

}
