# DESIGN.md
# Desain Website Publik SIAKAD SMP Muhammadiyah 7 Medan

**Versi:** 1.0  
**Acuan kebutuhan:** PRD SIAKAD SMP Muhammadiyah 7 Medan v0.2  
**Status:** Draft desain frontend  
**Teknologi:** Laravel Blade, Tailwind CSS, Vite  
**Bahasa antarmuka:** Bahasa Indonesia  
**Pendekatan:** Mobile-first, responsive, ringan, aksesibel

---

## 1. Gambaran Umum

Website publik SIAKAD SMP Muhammadiyah 7 Medan merupakan pusat informasi sekolah yang dapat diakses masyarakat tanpa login. Website dirancang untuk membantu orang tua, calon siswa, alumni, dan masyarakat menemukan informasi resmi sekolah dengan mudah.

Desain mengadaptasi pendekatan website sekolah modern seperti SD Muhammadiyah Sapen Yogyakarta: identitas sekolah yang kuat, navigasi yang jelas, hero yang menonjol, konten berita yang mudah dijelajahi, serta informasi profil yang disusun secara terstruktur.

Tampilan tersebut disesuaikan dengan identitas warna hijau yang telah ditentukan untuk SMP Muhammadiyah 7 Medan.

### Tujuan desain

- Menampilkan identitas dan informasi sekolah secara profesional.
- Memudahkan pengunjung menemukan profil, guru, siswa, kegiatan, berita, dan pengumuman.
- Menyediakan akses langsung ke portal CBT, Rapor, PPDB, dan Login SIAKAD.
- Memastikan website nyaman digunakan di desktop, tablet, dan smartphone.
- Menjaga website tetap ringan, cepat, dan mudah dikembangkan.
- Menyiapkan struktur frontend yang memungkinkan data dummy diganti dengan sumber data resmi tanpa mengubah keseluruhan layout.

### Prinsip desain

1. **School identity first:** nama dan identitas sekolah mudah dikenali.
2. **Clear navigation:** pengunjung dapat memahami struktur menu tanpa kebingungan.
3. **Content first:** berita, pengumuman, dan informasi sekolah menjadi fokus utama.
4. **Consistent components:** kartu, tombol, badge, heading, dan elemen lainnya menggunakan sistem desain yang sama.
5. **Mobile-first:** layout dibuat untuk layar kecil terlebih dahulu.
6. **Lightweight:** menghindari animasi, library, dan aset yang membebani website.
7. **Privacy by design:** direktori publik hanya menampilkan data yang diizinkan PRD.

---

## 2. Identitas Visual

### 2.1 Color palette

Palet utama menggunakan warna hijau yang memberikan kesan pendidikan, kepercayaan, ketenangan, dan kedekatan dengan identitas sekolah.

| Nama token | Hex | Penggunaan |
|---|---|---|
| Forest Green | `#344B39` | Tombol utama, heading tertentu, navigasi aktif |
| Sage Green | `#819A73` | Aksen, ikon, elemen dekoratif |
| Mint Green | `#B8CCC3` | Latar aksen, highlight lembut |
| Teal Green | `#3F5F58` | Tautan, ikon sekunder, elemen pendukung |
| Dark Green | `#0F1D0F` | Teks utama dan heading |
| Olive Green | `#697B65` | Teks sekunder beraksen dan elemen tambahan |
| White | `#FFFFFF` | Latar utama, kartu, area konten |
| Soft Background | `#F7F9F6` | Latar alternatif antarseksi |
| Light Border | `#E5EAE3` | Border kartu, pembatas, garis navigasi |
| Muted Text | `#647067` | Teks pendukung dan metadata |

### 2.2 Aturan penggunaan warna

- Forest Green menjadi warna utama tombol dan elemen interaktif.
- Dark Green digunakan untuk teks utama agar mudah dibaca.
- Sage Green digunakan sebagai aksen, bukan untuk teks kecil di atas latar putih jika kontrasnya tidak mencukupi.
- Mint Green digunakan pada latar lembut, chip, dan section alternatif.
- Teal Green dan Olive Green digunakan secara terbatas untuk elemen pendukung.
- Putih menjadi latar utama agar halaman terasa bersih dan lapang.
- Hindari penggunaan terlalu banyak warna dalam satu section.
- Hindari gradient berwarna yang tidak sesuai dengan identitas hijau sekolah.
- Pastikan kontras teks dan latar memenuhi WCAG AA.

### 2.3 Rekomendasi distribusi warna

- 65% putih dan latar netral.
- 20% Forest Green dan Dark Green.
- 10% Sage Green dan Mint Green.
- 5% Teal Green dan Olive Green.

Persentase tersebut merupakan pedoman visual, bukan batas yang kaku.

---

## 3. Tipografi

Gunakan tipografi sans-serif yang sederhana, modern, dan mudah dibaca.

### Rekomendasi

- **Utama:** system font stack atau font sans-serif ringan yang tersedia secara lokal.
- **Alternatif:** Plus Jakarta Sans atau Inter, apabila font telah tersedia dan dampak performanya sudah diperhitungkan.
- Hindari memuat banyak keluarga font atau terlalu banyak variasi ketebalan.

