<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanExport implements WithMultipleSheets
{
    protected $arsip;
    protected $dari;
    protected $sampai;
    protected $kategori;

    public function __construct($arsip, $dari, $sampai, $kategori)
    {
        $this->arsip = $arsip;
        $this->dari = $dari;
        $this->sampai = $sampai;
        $this->kategori = $kategori;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Group arsip berdasarkan bulan-tahun format 'F Y' (contoh: Mei 2025)
        $groupedByMonth = $this->arsip->groupBy(function ($item) {
            return $item->created_at->format('F Y');
        });

        foreach ($groupedByMonth as $month => $items) {
            $sheets[] = new LaporanPerBulanSheet($month, $items);
        }

        return $sheets;
    }
}
