-- RBAC SIAKAD (tambahan GRANT, bukan skema/migrasi) untuk MySQL 8.0
-- Jalankan HANYA pada salinan siakad_rkm_dev, sebagai DBA, setelah audit SHOW GRANTS.
-- Melengkapi 01_roles_dan_grants_mysql8.sql (14 Nov), bukan pengganti.
-- GRANT bersifat ADITIF: tidak mencabut privilege berlebihan yang sudah terpasang.
USE `siakad_rkm_dev`;

-- Login dan ganti password pada tabel siakad_users (tidak menyentuh m_admin RKM).
-- Model User memuat created_at/updated_at melalui SELECT *.
GRANT SELECT (`created_at`, `updated_at`)
ON `siakad_rkm_dev`.`siakad_users` TO `role_siakad_auth`;
GRANT UPDATE (`password`, `wajib_ganti_password`, `updated_at`)
ON `siakad_rkm_dev`.`siakad_users` TO `role_siakad_auth`;

-- Baca periode aktif untuk website publik (HomeController).
GRANT SELECT ON `siakad_rkm_dev`.`v_periode_aktif` TO `role_siakad_publik`;

-- CRUD master guru/siswa yang SUDAH ada di controller Laravel.
-- Konfirmasi arti ENUM stat_data terhadap kode RKM sebelum mengaktifkan UI nonaktif.
GRANT UPDATE (`stat_data`) ON `siakad_rkm_dev`.`m_guru` TO `role_siakad_admin`;
GRANT UPDATE (`stat_data`) ON `siakad_rkm_dev`.`m_siswa` TO `role_siakad_admin`;

-- Admin kelola akun guru/siswa dan admin biasa, tetapi TIDAK diberi UPDATE
-- atas role/is_admin_utama/id_guru/id_siswa (jangan jadikan GRANT saja otorisasi admin utama).
-- INSERT tabel dibutuhkan untuk pembuatan akun baru; amankan melalui backend dan audit.
GRANT INSERT ON `siakad_rkm_dev`.`siakad_users` TO `role_siakad_admin`;
GRANT UPDATE (`username`, `password`, `is_active`, `wajib_ganti_password`, `updated_at`)
ON `siakad_rkm_dev`.`siakad_users` TO `role_siakad_admin`;

-- Upload staging dan batal impor (fitur ini tetap DIBLOKIR sampai SP benar-benar siap).
GRANT SELECT, INSERT, UPDATE, DELETE
ON `siakad_rkm_dev`.`siakad_impor_siswa_baris` TO `role_siakad_admin`;
GRANT DELETE ON `siakad_rkm_dev`.`siakad_impor_siswa` TO `role_siakad_admin`;

-- Tidak ada GRANT INSERT/UPDATE/DELETE untuk siswa pada tabel akademik.
-- Tidak ada hak DDL atau hak tulis nilai/rapor RKM.
-- Tidak memberikan EXECUTE sp_simpan_absensi_siswa kepada guru sebelum actor tervalidasi.

-- Verifikasi setelah menautkan role ke akun (nama host mungkin bukan localhost):
-- SHOW GRANTS FOR 'role_siakad_admin';
-- SHOW GRANTS FOR 'role_siakad_guru';
-- SHOW GRANTS FOR 'role_siakad_siswa';
-- SHOW GRANTS FOR 'role_siakad_auth';
-- SHOW GRANTS FOR 'role_siakad_publik';
