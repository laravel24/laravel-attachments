<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Concerns\AttachableConcern;

class Post extends Model
{

  use AttachableConcern;

  public $fillable = ['name', 'content'];

  public static function draft()
  {
    return self::firstOrCreate([ 'name' => null ], [ 'content' => '' ]);
  }

  public function scopeNotDraft($query)
  {
    return $query->whereNotNull('name');
  }

}
