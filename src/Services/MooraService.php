<?php

namespace Fanboykun\DecissionSupportSystem\Services;

class MooraService
{
    public function execute(array $data)
    {
        $normalized_criterias = $this->normalize($data);
        $optimized_criterias = $this->optimize($normalized_criterias);
        $finalized_alternatives = $this->finalize($optimized_criterias);
        return $finalized_alternatives;
    }

    public function normalize(array $criterias)
    {
        $normalized_criterias[] = [];
        foreach ($criterias as $key => $criteria) {
            $normalized_criterias[$key]['criteria_id'] = $criteria['criteria_id'];
            $normalized_criterias[$key]['weight'] = $criteria['weight'];
            $normalized_criterias[$key]['type'] = $criteria['type'];

            $normalized_criteria_values = $this->calculatePow($criteria['alternatives']);
            $normalized_criterias[$key]['divider'] = $normalized_criteria_values;
            foreach($criteria['alternatives'] as $k => $alternative){
                $normalized_criterias[$key]['alternatives'][$k]['alternative_id'] = $alternative['alternative_id'];
                $normalized_criterias[$key]['alternatives'][$k]['value'] = $alternative['value'];
                $normalized_criterias[$key]['alternatives'][$k]['normalized_value'] = $alternative['value'] / $normalized_criteria_values;
            }
        }
        $finalize_normalized = $this->groupByAlternativeId($normalized_criterias);
        return $finalize_normalized;
    }

    public function optimize(array $array)
    {
        $opmitized_alternatives[] = [];
        foreach ($array as $key => $alternative) {
            $opmitized_value = $this->calculateValueAndWeight($alternative['criterias']);

            $opmitized_alternatives[$key]['alternative_id'] = $alternative['alternative_id'];
            $opmitized_alternatives[$key]['optimized_value'] = $opmitized_value;
        }

        return $opmitized_alternatives;
    }

    public function finalize(array $optimized_criterias)
    {
        $sorted_value = $this->sortByOptimizedValue($optimized_criterias);
        $ranked_alternatives = $this->assignRank($sorted_value);
        return $ranked_alternatives;
    }

    private function calculatePow(array $numbers)
    {
        $sum = 0;
        foreach ($numbers as $number) {
            $sum += pow($number['value'], 2);
        }

        return sqrt($sum);
    }

    public function calculateValueAndWeight(array $numbers)
    {
        $grouped_array = $this->groupByType($numbers);
        $summed_benefit = 0;
        $summed_cost = 0;
        foreach ($grouped_array['benefit'] as $key => $benefit) {
            $summed_benefit += $benefit['normalized_value'] * $benefit['weight'];
        }

        foreach ($grouped_array['cost'] as $k => $cost) {
            $summed_cost += $cost['normalized_value'] * $cost  ['weight'];
        }
        $calculated_value = $summed_benefit - $summed_cost;

        return $calculated_value;
    }

    public function groupByType(array $array)
    {
        $benefit = [];
        $cost = [];
        foreach ($array as $key => $value) {
            if ($value['type'] == false) {
                $benefit[$key] = $value;
            }
            if ($value['type'] == true) {
                $cost[$key] = $value;
            }
        }
        $grouped_array = [
            'benefit' => $benefit,
            'cost' => $cost,
        ];
        return $grouped_array;
    }

    public function groupByAlternativeId(array $array)
    {
        $grouped_array = [];
        foreach ($array as $key => $value) {
            foreach ($value['alternatives'] as $k => $alternative) {
                $grouped_array[$k]['alternative_id'] = $alternative['alternative_id'];
                $grouped_array[$k]['value'] = $alternative['value'];
                $grouped_array[$k]['criterias'][] = $value;
                $grouped_array[$k]['criterias'][$key]['normalized_value'] = $alternative['normalized_value'];
                unset($grouped_array[$k]['criterias'][$key]['alternatives']);
            }
        }
        return $grouped_array;
    }

    public function sortByOptimizedValue(array $array)
    {
        array_multisort(array_column($array, 'optimized_value'), SORT_DESC, $array);
        return $array;
    }

    public function assignRank(array $items)
    {
        for ($i = 0; $i < count($items); $i++) {
            $items[$i]['rank'] = $i + 1;
        }
        return $items;
    }

}
