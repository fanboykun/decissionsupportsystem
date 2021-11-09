<?php

namespace Fanboykun\DecissionSupportSystem;

use Fanboykun\DecissionSupportSystem\Services\MooraService;
use Fanboykun\DecissionSupportSystem\Services\WaspasService;

class DecissionSupportSystem
{
    
    public function mooraOperator(array $data) : array
    {
        $mooraService = new MooraService();
        $moora_result = $mooraService->execute($data);
        return $moora_result;
    }

    public function waspasOperator(array $data) : array
    {
        $waspasService = new WaspasService();
        $waspas_result = $waspasService->execute($data);
        return $waspas_result;
    }
}
