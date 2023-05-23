<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_order_model extends Model
{
    use HasFactory;

    protected $table = 'service_orders';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "vehiclePlate",
        "entryDateTime",
        "exitDateTime",
        "priceType",
        "price",
        "userId",
    ];

    public function user()
    {
        return $this->hasOne(User_model::class, 'id', "userId");
    }
}
