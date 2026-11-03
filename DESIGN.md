# DESIGN.md --- Panduan Desain UI SIAKAD SMP Muhammadiyah 7 Medan

  -------------- -----------------------------------------------------
  Produk         SIAKAD SMP Muhammadiyah 7 Medan
  Acuan          `PRD_V2.md` v0.3, 31 Oktober 2026 (Draft Sementara)
  Versi desain   v0.1 --- Draft
  Teknologi      Laravel Blade + Tailwind CSS + Vite
  -------------- -----------------------------------------------------

> Dokumen ini adalah panduan visual dan pola UI. Fitur, alur, prioritas,
> serta hak akses tetap mengikuti PRD v0.3. Karena PRD terbaru mencakup
> website publik dan seluruh SIAKAD, panduan ini mencakup website
> publik, login, dashboard Admin, Guru/Wali Kelas, dan Siswa.

## 1. Arah Visual

Gunakan gaya seperti referensi desain yang diberikan: modern, bersih,
ramah, profesional, dan bernuansa hijau sekolah. Ciri utamanya adalah
latar hijau sangat pucat, permukaan putih, kartu dengan sudut membulat,
border tipis, shadow lembut, foto yang tertata rapi, serta whitespace
yang cukup.

Prinsip: - **Green school identity:** hijau menjadi identitas utama,
bukan dekorasi berlebihan. - **Clean and organized:** konten
dikelompokkan dengan section dan card. - **Friendly but professional:**
mudah dipahami siswa, orang tua, guru, dan staf. - **Readable first:**
keterbacaan lebih penting daripada ornamen. - **Consistent components:**
tombol, kartu, form, tabel, badge, dan navigasi konsisten. -
**Mobile-first:** mulai dari layar kecil lalu dikembangkan ke desktop. -
**Lightweight:** hindari efek, aset, dan JavaScript yang tidak
diperlukan.

Gambar referensi adalah inspirasi warna, bentuk, proporsi, dan komposisi
visual---bukan daftar fitur tambahan.

## 2. Design Tokens

### 2.1 Palet warna

  Token             Hex         Penggunaan
  ----------------- ----------- --------------------------------------------
  Forest Green      `#344B39`   Warna utama, tombol primer, sidebar aktif
  Sage Green        `#819A73`   Aksen, ikon, hover
  Mint Green        `#B8CCC3`   Latar aksen, border lembut, panel sekunder
  Teal Green        `#3F5F58`   Tautan dan aksen sekunder
  Dark Green        `#0F1D0F`   Heading dan teks utama
  Olive Green       `#697B65`   Badge dan aksen tambahan
  White             `#FFFFFF`   Permukaan kartu, form, navbar
  Soft Background   `#F5F8F5`   Latar utama halaman

### 2.2 Aturan warna

-   Latar halaman publik dan dashboard: `#F5F8F5`.
-   Kartu, modal, dropdown, dan form: putih.
-   Teks utama: Dark Green.
-   Tombol utama dan navigasi aktif: Forest Green.
-   Ikon dan aksen: Sage atau Teal Green.
-   Border: Mint Green dengan opacity rendah.
-   Teks putih hanya di atas permukaan hijau yang cukup gelap.
-   Warna status sukses, peringatan, dan kesalahan boleh memakai warna
    semantik; jangan mengganti identitas hijau utama.
-   Jangan memakai semua warna palet sekaligus dalam satu komponen.

## 3. Bentuk dan Komponen Dasar

### Radius

  Komponen              Gaya
  --------------------- ----------------------------------
  Section/kartu besar   `rounded-2xl` atau `rounded-3xl`
  Kartu konten          `rounded-2xl`
  Input/select          `rounded-xl`
  Tombol                `rounded-lg` atau `rounded-xl`
  Badge/filter chip     `rounded-full`
  Modal                 `rounded-2xl`

Gunakan bentuk membulat secara konsisten. Jangan membuat semua elemen
berbentuk kapsul; kapsul terutama untuk badge dan filter.

### Border dan shadow

-   Border tipis seperti `border-[#B8CCC3]/60`.
-   Kartu memakai `shadow-sm`.
-   Modal boleh memakai `shadow-lg` yang lembut.
-   Hindari bayangan hitam tebal, outline dekoratif, dan gradient
    mencolok.
