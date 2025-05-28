<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanPerBulanSheet implements WithTitle, WithEvents
{
    protected $month;
    protected $items;

    public function __construct($month, $items)
    {
        $this->month = $month;
        $this->items = $items;
    }

    public function title(): string
    {
        return $this->month;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // LOGO
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo Pemkab');
                $drawing->setPath(public_path('logo.png'));
                $drawing->setHeight(70);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);

                // HEADER INSTANSI
                $sheet->mergeCells("B1:F1")->setCellValue("B1", "PEMERINTAH KABUPATEN TANGERANG");
                $sheet->mergeCells("B2:F2")->setCellValue("B2", "DINAS TENAGA KERJA");
                $sheet->mergeCells("B3:F3")->setCellValue("B3", "Jalan Raya Parahu RT/RW. 05/01, Desa Parahu, Kecamatan Sukamulya, Kab Tangerang, Banten");
                $sheet->mergeCells("B4:F4")->setCellValue("B4", "Kode Pos, 15612 Telepon 021-59433197, Laman : disnaker@tangerangkab.go.id");

                $sheet->getStyle("B1")->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("B2")->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 18, 'bold' => true],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                $sheet->getStyle("B3:B4")->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // BORDER atas garis pemisah
                $sheet->getStyle("A5:F5")->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM);

                // INFORMASI JADWAL
                $sheet->mergeCells("A6:F6")->setCellValue("A6", "JADWAL HARIAN   :   DINAS TENAGA KERJA KAB. TANGERANG");
                $sheet->mergeCells("A7:F7")->setCellValue("A7", "HARI/TANGGAL      :   " . now()->translatedFormat('l, d F Y'));

                $sheet->getStyle("A6:A7")->applyFromArray([
                    'font' => ['name' => 'Arial', 'size' => 11, 'bold' => true],
                ]);

                // HEADER KOLOM
                $headers = ['NO', 'JAM', 'KEGIATAN', 'TEMPAT', 'PEJABAT YANG HADIR', 'KET.'];
                $startRow = 9;
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue("{$col}{$startRow}", $header);
                    $sheet->getStyle("{$col}{$startRow}")->applyFromArray([
                        'font' => ['name' => 'Arial', 'size' => 11, 'bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);
                    $col++;
                }

                // ISI DATA
                $row = $startRow + 1;
                $no = 1;
                foreach ($this->items as $item) {
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $item->jam);
                    $sheet->setCellValue("C{$row}", $item->kegiatan . "\n(" . $item->nama_surat . ")");
                    $sheet->setCellValue("D{$row}", $item->tempat);
                    $sheet->setCellValue("E{$row}", $item->pejabat);
                    $sheet->setCellValue("F{$row}", $item->keterangan ?? '-');

                    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                        'font' => ['name' => 'Arial', 'size' => 11],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                    ]);

                    $row++;
                }

                // WRAP TEXT kolom kegiatan
                $sheet->getStyle("C10:C" . ($row - 1))->getAlignment()->setWrapText(true);

                // AUTO SIZE semua kolom
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
