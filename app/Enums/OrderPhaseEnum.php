<?php

namespace App\Enums;

enum OrderPhaseEnum: string
{
    //define the actual enum values (values stored in db)
    case PENDING='pending';
    case CUTTING='cutting';
    case PRINTING='printing';
    case SEWING='sewing';
    case PACKAGING='packaging';
    case DELIVERY='delivery';
    case COMPLETED='completed';
    case CANCELLED='cancelled';


    public function label(): string
    {
        //text shown to users
        return match($this) {
            self::PENDING => 'Pending',
            self::CUTTING => 'Cutting',
            self::PRINTING => 'Printing',
            self::SEWING => 'Sewing',
            self::PACKAGING => 'Packaging',
            self::DELIVERY => 'Delivery',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }


    public static function getAvailablePhases(bool $requiresPrinting =true){

        $phases=[
            self::PENDING,
            self::CUTTING,
            self::SEWING,
            self::PACKAGING,
            self::DELIVERY,
            self::COMPLETED,
            self::CANCELLED,
        ];
        if($requiresPrinting)
        {
             $result = [];
            foreach ($phases as $phase) {
                $result[] = $phase;
                // Insert PRINTING after CUTTING (which is after PENDING)
                if ($phase === self::CUTTING) {
                    $result[] = self::PRINTING;
                }
            }
            return $result;
    }
    return $phases;
}
    public static function forDropdown(bool $requiresPrinting=true){

        $phases=[];
        foreach(self::getAvailablePhases($requiresPrinting)as $phase){
            $phases[$phase->value]=$phase->label();
        }
        return $phases;
    }
}