<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    /**
     * Example of method you may use if not storing css classes in database.
     */
    public function getCSSClasses(): string
    {
        $colors = [
            'Open'          => 'bg-gray-200',
            'Considering'   => 'bg-purple text-white',
            'In Progress'   => 'bg-yellow text-white',
            'Implemented'   => 'bg-green text-white',
            'Closed'        => 'bg-red text-white',
        ];

        return $colors[$this->name];
    }
}
