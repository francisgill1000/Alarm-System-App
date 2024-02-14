<?php

namespace App\Models\Community;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the floor that owns the Parking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }


    public function vehicle()
    {
        return $this->hasOne(Vehicle::class)->with([
            "tanent" => fn ($q) => $q->with(["floor", "room"])
        ]);
    }
}
