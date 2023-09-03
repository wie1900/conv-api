<?php

namespace Conv\Domain\PortsOut;

interface ConverterInterface
{
    function setNumberForConversion(string $number): void;
    function getNumberToWords(): string;
}