<?php

namespace Conv\Domain\Services;

use Conv\Domain\portsIn\ConvServiceInterface;
use Conv\Domain\PortsOut\ConverterInterface;

class ConvService implements ConvServiceInterface
{
    public function __construct(private ConverterInterface $converterInterface)
    {
        
    }

    public function getWords(string $number): string
    {
        $this->converterInterface->setNumberForConversion($number);
        return $this->converterInterface->getNumberToWords();
    }
}