<?php
//how many ways can you break a dollar
require __DIR__ . '/vendor/autoload.php';
use Illuminate\Support\Collection;
function countCoins($amount, $kindsOfCoins)
{
    if ($amount == 0) {
        return 1;
    }

    if ($amount < 0) {
        return 0;
    }

    if ($kindsOfCoins == 0) {
        return 0;
    }

    return countCoins($amount, $kindsOfCoins - 1) + countCoins($amount - whichCoin($kindsOfCoins), $kindsOfCoins);
}
function whichCoin($coin)
{
    $coins = [
        1 => 1,
        2 => 5,
        3 => 10,
        4 => 25,
        5 => 50
    ];
    return $coins[$coin];
}
echo countCoins(100, 5) . PHP_EOL;