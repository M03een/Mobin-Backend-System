<?php

namespace App\Services\Points;

class PointFactory {
    public function make(string $type): PointSignerInterface {
        return match ($type) {
            'zikr' => new ZikrSigner(),
            'tasbeh' => new TasbehSigner()
        };
    }
}