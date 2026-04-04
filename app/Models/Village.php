<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Village extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'status',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class, 'village_id');
    }
}
