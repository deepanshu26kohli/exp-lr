<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Head extends Model
{
    use HasFactory;
    public function type_of_transaction()
    {
        return $this->hasMany(TypeOfTransaction::class,"id","typeoftransaction_id");
    }

}
