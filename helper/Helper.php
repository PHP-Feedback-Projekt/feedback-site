<?php

function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    exit();
}
//da str_contains ab v8 zu verfügung ist und die wbs blöd ist hier die altanative 
function string_contains($haystack, $needle)
{
    return $needle !== '' && mb_strpos($haystack, $needle) !== false;
}
function hasbadword($string)
{
    $badwords = ['arsch', 'arschloch', 'penis', 'wixer', 'hure', 'hurensohn', 'schlampe', 'armleuchter', 'arschgeige', 'arschgesicht', 'affenarsch', 'blödian', 'blödmann', 'depp', 'dummkopf', 'dussel', 'dumpfbacke', 'dödel', 'dorfdepp', 'einfaltspinsel', 'eierkopf', 'faulpelz', 'fetzenschädel', 'hackfresse', 'hornochse', 'hurenkind', 'hammel', 'idiot', 'kackbratze', 'kackbratze', 'lauch', 'luder', 'lümmel', 'mistvieh', 'nigger', 'nichtsnutz', 'pissnelke', 'rindvieh', 'saftsack', 'sackratte', 'sau', 'schmierfink', 'schwachkopf', 'schwanzlutscher', 'schwanzlutscher', 'sack', 'trottel', 'vollpfosten', 'wurm', 'walross', 'wichser'];

    foreach ($badwords as $badword) {
        if (string_contains($string, $badword)) {
            var_dump('true');
            return true;
        }
    }
    return false;
}
