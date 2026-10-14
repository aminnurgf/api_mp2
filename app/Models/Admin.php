<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $table = 'admin';

    /**
     * Tabel admin tidak memiliki kolom created_at / updated_at.
     */
    public $timestamps = false;

    protected $fillable = ['username', 'password'];

    /**
     * Password tidak pernah ikut dikirim di response JSON.
     */
    protected $hidden = ['password'];

    /**
     * Otomatis hash password setiap kali di-set (create / update).
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
}

