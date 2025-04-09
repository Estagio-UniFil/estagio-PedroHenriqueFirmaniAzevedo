<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresencaMonitor extends Model
{
    use HasFactory;

    protected $table = 'presenca_monitores';
    protected $fillable = ['presenca_id', 'monitor_id', 'presente', 'observacao'];

    public function presenca()
    {
        return $this->belongsTo(Presenca::class);
    }
}
