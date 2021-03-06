<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    
    public static function getCount(): array
    {
        return Idea::query()
            ->selectRaw('count(*) as all_statuses')
            ->selectRaw('count(case when status_id = 1 then 1 end) as open')
            ->selectRaw('count(case when status_id = 2 then 1 end) as considering')
            ->selectRaw('count(case when status_id = 3 then 1 end) as in_progress')
            ->selectRaw('count(case when status_id = 4 then 1 end) as implemented')
            ->selectRaw('count(case when status_id = 5 then 1 end) as closed')
            ->first()
            ->toArray();
    }

    public function getTranslatedName(): string
    {
        $translationKey = 'messages.ideas.' . $this->name;

        $translatedString = __($translationKey);

        if ($translatedString === $translationKey) {
            return $this->name;
        }
            
        return $translatedString;
    }
}