-   Utamakan spacing untuk memisahkan konten, bukan garis tebal.

### Ikon

-   Gunakan satu keluarga ikon outline yang konsisten.
-   Ukuran ikon navigasi sekitar 16--20 px.
-   Ikon dapat ditempatkan pada kotak kecil berlatar Mint/Sage
    transparan.
-   Hindari emoji sebagai ikon antarmuka.

## 4. Tipografi

Gunakan Inter atau system sans-serif. Hindari banyak keluarga font.

  Elemen                 Ukuran
  ---------------------- -----------
  Hero heading desktop   36--48 px
  Hero heading mobile    28--32 px
  Page title             24--30 px
  Section heading        18--22 px
  Card title             16--18 px
  Body                   14--16 px
  Metadata/label         12--14 px

Heading tegas dan ringkas. Body memakai line-height nyaman. Label form
harus terlihat dan tidak hanya mengandalkan placeholder. Metadata boleh
lebih kecil tetapi tetap terbaca.

## 5. Layout dan Spacing

-   Container publik: `max-w-7xl mx-auto`.
-   Container login: `max-w-md`.
-   Latar halaman Soft Background; konten utama di atas permukaan putih.
-   Padding halaman desktop 24--32 px, mobile sekitar 16 px.
-   Jarak antar-section 24--40 px; antar-kartu 16--24 px.
-   Padding kartu 16--24 px.
-   Desktop menggunakan grid 2--4 kolom sesuai konten; tablet 2 kolom;
    mobile umumnya 1 kolom.
-   Hindari lebar tetap yang menyebabkan overflow.

## 6. Navigasi dan Shell Aplikasi

### 6.1 Website publik

-   Navbar putih dengan logo dan nama sekolah di kiri.
-   Navigasi utama di tengah/kanan.
-   Tombol **Login SIAKAD** sebagai CTA Forest Green.
-   Submenu untuk Profil Sekolah, Direktori, dan Kesiswaan.
-   Navbar boleh sticky jika tidak mengganggu ruang baca.
-   Footer memakai hijau gelap dan putih dengan kontras yang baik;
    tampilkan kontak dan tautan penting.

Navigasi mengikuti PRD: Beranda, Profil Sekolah, Direktori, Kesiswaan,
Galeri, Berita, Pengumuman, Login SIAKAD.

### 6.2 Panel setelah login

-   Sidebar hijau gelap di kiri pada desktop.
-   Logo/nama SIAKAD di bagian atas sidebar.
-   Menu aktif diberi latar Forest Green yang lebih terang atau panel
    kontras yang jelas.
-   Area konten utama memakai Soft Background.
-   Topbar ringkas memuat judul halaman, konteks tahun ajaran bila
    relevan, dan menu profil.
-   Pada tablet/mobile, sidebar berubah menjadi drawer.
-   Menu ditampilkan sesuai role dan penetapan wali kelas, bukan seluruh
    menu untuk semua pengguna.

Role: - **Admin:** menu administrasi; admin utama hanya penanda izin
tambahan, bukan role baru. - **Guru:** dashboard guru. - **Wali Kelas:**
dashboard Guru yang sama dengan menu tambahan saat penetapan wali kelas
aktif. - **Siswa:** dashboard khusus dengan navigasi lebih sederhana.

## 7. Komponen UI

### Buttons

-   Primary: Forest Green, teks putih, `rounded-xl`.
-   Secondary: putih, border hijau lembut, teks Forest Green.
-   Ghost: transparan, teks hijau, hover Mint lembut.
-   Danger: warna merah semantik untuk tindakan destruktif.
-   Tinggi tombol umum 40--44 px.
-   Sediakan hover, focus, active, disabled, dan loading state.

### Cards

-   Putih, border tipis, shadow-sm, radius-2xl.
-   Padding 16--24 px.
-   Judul, metadata, dan aksi memiliki hierarki jelas.
-   Kartu statistik memakai angka besar, label singkat, ikon sederhana.
-   Kartu berita/galeri memakai rasio gambar konsisten dan
    `object-cover`.

### Forms

-   Label di atas input.
-   Input putih, border halus, radius-xl, padding nyaman.
-   Focus ring hijau yang jelas.
-   Pesan validasi dekat field terkait.
-   Form panjang dikelompokkan per section/card.
-   Tombol simpan dan batal mudah ditemukan.

