<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    public function head()
    {
        return $this->hasMany(Head::class,"id","head_id");
    }
    public function bank()
    {
        return $this->hasMany(Bank::class,"id","bank_id");
    }
    public function typeoftransaction()
    {
        return $this->hasMany(TypeOfTransaction::class,"id","typeoftransaction_id");
    }
}