### Hierarki tipografi

| Elemen | Ukuran desktop | Ukuran mobile | Ketebalan |
|---|---:|---:|---|
| Hero heading | 44–56 px | 32–38 px | Bold/Extra Bold |
| Page heading | 36–44 px | 28–34 px | Bold |
| Section heading | 28–32 px | 23–28 px | Bold |
| Card heading | 18–22 px | 17–20 px | Semibold/Bold |
| Body | 16–18 px | 15–16 px | Regular |
| Caption | 13–14 px | 12–13 px | Regular |
| Navigation | 14–16 px | 14–16 px | Medium/Semibold |

Ukuran bersifat fleksibel dan dapat menggunakan responsive utility Tailwind.

### Aturan tipografi

- Gunakan heading yang jelas dan tidak terlalu panjang.
- Batasi lebar paragraf agar nyaman dibaca.
- Hindari teks kecil untuk informasi penting.
- Gunakan line-height yang cukup, terutama untuk paragraf panjang.
- Hindari penggunaan huruf kapital penuh pada seluruh judul.

---

## 4. Layout dan Grid

### 4.1 Container

- Lebar konten maksimum desktop: sekitar 1200–1280 px.
- Padding horizontal desktop: 32–40 px.
- Padding horizontal tablet: 24 px.
- Padding horizontal mobile: 16–20 px.
- Jarak antarseksi: 64–88 px di desktop dan 40–56 px di mobile.

### 4.2 Grid

- Desktop: grid 12 kolom untuk layout kompleks.
- Tablet: 6 kolom atau grid 2 kolom.
- Mobile: satu kolom sebagai default.
- Kartu berita dan galeri menggunakan grid responsif.
- Hindari fixed width yang menyebabkan horizontal scrolling.

### 4.3 Bentuk dan radius

- Tombol: radius 10–12 px.
- Kartu: radius 16–20 px.
- Gambar hero: radius 20–28 px sesuai layout.
- Badge dan chip: bentuk pill.
- Border: tipis dan lembut.
- Shadow: ringan, hanya untuk memberi kedalaman seperlunya.

Desain tidak menggunakan terlalu banyak efek kaca, shadow tebal, atau ornamen yang mengganggu informasi.

---

## 5. Navigasi Utama

Navbar digunakan secara konsisten di seluruh halaman publik.

### 5.1 Struktur navbar desktop

**Bagian kiri**
- Logo atau identitas sekolah.
- Nama SMP Muhammadiyah 7 Medan.

**Bagian tengah dan kanan**
- Beranda.
- Profil Sekolah.
- Direktori.
- Kesiswaan.
- Galeri.
- Berita.
- Pengumuman.
- Tombol Login SIAKAD.

Navbar dapat menggunakan submenu pada Profil Sekolah, Direktori, dan Kesiswaan.

### 5.2 Perilaku navbar

- Latar putih.
- Border bawah tipis.
- Tinggi sekitar 76–88 px pada desktop.
- Logo dan nama sekolah selalu mudah dikenali.
- Menu aktif ditandai dengan warna Forest Green dan indikator visual yang jelas.
- Tombol Login SIAKAD menggunakan gaya tombol utama.
- Navbar boleh menjadi sticky jika implementasinya tetap ringan dan tidak mengurangi ruang konten.

### 5.3 Navbar mobile

- Logo dan nama sekolah di sebelah kiri.
- Tombol hamburger di sebelah kanan.
- Menu ditampilkan sebagai panel dropdown atau drawer.
- Tombol Login SIAKAD tetap mudah ditemukan.
- Panel dapat ditutup dengan tombol tutup atau memilih tautan.
- Navigasi mendukung keyboard dan pembaca layar.

### 5.4 Struktur submenu

**Profil Sekolah**
- Tentang Sekolah
- Kepala Sekolah
- Struktur Organisasi
- Sarana dan Prasarana
- Kontak Sekolah, jika ditampilkan sebagai bagian profil

**Direktori**
- Direktori Guru
- Direktori Siswa

**Kesiswaan**
- Organisasi Siswa
- Ekstrakurikuler
- Prestasi Siswa

---

## 6. Halaman Beranda

**Route:** `/`

Beranda merupakan halaman utama yang memperkenalkan sekolah dan mengarahkan pengunjung ke informasi penting.

### 6.1 Hero section

**Tujuan:** memberikan kesan pertama yang kuat dan menjelaskan identitas sekolah.

**Layout desktop**
- Dua kolom.
- Kolom kiri berisi label identitas, heading, deskripsi singkat, dan tombol aksi.
- Kolom kanan berisi foto sekolah asli.
- Gambar menggunakan sudut membulat dan rasio yang konsisten.

**Layout mobile**
- Teks ditampilkan lebih dahulu.
- Gambar sekolah berada di bawah teks.
- Tombol disusun vertikal atau membungkus sesuai lebar layar.

**Konten**
- Nama SMP Muhammadiyah 7 Medan.
- Slogan atau deskripsi singkat yang telah disetujui sekolah.
- Tombol “Tentang Sekolah”.
- Tombol “Lihat Berita”.

