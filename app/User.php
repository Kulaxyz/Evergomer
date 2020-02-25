<?php

namespace App;

use App\Interfaces\MustVerifyPhoneNumber;
use App\Models\BackpackUser;
use App\Traits\SavesImages;
use Backpack\Settings\app\Models\Setting;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use \App\Traits\MustVerifyPhoneNumber;
    use CrudTrait;
    use SavesImages;
    use HasRoles;
    use Notifiable;

     protected $guard_name = 'backpack';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rfid', 'name', 'phone', 'address','balance', 'email', 'identity_document', 'password', 'verified', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function roles()
    {
//        return $this->belongsToMany('Backpack\PermissionManager\app\Models\Role', 'model_has_roles', 'model_id', 'role_id');
        return $this->morphedByMany('Backpack\PermissionManager\app\Models\Role',
            'model',
            'model_has_roles',
            'model_id',
            'role_id'
        );
    }

    public function stations()
    {
        return $this->hasMany(Device::class, 'owner_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'rfid', 'user_rfid');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function devicesList()
    {
        return view('vendor.backpack.additional.devicesList', ['devices' => $this->stations]);
    }

    public function setIdentityDocumentAttribute($value)
    {
        $attribute_name = "identity_document";
        $destination_path = "public/images/documents"; // path relative to the disk above
        $this->attributes[$attribute_name] = $this->storeImage($value, $attribute_name, $destination_path);
    }

    public function getAvatarSrc()
    {
        if ($this->avatar) {
            return $this->avatar;
        } elseif ($this->hasRole('admin')) {
            return '/storage/images/avatars/admin.png';
        } elseif ($this->hasRole('owner')) {
           return '/storage/images/avatars/owner.png';
        }
        return '/storage/images/avatars/default.jpg';
    }

    public function routeNotificationForMsg91($notification)
    {
        return ltrim($this->phone);
    }

}
