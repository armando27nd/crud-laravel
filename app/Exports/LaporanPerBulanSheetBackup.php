<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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

                // Tambahkan logo di A1
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo Pemkab');
                $drawing->setPath(public_path('logo.png')); // pastikan file ada di /public/logo.png
                $drawing->setHeight(80);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);

                // Header teks di kolom B
                $sheet->mergeCells("B1:F1")->setCellValue("B1", "PEMERINTAH KABUPATEN TANGERANG");
                $sheet->mergeCells("B2:F2")->setCellValue("B2", "DINAS TENAGA KERJA");
                $sheet->mergeCells("B3:F3")->setCellValue("B3", "Jalan Raya Parahu RT/RW. 05/01, Desa Parahu, Kec. Sukamulya, Kab. Tangerang, Banten");
                $sheet->mergeCells("B4:F4")->setCellValue("B4", "Kode Pos 15612 | Telp: 021-59433197 | Email: disnaker@tangerangkab.go.id");

                $sheet->getStyle("B1:F2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B2:F2")->getFont()->setBold(true);

                $row = 6;
                $sheet->mergeCells("A{$row}:F{$row}")->setCellValue("A{$row}", "JADWAL HARIAN : DINAS TENAGA KERJA KAB. TANGERANG");
                $row++;
                $sheet->mergeCells("A{$row}:F{$row}")->setCellValue("A{$row}", "HARI/TANGGAL : " . now()->translatedFormat('l, d F Y'));
                $row += 2;

                // Header kolom
                $headers = ['NO', 'JAM', 'KEGIATAN', 'TEMPAT', 'PEJABAT YANG HADIR', 'KET.'];
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue("{$col}{$row}", $header);
                    $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("{$col}{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                    $col++;
                }

                $startDataRow = ++$row;

                $no = 1;
                foreach ($this->items as $item) {
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $item->jam);
                    $sheet->setCellValue("C{$row}", $item->kegiatan . "\n(" . $item->nama_surat . ")");
                    $sheet->setCellValue("D{$row}", $item->tempat);
                    $sheet->setCellValue("E{$row}", $item->pejabat);
                    $sheet->setCellValue("F{$row}", $item->keterangan ?? '-');
                    $row++;
                }

                // Border
                $sheet->getStyle("A" . ($startDataRow - 1) . ":F" . ($row - 1))
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Auto-size kolom
                foreach (range('A', 'F') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                // Wrap text di kolom kegiatan
                $sheet->getStyle("C{$startDataRow}:C" . ($row - 1))
                    ->getAlignment()->setWrapText(true);
            },
        ];
    }
}