**Visual**
- Latar putih atau Soft Background.
- Heading Dark Green.
- Tombol utama Forest Green.
- Aksen Sage Green atau Mint Green.
- Foto sekolah menggunakan gambar resmi yang diberikan sekolah.

Hindari menggunakan data, slogan, atau klaim prestasi yang belum diverifikasi sebagai fakta resmi.

### 6.2 Pintasan portal

Tampilkan tiga kartu atau ikon akses:

1. CBT
2. Rapor
3. PPDB

**Desain**
- Satu baris pada desktop.
- Tiga kartu ringkas atau layout yang tetap nyaman di mobile.
- Setiap kartu memiliki ikon, nama portal, dan indikator tautan.
- Gunakan latar putih dengan border lembut dan hover sederhana.

**Perilaku**
- Tautan tujuan menggunakan URL yang dikonfigurasi.
- URL yang belum tersedia harus ditandai sebagai placeholder dalam kode.
- Jangan mengarahkan pengunjung ke alamat yang tidak berkaitan.
- Tautan dibuka pada tab yang sama sesuai PRD.

### 6.3 Sambutan kepala sekolah

Tampilkan foto kepala sekolah, nama, jabatan, dan ringkasan sambutan.

**Konten awal**
- Nama: M Reza Akbar, S.Pd.
- Jabatan: Kepala Sekolah.
- Foto: menggunakan foto kepala sekolah yang diberikan.
- Ringkasan sambutan: menggunakan teks yang telah disetujui sekolah.

**Layout**
- Foto pada satu sisi.
- Sambutan pada sisi lainnya.
- Tombol “Baca Sambutan” mengarah ke halaman kepala sekolah.
- Pada mobile, foto dan teks disusun vertikal.

Data kepala sekolah harus menggunakan satu sumber yang sama untuk beranda dan halaman profil kepala sekolah.

### 6.4 Berita terbaru

Menampilkan beberapa berita terbaru.

Setiap kartu berita memuat:
- Foto utama.
- Judul.
- Ringkasan.
- Tanggal.
- Tautan “Baca Selengkapnya”.

**Desain**
- Grid tiga kolom pada desktop.
- Dua kolom pada tablet jika ruang mencukupi.
- Satu kolom atau daftar horizontal pada mobile.
- Gambar menggunakan rasio konsisten.
- Judul maksimal beberapa baris agar tinggi kartu relatif seragam.

Berita “SMP Muhammadiyah 7 Medan Juara lagi” dapat digunakan sebagai konten awal dengan foto prestasi yang diberikan sekolah. Isi dan tanggal publikasi harus mengikuti sumber yang tersedia.

### 6.5 Pengumuman terbaru

Tampilkan pengumuman dalam bentuk daftar atau kartu ringkas.

Setiap item memuat:
- Judul.
- Tanggal.
- Ringkasan.
- Tautan detail.

Gunakan tampilan yang berbeda secara halus dari berita, misalnya ikon megafon dan label “Pengumuman”, tanpa membuat sistem warna baru.

### 6.6 Statistik sekolah

PRD menetapkan statistik dummy pada beranda. Angka resmi harus diverifikasi sebelum dipublikasikan.

Data yang dapat dipersiapkan:
- Jumlah siswa.
- Jumlah guru.
- Jumlah kelas.

Angka yang pernah diberikan untuk kebutuhan desain:
- Siswa aktif: 471.
- Guru: 34.
- Staf: 4.
- Ekstrakurikuler: 16.

Angka tersebut jangan otomatis dianggap sebagai statistik final PRD. Khususnya, statistik jumlah kelas perlu menunggu data yang terverifikasi. Tandai data dummy di kode dan jangan menyamarkan data contoh sebagai informasi resmi.

---

## 7. Halaman Profil Sekolah

**Route utama:** `/profil`

Halaman profil berisi informasi dasar sekolah, kepala sekolah, struktur organisasi, serta sarana dan prasarana sesuai PRD.

### 7.1 Hero profil

- Heading “Profil Sekolah”.
- Deskripsi singkat.
- Foto sekolah sebagai elemen visual pendukung.
- Breadcrumb: Beranda / Profil Sekolah.

### 7.2 Tentang sekolah

Konten:
- Nama sekolah.
- Deskripsi sekolah.
- Alamat.
- Visi.
- Misi.
- Sejarah, apabila kontennya telah disetujui.

Informasi kontak resmi boleh ditampilkan di bagian ini dan harus konsisten dengan footer.

Informasi identitas yang telah diberikan:
- Nama: SMP Muhammadiyah 7 Medan.
- NPSN: 10257325.
- Alamat: Jl. Pelita II No. 3-5, Sidorame Barat I, Kec. Medan Perjuangan, Kota Medan, Sumatera Utara 20236.
- Email: smpm7medan@gmail.com.
- Telepon: (061) 6621557.

Gunakan layout dua kolom pada desktop bila sesuai. Hindari menumpuk terlalu banyak kartu kecil untuk informasi yang saling berkaitan.

### 7.3 Kepala sekolah

