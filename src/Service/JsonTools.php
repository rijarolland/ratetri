<?php 

namespace App\Service;

class JsonTools {

    /** 
     * Filter json data array
     * 
     * @param   mixed[]     $filterCriteria
     * @return  mixed[] 
     */
    public function filter(string $jsonPath, array $filterCriteria, string $rateKeyName) : array 
    {
        /** @var string */
        $jsonFileOneContent = file_get_contents($jsonPath);

        /** @var mixed[] */
        $decodedDataFileOne = json_decode($jsonFileOneContent, true);

        /** @var mixed[] */
        $results = array_filter($decodedDataFileOne, function (array $item) use ($filterCriteria) {

            /** @var string[] */
            $keys = array_keys($filterCriteria);

            /** @var bool */
            $hasResult = true;

            foreach ($keys as $key) {
                if ($filterCriteria[$key] != $item[$key]) {
                    $hasResult = false;
                }
            }

            return $hasResult;

        });

        /** @var mixed[] */
        $arrayValues = array_values($results);

        /** @var mixed[] */
        $singleResults = isset($arrayValues[0]) ? $arrayValues[0] : [];
        
        if (isset($singleResults[$rateKeyName])) {
            $singleResults["value_for_tri"] = $singleResults[$rateKeyName];
        }

        return $singleResults;
    }

    public function tri(array $results)
    {
        uasort($results, [$this, 'sortByRate']);

        return $results;
    }

    private function sortByRate(array $firstVal, array $secondVal) {

        $firstVal = $firstVal['value_for_tri'];
        $secondVal = $secondVal['value_for_tri'];
    
        if ($firstVal == $secondVal) return 0;
        return ($firstVal < $secondVal) ? -1 : 1;

    }

}