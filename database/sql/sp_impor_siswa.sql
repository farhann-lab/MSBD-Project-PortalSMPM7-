-- ============================================================================
-- STORED PROCEDURES IMPOR SISWA MASSAL (ARCHITECTURE.md 8.6 & 8.8)
-- SIAKAD SMP Muhammadiyah 7 Medan
-- ============================================================================

DELIMITER $$

-- ----------------------------------------------------------------------------
-- 1. sp_impor_siswa_validasi
-- Menandai baris staging: 'valid', 'duplikat', atau 'error' beserta alasannya
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_impor_siswa_validasi$$

CREATE PROCEDURE sp_impor_siswa_validasi(
    IN p_impor_id BIGINT UNSIGNED
)
BEGIN
    -- 1. Reset status ke valid
    UPDATE siakad_impor_siswa_baris
    SET status_validasi = 'valid',
        pesan_error = NULL
    WHERE impor_id = p_impor_id;

    -- 2. Validasi field wajib kosong (NIS atau Nama kosong)
    UPDATE siakad_impor_siswa_baris
    SET status_validasi = 'error',
        pesan_error = CONCAT_WS(', ', 
            IF(TRIM(nis) = '', 'NIS wajib diisi', NULL),
            IF(TRIM(nama) = '', 'Nama siswa wajib diisi', NULL)
        )
    WHERE impor_id = p_impor_id
      AND (TRIM(nis) = '' OR TRIM(nama) = '');

    -- 3. Validasi duplikat NIS antar-baris dalam file Excel yang sama
    UPDATE siakad_impor_siswa_baris b
    JOIN (
        SELECT nis
        FROM siakad_impor_siswa_baris
        WHERE impor_id = p_impor_id
          AND TRIM(nis) != ''
        GROUP BY nis
        HAVING COUNT(*) > 1
    ) dup ON b.nis = dup.nis
    SET b.status_validasi = 'duplikat',
        b.pesan_error = 'NIS ganda/duplikat ditemukan di dalam file yang sama'
    WHERE b.impor_id = p_impor_id
      AND b.status_validasi = 'valid';

    -- 4. Validasi duplikat NIS terhadap database m_siswa yang sudah ada
    UPDATE siakad_impor_siswa_baris b
    JOIN m_siswa s ON b.nis = s.nis
    SET b.status_validasi = 'duplikat',
        b.pesan_error = CONCAT('NIS ', b.nis, ' sudah terdaftar atas nama: ', s.nama)
    WHERE b.impor_id = p_impor_id
      AND b.status_validasi = 'valid';

    -- 5. Perbarui ringkasan total di tabel header siakad_impor_siswa
    UPDATE siakad_impor_siswa
    SET jumlah_baris = (SELECT COUNT(*) FROM siakad_impor_siswa_baris WHERE impor_id = p_impor_id),
        jumlah_berhasil = (SELECT COUNT(*) FROM siakad_impor_siswa_baris WHERE impor_id = p_impor_id AND status_validasi = 'valid'),
        jumlah_gagal = (SELECT COUNT(*) FROM siakad_impor_siswa_baris WHERE impor_id = p_impor_id AND status_validasi IN ('error', 'duplikat'))
    WHERE id = p_impor_id;

END$$

-- ----------------------------------------------------------------------------
-- 2. sp_impor_siswa_commit
-- Memindahkan baris yang valid ke m_siswa dalam satu transaksi ACID
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS sp_impor_siswa_commit$$

CREATE PROCEDURE sp_impor_siswa_commit(
    IN p_impor_id BIGINT UNSIGNED,
    OUT p_inserted INT,
    OUT p_skipped INT
)
sp_block: BEGIN
    DECLARE v_valid_count INT DEFAULT 0;
    
    -- Penanganan Error SQL: Rollback otomatis jika ada error fatal
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        UPDATE siakad_impor_siswa SET status = 'gagal' WHERE id = p_impor_id;
        RESIGNAL;
    END;

    -- Hitung jumlah baris yang berstatus valid
    SELECT COUNT(*) INTO v_valid_count
    FROM siakad_impor_siswa_baris
    WHERE impor_id = p_impor_id AND status_validasi = 'valid';

    IF v_valid_count = 0 THEN
        SET p_inserted = 0;
        SET p_skipped = (SELECT COUNT(*) FROM siakad_impor_siswa_baris WHERE impor_id = p_impor_id);
        LEAVE sp_block;
    END IF;

    START TRANSACTION;

    -- Insert baris valid ke m_siswa dengan nilai default RKM
    INSERT INTO m_siswa (
        nis, nisn, nama, jk, tmp_lahir, tgl_lahir, agama, status, anakke,
        alamat, notelp, sek_asal, sek_asal_alamat, diterima_kelas,
        diterima_tgl, diterima_smt, ijazah_no, ijazah_thn, skhun_no, skhun_thn,
        ortu_ayah, ortu_ibu, ortu_alamat, ortu_notelp, ortu_ayah_pkj, ortu_ibu_pkj,
        wali, wali_alamat, notelp_rumah, wali_pkj, inputID, stat_data, foto
    )
    SELECT
        b.nis,
        IFNULL(b.nisn, '0'),
        b.nama,
        b.jk,
        IFNULL(b.tmp_lahir, ''),
        IFNULL(b.tgl_lahir, '0000-00-00'),
        IFNULL(b.agama, ''),
        IFNULL(b.status, ''),
        IFNULL(b.anakke, 1),
        IFNULL(b.alamat, ''),
        IFNULL(b.notelp, ''),
        IFNULL(b.sek_asal, ''),
        IFNULL(b.sek_asal_alamat, ''),
        IFNULL(b.diterima_kelas, ''),
        IFNULL(b.diterima_tgl, '0000-00-00'),
        IFNULL(b.diterima_smt, ''),
        IFNULL(b.ijazah_no, ''),
        IFNULL(b.ijazah_thn, ''),
        IFNULL(b.skhun_no, ''),
        IFNULL(b.skhun_thn, ''),
        IFNULL(b.ortu_ayah, ''),
        IFNULL(b.ortu_ibu, ''),
        IFNULL(b.ortu_alamat, ''),
        IFNULL(b.ortu_notelp, ''),
        IFNULL(b.ortu_ayah_pkj, ''),
        IFNULL(b.ortu_ibu_pkj, ''),
        IFNULL(b.wali, ''),
        IFNULL(b.wali_alamat, ''),
        IFNULL(b.notelp_rumah, ''),
        IFNULL(b.wali_pkj, ''),
        0,   -- inputID default 0 RKM
        'A', -- stat_data default A (Aktif)
        ''   -- foto default string kosong
    FROM siakad_impor_siswa_baris b
    WHERE b.impor_id = p_impor_id
      AND b.status_validasi = 'valid';

    -- Catat jumlah data yang dimasukkan
    SET p_inserted = ROW_COUNT();
    SET p_skipped = (SELECT COUNT(*) FROM siakad_impor_siswa_baris WHERE impor_id = p_impor_id AND status_validasi != 'valid');

    -- Update status header proses impor
    UPDATE siakad_impor_siswa
    SET status = 'selesai',
        jumlah_berhasil = p_inserted,
        jumlah_gagal = p_skipped
    WHERE id = p_impor_id;

    COMMIT;
END$$

DELIMITER ;
