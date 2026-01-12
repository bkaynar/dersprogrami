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
        // Sabit kurallar:
        // 1 saat -> [1] (tek blok)
        // 2 saat -> [2] (tek blok, arka arkaya)
        // 3 saat -> [3] (tek blok, arka arkaya)
        // 4 saat -> [2, 2] (2 blok, farklı günlerde)
        // 5 saat -> [2, 3] (2 blok, farklı günlerde)
        // 6 saat -> [3, 3] (2 blok, farklı günlerde)
        // 7 saat -> [2, 2, 3] (3 blok, farklı günlerde)
        // 8 saat -> [2, 3, 3] (3 blok, farklı günlerde)

        $rules = [
            1 => [1],
            2 => [2],
            3 => [3],
            4 => [2, 2],
            5 => [2, 3],
            6 => [3, 3],
            7 => [2, 2, 3],
            8 => [2, 3, 3],
        ];

        return $rules[$weeklyHours] ?? [$weeklyHours];
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
