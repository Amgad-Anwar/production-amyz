<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shippingprice extends Model
{
    use HasFactory;

    /*

        CREATE TABLE `shippingprices` (
        `id` int(11) NOT NULL,
        `state_id` int(11) NOT NULL,
        `catshipping_id` int(11) NOT NULL,
        `price` DECIMAL(13,2) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    */


    protected $fillable = ['state_id', 'catshipping_id', 'price'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function catshipping()
    {
        return $this->belongsTo(Catshipping::class);
    }




}
