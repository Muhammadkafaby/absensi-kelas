<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceRecordModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExportController extends BaseController
{
    /**
     * Export rekap absensi ke Excel
     */
    public function exportRecapExcel()
    {
        $recordModel = new AttendanceRecordModel();

        // Get filter parameters
        $classId = $this->request->getGet('class_id');
        $subjectId = $this->request->getGet('subject_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $teacherId = $this->request->getGet('teacher_id'); // For guru role

        // Build filters
        $filters = [];
        if ($classId) $filters['class_id'] = $classId;
        if ($subjectId) $filters['subject_id'] = $subjectId;
        if ($dateFrom) $filters['date_from'] = $dateFrom;
        if ($dateTo) $filters['date_to'] = $dateTo;
        if ($teacherId) $filters['teacher_id'] = $teacherId;

        // Get recap data
        $recapData = $recordModel->getRecapByStudent($filters);

        if (empty($recapData)) {
            session()->setFlashdata('error', 'Tidak ada data untuk diexport');
            return redirect()->back();
        }

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('SMA NU Kaplongan')
            ->setTitle('Rekap Absensi')
            ->setSubject('Rekap Absensi Siswa')
            ->setDescription('Rekap absensi siswa SMA NU Kaplongan');

        // Header
        $sheet->setCellValue('A1', 'REKAP ABSENSI SISWA');
        $sheet->setCellValue('A2', 'SMA NU KAPLONGAN');

        // Filter info
        $row = 4;
        if ($dateFrom || $dateTo) {
            $period = 'Periode: ';
            if ($dateFrom) $period .= date('d-m-Y', strtotime($dateFrom));
            $period .= ' s/d ';
            if ($dateTo) $period .= date('d-m-Y', strtotime($dateTo));
            $sheet->setCellValue('A' . $row, $period);
            $row++;
        }

        // Table header
        $row += 2;
        $headers = ['No', 'NIS', 'Nama', 'Kelas', 'H', 'I', 'S', 'A', 'T', 'Total', '%Hadir'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366F1']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);

        // Data rows
        $row++;
        $no = 1;
        foreach ($recapData as $data) {
            $persen = ($data['total_pertemuan'] > 0) ? round(($data['hadir'] / $data['total_pertemuan']) * 100, 1) : 0;

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data['nis']);
            $sheet->setCellValue('C' . $row, $data['student_name']);
            $sheet->setCellValue('D' . $row, $data['class_name']);
            $sheet->setCellValue('E' . $row, $data['hadir']);
            $sheet->setCellValue('F' . $row, $data['izin']);
            $sheet->setCellValue('G' . $row, $data['sakit']);
            $sheet->setCellValue('H' . $row, $data['alpa']);
            $sheet->setCellValue('I' . $row, $data['terlambat']);
            $sheet->setCellValue('J' . $row, $data['total_pertemuan']);
            $sheet->setCellValue('K' . $row, $persen . '%');

            // Color coding for attendance percentage
            $color = $persen >= 75 ? '10B981' : ($persen >= 50 ? 'F59E0B' : 'EF4444');
            $sheet->getStyle('K' . $row)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($color);

            $row++;
        }

        // Style data rows
        $lastRow = $row - 1;
        $sheet->getStyle('A7:K' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]
            ]
        ]);

        // Auto-size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Merge title cells
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true);
        $sheet->getStyle('A2')->getFont()->setSize(12);

        // Generate filename
        $filename = 'Rekap_Absensi_' . date('Y-m-d_His') . '.xlsx';

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Export daftar siswa ke Excel
     */
    public function exportStudentsExcel()
    {
        $studentModel = new \App\Models\StudentModel();
        $students = $studentModel->getStudentsWithClass();

        if (empty($students)) {
            session()->setFlashdata('error', 'Tidak ada data siswa untuk diexport');
            return redirect()->back();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'DATA SISWA SMA NU KAPLONGAN');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setSize(14)->setBold(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Table header
        $headers = ['No', 'NIS', 'NISN', 'Nama', 'Kelas', 'JK', 'Status'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $col++;
        }

        $sheet->getStyle('A3:G3')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '6366F1']
            ],
            'font' => ['color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data
        $row = 4;
        $no = 1;
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $student['nis']);
            $sheet->setCellValue('C' . $row, $student['nisn'] ?? '-');
            $sheet->setCellValue('D' . $row, $student['name']);
            $sheet->setCellValue('E' . $row, $student['class_name']);
            $sheet->setCellValue('F' . $row, $student['gender']);
            $sheet->setCellValue('G' . $row, $student['status'] == 'active' ? 'Aktif' : 'Tidak Aktif');
            $row++;
        }

        // Auto-size
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Data_Siswa_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
