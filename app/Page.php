<?php

namespace App;

use App\Traits\SavesImages;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use SavesImages;

    protected $guarded = ['id'];

    const CAROUSELS = ['top', 'testim', 'adv'];
    const TOP_AND_MOB_FIELDS = ['title', 'href', 'subtitle', 'text', 'btn', 'img'];
    const TESTIM_FIELDS = ['name', 'status','text', 'img'];

    protected $casts = [
        'blocks' => 'array',
    ];

//    public function saveImg($image)
//    {
//        return $this->storeImage($image, null, 'public/images/frontend');
//    }

    public static function getAdvFields() : array
    {
        $adv_fields = [];
        for ($i = 1; $i <= 4; $i++) {
            $adv_fields[] = 'title'.$i;
            $adv_fields[] = 'text'.$i;
            $adv_fields[] = 'icon'.$i;
        }

        return $adv_fields;
    }

    public function getSlugAttribute()
    {
        return strtolower(str_replace(' ', '_', $this->name));
    }

}
