<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class MagazineExport implements FromCollection
{
   protected $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items;
    }
     public function headings(): array
    {
        return ['Дата', 'Час', 'Коментар', 'Користувач'];
    }
}
