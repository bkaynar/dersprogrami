<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableSetting extends Model
{
    protected $fillable = [
        'min_block_size',
        'max_block_size',
        'enforce_consecutive',
        'split_rules',
    ];

    protected $casts = [
        'split_rules' => 'array',
        'enforce_consecutive' => 'boolean',
    ];

    /**
     * Verilen haftalık saat için uygun split kuralını döndürür
     *
     * @param int $weeklyHours
     * @return array
     */
    public function getSplitRule(int $weeklyHours): array
    {
        // split_rules içinde varsa onu döndür
        if (isset($this->split_rules[$weeklyHours])) {
            return $this->split_rules[$weeklyHours];
        }

        // Yoksa tüm saatleri tek blokta döndür
        return [$weeklyHours];
    }

    /**
     * Singleton pattern - tek bir ayar kaydı olacak
     */
    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'min_block_size' => 2,
                'max_block_size' => 3,
                'enforce_consecutive' => true,
                'split_rules' => [
                    '4' => [2, 2],
                    '5' => [3, 2],
                    '6' => [3, 3],
                ],
            ]
        );
    }
}