### Tables and lists

-   Header tabel berlatar hijau sangat pucat.
-   Baris putih dengan border bawah tipis.
-   Hover row lembut.
-   Aksi edit/hapus di kolom kanan.
-   Gunakan pagination dan pencarian sesuai PRD.
-   Tabel lebar pada mobile memakai overflow horizontal terkendali atau
    diubah menjadi kartu.
-   Jangan mengecilkan font sampai sulit dibaca.

### Badges and status

Badge berbentuk kapsul dengan teks yang jelas. Status tidak boleh
dibedakan berdasarkan warna saja. - Konten: Draft, Published,
Archived. - Absensi: Hadir, Sakit, Izin, Tanpa Keterangan. - Gunakan
warna status semantik yang lembut dan tetap terbaca.

### Search, filter, pagination

-   Search memiliki ikon di kiri.
-   Filter berada dalam satu baris pada desktop dan dapat membungkus di
    mobile.
-   Filter terpilih memakai Forest Green dan teks putih.
-   Pagination sederhana; halaman aktif Forest Green.

### Modal, confirmation, states

-   Modal putih, radius-2xl, shadow lembut.
-   Judul menjelaskan tindakan; tombol batal dan konfirmasi dibedakan.
-   Tindakan destruktif perlu konfirmasi.
-   Empty state memakai ikon sederhana, judul singkat, dan arahan.
-   Loading memakai skeleton ringan.
-   Error tidak membocorkan detail teknis.
-   Notifikasi sukses ringkas dan tidak menghalangi pekerjaan.

## 8. Website Publik

### Beranda

Komposisi mengikuti PRD: 1. Hero: foto/banner sekolah, nama, slogan,
deskripsi, tombol profil, tombol berita/pengumuman. 2. Tiga pintasan
portal: CBT, Rapor, PPDB. 3. Sambutan kepala sekolah. 4. Berita terbaru.
5. Pengumuman terbaru. 6. Statistik siswa, guru, dan kelas. 7. Footer.

Hero memakai foto lebar dengan overlay hijau gelap transparan agar teks
terbaca. Gunakan radius-2xl/3xl. Hindari carousel berat jika tidak
diperlukan.

### Profil sekolah

-   Judul halaman dan breadcrumb.
-   Tentang sekolah, visi/misi, kepala sekolah, struktur organisasi,
    sarana dan prasarana.
-   Sambutan kepala sekolah dapat memakai foto dan teks dua kolom pada
    desktop.
-   Struktur organisasi berupa daftar atau bagan sederhana.
-   Fasilitas memakai grid kartu foto dan deskripsi.

### Direktori guru dan siswa

-   Judul, deskripsi, pencarian, filter, dan daftar data.
-   Guru: nama serta jabatan/mata pelajaran hanya jika tersedia dan
    boleh dipublikasikan.
-   Siswa: nama dan kelas sesuai kebijakan publikasi.
-   Jangan tampilkan NIS/NISN, NIK, tanggal lahir, alamat, nomor
    telepon, data orang tua, nilai, atau absensi pada direktori publik.
-   Gunakan tabel atau kartu sesuai kepadatan data.

### Kesiswaan

-   Organisasi siswa, ekstrakurikuler, dan prestasi menggunakan kartu.
-   Organisasi siswa merupakan konten statis.
-   Ekstrakurikuler menampilkan nama, gambar, deskripsi, dan jadwal
    hanya jika dikonfirmasi.
-   Prestasi menampilkan nama, jenis, tingkat, tahun, dan deskripsi.
-   Nama/foto siswa atau tim mengikuti izin dan kebijakan sekolah.
-   Data dummy tidak boleh terlihat sebagai prestasi resmi.

### Galeri

-   Grid foto konsisten, radius-xl/2xl.
-   Gunakan thumbnail dan lazy loading.
-   Tanggal/kategori hanya jika tersedia.
-   Lightbox hanya jika diperlukan dan memakai solusi ringan.

### Berita dan pengumuman

