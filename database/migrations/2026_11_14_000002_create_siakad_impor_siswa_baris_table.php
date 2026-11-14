<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Pastikan tabel header siakad_impor_siswa ada / sesuai
        if (!Schema::hasTable('siakad_impor_siswa')) {
            Schema::create('siakad_impor_siswa', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_user_id');
                $table->string('nama_file');
                $table->unsignedInteger('jumlah_baris')->default(0);
                $table->unsignedInteger('jumlah_berhasil')->default(0);
                $table->unsignedInteger('jumlah_gagal')->default(0);
                $table->enum('status', ['validasi', 'selesai', 'gagal'])->default('validasi');
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 2. Buat tabel staging baris siakad_impor_siswa_baris
        if (!Schema::hasTable('siakad_impor_siswa_baris')) {
            Schema::create('siakad_impor_siswa_baris', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('impor_id');
                $table->unsignedInteger('nomor_baris');

                // 30 Kolom Siswa Sesuai Template RKM
                $table->string('nis', 10)->default('');
                $table->string('nisn', 10)->default('0');
                $table->string('nama', 100)->default('');
                $table->enum('jk', ['L', 'P'])->default('L');
                $table->string('tmp_lahir', 50)->default('');
                $table->date('tgl_lahir')->default('0000-00-00');
                $table->string('agama', 10)->default('');
                $table->string('status', 2)->default('');
                $table->integer('anakke')->default(1);
                $table->string('alamat', 50)->default('');
                $table->string('notelp', 13)->default('');
                $table->string('sek_asal', 30)->default('');
                $table->string('sek_asal_alamat', 50)->default('');
                $table->string('diterima_kelas', 5)->default('');
                $table->date('diterima_tgl')->default('0000-00-00');
                $table->string('diterima_smt', 2)->default('');
                $table->string('ijazah_no', 50)->default('');
                $table->string('ijazah_thn', 4)->default('');
                $table->string('skhun_no', 50)->default('');
                $table->string('skhun_thn', 4)->default('');
                $table->string('ortu_ayah', 50)->default('');
                $table->string('ortu_ibu', 50)->default('');
                $table->string('ortu_alamat', 50)->default('');
                $table->string('ortu_notelp', 13)->default('');
                $table->string('ortu_ayah_pkj', 30)->default('');
                $table->string('ortu_ibu_pkj', 30)->default('');
                $table->string('wali', 20)->default('');
                $table->string('wali_alamat', 50)->default('');
                $table->string('notelp_rumah', 13)->default('');
                $table->string('wali_pkj', 13)->default('');

                // Status Staging & Pesan Validasi
                $table->enum('status_validasi', ['valid', 'duplikat', 'error'])->default('valid');
                $table->text('pesan_error')->nullable();

                $table->foreign('impor_id')
                    ->references('id')
                    ->on('siakad_impor_siswa')
                    ->onDelete('cascade');

                $table->index(['impor_id', 'status_validasi']);
                $table->index('nis');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siakad_impor_siswa_baris');
    }
};
