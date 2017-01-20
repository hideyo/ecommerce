<?php 

namespace Hideyo\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ClientToken extends Model
{

    protected $table = 'client_token';

    protected $fillable = ['api_token', 'client', 'client_id', 'expires_on'];


    public function scopeValid()
    {
        return !Carbon\Carbon::createFromTimeStamp(strtotime($this->expires_on))->isPast();
    }

    public function client()
    {
        return $this->belongsTo('Client', 'client_id');
    }
}
