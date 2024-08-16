<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jadwal;
use Carbon\Carbon;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'divisis';
    protected $primaryKey = "id";
    protected $fillable = [
        'id',
        'nama',
        'aktifasi',
        'tenggat',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
    public function getAktifasiAttribute($value)// method accesor
    {
        $currentTime = Carbon::now()->format('H:i:00');
        if ($currentTime > $this->tenggat) {
            return 0; // Jika currentTime melebihi tenggat, kembalikan 0
        }
        return $value; // Jika tidak, kembalikan nilai aktifasi yang ada
    }
}