Konten:
- Foto kepala sekolah.
- Nama dan jabatan.
- Kata sambutan lengkap.

**Desain**
- Foto potret dengan ukuran yang proporsional.
- Latar putih.
- Heading Dark Green.
- Paragraf dengan lebar yang nyaman dibaca.
- Hindari menampilkan teks sambutan sebagai satu blok panjang tanpa jarak antarparagraf.

### 7.4 Struktur organisasi

Menampilkan jabatan dan nama pejabat dalam bentuk daftar atau bagan sederhana.

**Desktop**
- Bagan bertingkat jika data hubungan jabatan tersedia.
- Kartu dengan nama dan jabatan.
- Foto dan deskripsi tugas hanya jika tersedia.

**Mobile**
- Susunan vertikal.
- Garis penghubung sederhana jika diperlukan.
- Jangan mengecilkan seluruh bagan sampai teks tidak terbaca.

Nama jabatan dan pejabat yang belum diverifikasi menggunakan data contoh yang diberi penanda.

### 7.5 Sarana dan prasarana

Menampilkan daftar fasilitas sekolah.

Setiap item dapat memuat:
- Foto fasilitas.
- Nama fasilitas.
- Deskripsi singkat.

Gunakan grid kartu yang konsisten dengan kartu berita dan galeri. Konten pada MVP menggunakan data dummy yang diberi penanda.

### 7.6 Kontak sekolah

Bagian kontak menjadi bagian dari halaman Profil Sekolah.

Konten:
- Alamat lengkap.
- Email sekolah.
- Nomor telepon sekolah.
- Tombol untuk mengirim email.
- Tombol untuk menghubungi nomor sekolah jika didukung perangkat.
- Tautan peta hanya jika alamat tujuan telah diverifikasi.

**Desain**
- Panel informasi kontak dengan ikon.
- Alamat dapat membungkus menjadi beberapa baris.
- Tautan email dan telepon mudah ditekan pada mobile.
- Hindari menampilkan informasi kontak pribadi guru atau siswa.

---

## 8. Halaman Direktori

Menu Direktori memiliki dua halaman: Guru dan Siswa.

### 8.1 Direktori guru

**Route:** `/guru`

**Sumber data:** tabel dan relasi terkait pada database RKM.

Konten:
- Nama guru.
- Mata pelajaran yang diampu jika tersedia.
- Jabatan hanya jika tersedia pada sumber data.

**Komponen halaman**
1. Page heading dan deskripsi.
2. Kolom pencarian nama.
3. Filter mata pelajaran jika relasi penugasan mendukung.
4. Filter jabatan jika kolom jabatan tersedia.
5. Daftar guru.
6. Pagination.
7. Empty state ketika tidak ada hasil.

**Desain**
- Daftar kartu sederhana atau tabel responsif.
- Foto guru tidak ditampilkan pada MVP sesuai PRD.
- Jangan membuat jabatan atau mata pelajaran berdasarkan asumsi.
- Urutkan berdasarkan nama.

### 8.2 Direktori siswa

**Route:** `/siswa`

**Sumber data:** tabel siswa dan relasi kelas aktif di RKM.

Konten:
- Nama siswa.
- Kelas aktif.

**Komponen halaman**
1. Page heading.
2. Kolom pencarian nama.
3. Filter kelas.
4. Daftar siswa.
5. Pagination.
6. Pesan jika tidak ada hasil.

**Desain**
- Utamakan daftar yang rapi dan mudah dipindai.
- Tidak perlu menampilkan foto siswa.
- Filter kelas ditempatkan di atas daftar.
- Pada mobile, filter dapat diletakkan di panel sederhana.

**Privasi wajib**
Jangan menampilkan NIS, NISN, NIK, tanggal lahir, alamat rumah, nomor telepon pribadi, data orang tua, nilai, absensi, maupun informasi sensitif lainnya.

### 8.3 Aturan integrasi RKM

- Laravel membaca database yang sama dengan RKM.
- Gunakan hanya kolom yang diperlukan.
- Verifikasi nama tabel, kolom, relasi, dan tahun ajaran aktif sebelum query dibuat.
- Jangan mengubah skema RKM.
- Jangan melakukan penulisan data pada tabel inti RKM.
- Gunakan pagination agar query tidak mengambil semua data sekaligus.
- Jangan menampilkan guru nonaktif sebagai guru aktif jika statusnya tersedia dan sudah diverifikasi.
- Jika data jabatan atau mata pelajaran tidak tersedia, hilangkan informasi tersebut dari tampilan.

---

## 9. Halaman Kesiswaan

**Route utama:** `/kesiswaan`

Subhalaman:
- `/kesiswaan/organisasi`
- `/kesiswaan/ekstrakurikuler`
- `/kesiswaan/prestasi`

### 9.1 Organisasi siswa

Menampilkan organisasi siswa yang ada di sekolah.

Setiap kartu berisi:
- Nama organisasi.
- Logo atau gambar.
- Deskripsi singkat.
- Kegiatan utama.

Gunakan grid dua atau tiga kolom pada desktop dan satu kolom pada mobile.

