<?php
echo "<pre>";
function getValues() {
    return 'value';
}
var_dump(getValues()); // string(5) "value"

function getValues2() {
    yield 'value';
    yield 'value2';
    yield 'value3';
}
var_dump(getValues2()->current()); // class Generator#1 (0)


function generate_numbers()
{
    $number = 1;
    while (true) {
        yield $number;
        $number++;
    }
}
$generator = generate_numbers();
var_dump($generator->current()); // Dumps: 1

$generator->next();
var_dump($generator->current()); // Dumps: 2

$generator->next();
var_dump($generator->current()); // Dumps: 3
