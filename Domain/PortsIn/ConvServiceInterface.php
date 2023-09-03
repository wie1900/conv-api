<?php

namespace Conv\Domain\PortsIn;

interface ConvServiceInterface
{
    function getWords(string $number): string;
}