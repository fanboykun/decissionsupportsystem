<?php

namespace Fanboykun\DecissionSupportSystem\Services;

class WaspasService
{
    public function execute(array $data)
    {
        $normalized_criterias = $this->normalize($data);
        $optimized_criterias = $this->optimize($normalized_criterias);
        $finalized_alternatives = $this->finalize($optimized_criterias);
        return $finalized_alternatives;
    }

    public function normalize(array $data)
    {
        $normalized_criterias = $data;
        foreach ($data as $key => $criteria) {
            if ($criteria['max_value'] == null){
                $normalized_criterias[$key]['max_value'] = $this->findMaxValue($criteria['alternatives']);
            }
            $normalized_criterias[$key]['min_value'] = $this->findMinValue($criteria['alternatives']);

            foreach ($criteria['alternatives'] as $k => $alternative) {
                $normalized_criterias[$key]['alternatives'][$k]['normalized_value'] = $this->normalizeValue($alternative['value'], $criteria['type'], $normalized_criterias[$key]['min_value'], $normalized_criterias[$key]['max_value']);
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

    public function finalize(array $optimized_alternatives)
    {
        $sorted_value = $this->sortByOptimizedValue($optimized_alternatives);
        $ranked_alternatives = $this->assignRank($sorted_value);
        return $ranked_alternatives;
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

    public function calculateValueAndWeight(array $numbers)
    {
        $multiplied_values = 0;
        $powered_values = 0;
        foreach ($numbers as $key => $number) {
            $multiplied_values += $number['normalized_value'] * $number['weight'];
            $powered_values *= pow($number['normalized_value'], $number['weight']);
        }
        $opmitized_value = 0.5 * $multiplied_values + 0.5 * $powered_values;
        return $opmitized_value;
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

    public function findMaxValue(array $data)
    {
        $collection = $data;
        $sorted = [];
        foreach ($collection as $key => $value) {
            $sorted[$key] = $value['value'];
        }
        $max = max($sorted);

        return $max;

    }

    public function findMinValue(array $data)
    {
        $collection = $data;
        $sorted = [];
        foreach ($collection as $key => $value) {
            $sorted[$key] = $value['value'];
        }
        $min =  min($sorted);

        return $min;
    }

    public function normalizeValue($value, $type, $min, $max)
    {
        if ($type == false){
            return $value / $max;
        }else{
            return $min / $value;
        }
    }
}
