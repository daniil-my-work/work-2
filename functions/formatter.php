<?php

// Делает первую букву заглавной, если она не заглавная
function formatFirstLetter($word)
{
    $firstLetter = mb_substr($word, 0, 1, 'UTF-8');
    $endWord = mb_substr($word, 1, mb_strlen($word), 'UTF-8');
    return mb_strtoupper($firstLetter, 'UTF-8') . $endWord;
}


// Форматирует число: прибавляет валюту
function formatSum($word)
{
    return "$word руб.";
}