-   Daftar dan detail berita/pengumuman dibuat terpisah.
-   Kartu berita: foto, judul, ringkasan, tanggal, tautan detail.
-   Pengumuman memakai list/kartu lebih ringkas.
-   Detail berisi judul, tanggal, isi, dan gambar utama untuk berita.
-   Setelah integrasi backend, konten publik hanya menampilkan status
    Published.

## 9. Halaman Login

-   Latar Soft Background dengan panel putih.
-   Logo dan nama sekolah/SIAKAD terlihat jelas.
-   Form di tengah dengan lebar terbatas.
-   Username dan password memakai komponen input standar.
-   Tombol Login Forest Green dan lebar penuh.
-   Pesan gagal tidak mengungkap apakah username tertentu terdaftar.
-   Jangan menambahkan login sosial atau fitur lupa password jika belum
    ditetapkan PRD.
-   Pastikan nyaman digunakan di ponsel dan keyboard.

## 10. Dashboard per Role

### 10.1 Admin

-   Sidebar hijau gelap, area kerja terang.
-   Kartu ringkasan guru, siswa, kelas, mata pelajaran, tahun ajaran
    aktif.
-   Ringkasan absensi jika tersedia.
-   Aktivitas pengelolaan konten jika tersedia.
-   Kartu statistik dapat diklik menuju detail terkait.

Kelompok menu admin: - Dashboard. - Website/kelola konten publik. - Data
master: Guru, Siswa, Manajemen Kelas, Mata Pelajaran, Penugasan Guru,
Tahun Ajaran, Ekstrakurikuler. - Akademik: Jadwal Pelajaran, Kenaikan
Kelas, Pemantauan Absensi. - Akun dan pengaturan sesuai PRD.

Gunakan submenu/collapse agar sidebar tidak terlalu panjang.

### 10.2 Guru

-   Sapaan dan identitas guru.
-   Kartu kelas dan mata pelajaran yang diampu.
-   Jadwal mengajar hari ini.
-   Ringkasan absensi siswa yang perlu dilengkapi.
-   Materi pembelajaran yang dikelola.
-   Tautan aplikasi RKM di sidebar.
-   Akses data dibatasi sesuai penugasan.

### 10.3 Wali Kelas

Wali kelas menggunakan dashboard Guru yang sama. Tambahkan area/menu
wali kelas saat penetapan aktif: - Ringkasan jumlah siswa kelas
perwalian. - Daftar siswa. - Ringkasan dan riwayat absensi untuk
pemantauan. - Filter tanggal/pertemuan bila diperlukan.

Wali kelas memantau absensi kelas perwaliannya dan tidak mengubah
catatan guru lain. Jika wali kelas juga guru pengampu, hak pengajaran
mengikuti penugasan dan aturan PRD/TBD-36.

### 10.4 Siswa

Tampilan sederhana: - Identitas, kelas, dan tahun ajaran aktif. - Jadwal
pelajaran. - Materi terbaru. - Ringkasan absensi pribadi. - Navigasi
Jadwal, Materi Pembelajaran, Absensi Saya, dan Profil.

Siswa hanya melihat data miliknya dan tidak dapat mengubah data
akademik.

## 11. Pola Visual Modul Operasional

### Data master

Halaman guru, siswa, kelas, mata pelajaran, penugasan, tahun ajaran, dan
ekstrakurikuler menggunakan pola: - Page title dan deskripsi. - Tombol
aksi utama. - Toolbar pencarian/filter. - Tabel data dan pagination. -
Form tambah/edit di halaman atau modal sesuai panjang field.

### Impor Excel siswa

Urutan visual: 1. Area unduh template dan instruksi. 2. Area unggah
file. 3. Panel hasil validasi. 4. Daftar kesalahan beserta nomor baris
dan alasan. 5. Ringkasan duplikat. 6. Tombol konfirmasi impor.

Jangan menyimpan otomatis setelah upload. Tampilkan hasil validasi dan
minta konfirmasi sesuai PRD.

### Manajemen kelas dan tahun ajaran

-   Tab/submenu Data Kelas, Penempatan Siswa, Penetapan Wali Kelas.
-   Filter tahun ajaran mudah ditemukan.
-   Penempatan menampilkan kelas tujuan dan daftar siswa.
-   Riwayat tahun ajaran sebelumnya tetap dapat dilihat.

### Jadwal pelajaran