Data organisasi menggunakan data dummy pada MVP sampai sekolah menyediakan daftar resmi.

### 9.2 Ekstrakurikuler

Menampilkan kegiatan ekstrakurikuler yang ditawarkan sekolah.

Setiap kartu berisi:
- Nama ekstrakurikuler.
- Gambar.
- Deskripsi.

Jadwal hanya ditampilkan jika telah dikonfirmasi sekolah.

Angka 16 ekstrakurikuler dapat dipakai sebagai informasi sementara untuk rancangan desain, tetapi daftar dan jumlah final harus diverifikasi sebelum dianggap resmi.

### 9.3 Prestasi siswa

Menampilkan dokumentasi prestasi sekolah atau siswa.

Setiap kartu berisi:
- Nama prestasi.
- Jenis prestasi.
- Tingkat kompetisi.
- Tahun.
- Deskripsi.
- Foto dokumentasi jika tersedia dan diizinkan.

Gunakan foto prestasi yang diberikan sekolah untuk berita yang sesuai. Jangan membuat prestasi, nama siswa, tahun, atau tingkat kompetisi yang tidak didukung sumber.

Jika data masih dummy, beri penanda yang jelas dan jangan menampilkannya seolah-olah pencapaian resmi.

---

## 10. Halaman Galeri

**Route:** `/galeri`

Galeri berfungsi menampilkan dokumentasi kegiatan sekolah.

### 10.1 Layout

- Heading “Galeri Sekolah”.
- Deskripsi singkat.
- Grid gambar.
- Judul dan keterangan gambar.
- Kategori atau tanggal jika tersedia.

### 10.2 Perilaku visual

- Desktop: tiga atau empat kolom sesuai lebar konten.
- Tablet: dua atau tiga kolom.
- Mobile: dua kolom atau satu kolom jika detail gambar membutuhkan ruang lebih besar.
- Rasio gambar konsisten.
- `object-fit: cover`.
- Thumbnail digunakan untuk mengurangi beban unduhan.
- Gambar memakai lazy loading, kecuali gambar yang tampil di layar pertama.
- Setiap gambar mempunyai teks alternatif yang sesuai.

Galeri pada MVP dapat menggunakan data dummy atau dokumentasi sekolah yang telah disetujui.

---

## 11. Halaman Berita

### 11.1 Daftar berita

**Route:** `/berita`

Setiap kartu berita menampilkan:
- Gambar utama.
- Judul.
- Ringkasan.
- Tanggal.
- Tautan detail.

Gunakan grid kartu yang sama dengan bagian Berita Terbaru di beranda.

### 11.2 Detail berita

**Route:** `/berita/{slug}`

Urutan konten:
1. Breadcrumb.
2. Kategori berita jika tersedia.
3. Judul berita.
4. Tanggal publikasi.
5. Gambar utama.
6. Isi berita.
7. Tautan kembali ke daftar berita.

Isi berita harus dapat ditampilkan dalam paragraf yang nyaman dibaca, termasuk tautan eksternal yang relevan.

### 11.3 Data berita dummy

Struktur data:
- `title`
- `slug`
- `summary`
- `content`
- `image`
- `date`

Data disimpan terpisah dari markup Blade agar nantinya mudah dialihkan ke database.

Berita “SMP Muhammadiyah 7 Medan Juara lagi” dapat dijadikan data awal berdasarkan materi yang telah diberikan. Tautan artikel asli dapat digunakan sebagai referensi, tetapi isi yang dipublikasikan perlu mengikuti materi yang sudah diverifikasi.

---

## 12. Halaman Pengumuman

### 12.1 Daftar pengumuman

**Route:** `/pengumuman`

Setiap item menampilkan:
- Judul.
- Tanggal.
- Ringkasan.
- Tautan detail.

Gunakan tampilan daftar yang lebih informatif daripada dekoratif.

### 12.2 Detail pengumuman

**Route:** `/pengumuman/{slug}`

Konten:
- Breadcrumb.
- Judul.
- Tanggal.
- Isi pengumuman.
- Tombol kembali ke daftar.

Pada MVP, pengumuman terpisah dari berita. Jangan menambahkan logika tanggal mulai atau tanggal berakhir karena belum termasuk kebutuhan tahap pertama.

---

## 13. Login SIAKAD dan Portal Sekolah

### 13.1 Login SIAKAD

**Route:** `/login`

Tombol Login SIAKAD menjadi bagian navbar dan footer.

Pada tahap pertama, halaman ini berfungsi sebagai pintasan menuju halaman login SIAKAD. Proses autentikasi dan dashboard akademik berada di luar cakupan PRD website publik ini.

Gunakan URL konfigurasi atau placeholder yang jelas sampai URL resmi tersedia.

### 13.2 Portal CBT, Rapor, dan PPDB

Tampilkan pintasan pada beranda untuk:
- CBT.
- Rapor.
- PPDB.

URL masing-masing portal disimpan terpisah dari markup agar mudah diperbarui.

Jangan mengarang URL portal resmi.

---

## 14. Footer

Footer digunakan bersama di seluruh halaman publik.

### Konten

