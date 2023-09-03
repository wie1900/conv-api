<?php

namespace Conv\Infra;

use Conv\Domain\PortsOut\ConverterInterface;

class Converter implements ConverterInterface
{

    // converter number to words + digits for decimals
    // input required: 1-30 digit with optional 1-2 decimals (separator: dot)

    // more names can be added, each bringing 3 next digits (e.g.: decillion (33), undecillion (36) etc.)
    private $names = [
        0 => '',
        1 => 'thousand',    // 3 number of zeros
        2 => 'million',     // 6 number of zeros
        3 => 'billion',     // 9 number of zeros
        4 => 'trillion',    // etc.
        5 => 'quadrillion',
        6 => 'quintillion',
        7 => 'sextillion',
        8 => 'septillion',
        9 => 'octillion',
        10 => 'nonillion' // 30 number of zeros
    ];

    private $number;
    private $fraction = 0;
    private $divisor = 3;

	public function setNumberForConversion(string $number): void
	{
		$this->number = $number;
	}
    
    public function getNumberToWords(): string
    {
		// validation needed:
		// max. 30 digit number with optional decimal separator and 1-2 decimal digits
		// 'regex:/^(\d{1,30})$|^\d{0,30}(\.\d{1,2})$/'
			$this->defractNumber($this->number);

		// returns full conversion to words: integers with fraction in form XX/100 (if exists)

		$checkzeros = str_replace('0','',$this->number);
		
		if($checkzeros == '') {
			return $this->getFraction();
		}else{
			return $this->fraction != 0 ? $this->getInteger().' and '.$this->getFraction() : $this->getInteger();
		}

    }

    // returns conversion to words of integers only
    public function getInteger()
    {
        $inWords = '';

        // length of the input
		$lnum = strlen($this->number);
        
		// full 3-digit parts + 1 part (not full if exists)
		$fullparts = (int)($lnum / $this->divisor);
        
		// if there is a not full part: 1
		$add_part = ($lnum % $this->divisor) > 0 ? 1 : 0;
        
		// number of parts in total
		$parts = $fullparts + $add_part;
        
        // number of digits in the first element (if not full) - otherwise: 3
        $first_part_length = $add_part == 1 ? $lnum-($fullparts*$this->divisor) : $this->divisor;
        
        $recount = $this->divisor; // counter for the descreased position for substr(number)
        $count = 0; // counter for names array
        
        // iteration over 'parts' (3-digit parts of the number)
        for($i=$parts-1;$i>-1;$i--){
            //first element
            if($i==0){
                $inWords = $this->getPartInWords(substr($this->number, 0, $first_part_length), $this->names[$count]).$inWords;
            }else{
                $inWords = $this->getPartInWords(substr($this->number, $lnum-$recount, $this->divisor), $this->names[$count]).$inWords;
            }
            $recount += $this->divisor;
            $count++;
        }
		do {
			$inWords = str_replace("  "," ",$inWords);
		} while (strpos($inWords,"  ") > -1);

		$inWords = str_ends_with($inWords, " ") ? substr($inWords,0,(strlen($inWords)-1)) : $inWords;
		$inWords = str_starts_with($inWords, " ") ? substr($inWords,1,(strlen($inWords))) : $inWords;
		return $inWords;
    }

    // returns fraction only in form XX/100 (if exists)
    public function getFraction()
    {
        if($this->fraction !=0 ) {
            $this->fraction = strlen($this->fraction) > 1 ? $this->fraction : $this->fraction.'0';
            return $this->fraction.'/100';    
        }else{
            return '';
        }
    } 

    // divides input into integer and fraction by using '.' as separator
    private function defractNumber($number)
    {
        if(strpos($number,'.') > -1) {
            $numbers = explode('.',$number);
            $this->number = $numbers[0];
            $this->fraction = $numbers[1];
        }else{
            $this->number = $number;
        }
    }

    // helper to return single part
    private function getPartInWords($part, $name)
    {
		$checkzeros = str_replace(0,'',$part);
		if($checkzeros == '') return ' ';

        $forString = '';
    	switch(strlen($part)){
            case 1:
    			$forString = $this->addOnesDigits($part);
    			break;
			case 2:
    			$forString = $this->addTwoDigits($part);
    			break;
			case 3:
				$forString = " ".$this->addHundreds($part);
				$forString .= " ".$this->addTwoDigits(substr($part,1,2));
				break;
            default:
				$forString = "";                
    	}
		return  $forString.' '.$name;
    }

    // add units
    private function addOnesDigits($enterNumber)
    {
    	switch($enterNumber){
    		case 0: return ''; break;
			case 1: return 'one'; break;
			case 2: return 'two'; break;
			case 3: return 'three'; break;
			case 4: return 'four'; break;
			case 5: return 'five'; break;
			case 6: return 'six'; break;
			case 7: return 'seven'; break;
			case 8: return 'eight'; break;
			case 9: return 'nine'; break;
			default: return ''; break;
    	}
    }

    // add 2 digits
    private function addTwoDigits($enterNumber)
    {
    	switch(substr($enterNumber,0,1)){
    		case 0:
    			return $this->addOnesDigits(substr($enterNumber,1,1));
    			break;

    		case 1:
	    		switch(substr($enterNumber,1,1)){
	    			case 0: return 'ten'; break;
	    			case 1: return 'eleven'; break;
	    			case 2: return 'twelve'; break;
	    			case 3: return 'thirteen'; break;
	    			case 4: return 'fourteen'; break;
	    			case 5: return 'fifteen'; break;
	    			case 6: return 'sixteen'; break;
	    			case 7: return 'seventeen'; break;
	    			case 8: return 'eighteen'; break;
	    			case 9: return 'nineteen'; break;
	    			default: return 'zero'; break;
	    		}
    			break;

    		case 2:
    			$forReturn = 'twenty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 3:
    			$forReturn = 'thirty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 4:
    			$forReturn = 'forty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 5:
    			$forReturn = 'fifty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 6:
    			$forReturn = 'sixty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 7:
    			$forReturn = 'seventy';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 8:
    			$forReturn = 'eighty';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;

    		case 9:
    			$forReturn = 'ninety';
    			if(substr($enterNumber,1,1)!=0) $forReturn .= '-'.$this->addOnesDigits(substr($enterNumber,1,1));
    			return $forReturn;
    			break;
			default: return ''; break;
    	}
    }

    // add hundreds
    private function addHundreds($enterNumber)
    {
    	switch(substr($enterNumber,0,1)){
    		case 0: return ''; break;
			case 1: return 'one hundred'; break;
			case 2: return 'two hundred'; break;
			case 3: return 'three hundred'; break;
			case 4: return 'four hundred'; break;
			case 5: return 'five hundred'; break;
			case 6: return 'six hundred'; break;
			case 7: return 'seven hundred'; break;
			case 8: return 'eight hundred'; break;
			case 9: return 'nine hundred'; break;
			default: return ''; break;
    	}
    }
}