-   Tabel/grid jadwal mingguan.
-   Hari dikelompokkan jelas; jam disusun kronologis.
-   Item jadwal menampilkan mata pelajaran dan guru/kelas.
-   Konflik jadwal ditampilkan sebagai validasi yang jelas.

### Absensi siswa

-   Tampilkan kelas, mata pelajaran, tanggal, dan pertemuan di bagian
    atas.
-   Daftar siswa dalam tabel/list.
-   Status Hadir, Sakit, Izin, Tanpa Keterangan memakai kontrol yang
    jelas.
-   Tombol simpan mudah ditemukan.
-   Wali kelas/admin menggunakan tampilan pemantauan sesuai hak akses.
-   Siswa hanya melihat riwayat miliknya.

### Materi pembelajaran

-   Daftar materi: judul, mata pelajaran, kelas tujuan, tanggal, tipe
    lampiran.
-   Form membedakan unggahan file dan tautan eksternal.
-   Aksi edit/hapus hanya untuk guru pemilik materi sesuai PRD.
-   Siswa hanya melihat materi kelasnya.
-   Status Draft/Published/Archived materi tidak dibuat sebagai fitur
    wajib karena PRD menempatkannya di fase lanjutan opsional.

## 12. Responsivitas

**Desktop** - Navbar publik horizontal. - Dashboard memakai sidebar dan
konten utama. - Tabel lebar penuh. - Grid 3--4 kolom jika sesuai.

**Tablet** - Sidebar dipersempit atau menjadi drawer. - Grid 2 kolom. -
Toolbar filter boleh membungkus.

**Mobile** - Navbar publik menjadi menu mobile. - Sidebar dashboard
menjadi drawer. - Section ditumpuk vertikal. - Form satu kolom. - Tombol
utama dapat full-width. - Tabel lebar memakai scroll horizontal atau
kartu. - Jangan menyembunyikan fungsi penting di hover.

## 13. Motion dan Interaksi

-   Animasi singkat dan fungsional.
-   Hover kartu mengubah border/shadow secara halus.
-   Transisi tombol sekitar 150--200 ms.
-   Dropdown, drawer, dan modal memiliki state buka/tutup yang jelas.
-   Hormati `prefers-reduced-motion`.
-   Hindari parallax, autoplay carousel, dan animasi besar.

## 14. Aksesibilitas

-   Gunakan semantic HTML.
-   Semua input memiliki label.
-   Tombol dan tautan memiliki nama yang jelas.
-   Focus ring terlihat saat navigasi keyboard.
-   Kontras teks dan latar memadai.
-   Status tidak dibedakan dengan warna saja.
-   Gambar informatif memiliki alt text.
-   Dropdown, modal, dan drawer dapat dioperasikan dengan keyboard.
-   Tabel memiliki header kolom yang tepat.
-   Data pribadi siswa tidak ditampilkan di area publik di luar
    ketentuan PRD.

## 15. Implementasi Laravel Blade + Tailwind CSS

Gunakan Laravel Blade + Tailwind CSS + Vite. **Jangan gunakan
Bootstrap.** Utility class ditulis langsung pada Blade dan komponen
dibuat reusable. CSS global hanya untuk import Tailwind, token tema,
font, dan kebutuhan dasar yang tidak praktis ditangani utility.

### Contoh token Tailwind CSS v4

``` css
@import "tailwindcss";

@theme {
  --color-forest: #344B39;
  --color-sage: #819A73;
  --color-mint: #B8CCC3;
  --color-teal: #3F5F58;
  --color-dark-green: #0F1D0F;
  --color-olive: #697B65;
  --color-soft-background: #F5F8F5;
}
```

Contoh class: `bg-forest`, `text-dark-green`, `border-mint`,
`bg-soft-background`.

Jika proyek menggunakan Tailwind CSS v3, daftarkan warna melalui
`tailwind.config.js` sesuai sintaks v3. Pastikan versi Tailwind sebelum
menyalin konfigurasi.

### Contoh komposisi

``` html
<div class="min-h-screen bg-soft-background text-dark-green">
  <header class="border-b border-mint/60 bg-white">
    <!-- Navbar -->
  </header>

  <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    <section class="rounded-3xl border border-mint/60 bg-white p-5 shadow-sm sm:p-6">
      <!-- Konten -->
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
      <article class="rounded-2xl border border-mint/60 bg-white p-5 shadow-sm">
        <!-- Kartu -->
      </article>
    </section>
  </main>
</div>
```

