<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImporSiswa;
use App\Models\ImporSiswaBaris;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminImporSiswaController extends Controller
{
    /**
     * Download template resmi Excel siswa (.xls)
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $path = storage_path('app/private/import_siswa.xls');
        if (!file_exists($path)) {
            abort(404, 'File template import_siswa.xls tidak ditemukan.');
        }

        return response()->download($path, 'template_impor_siswa_smpm7.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    /**
     * Upload & parse file Excel ke tabel staging
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xls,xlsx', 'max:5120'], // Max 5MB
        ], [
            'file_excel.required' => 'Pilih file Excel yang akan diunggah.',
            'file_excel.mimes'    => 'Format file harus berupa Excel .xls atau .xlsx.',
            'file_excel.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_excel');
        $namaFile = $file->getClientOriginalName();

        try {
            // Load file dengan PhpSpreadsheet
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            if ($highestRow < 2) {
                return back()->with('error', 'File Excel kosong atau tidak memiliki baris data.');
            }

            // Baca header baris 1
            $headerRow = $sheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, false)[0] ?? [];
            $headers = array_map(fn($h) => strtolower(trim((string)$h)), $headerRow);

            if (!in_array('nis', $headers) || !in_array('nama', $headers)) {
                return back()->with('error', 'Format header file tidak sesuai. Kolom "nis" dan "nama" wajib ada.');
            }

            DB::beginTransaction();

            // 1. Buat Header Impor
            $impor = ImporSiswa::create([
                'admin_user_id'   => auth()->id(),
                'nama_file'       => $namaFile,
                'jumlah_baris'    => 0,
                'jumlah_berhasil' => 0,
                'jumlah_gagal'    => 0,
                'status'          => 'validasi',
                'created_at'      => now(),
            ]);

            // 2. Baca baris 2 s.d. selesai ke tabel staging
            $insertedRows = 0;
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, false)[0] ?? [];

                // Cek apakah seluruh baris kosong
                $nonEmpty = array_filter($rowData, fn($val) => !is_null($val) && trim((string)$val) !== '');
                if (empty($nonEmpty)) {
                    continue;
                }

                // Petakan nilai baris sesuai header
                $record = [];
                foreach ($headers as $index => $colName) {
                    if (empty($colName)) continue;
                    $rawVal = $rowData[$index] ?? '';
                    $val = is_null($rawVal) ? '' : trim((string)$rawVal);

                    // Sanitasi formula injection (=, +, -, @)
                    if (in_array(substr($val, 0, 1), ['=', '+', '-', '@'])) {
                        $val = "'" . $val;
                    }

                    $record[$colName] = $val;
                }

                // Normalisasi field tanggal
                $tglLahir = $this->parseDateValue($record['tgl_lahir'] ?? null);
                $diterimaTgl = $this->parseDateValue($record['diterima_tgl'] ?? null);

                ImporSiswaBaris::create([
                    'impor_id'        => $impor->id,
                    'nomor_baris'     => $row,
                    'nis'             => substr($record['nis'] ?? '', 0, 10),
                    'nisn'            => substr($record['nisn'] ?? '0', 0, 10) ?: '0',
                    'nama'            => substr($record['nama'] ?? '', 0, 100),
                    'jk'              => strtoupper($record['jk'] ?? 'L') === 'P' ? 'P' : 'L',
                    'tmp_lahir'       => substr($record['tmp_lahir'] ?? '', 0, 50),
                    'tgl_lahir'       => $tglLahir,
                    'agama'           => substr($record['agama'] ?? '', 0, 10),
                    'status'          => substr($record['status'] ?? '', 0, 2),
                    'anakke'          => is_numeric($record['anakke'] ?? null) ? (int)$record['anakke'] : 1,
                    'alamat'          => substr($record['alamat'] ?? '', 0, 50),
                    'notelp'          => substr($record['notelp'] ?? '', 0, 13),
                    'sek_asal'        => substr($record['sek_asal'] ?? '', 0, 30),
                    'sek_asal_alamat' => substr($record['sek_asal_alamat'] ?? '', 0, 50),
                    'diterima_kelas'  => substr($record['diterima_kelas'] ?? '', 0, 5),
                    'diterima_tgl'    => $diterimaTgl,
                    'diterima_smt'    => substr($record['diterima_smt'] ?? '', 0, 2),
                    'ijazah_no'       => substr($record['ijazah_no'] ?? '', 0, 50),
                    'ijazah_thn'      => substr($record['ijazah_thn'] ?? '', 0, 4),
                    'skhun_no'        => substr($record['skhun_no'] ?? '', 0, 50),
                    'skhun_thn'       => substr($record['skhun_thn'] ?? '', 0, 4),
                    'ortu_ayah'       => substr($record['ortu_ayah'] ?? '', 0, 50),
                    'ortu_ibu'        => substr($record['ortu_ibu'] ?? '', 0, 50),
                    'ortu_alamat'     => substr($record['ortu_alamat'] ?? '', 0, 50),
                    'ortu_notelp'     => substr($record['ortu_notelp'] ?? '', 0, 13),
                    'ortu_ayah_pkj'   => substr($record['ortu_ayah_pkj'] ?? '', 0, 30),
                    'ortu_ibu_pkj'    => substr($record['ortu_ibu_pkj'] ?? '', 0, 30),
                    'wali'            => substr($record['wali'] ?? '', 0, 20),
                    'wali_alamat'     => substr($record['wali_alamat'] ?? '', 0, 50),
                    'notelp_rumah'    => substr($record['notelp_rumah'] ?? '', 0, 13),
                    'wali_pkj'        => substr($record['wali_pkj'] ?? '', 0, 13),
                    'status_validasi' => 'valid',
                ]);

                $insertedRows++;
            }

            if ($insertedRows === 0) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada baris data siswa yang ditemukan pada file.');
            }

            // 3. Jalankan Stored Procedure Validasi di DB
            DB::statement('CALL sp_impor_siswa_validasi(?)', [$impor->id]);

            DB::commit();

            return redirect()->route('admin.master.siswa.impor.preview', $impor);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pratinjau hasil validasi staging
     */
    public function preview(ImporSiswa $impor): View
    {
        $impor->load('baris');

        $barisList = $impor->baris()->orderBy('nomor_baris')->get();
        $validRows = $barisList->where('status_validasi', 'valid');
        $duplikatRows = $barisList->where('status_validasi', 'duplikat');
        $errorRows = $barisList->where('status_validasi', 'error');

        return view('pages.admin.master.siswa.impor_preview', compact('impor', 'barisList', 'validRows', 'duplikatRows', 'errorRows'));
    }

    /**
     * Eksekusi Commit Data Valid ke m_siswa via Stored Procedure
     */
    public function commit(ImporSiswa $impor): RedirectResponse
    {
        if ($impor->status === 'selesai') {
            return redirect()->route('admin.master.siswa.index')->with('error', 'Sesi impor ini sudah pernah dieksekusi.');
        }

        try {
            // Jalankan Stored Procedure Commit Bertransaksi
            DB::statement('CALL sp_impor_siswa_commit(?, @p_inserted, @p_skipped)', [$impor->id]);
            $res = DB::select('SELECT @p_inserted as inserted, @p_skipped as skipped')[0] ?? null;

            $inserted = $res->inserted ?? 0;
            $skipped = $res->skipped ?? 0;

            return redirect()
                ->route('admin.master.siswa.index')
                ->with('success', "Impor berhasil! {$inserted} siswa baru dimasukkan ke basis data m_siswa. ({$skipped} baris dilewati karena duplikat/error).");
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyimpan data impor: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan sesi impor dan bersihkan tabel staging
     */
    public function cancel(ImporSiswa $impor): RedirectResponse
    {
        try {
            $impor->delete(); // cascade delete baris
            return redirect()->route('admin.master.siswa.index')->with('success', 'Sesi impor berhasil dibatalkan.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal membatalkan sesi impor: ' . $e->getMessage());
        }
    }

    /**
     * Helper konversi tanggal Excel / Text ke YYYY-MM-DD
     */
    private function parseDateValue($val): string
    {
        if (empty($val) || $val === '0000-00-00') {
            return '0000-00-00';
        }

        // Jika angka numerik serial Excel (misal 44561)
        if (is_numeric($val) && (int)$val > 1000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($val)->format('Y-m-d');
            } catch (\Throwable) {
                return '0000-00-00';
            }
        }

        // Jika format string tanggal
        try {
            $ts = strtotime((string)$val);
            if ($ts !== false && $ts > 0) {
                return date('Y-m-d', $ts);
            }
        } catch (\Throwable) {}

        return '0000-00-00';
    }
}