**Kolom 1 — Identitas sekolah**
- Nama SMP Muhammadiyah 7 Medan.
- Deskripsi singkat.

**Kolom 2 — Navigasi**
- Beranda.
- Profil Sekolah.
- Direktori.
- Kesiswaan.
- Galeri.

**Kolom 3 — Informasi**
- Berita.
- Pengumuman.
- Login SIAKAD.
- Pintasan portal yang tersedia.

**Kolom 4 — Kontak**
- Alamat sekolah.
- Email.
- Telepon.

**Bagian bawah**
- Informasi hak cipta.
- Tahun dinamis.

### Desain

- Latar Forest Green atau Dark Green.
- Teks putih.
- Teks pendukung dengan kontras yang cukup.
- Tautan memiliki state hover dan focus.
- Desktop menggunakan tiga atau empat kolom.
- Mobile menggunakan susunan vertikal.
- Informasi kontak tidak boleh terpotong pada layar kecil.

---

## 15. Design System Components

Komponen dibuat reusable agar seluruh halaman memiliki gaya yang konsisten.

### 15.1 Komponen layout

- `PublicLayout`
- `Navbar`
- `Footer`
- `PageContainer`
- `Breadcrumb`
- `SectionHeading`

### 15.2 Komponen konten

- `Button`
- `NewsCard`
- `AnnouncementCard`
- `DirectoryCard`
- `GalleryCard`
- `StatCard`
- `PortalCard`
- `ContactItem`
- `Badge`
- `SearchInput`
- `FilterSelect`
- `Pagination`
- `EmptyState`
- `ImagePlaceholder`

### 15.3 Aturan komponen

- Setiap komponen menggunakan utility Tailwind CSS.
- Hindari menyalin markup yang sama ke banyak halaman.
- Gunakan props Blade untuk konten yang berbeda.
- Gunakan slot untuk konten yang fleksibel.
- Data dummy dipisahkan dari struktur tampilan.
- Setiap tombol atau tautan harus memiliki tujuan yang jelas.
- Hindari tautan `#` untuk fitur yang seolah-olah sudah berfungsi, kecuali sebagai placeholder yang diberi penanda selama pengembangan.

---

## 16. Struktur Halaman dan Route

| Halaman | Route | Status |
|---|---|---|
| Beranda | `/` | MVP |
| Profil sekolah | `/profil` | MVP |
| Kepala sekolah | `/profil/kepala-sekolah` | MVP |
| Struktur organisasi | `/profil/struktur` | MVP |
| Sarana dan prasarana | `/profil/fasilitas` | MVP |
| Direktori guru | `/guru` | MVP |
| Direktori siswa | `/siswa` | MVP |
| Kesiswaan | `/kesiswaan` | MVP |
| Organisasi siswa | `/kesiswaan/organisasi` | MVP |
| Ekstrakurikuler | `/kesiswaan/ekstrakurikuler` | MVP |
| Prestasi siswa | `/kesiswaan/prestasi` | MVP |
| Galeri | `/galeri` | MVP |
| Daftar berita | `/berita` | MVP |
| Detail berita | `/berita/{slug}` | MVP |
| Daftar pengumuman | `/pengumuman` | MVP |
| Detail pengumuman | `/pengumuman/{slug}` | MVP |
| Login SIAKAD | `/login` | Pintasan/placeholder sampai URL dikonfirmasi |

Struktur route final mengikuti implementasi Laravel dan dapat disesuaikan tanpa mengubah prinsip desain.

---

## 17. Struktur File Laravel

Struktur yang direkomendasikan:

```text
resources/
├── css/
│   └── app.css
├── js/
│   └── app.js
└── views/
    ├── layouts/
    │   └── public.blade.php
    ├── components/
    │   ├── navbar.blade.php
    │   ├── footer.blade.php
    │   ├── section-heading.blade.php
    │   ├── news-card.blade.php
    │   ├── announcement-card.blade.php
    │   ├── directory-card.blade.php
    │   ├── gallery-card.blade.php
    │   └── pagination.blade.php
    └── pages/
        ├── home.blade.php
        ├── profile/
        │   ├── index.blade.php
        │   ├── principal.blade.php
        │   ├── structure.blade.php
        │   └── facilities.blade.php
        ├── directory/
        │   ├── teachers.blade.php
        │   └── students.blade.php
        ├── student-life/
        │   ├── organizations.blade.php
        │   ├── extracurriculars.blade.php
        │   └── achievements.blade.php
        ├── gallery/
        │   └── index.blade.php
        ├── news/
        │   ├── index.blade.php
        │   └── show.blade.php
        └── announcements/
            ├── index.blade.php
            └── show.blade.php

app/
├── Http/
│   └── Controllers/
├── Data/
│   └── PublicSite/
│       ├── profile.php
│       ├── news.php
│       ├── announcements.php
│       ├── organizations.php
│       ├── extracurriculars.php
│       ├── achievements.php
│       ├── gallery.php
│       └── portals.php
└── ...

public/
└── images/
    ├── school/
    ├── principal/
    ├── news/
    ├── facilities/
    ├── student-life/
    └── gallery/
```

