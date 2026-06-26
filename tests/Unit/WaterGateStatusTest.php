<?php

namespace Tests\Unit;

use App\Models\WaterGate;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WaterGateStatusTest extends TestCase
{
    #[DataProvider('dangerStatusProvider')]
    public function test_calculate_danger_status_from_water_level(float $level, string $expected): void
    {
        $this->assertSame($expected, WaterGate::calculateDangerStatus($level));
    }

    public static function dangerStatusProvider(): array
    {
        return [
            'normal' => [50, 'Normal'],
            'siaga 3 lower bound' => [80, 'Siaga_3'],
            'siaga 3 upper' => [149.99, 'Siaga_3'],
            'siaga 2 lower bound' => [150, 'Siaga_2'],
            'siaga 2 upper' => [250, 'Siaga_2'],
            'siaga 1' => [251, 'Siaga_1'],
            'pondok gede seeded level' => [350, 'Siaga_1'],
            'bekasi pasar baru seeded level' => [210, 'Siaga_2'],
        ];
    }

    public function test_saving_auto_sets_danger_status_from_water_level(): void
    {
        $gate = WaterGate::create([
            'gate_name' => 'Test Gate',
            'river_name' => 'Sungai Test',
            'water_level_cm' => 210,
            'danger_status' => 'Normal',
            'last_updated' => now(),
        ]);

        $this->assertSame('Siaga_2', $gate->fresh()->danger_status);
    }
}