### Aturan teknis

-   Tailwind dipasang sebagai dependency dan dikompilasi melalui Vite;
    jangan mengandalkan CDN di produksi.
-   Hindari Bootstrap, jQuery, slider besar, atau pustaka UI berat.
-   Gunakan JavaScript ringan untuk menu mobile, dropdown, tab, dan
    modal bila diperlukan.
-   Buat komponen Blade untuk navbar, footer, tombol, card, badge,
    input, pagination, dan elemen berulang.
-   Pastikan class dinamis terdeteksi saat build.
-   Optimalkan gambar, gunakan thumbnail dan lazy loading.
-   Minifikasi aset produksi dan uji build agar tidak ada class Tailwind
    yang hilang.

## 16. Aset dan Konten

-   Gunakan logo resmi sekolah.
-   Gunakan foto sekolah, kepala sekolah, guru, kegiatan, dan siswa yang
    disediakan atau disetujui sekolah.
-   Jangan memakai foto stok seolah-olah dokumentasi sekolah.
-   Kompres gambar dan sediakan ukuran sesuai konteks.
-   Data dummy dipisahkan dari Blade/view dan diberi komentar penanda.
-   Data dummy tidak boleh tampil sebagai data resmi, terutama statistik
    dan prestasi.
-   Jangan mengarang jabatan atau mata pelajaran guru.
-   Publikasi nama, kelas, dan foto siswa mengikuti kebijakan sekolah
    dan ketentuan perlindungan data.

## 17. Keputusan yang Masih Terbuka

Hal berikut masih TBD pada PRD dan tidak boleh dianggap keputusan
final: - Logo, palet resmi, font, dan kebutuhan mode gelap (TBD-15). -
Pilihan JavaScript ringan atau JavaScript biasa (TBD-16). - Lokasi
proses build Tailwind/Vite (TBD-17). - URL CBT, Rapor, PPDB, dan RKM
(TBD-7). - Konten profil dan foto resmi sekolah (TBD-8). - Kebijakan
publikasi nama, kelas, dan foto siswa (TBD-9). - Target performa dan
spesifikasi hosting (TBD-11). - Batas ukuran/format unggahan gambar dan
materi (TBD-24, TBD-32).

Mode gelap tidak termasuk rancangan ini sampai ada keputusan pada
TBD-15.

## 18. Checklist Review Desain

### Visual

-   [ ] Palet hijau konsisten.
-   [ ] Latar Soft Background dan permukaan putih konsisten.
-   [ ] Radius, border, shadow, dan spacing seragam.
-   [ ] Tipografi memiliki hierarki jelas.
-   [ ] Tidak ada dekorasi/animasi berlebihan.

### Komponen dan role

-   [ ] Navbar dan footer publik konsisten.
-   [ ] Sidebar dashboard konsisten dan menu sesuai role.
-   [ ] Tombol, kartu, form, tabel, badge, modal, pagination reusable.
-   [ ] Empty, loading, success, dan error state tersedia.
-   [ ] Wali kelas menggunakan dashboard Guru dengan menu tambahan
    sesuai penetapan.

### Responsif dan aksesibilitas

-   [ ] Desktop, tablet, dan mobile telah diuji.
-   [ ] Tidak ada horizontal overflow yang tidak disengaja.
-   [ ] Semua kontrol dapat digunakan dengan keyboard.
-   [ ] Focus state dan kontras terlihat.
-   [ ] Tabel dan form nyaman di layar kecil.

### Performa dan aset

-   [ ] Foto dioptimalkan, memakai thumbnail, dan lazy loading bila
    sesuai.
-   [ ] Tidak ada library UI/JS berat yang tidak diperlukan.
-   [ ] Tailwind dibuild melalui Vite dan aset produksi diminifikasi.
-   [ ] Tidak ada data dummy yang tampil sebagai data resmi.

------------------------------------------------------------------------

**Catatan:** DESIGN.md adalah panduan visual dan implementasi UI, bukan
pengganti PRD. Jika terjadi perbedaan, kebutuhan fungsional, alur,
prioritas, dan hak akses pada PRD v0.3 menjadi acuan utama.