Folder data di atas merupakan rekomendasi pemisahan data statis/dummy. Lokasi final dapat disesuaikan dengan struktur proyek yang telah ada.

---

## 18. Aturan Tailwind CSS

### 18.1 Prinsip implementasi

- Gunakan Laravel Blade untuk rendering halaman.
- Gunakan Tailwind CSS untuk styling.
- Gunakan Vite untuk mengompilasi aset.
- Jangan memuat Tailwind dari CDN pada produksi.
- Jangan menambahkan Bootstrap atau framework UI berat.
- Gunakan JavaScript seperlunya untuk navigasi mobile atau interaksi sederhana.
- Jangan menggunakan slider atau galeri berat jika layout grid biasa sudah memadai.

### 18.2 Token warna

Untuk Tailwind CSS v4, token dapat ditetapkan pada `resources/css/app.css`:

```css
@import "tailwindcss";

@theme {
  --color-forest: #344B39;
  --color-sage: #819A73;
  --color-mint: #B8CCC3;
  --color-teal: #3F5F58;
  --color-darkgreen: #0F1D0F;
  --color-olive: #697B65;
  --color-soft: #F7F9F6;
  --color-border-soft: #E5EAE3;
}
```

Jika proyek menggunakan Tailwind CSS v3, definisikan warna tersebut di `tailwind.config.js` sesuai konfigurasi v3. Jangan mencampur konfigurasi v3 dan v4.

### 18.3 Pola class

Contoh tombol utama:

```html
<a
  href="/profil"
  class="inline-flex min-h-11 items-center justify-center
         rounded-xl bg-forest px-5 py-3 text-sm font-semibold
         text-white transition-colors hover:bg-teal
         focus-visible:outline-2 focus-visible:outline-offset-2
         focus-visible:outline-forest"
>
  Tentang Sekolah
</a>
```

Contoh kartu:

```html
<article
  class="overflow-hidden rounded-2xl border border-border-soft
         bg-white transition-shadow hover:shadow-md"
>
  <!-- Konten kartu -->
</article>
```

Pastikan class yang digunakan dapat ditemukan oleh proses build Tailwind. Hindari membentuk nama class secara dinamis dengan cara yang tidak dapat dideteksi oleh scanner Tailwind.

---

## 19. Gambar dan Aset Visual

### Foto utama yang disarankan

- Foto sekolah untuk hero beranda.
- Foto kepala sekolah untuk sambutan.
- Foto prestasi untuk berita yang sesuai.
- Dokumentasi kegiatan sekolah untuk galeri.
- Foto fasilitas untuk halaman sarana dan prasarana.

### Aturan gambar

- Utamakan foto asli yang diberikan atau disetujui sekolah.
- Jangan mengganti foto sekolah dengan gambar stok yang tidak mewakili sekolah.
- Gunakan format WebP atau AVIF jika memungkinkan.
- Tetapkan dimensi gambar agar layout tidak bergeser saat gambar dimuat.
- Gunakan `loading="lazy"` untuk gambar di bawah layar pertama.
- Sediakan placeholder apabila gambar tidak tersedia.
- Setiap gambar memiliki `alt` yang deskriptif.
- Pastikan izin publikasi foto siswa dan dokumentasi kegiatan telah diperoleh jika diperlukan.

---

## 20. Responsivitas

### Breakpoint

| Ukuran | Pendekatan |
|---|---|
| Mobile: di bawah 640 px | Satu kolom, navigasi hamburger, tombol mudah disentuh |
| Tablet: 640–1023 px | Grid dua kolom dan navigasi yang disederhanakan |
| Desktop: 1024 px ke atas | Grid lebih luas, submenu, layout dua kolom |
| Wide desktop: 1280 px ke atas | Konten dibatasi container agar tetap nyaman dibaca |

### Aturan responsif

- Tidak ada horizontal scrolling yang tidak diperlukan.
- Target sentuh interaktif minimal 44 × 44 px.
- Gambar tidak boleh memotong subjek penting.
- Menu tidak boleh menutupi konten secara permanen.
- Teks dan tabel direktori harus tetap terbaca pada layar kecil.
- Hover tidak boleh menjadi satu-satunya cara menggunakan fitur.

---

## 21. Aksesibilitas

- Gunakan elemen HTML semantik seperti `header`, `nav`, `main`, `section`, dan `footer`.
- Setiap halaman mempunyai satu heading utama yang jelas.
- Gunakan label untuk kolom pencarian dan filter.
- Tombol memiliki nama yang dapat dibaca pembaca layar.
- Semua fungsi navigasi dapat digunakan dengan keyboard.
- Fokus keyboard harus terlihat.
- Gunakan teks alternatif pada gambar informatif.
- Jangan menggunakan warna sebagai satu-satunya indikator status.
- Pastikan kontras teks memenuhi WCAG AA.
- Hormati preferensi `prefers-reduced-motion`.
- Tampilkan pesan yang jelas ketika pencarian tidak menemukan hasil.

---

## 22. Performa

Targetnya adalah website yang ringan dan nyaman digunakan pada koneksi lambat.

