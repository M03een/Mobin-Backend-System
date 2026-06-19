<?php

namespace App\Services\Points;

interface PointSignerInterface {
    public function sign(string $userId, int $amount): void;
}