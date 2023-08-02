<?php

namespace App\Models;

use App\Models\Contracts\HasAddress;
use App\Models\Traits\WithAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model implements HasAddress
{
    use HasFactory;
    use WithAddress;

    protected $table = "profiles_billing";
}
