<?php

namespace App\Exports;

//use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SheetExportPayment implements FromArray, WithTitle,WithColumnWidths
{
    protected $data;
    protected $name;

    public function __construct(array $data, $name)
    {
        $this->data = $data;
        $this->name = $name;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->name;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 10,
            'D' => 30,
            'E' => 20,
            'F' => 15,
            'G' => 10,
            'H' => 30,
            'I' => 20,
            'J' => 20,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
            'S' => 15,
            'T' => 10,
            'U' => 10,
            'V' => 30,
            'W' => 15,
            'X' => 15,
            'Y' => 30,
            'Z' => 10,
            'AA' => 10,
            'AB' => 10,
            'AC' => 10,
            'AD' => 30,
            'AE' => 15,
            'AF' => 20,
            'AG' => 20,
            'AH' => 10,
            'AI' => 10,
            'AJ' => 10,
            'AK' => 10,
            'AL' => 30,
            'AM' => 15,
            'AN' => 20,
            'AO' => 20,
            'AP' => 10,
        ];
    }
}
