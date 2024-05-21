<?php

function getData($url) {
    $response = file_get_contents($url);
    if ($response === false) {
        return null;
    }
    return json_decode($response);
}

echo 'Type your name: ';
$name = trim(fgets(STDIN));

$genderizeUrl = 'https://api.genderize.io?name=' . urlencode($name);
$agifyUrl = 'https://api.agify.io?name=' . urlencode($name);
$nationalizeUrl = 'https://api.nationalize.io?name=' . urlencode($name);

$genderData = getData($genderizeUrl);
$ageData = getData($agifyUrl);
$nationalizeData = getData($nationalizeUrl);

if ($genderData !== null && isset($genderData->gender)) {
    $gender = $genderData->gender;
    $probability = intval($genderData->probability * 100);
    echo "Name $name is $probability% $gender" . PHP_EOL;
} else {
    echo "Failed to get gender data." . PHP_EOL;
}

if ($ageData !== null && isset($ageData->age)) {
    $age = $ageData->age;
    echo "The predicted age for $name is $age" . PHP_EOL;
} else {
    echo "Failed to get age data." . PHP_EOL;
}

if ($nationalizeData !== null && isset($nationalizeData->country)) {
    $highestProbabilityCountry = null;
    $highestProbability = 0;
    foreach ($nationalizeData->country as $country) {
        if ($country->probability > $highestProbability) {
            $highestProbabilityCountry = $country;
            $highestProbability = $country->probability;
        }
    }
    if ($highestProbabilityCountry !== null) {
        $countryName = $highestProbabilityCountry->country_id;
        $probability = intval($highestProbability * 100);
        echo "The predicted nationality for $name is $countryName with $probability% probability" . PHP_EOL;
    } else {
        echo "Failed to determine nationality." . PHP_EOL;
    }
} else {
    echo "Failed to get nationality data." . PHP_EOL;
}
