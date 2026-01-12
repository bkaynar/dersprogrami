<?php

namespace App\Jobs;

use App\Services\TimetableGeneticAlgorithm;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateTimetable implements ShouldQueue
{
    use Queueable;

    public $timeout = 900; // 15 dakika timeout
    public $tries = 1; // Sadece 1 kez dene

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Veritabanı bağlantısını yenile (MySQL gone away hatasını önle)
            DB::reconnect();

            // İşlem durumunu başlat
            Cache::put('timetable_generation_status', [
                'status' => 'running',
                'progress' => 0,
                'message' => 'Genetik algoritma başlatılıyor...',
                'started_at' => now(),
            ], 600);

            Log::info('Timetable generation started');

            $ga = new TimetableGeneticAlgorithm();

            // İlerleme callback'i ile programı oluştur
            $result = $ga->generateWithProgress(function ($generation, $maxGenerations, $bestFitness) {
                $progress = round(($generation / $maxGenerations) * 100);

                Cache::put('timetable_generation_status', [
                    'status' => 'running',
                    'progress' => $progress,
                    'message' => "Nesil {$generation}/{$maxGenerations} - En iyi fitness: {$bestFitness}",
                    'generation' => $generation,
                    'max_generations' => $maxGenerations,
                    'best_fitness' => $bestFitness,
                    'started_at' => Cache::get('timetable_generation_status')['started_at'] ?? now(),
                ], 600);
            });

            // Programı kaydet
            $ga->saveSchedule($result['schedule']);

            // Fitness kontrolü - negatifse ihlal var demektir
            $status = 'completed';
            $message = 'Program başarıyla oluşturuldu!';

            if ($result['fitness'] < 0) {
                $status = 'completed_with_warnings';
                $message = 'Program oluşturuldu ancak bazı kısıtlamalar ihlal edildi. Lütfen öğretmen müsaitliklerini kontrol edin.';
            }

            // Başarılı durumu kaydet
            Cache::put('timetable_generation_status', [
                'status' => $status,
                'progress' => 100,
                'message' => $message,
                'fitness' => $result['fitness'],
                'generations' => $result['generations'],
                'completed_at' => now(),
            ], 600);

            Log::info('Timetable generation completed', [
                'fitness' => $result['fitness'],
                'generations' => $result['generations'],
            ]);
        } catch (\Exception $e) {
            // Hata durumunu kaydet
            Cache::put('timetable_generation_status', [
                'status' => 'failed',
                'progress' => 0,
                'message' => 'Hata: ' . $e->getMessage(),
                'error' => $e->getMessage(),
                'failed_at' => now(),
            ], 600);

            Log::error('Timetable generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