- Gunakan rendering server-side Laravel Blade.
- Hindari library JavaScript besar.
- Hindari animasi scroll yang berat.
- Optimalkan gambar dan gunakan lazy loading.
- Gunakan pagination pada direktori guru dan siswa.
- Query hanya kolom yang diperlukan.
- Gunakan cache secara hati-hati jika sesuai.
- Jangan melakukan query berulang yang tidak diperlukan.
- Batasi variasi font.
- Gunakan Vite untuk mengompilasi aset.
- Hindari mengirim seluruh dataset ke browser untuk kemudian difilter di sisi klien.

---

## 23. Keamanan dan Privasi

- Website publik dapat diakses tanpa login untuk informasi yang memang bersifat publik.
- Direktori guru dan siswa tidak boleh membuka data pribadi.
- Database RKM diperlakukan sebagai sumber data baca-saja pada tahap pertama.
- Jangan mengubah skema atau menulis data ke tabel inti RKM.
- Gunakan konfigurasi lingkungan untuk kredensial database dan URL portal.
- Jangan menampilkan kredensial atau informasi internal di halaman publik.
- Jangan menganggap konten dummy sebagai konten resmi.
- Foto dan informasi siswa hanya dipublikasikan jika sesuai kebijakan sekolah.

---

## 24. Batasan MVP dan Fase Lanjutan

### Termasuk MVP

- Navbar dan footer bersama.
- Beranda.
- Pintasan CBT, Rapor, dan PPDB.
- Sambutan kepala sekolah.
- Berita dan pengumuman dummy.
- Profil sekolah.
- Direktori guru dan siswa yang membaca database RKM.
- Pencarian dan filter direktori.
- Kesiswaan: organisasi, ekstrakurikuler, prestasi.
- Galeri.
- Halaman daftar dan detail berita.
- Halaman daftar dan detail pengumuman.
- Login SIAKAD sebagai pintasan.
- UI responsif berbasis Tailwind CSS.
- Komponen Blade reusable.

### Belum termasuk MVP

- Dashboard admin CMS.
- CRUD berita dan pengumuman.
- Upload dan pengelolaan gambar melalui dashboard.
- CRUD fasilitas, organisasi, ekstrakurikuler, prestasi, dan galeri.
- Penyimpanan konten publik dalam database.
- Pengaturan status draf, publikasi, dan arsip.
- Sinkronisasi lanjutan dengan RKM.
- Autentikasi dan pengelolaan hak akses SIAKAD.
- Presensi, nilai, rapor, dan modul akademik internal.

Fitur tersebut dapat direncanakan pada fase berikutnya tanpa mengubah fondasi desain frontend.

---

## 25. Checklist Sebelum Implementasi

### Desain
- [ ] Warna sesuai palette yang ditetapkan.
- [ ] Tampilan konsisten dengan identitas sekolah.
- [ ] Layout desktop, tablet, dan mobile sudah dirancang.
- [ ] Komponen UI reusable sudah ditentukan.
- [ ] Foto asli dan placeholder sudah dibedakan.
- [ ] Navigasi semua halaman sudah jelas.

### Data
- [ ] URL CBT, Rapor, PPDB, dan Login SIAKAD dikonfirmasi.
- [ ] Nama kolom dan relasi database RKM diverifikasi.
- [ ] Aturan kelas dan tahun ajaran aktif diverifikasi.
- [ ] Data dummy diberi komentar dan penanda.
- [ ] Kontak dan informasi profil sekolah diverifikasi.
- [ ] Konten foto dan prestasi mendapat persetujuan publikasi.

### Teknis
- [ ] Laravel Blade digunakan untuk rendering.
- [ ] Tailwind CSS dikompilasi melalui Vite.
- [ ] Tidak ada ketergantungan pada Tailwind CDN di produksi.
- [ ] Direktori menggunakan query efisien dan pagination.
- [ ] Tidak ada data sensitif dalam output publik.
- [ ] Tidak ada tautan yang tampak berfungsi tetapi tidak memiliki tujuan.
- [ ] Tampilan diuji di mobile dan desktop.
- [ ] Aksesibilitas keyboard dan kontras diperiksa.

---

## 26. Referensi dan Arah Visual

Desain mengambil inspirasi dari website sekolah modern, khususnya pendekatan presentasi informasi pada SD Muhammadiyah Sapen Yogyakarta, tanpa menyalin identitas visualnya secara langsung.

Arah desain yang dipakai:
- Identitas sekolah kuat di bagian hero dan navbar.
- Informasi disusun berdasarkan kebutuhan pengunjung.
- Foto sekolah dan kegiatan menjadi elemen visual utama.
- Berita dan pengumuman mudah ditemukan.
- Profil, direktori, kesiswaan, dan galeri memiliki struktur yang konsisten.
- Warna hijau menjadi identitas khas SMP Muhammadiyah 7 Medan.
- Antarmuka tetap bersih, ringan, responsif, dan mudah dikembangkan.

**Prinsip akhir:** desain harus mengikuti kebutuhan PRD, bukan menambahkan fitur hanya demi visual. Setiap komponen visual perlu mendukung informasi atau alur pengguna yang jelas.
