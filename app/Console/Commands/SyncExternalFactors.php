<?php

namespace App\Console\Commands;

use App\Models\ExternalFactor;
use Illuminate\Console\Command;

class SyncExternalFactors extends Command
{
    protected $signature = 'sync:external-factors';
    protected $description = 'Seed external factor events (wars, inflation, holidays, etc.)';

    public function handle(): int
    {
        $factors = [
            [
                'event_date' => '2022-02-24',
                'category' => 'war',
                'title' => 'Inicio guerra Rusia-Ucrania',
                'description' => 'Impacto global en cadenas de suministro, inflación y confianza del consumidor.',
                'source' => 'Reuters',
                'impact_level' => 'high',
            ],
            [
                'event_date' => '2023-10-07',
                'category' => 'war',
                'title' => 'Conflicto Israel-Hamás',
                'description' => 'Inestabilidad geopolítica en Medio Oriente afecta mercados globales.',
                'source' => 'BBC',
                'impact_level' => 'high',
            ],
            [
                'event_date' => '2022-01-01',
                'category' => 'inflation',
                'title' => 'Inflación global 2022-2024',
                'description' => 'Inflación elevada en Costa Rica y el mundo, reducción de poder adquisitivo.',
                'source' => 'Banco Central CR',
                'impact_level' => 'high',
            ],
            [
                'event_date' => '2022-11-20',
                'category' => 'world_cup',
                'title' => 'Mundial Qatar 2022',
                'description' => 'Desviación de atención y gasto del consumidor hacia eventos deportivos.',
                'source' => 'FIFA',
                'impact_level' => 'medium',
            ],
            [
                'event_date' => '2026-06-11',
                'category' => 'world_cup',
                'title' => 'Mundial 2026',
                'description' => 'Mundial organizado por USA, Canadá y México. Posible impacto regional.',
                'source' => 'FIFA',
                'impact_level' => 'medium',
            ],
            [
                'event_date' => '2023-01-01',
                'category' => 'season',
                'title' => 'Temporada baja enero-marzo',
                'description' => 'Estacionalidad: menor consumo post-navideño.',
                'source' => 'Histórico',
                'impact_level' => 'medium',
            ],
            [
                'event_date' => '2024-05-01',
                'category' => 'economic',
                'title' => 'Tasa básica pasiva alta en CR',
                'description' => 'Encarecimiento del crédito al consumo.',
                'source' => 'BCCR',
                'impact_level' => 'medium',
            ],
            [
                'event_date' => '2024-12-01',
                'category' => 'season',
                'title' => 'Temporada alta navideña',
                'description' => 'Aumento estacional del consumo.',
                'source' => 'Histórico',
                'impact_level' => 'positive',
            ],
        ];

        $count = 0;
        foreach ($factors as $factor) {
            ExternalFactor::firstOrCreate(
                ['title' => $factor['title'], 'event_date' => $factor['event_date']],
                $factor
            );
            $count++;
        }

        $this->info("Seeded {$count} external factors.");
        return Command::SUCCESS;
    }
}
