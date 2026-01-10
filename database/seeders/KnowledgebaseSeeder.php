<?php

namespace Database\Seeders;

use App\Models\KbCategory;
use App\Models\KbArticle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KnowledgeBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $categories = [
            [
                'name' => 'Panduan Bermula',
                'slug' => 'panduan-bermula',
                'description' => 'Panduan lengkap untuk pengguna baru memulakan penggunaan sistem helpdesk. Termasuk cara pendaftaran, log masuk, dan navigasi asas.',
                'icon' => 'rocket_launch',
                'color' => '#3b82f6',
                'sort_order' => 1,
                'status' => 'active',
            ],
            [
                'name' => 'Pengurusan Tiket',
                'slug' => 'pengurusan-tiket',
                'description' => 'Segala panduan berkaitan dengan cara membuat, mengemaskini, dan menguruskan tiket sokongan teknikal anda.',
                'icon' => 'confirmation_number',
                'color' => '#10b981',
                'sort_order' => 2,
                'status' => 'active',
            ],
            [
                'name' => 'Akaun & Keselamatan',
                'slug' => 'akaun-keselamatan',
                'description' => 'Maklumat tentang pengurusan akaun pengguna, tetapan keselamatan, dan perlindungan data peribadi.',
                'icon' => 'security',
                'color' => '#8b5cf6',
                'sort_order' => 3,
                'status' => 'active',
            ],
            [
                'name' => 'Soalan Lazim (FAQ)',
                'slug' => 'soalan-lazim',
                'description' => 'Jawapan kepada soalan-soalan yang kerap ditanya oleh pengguna sistem helpdesk.',
                'icon' => 'help',
                'color' => '#f59e0b',
                'sort_order' => 4,
                'status' => 'active',
            ],
            [
                'name' => 'Penyelesaian Masalah',
                'slug' => 'penyelesaian-masalah',
                'description' => 'Panduan untuk menyelesaikan masalah teknikal yang biasa dihadapi oleh pengguna.',
                'icon' => 'build',
                'color' => '#ef4444',
                'sort_order' => 5,
                'status' => 'active',
            ],
            [
                'name' => 'Polisi & Prosedur',
                'slug' => 'polisi-prosedur',
                'description' => 'Maklumat tentang polisi syarikat, prosedur operasi standard, dan garis panduan penggunaan sistem.',
                'icon' => 'policy',
                'color' => '#06b6d4',
                'sort_order' => 6,
                'status' => 'active',
            ],
        ];

        foreach ($categories as $categoryData) {
            KbCategory::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Get category IDs
        $panduanBermula = KbCategory::where('slug', 'panduan-bermula')->first();
        $pengurusanTiket = KbCategory::where('slug', 'pengurusan-tiket')->first();
        $akaunKeselamatan = KbCategory::where('slug', 'akaun-keselamatan')->first();
        $soalanLazim = KbCategory::where('slug', 'soalan-lazim')->first();
        $penyelesaianMasalah = KbCategory::where('slug', 'penyelesaian-masalah')->first();
        $polisiProsedur = KbCategory::where('slug', 'polisi-prosedur')->first();

        // Create Articles
        $articles = [
            // Panduan Bermula
            [
                'category_id' => $panduanBermula->id,
                'title' => 'Selamat Datang ke Sistem Helpdesk Orien',
                'slug' => 'selamat-datang-sistem-helpdesk-orien',
                'excerpt' => 'Panduan komprehensif untuk memulakan penggunaan sistem helpdesk Orien. Ketahui cara mendaftar, log masuk, dan memahami antara muka pengguna.',
                'content' => $this->getWelcomeArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(150, 500),
            ],
            [
                'category_id' => $panduanBermula->id,
                'title' => 'Cara Mendaftar Akaun Baru',
                'slug' => 'cara-mendaftar-akaun-baru',
                'excerpt' => 'Langkah demi langkah untuk mendaftar akaun baru dalam sistem helpdesk. Termasuk maklumat yang diperlukan dan proses pengesahan.',
                'content' => $this->getRegistrationArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(100, 300),
            ],
            [
                'category_id' => $panduanBermula->id,
                'title' => 'Memahami Dashboard Pengguna',
                'slug' => 'memahami-dashboard-pengguna',
                'excerpt' => 'Penjelasan terperinci tentang setiap komponen dalam dashboard pengguna dan cara menggunakannya dengan efektif.',
                'content' => $this->getDashboardArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(80, 200),
            ],

            // Pengurusan Tiket
            [
                'category_id' => $pengurusanTiket->id,
                'title' => 'Cara Membuat Tiket Sokongan Baru',
                'slug' => 'cara-membuat-tiket-sokongan-baru',
                'excerpt' => 'Panduan lengkap untuk membuat tiket sokongan teknikal baru. Termasuk cara memilih kategori, keutamaan, dan menulis penerangan yang berkesan.',
                'content' => $this->getCreateTicketArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(200, 600),
            ],
            [
                'category_id' => $pengurusanTiket->id,
                'title' => 'Memahami Status dan Keutamaan Tiket',
                'slug' => 'memahami-status-keutamaan-tiket',
                'excerpt' => 'Penjelasan tentang pelbagai status tiket dan tahap keutamaan. Ketahui maksud setiap status dan bila ia digunakan.',
                'content' => $this->getTicketStatusArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(120, 350),
            ],
            [
                'category_id' => $pengurusanTiket->id,
                'title' => 'Cara Membalas dan Mengemaskini Tiket',
                'slug' => 'cara-membalas-mengemaskini-tiket',
                'excerpt' => 'Panduan untuk berkomunikasi dengan pasukan sokongan melalui sistem tiket. Termasuk cara menambah lampiran dan maklumat tambahan.',
                'content' => $this->getReplyTicketArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(90, 250),
            ],
            [
                'category_id' => $pengurusanTiket->id,
                'title' => 'Menjejak Sejarah dan Aktiviti Tiket',
                'slug' => 'menjejak-sejarah-aktiviti-tiket',
                'excerpt' => 'Cara melihat sejarah lengkap tiket anda termasuk semua perubahan status, balasan, dan aktiviti yang berkaitan.',
                'content' => $this->getTicketHistoryArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(60, 180),
            ],

            // Akaun & Keselamatan
            [
                'category_id' => $akaunKeselamatan->id,
                'title' => 'Mengemaskini Profil dan Maklumat Peribadi',
                'slug' => 'mengemaskini-profil-maklumat-peribadi',
                'excerpt' => 'Panduan untuk mengemaskini maklumat profil anda termasuk nama, e-mel, nombor telefon, dan maklumat syarikat.',
                'content' => $this->getUpdateProfileArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(70, 200),
            ],
            [
                'category_id' => $akaunKeselamatan->id,
                'title' => 'Menukar Kata Laluan dengan Selamat',
                'slug' => 'menukar-kata-laluan-selamat',
                'excerpt' => 'Langkah-langkah untuk menukar kata laluan akaun anda dan amalan terbaik untuk mencipta kata laluan yang kukuh.',
                'content' => $this->getChangePasswordArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(100, 280),
            ],
            [
                'category_id' => $akaunKeselamatan->id,
                'title' => 'Mengaktifkan Pengesahan Dua Faktor (2FA)',
                'slug' => 'mengaktifkan-pengesahan-dua-faktor',
                'excerpt' => 'Panduan lengkap untuk mengaktifkan dan menggunakan pengesahan dua faktor bagi meningkatkan keselamatan akaun anda.',
                'content' => $this->get2FAArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(80, 220),
            ],

            // Soalan Lazim
            [
                'category_id' => $soalanLazim->id,
                'title' => 'Berapa Lama Masa Respons untuk Tiket Saya?',
                'slug' => 'masa-respons-tiket',
                'excerpt' => 'Maklumat tentang jangka masa respons berdasarkan tahap keutamaan tiket dan waktu operasi pasukan sokongan.',
                'content' => $this->getResponseTimeArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(150, 400),
            ],
            [
                'category_id' => $soalanLazim->id,
                'title' => 'Bagaimana Jika Saya Lupa Kata Laluan?',
                'slug' => 'lupa-kata-laluan',
                'excerpt' => 'Panduan untuk memulihkan akses ke akaun anda jika anda terlupa kata laluan.',
                'content' => $this->getForgotPasswordArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(180, 450),
            ],
            [
                'category_id' => $soalanLazim->id,
                'title' => 'Bolehkah Saya Membatalkan atau Menutup Tiket?',
                'slug' => 'membatalkan-menutup-tiket',
                'excerpt' => 'Maklumat tentang cara dan bila anda boleh membatalkan atau menutup tiket sokongan yang telah dibuat.',
                'content' => $this->getCancelTicketArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(90, 250),
            ],

            // Penyelesaian Masalah
            [
                'category_id' => $penyelesaianMasalah->id,
                'title' => 'Tidak Dapat Log Masuk ke Akaun',
                'slug' => 'tidak-dapat-log-masuk',
                'excerpt' => 'Penyelesaian untuk masalah log masuk yang biasa dihadapi termasuk masalah kata laluan, akaun dikunci, dan isu teknikal.',
                'content' => $this->getLoginIssuesArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(200, 500),
            ],
            [
                'category_id' => $penyelesaianMasalah->id,
                'title' => 'E-mel Notifikasi Tidak Diterima',
                'slug' => 'emel-notifikasi-tidak-diterima',
                'excerpt' => 'Langkah-langkah untuk menyelesaikan masalah e-mel notifikasi yang tidak sampai ke peti masuk anda.',
                'content' => $this->getEmailIssuesArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(120, 320),
            ],
            [
                'category_id' => $penyelesaianMasalah->id,
                'title' => 'Lampiran Gagal Dimuat Naik',
                'slug' => 'lampiran-gagal-dimuat-naik',
                'excerpt' => 'Penyelesaian untuk masalah muat naik lampiran termasuk had saiz fail dan format yang disokong.',
                'content' => $this->getAttachmentIssuesArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(80, 200),
            ],

            // Polisi & Prosedur
            [
                'category_id' => $polisiProsedur->id,
                'title' => 'Polisi Privasi dan Perlindungan Data',
                'slug' => 'polisi-privasi-perlindungan-data',
                'excerpt' => 'Maklumat lengkap tentang bagaimana kami mengumpul, menyimpan, dan melindungi data peribadi anda.',
                'content' => $this->getPrivacyPolicyArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(60, 150),
            ],
            [
                'category_id' => $polisiProsedur->id,
                'title' => 'Terma dan Syarat Penggunaan',
                'slug' => 'terma-syarat-penggunaan',
                'excerpt' => 'Terma dan syarat yang mengawal penggunaan sistem helpdesk Orien.',
                'content' => $this->getTermsArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(50, 120),
            ],
            [
                'category_id' => $polisiProsedur->id,
                'title' => 'Prosedur Eskalasi Tiket',
                'slug' => 'prosedur-eskalasi-tiket',
                'excerpt' => 'Panduan tentang proses eskalasi tiket jika isu anda memerlukan perhatian segera atau penyelesaian di peringkat lebih tinggi.',
                'content' => $this->getEscalationArticleContent(),
                'status' => 'published',
                'published_at' => now(),
                'views' => rand(70, 180),
            ],
        ];

        foreach ($articles as $articleData) {
            KbArticle::updateOrCreate(
                ['slug' => $articleData['slug']],
                $articleData
            );
        }
    }

    private function getWelcomeArticleContent(): string
    {
        return <<<HTML
<h2>Pengenalan Sistem Helpdesk Orien</h2>
<p>Selamat datang ke Sistem Helpdesk Orien! Platform ini direka khas untuk memudahkan komunikasi antara anda dan pasukan sokongan teknikal kami. Dengan sistem ini, anda boleh membuat, menjejak, dan menguruskan semua permintaan sokongan anda di satu tempat yang berpusat.</p>

<h3>Apa Itu Sistem Helpdesk?</h3>
<p>Sistem helpdesk adalah platform digital yang membolehkan pengguna menghantar permintaan bantuan teknikal (dikenali sebagai "tiket") kepada pasukan sokongan. Setiap tiket akan dijejak dari awal hingga penyelesaian, memastikan tiada permintaan yang terlepas pandang.</p>

<h3>Kelebihan Menggunakan Sistem Helpdesk Orien</h3>
<ul>
    <li><strong>Penjejakan Telus:</strong> Anda boleh melihat status tiket anda pada bila-bila masa dan mengetahui tahap kemajuan penyelesaian.</li>
    <li><strong>Komunikasi Berpusat:</strong> Semua perbualan dan dokumen berkaitan disimpan dalam satu tiket untuk rujukan mudah.</li>
    <li><strong>Respons Pantas:</strong> Sistem keutamaan memastikan isu kritikal ditangani dengan segera.</li>
    <li><strong>Sejarah Lengkap:</strong> Akses sejarah semua tiket anda untuk rujukan masa hadapan.</li>
    <li><strong>Notifikasi Automatik:</strong> Terima e-mel notifikasi untuk setiap kemaskini pada tiket anda.</li>
</ul>

<h3>Cara Mengakses Sistem</h3>
<p>Anda boleh mengakses sistem helpdesk melalui pelayar web di mana-mana peranti. Sistem ini serasi dengan:</p>
<ul>
    <li>Google Chrome (disyorkan)</li>
    <li>Mozilla Firefox</li>
    <li>Microsoft Edge</li>
    <li>Safari</li>
</ul>

<h3>Struktur Antara Muka Pengguna</h3>
<p>Antara muka sistem helpdesk terbahagi kepada beberapa bahagian utama:</p>

<h4>1. Bar Navigasi Sisi (Sidebar)</h4>
<p>Terletak di sebelah kiri skrin, bar navigasi ini mengandungi menu utama untuk mengakses pelbagai fungsi sistem seperti Dashboard, Tiket, dan Pangkalan Pengetahuan.</p>

<h4>2. Dashboard</h4>
<p>Halaman utama yang memaparkan ringkasan statistik tiket anda, tiket terkini, dan carta prestasi.</p>

<h4>3. Senarai Tiket</h4>
<p>Halaman yang memaparkan semua tiket anda dengan pilihan penapisan dan carian.</p>

<h4>4. Pangkalan Pengetahuan</h4>
<p>Koleksi artikel bantuan dan panduan yang boleh membantu anda menyelesaikan masalah tanpa perlu membuat tiket.</p>

<h3>Langkah Seterusnya</h3>
<p>Sekarang anda telah memahami asas sistem helpdesk, kami cadangkan anda membaca artikel-artikel berikut:</p>
<ul>
    <li>Cara Mendaftar Akaun Baru</li>
    <li>Cara Membuat Tiket Sokongan Baru</li>
    <li>Memahami Status dan Keutamaan Tiket</li>
</ul>

<p>Jika anda memerlukan bantuan tambahan, jangan teragak-agak untuk menghubungi pasukan sokongan kami melalui sistem tiket atau e-mel.</p>
HTML;
    }

    private function getRegistrationArticleContent(): string
    {
        return <<<HTML
<h2>Panduan Pendaftaran Akaun Baru</h2>
<p>Untuk menggunakan sistem helpdesk Orien, anda perlu mendaftar akaun terlebih dahulu. Proses pendaftaran adalah mudah dan hanya mengambil masa beberapa minit sahaja.</p>

<h3>Maklumat yang Diperlukan</h3>
<p>Sebelum memulakan pendaftaran, sila pastikan anda mempunyai maklumat berikut:</p>
<ul>
    <li><strong>Nama Penuh:</strong> Nama penuh anda seperti dalam dokumen rasmi.</li>
    <li><strong>Alamat E-mel:</strong> E-mel yang aktif dan boleh diakses. E-mel ini akan digunakan untuk log masuk dan menerima notifikasi.</li>
    <li><strong>Kata Laluan:</strong> Kata laluan yang kukuh dengan sekurang-kurangnya 8 aksara.</li>
    <li><strong>Nombor Telefon:</strong> Nombor telefon yang boleh dihubungi (pilihan).</li>
</ul>

<h3>Langkah-langkah Pendaftaran</h3>

<h4>Langkah 1: Akses Halaman Pendaftaran</h4>
<p>Klik butang "Daftar" atau "Register" di halaman log masuk sistem helpdesk.</p>

<h4>Langkah 2: Isi Borang Pendaftaran</h4>
<p>Lengkapkan semua medan yang diperlukan dalam borang pendaftaran:</p>
<ul>
    <li>Masukkan nama penuh anda</li>
    <li>Masukkan alamat e-mel yang sah</li>
    <li>Cipta kata laluan yang kukuh</li>
    <li>Sahkan kata laluan dengan menaipnya semula</li>
    <li>Masukkan nombor telefon (jika diminta)</li>
</ul>

<h4>Langkah 3: Terima Terma dan Syarat</h4>
<p>Baca dan tandakan kotak persetujuan untuk terma dan syarat penggunaan sistem.</p>

<h4>Langkah 4: Hantar Pendaftaran</h4>
<p>Klik butang "Daftar" untuk menghantar permohonan pendaftaran anda.</p>

<h4>Langkah 5: Pengesahan E-mel</h4>
<p>Semak peti masuk e-mel anda untuk e-mel pengesahan. Klik pautan dalam e-mel tersebut untuk mengaktifkan akaun anda.</p>

<h3>Keperluan Kata Laluan</h3>
<p>Untuk keselamatan akaun anda, kata laluan mestilah memenuhi kriteria berikut:</p>
<ul>
    <li>Sekurang-kurangnya 8 aksara</li>
    <li>Mengandungi huruf besar dan huruf kecil</li>
    <li>Mengandungi sekurang-kurangnya satu nombor</li>
    <li>Mengandungi sekurang-kurangnya satu aksara khas (!@#$%^&*)</li>
</ul>

<h3>Masalah Semasa Pendaftaran?</h3>
<p>Jika anda menghadapi masalah semasa pendaftaran, sila semak perkara berikut:</p>
<ul>
    <li><strong>E-mel sudah digunakan:</strong> Setiap alamat e-mel hanya boleh didaftarkan sekali. Jika anda sudah mempunyai akaun, sila gunakan fungsi "Lupa Kata Laluan".</li>
    <li><strong>E-mel pengesahan tidak diterima:</strong> Semak folder spam/junk anda. E-mel pengesahan mungkin tersaring ke sana.</li>
    <li><strong>Kata laluan tidak diterima:</strong> Pastikan kata laluan anda memenuhi semua keperluan keselamatan.</li>
</ul>

<h3>Selepas Pendaftaran</h3>
<p>Setelah akaun anda berjaya didaftarkan dan disahkan, anda boleh:</p>
<ul>
    <li>Log masuk ke sistem helpdesk</li>
    <li>Melengkapkan profil anda dengan maklumat tambahan</li>
    <li>Mula membuat tiket sokongan</li>
    <li>Mengakses pangkalan pengetahuan</li>
</ul>
HTML;
    }

    private function getDashboardArticleContent(): string
    {
        return <<<HTML
<h2>Memahami Dashboard Pengguna</h2>
<p>Dashboard adalah halaman utama yang anda lihat selepas log masuk ke sistem helpdesk. Ia menyediakan gambaran keseluruhan tentang aktiviti tiket anda dan akses pantas ke fungsi-fungsi penting.</p>

<h3>Komponen Utama Dashboard</h3>

<h4>1. Kad Statistik</h4>
<p>Di bahagian atas dashboard, anda akan melihat beberapa kad statistik yang memaparkan:</p>
<ul>
    <li><strong>Jumlah Tiket:</strong> Bilangan keseluruhan tiket yang anda telah buat.</li>
    <li><strong>Tiket Terbuka:</strong> Bilangan tiket yang masih dalam proses penyelesaian.</li>
    <li><strong>Tiket Ditutup:</strong> Bilangan tiket yang telah selesai diselesaikan.</li>
    <li><strong>Tiket Tertunda:</strong> Bilangan tiket yang menunggu maklum balas atau tindakan.</li>
</ul>

<h4>2. Carta dan Graf</h4>
<p>Dashboard memaparkan beberapa carta visual untuk membantu anda memahami corak tiket anda:</p>
<ul>
    <li><strong>Tiket Mengikut Status:</strong> Carta donat yang menunjukkan taburan tiket berdasarkan status.</li>
    <li><strong>Tiket Mengikut Keutamaan:</strong> Carta bar yang menunjukkan bilangan tiket untuk setiap tahap keutamaan.</li>
    <li><strong>Trend Tiket:</strong> Graf garis yang menunjukkan bilangan tiket dalam 7 hari terakhir.</li>
</ul>

<h4>3. Tiket Terkini</h4>
<p>Senarai 5 tiket terkini anda dengan maklumat ringkas termasuk:</p>
<ul>
    <li>Tajuk tiket</li>
    <li>Nombor tiket</li>
    <li>Status semasa</li>
    <li>Masa dibuat</li>
</ul>

<h3>Navigasi dari Dashboard</h3>
<p>Dari dashboard, anda boleh:</p>
<ul>
    <li>Klik pada mana-mana tiket untuk melihat butiran penuh</li>
    <li>Klik "Lihat Semua" untuk pergi ke senarai tiket lengkap</li>
    <li>Gunakan menu sisi untuk mengakses bahagian lain sistem</li>
</ul>

<h3>Maklumat Berdasarkan Peranan</h3>
<p>Dashboard memaparkan maklumat yang berbeza berdasarkan peranan anda:</p>
<ul>
    <li><strong>Pelanggan:</strong> Melihat statistik tiket peribadi sahaja.</li>
    <li><strong>Ejen:</strong> Melihat statistik tiket yang ditugaskan kepada mereka.</li>
    <li><strong>Pentadbir:</strong> Melihat statistik keseluruhan sistem.</li>
</ul>
HTML;
    }

    private function getCreateTicketArticleContent(): string
    {
        return <<<HTML
<h2>Panduan Membuat Tiket Sokongan Baru</h2>
<p>Tiket sokongan adalah cara utama untuk mendapatkan bantuan daripada pasukan sokongan teknikal kami. Panduan ini akan membantu anda membuat tiket yang berkesan untuk mempercepatkan proses penyelesaian.</p>

<h3>Bila Perlu Membuat Tiket?</h3>
<p>Anda perlu membuat tiket apabila:</p>
<ul>
    <li>Menghadapi masalah teknikal yang tidak dapat diselesaikan sendiri</li>
    <li>Memerlukan bantuan atau maklumat daripada pasukan sokongan</li>
    <li>Ingin melaporkan pepijat atau isu dalam sistem</li>
    <li>Memerlukan perubahan atau penambahbaikan pada perkhidmatan</li>
</ul>

<h3>Langkah-langkah Membuat Tiket</h3>

<h4>Langkah 1: Akses Halaman Tiket Baru</h4>
<p>Klik butang "Tiket Baru" atau "Buat Tiket" di menu navigasi atau dashboard.</p>

<h4>Langkah 2: Pilih Kategori</h4>
<p>Pilih kategori yang paling sesuai dengan isu anda. Kategori yang tepat membantu pasukan sokongan mengarahkan tiket anda kepada pakar yang betul. Kategori yang tersedia termasuk:</p>
<ul>
    <li>Sokongan Teknikal</li>
    <li>Pertanyaan Umum</li>
    <li>Laporan Pepijat</li>
    <li>Permintaan Ciri</li>
    <li>Bil dan Pembayaran</li>
</ul>

<h4>Langkah 3: Tetapkan Keutamaan</h4>
<p>Pilih tahap keutamaan berdasarkan kesan isu terhadap operasi anda:</p>
<ul>
    <li><strong>Kritikal:</strong> Sistem tidak berfungsi langsung, tiada penyelesaian sementara</li>
    <li><strong>Tinggi:</strong> Fungsi utama terjejas teruk, impak besar kepada operasi</li>
    <li><strong>Sederhana:</strong> Fungsi terjejas tetapi ada penyelesaian sementara</li>
    <li><strong>Rendah:</strong> Isu kecil atau pertanyaan umum</li>
</ul>

<h4>Langkah 4: Tulis Tajuk yang Jelas</h4>
<p>Tulis tajuk yang ringkas tetapi deskriptif. Contoh tajuk yang baik:</p>
<ul>
    <li>"Tidak dapat log masuk selepas tukar kata laluan"</li>
    <li>"Laporan bulanan tidak dapat dimuat turun"</li>
    <li>"Ralat 500 semasa menghantar borang"</li>
</ul>

<h4>Langkah 5: Tulis Penerangan Terperinci</h4>
<p>Dalam penerangan, sertakan maklumat berikut:</p>
<ul>
    <li><strong>Apa yang berlaku:</strong> Terangkan masalah dengan jelas</li>
    <li><strong>Bila ia berlaku:</strong> Tarikh dan masa isu mula berlaku</li>
    <li><strong>Langkah untuk menghasilkan semula:</strong> Bagaimana kami boleh melihat masalah yang sama</li>
    <li><strong>Apa yang anda jangkakan:</strong> Hasil yang sepatutnya berlaku</li>
    <li><strong>Apa yang anda sudah cuba:</strong> Langkah penyelesaian yang sudah dicuba</li>
</ul>

<h4>Langkah 6: Lampirkan Fail (Jika Perlu)</h4>
<p>Lampirkan tangkapan skrin, log ralat, atau dokumen yang berkaitan untuk membantu pasukan sokongan memahami isu anda dengan lebih baik.</p>

<h4>Langkah 7: Hantar Tiket</h4>
<p>Semak semula semua maklumat dan klik "Hantar" untuk membuat tiket anda.</p>

<h3>Tips untuk Tiket yang Berkesan</h3>
<ul>
    <li>Satu tiket untuk satu isu - jangan gabungkan berbilang masalah dalam satu tiket</li>
    <li>Gunakan bahasa yang jelas dan profesional</li>
    <li>Sertakan maklumat teknikal yang relevan (pelayar, sistem operasi, dll.)</li>
    <li>Lampirkan tangkapan skrin jika boleh</li>
    <li>Nyatakan kesan isu terhadap operasi anda</li>
</ul>

<h3>Selepas Membuat Tiket</h3>
<p>Selepas tiket dihantar:</p>
<ul>
    <li>Anda akan menerima e-mel pengesahan dengan nombor tiket</li>
    <li>Tiket akan dipaparkan dalam senarai tiket anda</li>
    <li>Pasukan sokongan akan menyemak dan membalas dalam masa yang ditetapkan</li>
    <li>Anda akan dimaklumkan melalui e-mel untuk setiap kemaskini</li>
</ul>
HTML;
    }

    private function getTicketStatusArticleContent(): string
    {
        return <<<HTML
<h2>Memahami Status dan Keutamaan Tiket</h2>
<p>Setiap tiket dalam sistem mempunyai status dan tahap keutamaan yang menunjukkan keadaan semasa dan kepentingan tiket tersebut. Memahami makna setiap status dan keutamaan akan membantu anda menjejak kemajuan tiket anda dengan lebih baik.</p>

<h3>Status Tiket</h3>
<p>Status tiket menunjukkan di mana tiket anda berada dalam proses penyelesaian:</p>

<h4>🔵 Terbuka (Open)</h4>
<p>Tiket baru yang telah diterima tetapi belum ditugaskan atau mula diproses oleh pasukan sokongan. Ini adalah status awal untuk semua tiket baru.</p>

<h4>🟡 Dalam Proses (In Progress)</h4>
<p>Tiket sedang aktif diusahakan oleh ejen sokongan. Pasukan sokongan sedang menyiasat atau menyelesaikan isu anda.</p>

<h4>🟠 Menunggu (Pending)</h4>
<p>Tiket memerlukan maklumat tambahan atau tindakan daripada anda sebelum dapat diteruskan. Sila semak dan balas tiket anda secepat mungkin.</p>

<h4>🟣 Dibuka Semula (Reopened)</h4>
<p>Tiket yang sebelum ini ditutup tetapi dibuka semula kerana isu yang sama berulang atau penyelesaian tidak memuaskan.</p>

<h4>🟢 Ditutup (Closed)</h4>
<p>Tiket telah selesai diselesaikan dan ditutup. Jika anda masih menghadapi masalah yang sama, anda boleh membuka semula tiket ini atau membuat tiket baru.</p>

<h3>Tahap Keutamaan</h3>
<p>Keutamaan menentukan betapa segeranya tiket anda perlu ditangani:</p>

<h4>🔴 Kritikal</h4>
<p><strong>Masa Respons:</strong> 1 jam<br>
<strong>Bila digunakan:</strong> Sistem tidak berfungsi langsung, tiada penyelesaian sementara, impak kepada ramai pengguna atau operasi kritikal perniagaan.</p>

<h4>🟠 Tinggi</h4>
<p><strong>Masa Respons:</strong> 4 jam<br>
<strong>Bila digunakan:</strong> Fungsi utama terjejas teruk, impak besar kepada produktiviti, ada penyelesaian sementara tetapi tidak praktikal untuk jangka panjang.</p>

<h4>🔵 Sederhana</h4>
<p><strong>Masa Respons:</strong> 8 jam<br>
<strong>Bila digunakan:</strong> Fungsi terjejas tetapi ada penyelesaian sementara yang boleh diterima, impak sederhana kepada operasi.</p>

<h4>🟢 Rendah</h4>
<p><strong>Masa Respons:</strong> 24 jam<br>
<strong>Bila digunakan:</strong> Isu kecil, pertanyaan umum, permintaan maklumat, atau cadangan penambahbaikan.</p>

<h3>Aliran Kerja Tiket</h3>
<p>Berikut adalah aliran biasa untuk sebuah tiket:</p>
<ol>
    <li><strong>Terbuka:</strong> Tiket baru dibuat oleh pengguna</li>
    <li><strong>Dalam Proses:</strong> Ejen mula menyiasat isu</li>
    <li><strong>Menunggu:</strong> (Jika perlu) Menunggu maklumat tambahan daripada pengguna</li>
    <li><strong>Dalam Proses:</strong> Ejen meneruskan penyelesaian selepas menerima maklumat</li>
    <li><strong>Ditutup:</strong> Isu diselesaikan dan tiket ditutup</li>
</ol>

<h3>Perjanjian Tahap Perkhidmatan (SLA)</h3>
<p>Setiap tahap keutamaan mempunyai masa respons dan penyelesaian yang dijanjikan. Pasukan sokongan kami komited untuk mematuhi SLA ini bagi memastikan isu anda ditangani dalam masa yang munasabah.</p>
HTML;
    }

    private function getReplyTicketArticleContent(): string
    {
        return <<<HTML
<h2>Cara Membalas dan Mengemaskini Tiket</h2>
<p>Komunikasi yang berkesan dengan pasukan sokongan adalah kunci untuk penyelesaian isu yang pantas. Panduan ini menerangkan cara membalas tiket dan menambah maklumat tambahan.</p>

<h3>Membalas Tiket</h3>

<h4>Langkah 1: Buka Tiket</h4>
<p>Pergi ke senarai tiket anda dan klik pada tiket yang ingin dibalas untuk membuka halaman butiran tiket.</p>

<h4>Langkah 2: Tulis Balasan</h4>
<p>Di bahagian bawah halaman tiket, anda akan melihat kotak teks untuk menulis balasan. Taip mesej anda di sini.</p>

<h4>Langkah 3: Lampirkan Fail (Pilihan)</h4>
<p>Jika perlu, klik butang lampiran untuk menambah fail seperti tangkapan skrin atau dokumen.</p>

<h4>Langkah 4: Hantar Balasan</h4>
<p>Klik butang "Hantar Balasan" untuk menghantar mesej anda kepada pasukan sokongan.</p>

<h3>Tips untuk Balasan yang Berkesan</h3>
<ul>
    <li><strong>Balas dengan segera:</strong> Balasan pantas membantu mempercepatkan penyelesaian</li>
    <li><strong>Jawab semua soalan:</strong> Pastikan anda menjawab semua soalan yang ditanya oleh ejen</li>
    <li><strong>Berikan maklumat lengkap:</strong> Sertakan semua maklumat yang diminta</li>
    <li><strong>Gunakan bahasa yang jelas:</strong> Elakkan singkatan atau istilah yang mungkin tidak difahami</li>
    <li><strong>Sertakan bukti:</strong> Lampirkan tangkapan skrin atau log jika relevan</li>
</ul>

<h3>Menambah Lampiran</h3>
<p>Anda boleh melampirkan pelbagai jenis fail untuk membantu menjelaskan isu anda:</p>
<ul>
    <li><strong>Imej:</strong> PNG, JPG, GIF (maksimum 5MB)</li>
    <li><strong>Dokumen:</strong> PDF, DOC, DOCX, XLS, XLSX (maksimum 10MB)</li>
    <li><strong>Fail teks:</strong> TXT, LOG (maksimum 2MB)</li>
    <li><strong>Arkib:</strong> ZIP (maksimum 10MB)</li>
</ul>

<h3>Masa Bekerja untuk Balasan</h3>
<p>Anda boleh merekodkan masa yang dihabiskan untuk menyelesaikan isu (untuk ejen dan pentadbir). Ini membantu dalam pelaporan dan pengurusan prestasi.</p>

<h3>Nota Dalaman</h3>
<p>Ejen dan pentadbir boleh menambah nota dalaman yang tidak dapat dilihat oleh pelanggan. Ini berguna untuk komunikasi antara pasukan sokongan.</p>
HTML;
    }

    private function getTicketHistoryArticleContent(): string
    {
        return <<<HTML
<h2>Menjejak Sejarah dan Aktiviti Tiket</h2>
<p>Sistem helpdesk merekodkan semua aktiviti berkaitan tiket anda. Anda boleh melihat sejarah lengkap untuk memahami perjalanan penyelesaian isu anda.</p>

<h3>Maklumat yang Direkodkan</h3>
<p>Sejarah tiket merekodkan:</p>
<ul>
    <li>Tarikh dan masa tiket dibuat</li>
    <li>Semua balasan dan komunikasi</li>
    <li>Perubahan status tiket</li>
    <li>Perubahan keutamaan</li>
    <li>Penugasan ejen</li>
    <li>Lampiran yang dimuat naik</li>
    <li>Nota dalaman (untuk ejen sahaja)</li>
</ul>

<h3>Cara Melihat Sejarah</h3>
<ol>
    <li>Buka tiket yang ingin dilihat sejarahnya</li>
    <li>Tatal ke bahagian perbualan/chat</li>
    <li>Semua aktiviti dipaparkan dalam susunan kronologi</li>
</ol>

<h3>Memahami Garis Masa Tiket</h3>
<p>Setiap entri dalam sejarah memaparkan:</p>
<ul>
    <li><strong>Tarikh dan Masa:</strong> Bila aktiviti berlaku</li>
    <li><strong>Pengguna:</strong> Siapa yang melakukan tindakan</li>
    <li><strong>Jenis Aktiviti:</strong> Apa yang dilakukan (balasan, perubahan status, dll.)</li>
    <li><strong>Butiran:</strong> Maklumat terperinci tentang aktiviti</li>
</ul>
HTML;
    }

    private function getUpdateProfileArticleContent(): string
    {
        return <<<HTML
<h2>Mengemaskini Profil dan Maklumat Peribadi</h2>
<p>Menjaga maklumat profil anda terkini adalah penting untuk memastikan komunikasi yang lancar dengan pasukan sokongan. Panduan ini menerangkan cara mengemaskini pelbagai maklumat profil anda.</p>

<h3>Mengakses Halaman Profil</h3>
<ol>
    <li>Klik pada nama atau avatar anda di penjuru kanan atas</li>
    <li>Pilih "Profil" atau "Tetapan Akaun" dari menu</li>
</ol>

<h3>Maklumat yang Boleh Dikemaskini</h3>

<h4>Maklumat Peribadi</h4>
<ul>
    <li><strong>Nama Penuh:</strong> Nama yang akan dipaparkan dalam sistem</li>
    <li><strong>Nama Pengguna:</strong> Pengecam unik anda dalam sistem</li>
    <li><strong>Alamat E-mel:</strong> E-mel untuk log masuk dan notifikasi</li>
    <li><strong>Nombor Telefon:</strong> Nombor untuk dihubungi jika perlu</li>
    <li><strong>Nombor Mudah Alih:</strong> Nombor telefon bimbit</li>
</ul>

<h4>Alamat</h4>
<ul>
    <li>Alamat jalan</li>
    <li>Bandar</li>
    <li>Negeri</li>
    <li>Poskod</li>
    <li>Negara</li>
</ul>

<h4>Maklumat Syarikat (Jika Berkaitan)</h4>
<ul>
    <li>Nama syarikat</li>
    <li>Nombor pendaftaran</li>
    <li>Telefon syarikat</li>
    <li>E-mel syarikat</li>
    <li>Alamat syarikat</li>
    <li>Laman web</li>
    <li>Industri</li>
</ul>

<h3>Langkah Mengemaskini Profil</h3>
<ol>
    <li>Akses halaman profil anda</li>
    <li>Klik butang "Edit" atau terus ubah maklumat dalam medan</li>
    <li>Kemaskini maklumat yang diperlukan</li>
    <li>Klik "Simpan" untuk menyimpan perubahan</li>
</ol>

<h3>Menukar Gambar Profil</h3>
<ol>
    <li>Klik pada gambar profil semasa</li>
    <li>Pilih fail imej baru dari komputer anda</li>
    <li>Laraskan kedudukan dan saiz jika perlu</li>
    <li>Simpan perubahan</li>
</ol>
HTML;
    }

    private function getChangePasswordArticleContent(): string
    {
        return <<<HTML
<h2>Menukar Kata Laluan dengan Selamat</h2>
<p>Menukar kata laluan secara berkala adalah amalan keselamatan yang baik. Panduan ini menerangkan cara menukar kata laluan dan tips untuk mencipta kata laluan yang kukuh.</p>

<h3>Bila Perlu Menukar Kata Laluan?</h3>
<ul>
    <li>Secara berkala (setiap 3-6 bulan)</li>
    <li>Jika anda mengesyaki akaun anda telah dikompromi</li>
    <li>Selepas berkongsi kata laluan dengan orang lain</li>
    <li>Jika anda menggunakan kata laluan yang sama di laman web lain yang telah digodam</li>
</ul>

<h3>Langkah Menukar Kata Laluan</h3>
<ol>
    <li>Pergi ke halaman Profil atau Tetapan Akaun</li>
    <li>Cari bahagian "Keselamatan" atau "Kata Laluan"</li>
    <li>Masukkan kata laluan semasa anda</li>
    <li>Masukkan kata laluan baru</li>
    <li>Sahkan kata laluan baru</li>
    <li>Klik "Kemaskini Kata Laluan"</li>
</ol>

<h3>Keperluan Kata Laluan</h3>
<p>Kata laluan baru mestilah:</p>
<ul>
    <li>Sekurang-kurangnya 8 aksara</li>
    <li>Mengandungi huruf besar (A-Z)</li>
    <li>Mengandungi huruf kecil (a-z)</li>
    <li>Mengandungi nombor (0-9)</li>
    <li>Mengandungi aksara khas (!@#$%^&*)</li>
    <li>Berbeza daripada 5 kata laluan sebelumnya</li>
</ul>

<h3>Tips Kata Laluan Kukuh</h3>
<ul>
    <li><strong>Gunakan frasa:</strong> "SayaSukaM4k4nN4siLem@k!" lebih kukuh dan mudah diingat</li>
    <li><strong>Elakkan maklumat peribadi:</strong> Jangan gunakan nama, tarikh lahir, atau nombor telefon</li>
    <li><strong>Unik untuk setiap akaun:</strong> Jangan gunakan kata laluan yang sama di mana-mana</li>
    <li><strong>Gunakan pengurus kata laluan:</strong> Aplikasi seperti LastPass atau 1Password boleh membantu</li>
</ul>

<h3>Apa yang Berlaku Selepas Tukar?</h3>
<ul>
    <li>Anda akan dilog keluar dari semua sesi aktif</li>
    <li>E-mel pengesahan akan dihantar</li>
    <li>Anda perlu log masuk semula dengan kata laluan baru</li>
</ul>
HTML;
    }

    private function get2FAArticleContent(): string
    {
        return <<<HTML
<h2>Mengaktifkan Pengesahan Dua Faktor (2FA)</h2>
<p>Pengesahan Dua Faktor (2FA) menambah lapisan keselamatan tambahan kepada akaun anda. Walaupun seseorang mengetahui kata laluan anda, mereka masih memerlukan kod dari telefon anda untuk log masuk.</p>

<h3>Apa Itu 2FA?</h3>
<p>2FA memerlukan dua bentuk pengesahan untuk log masuk:</p>
<ol>
    <li><strong>Sesuatu yang anda tahu:</strong> Kata laluan anda</li>
    <li><strong>Sesuatu yang anda miliki:</strong> Kod dari aplikasi authenticator di telefon anda</li>
</ol>

<h3>Keperluan untuk 2FA</h3>
<ul>
    <li>Telefon pintar (Android atau iOS)</li>
    <li>Aplikasi authenticator seperti:
        <ul>
            <li>Google Authenticator</li>
            <li>Microsoft Authenticator</li>
            <li>Authy</li>
        </ul>
    </li>
</ul>

<h3>Langkah Mengaktifkan 2FA</h3>

<h4>Langkah 1: Muat Turun Aplikasi Authenticator</h4>
<p>Muat turun dan pasang aplikasi authenticator pilihan anda dari App Store atau Google Play Store.</p>

<h4>Langkah 2: Akses Tetapan 2FA</h4>
<p>Pergi ke Profil > Keselamatan > Pengesahan Dua Faktor dan klik "Aktifkan".</p>

<h4>Langkah 3: Imbas Kod QR</h4>
<p>Buka aplikasi authenticator dan imbas kod QR yang dipaparkan di skrin. Atau masukkan kod rahsia secara manual.</p>

<h4>Langkah 4: Masukkan Kod Pengesahan</h4>
<p>Masukkan kod 6 digit yang dipaparkan dalam aplikasi authenticator untuk mengesahkan persediaan.</p>

<h4>Langkah 5: Simpan Kod Pemulihan</h4>
<p>Simpan kod pemulihan di tempat yang selamat. Kod ini diperlukan jika anda kehilangan akses kepada telefon anda.</p>

<h3>Log Masuk dengan 2FA</h3>
<ol>
    <li>Masukkan e-mel dan kata laluan seperti biasa</li>
    <li>Apabila diminta, buka aplikasi authenticator</li>
    <li>Masukkan kod 6 digit yang dipaparkan</li>
    <li>Kod berubah setiap 30 saat, pastikan anda masukkan kod terkini</li>
</ol>

<h3>Kod Pemulihan</h3>
<p>Kod pemulihan adalah kod sekali guna yang boleh digunakan jika anda tidak dapat mengakses aplikasi authenticator. Setiap kod hanya boleh digunakan sekali. Simpan kod ini dengan selamat!</p>

<h3>Menyahaktifkan 2FA</h3>
<p>Jika anda perlu menyahaktifkan 2FA:</p>
<ol>
    <li>Pergi ke Profil > Keselamatan > Pengesahan Dua Faktor</li>
    <li>Klik "Nyahaktifkan"</li>
    <li>Masukkan kata laluan anda untuk pengesahan</li>
</ol>
<p><strong>Amaran:</strong> Menyahaktifkan 2FA akan mengurangkan keselamatan akaun anda.</p>
HTML;
    }

    private function getResponseTimeArticleContent(): string
    {
        return <<<HTML
<h2>Masa Respons untuk Tiket Sokongan</h2>
<p>Kami komited untuk memberikan respons pantas kepada semua permintaan sokongan. Masa respons bergantung kepada tahap keutamaan tiket dan waktu operasi pasukan sokongan.</p>

<h3>Jadual Masa Respons</h3>
<table>
    <tr>
        <th>Keutamaan</th>
        <th>Masa Respons Pertama</th>
        <th>Sasaran Penyelesaian</th>
    </tr>
    <tr>
        <td>🔴 Kritikal</td>
        <td>1 jam</td>
        <td>4 jam</td>
    </tr>
    <tr>
        <td>🟠 Tinggi</td>
        <td>4 jam</td>
        <td>8 jam</td>
    </tr>
    <tr>
        <td>🔵 Sederhana</td>
        <td>8 jam</td>
        <td>24 jam</td>
    </tr>
    <tr>
        <td>🟢 Rendah</td>
        <td>24 jam</td>
        <td>48 jam</td>
    </tr>
</table>

<h3>Waktu Operasi</h3>
<p>Pasukan sokongan kami beroperasi pada:</p>
<ul>
    <li><strong>Isnin - Jumaat:</strong> 9:00 pagi - 6:00 petang</li>
    <li><strong>Sabtu:</strong> 9:00 pagi - 1:00 tengah hari</li>
    <li><strong>Ahad & Cuti Umum:</strong> Tutup</li>
</ul>

<h3>Sokongan Kecemasan</h3>
<p>Untuk isu kritikal di luar waktu operasi, tiket dengan keutamaan "Kritikal" akan dimaklumkan kepada pasukan on-call dan akan ditangani secepat mungkin.</p>

<h3>Faktor yang Mempengaruhi Masa Respons</h3>
<ul>
    <li>Kerumitan isu</li>
    <li>Kelengkapan maklumat yang diberikan</li>
    <li>Keperluan untuk melibatkan pihak ketiga</li>
    <li>Beban kerja semasa pasukan sokongan</li>
</ul>

<h3>Cara Mempercepatkan Penyelesaian</h3>
<ul>
    <li>Pilih keutamaan yang tepat</li>
    <li>Berikan maklumat lengkap dalam tiket</li>
    <li>Balas dengan segera apabila diminta maklumat tambahan</li>
    <li>Lampirkan tangkapan skrin atau bukti yang relevan</li>
</ul>
HTML;
    }

    private function getForgotPasswordArticleContent(): string
    {
        return <<<HTML
<h2>Memulihkan Akses Akaun - Lupa Kata Laluan</h2>
<p>Jika anda terlupa kata laluan, jangan risau! Anda boleh menetapkan semula kata laluan dengan mudah melalui e-mel.</p>

<h3>Langkah Menetapkan Semula Kata Laluan</h3>

<h4>Langkah 1: Akses Halaman Lupa Kata Laluan</h4>
<p>Di halaman log masuk, klik pautan "Lupa Kata Laluan?" atau "Forgot Password?"</p>

<h4>Langkah 2: Masukkan Alamat E-mel</h4>
<p>Masukkan alamat e-mel yang anda gunakan untuk mendaftar akaun.</p>

<h4>Langkah 3: Semak E-mel</h4>
<p>Semak peti masuk e-mel anda untuk e-mel tetapan semula kata laluan. E-mel ini mengandungi pautan khas untuk menetapkan kata laluan baru.</p>

<h4>Langkah 4: Klik Pautan</h4>
<p>Klik pautan dalam e-mel. Pautan ini sah untuk 60 minit sahaja.</p>

<h4>Langkah 5: Cipta Kata Laluan Baru</h4>
<p>Masukkan kata laluan baru anda dan sahkan. Pastikan kata laluan memenuhi keperluan keselamatan.</p>

<h4>Langkah 6: Log Masuk</h4>
<p>Setelah berjaya, anda boleh log masuk dengan kata laluan baru.</p>

<h3>E-mel Tidak Diterima?</h3>
<ul>
    <li>Semak folder spam/junk</li>
    <li>Pastikan alamat e-mel betul</li>
    <li>Tunggu beberapa minit dan cuba semula</li>
    <li>Hubungi sokongan jika masalah berterusan</li>
</ul>

<h3>Keselamatan</h3>
<ul>
    <li>Pautan tetapan semula hanya sah untuk 60 minit</li>
    <li>Setiap pautan hanya boleh digunakan sekali</li>
    <li>Jangan kongsi pautan dengan sesiapa</li>
</ul>
HTML;
    }

    private function getCancelTicketArticleContent(): string
    {
        return <<<HTML
<h2>Membatalkan atau Menutup Tiket</h2>
<p>Ada kalanya anda mungkin perlu membatalkan tiket yang telah dibuat atau menutup tiket yang telah selesai. Panduan ini menerangkan bila dan bagaimana untuk melakukannya.</p>

<h3>Bila Boleh Membatalkan Tiket?</h3>
<ul>
    <li>Isu telah selesai dengan sendirinya</li>
    <li>Tiket dibuat secara tidak sengaja</li>
    <li>Maklumat dalam tiket tidak lagi relevan</li>
    <li>Anda telah menemui penyelesaian sendiri</li>
</ul>

<h3>Cara Menutup Tiket</h3>
<ol>
    <li>Buka tiket yang ingin ditutup</li>
    <li>Tambah balasan menerangkan sebab penutupan</li>
    <li>Hubungi ejen untuk meminta tiket ditutup</li>
</ol>

<h3>Membuka Semula Tiket</h3>
<p>Jika isu berulang selepas tiket ditutup:</p>
<ol>
    <li>Buka tiket yang telah ditutup</li>
    <li>Klik "Buka Semula Tiket"</li>
    <li>Terangkan mengapa tiket perlu dibuka semula</li>
</ol>

<h3>Nota Penting</h3>
<ul>
    <li>Tiket yang ditutup masih boleh dilihat dalam sejarah</li>
    <li>Anda tidak boleh memadam tiket secara kekal</li>
    <li>Tiket boleh dibuka semula dalam tempoh 30 hari</li>
</ul>
HTML;
    }

    private function getLoginIssuesArticleContent(): string
    {
        return <<<HTML
<h2>Penyelesaian Masalah Log Masuk</h2>
<p>Menghadapi masalah untuk log masuk ke akaun anda? Panduan ini menyenaraikan masalah biasa dan penyelesaiannya.</p>

<h3>Masalah 1: Kata Laluan Salah</h3>
<p><strong>Gejala:</strong> Mesej "Kata laluan tidak sah" atau "Invalid password"</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Pastikan Caps Lock tidak aktif</li>
    <li>Cuba taip kata laluan dengan perlahan</li>
    <li>Gunakan fungsi "Tunjukkan Kata Laluan" untuk memeriksa</li>
    <li>Gunakan "Lupa Kata Laluan" jika perlu</li>
</ul>

<h3>Masalah 2: Akaun Dikunci</h3>
<p><strong>Gejala:</strong> Mesej "Akaun anda telah dikunci" selepas beberapa percubaan gagal</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Tunggu 30 minit sebelum mencuba semula</li>
    <li>Gunakan "Lupa Kata Laluan" untuk menetapkan semula</li>
    <li>Hubungi sokongan jika masalah berterusan</li>
</ul>

<h3>Masalah 3: E-mel Tidak Dikenali</h3>
<p><strong>Gejala:</strong> Mesej "E-mel tidak dijumpai" atau "Email not found"</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Pastikan anda menggunakan e-mel yang betul</li>
    <li>Semak ejaan alamat e-mel</li>
    <li>Cuba e-mel lain yang mungkin anda gunakan</li>
    <li>Daftar akaun baru jika belum mempunyai akaun</li>
</ul>

<h3>Masalah 4: Akaun Dinyahaktifkan</h3>
<p><strong>Gejala:</strong> Mesej "Akaun anda telah dinyahaktifkan"</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Hubungi pentadbir sistem</li>
    <li>Minta pengaktifan semula akaun</li>
</ul>

<h3>Masalah 5: Halaman Tidak Dimuatkan</h3>
<p><strong>Gejala:</strong> Halaman log masuk tidak dipaparkan atau lambat</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Semak sambungan internet anda</li>
    <li>Kosongkan cache pelayar</li>
    <li>Cuba pelayar lain</li>
    <li>Nyahaktifkan sambungan pelayar (extensions)</li>
</ul>

<h3>Masalah 6: Kod 2FA Tidak Diterima</h3>
<p><strong>Gejala:</strong> Kod pengesahan dua faktor ditolak</p>
<p><strong>Penyelesaian:</strong></p>
<ul>
    <li>Pastikan masa pada telefon anda tepat</li>
    <li>Tunggu kod baru (kod berubah setiap 30 saat)</li>
    <li>Gunakan kod pemulihan jika ada</li>
    <li>Hubungi sokongan untuk menyahaktifkan 2FA</li>
</ul>
HTML;
    }

    private function getEmailIssuesArticleContent(): string
    {
        return <<<HTML
<h2>E-mel Notifikasi Tidak Diterima</h2>
<p>Jika anda tidak menerima e-mel notifikasi dari sistem helpdesk, ikuti langkah-langkah penyelesaian berikut.</p>

<h3>Langkah 1: Semak Folder Spam/Junk</h3>
<p>E-mel dari sistem mungkin tersaring ke folder spam. Semak folder ini dan tandakan e-mel dari kami sebagai "Bukan Spam".</p>

<h3>Langkah 2: Tambah ke Senarai Putih</h3>
<p>Tambahkan alamat e-mel pengirim kami ke senarai kenalan atau senarai putih anda:</p>
<ul>
    <li>noreply@orien.com.my</li>
    <li>support@orien.com.my</li>
</ul>

<h3>Langkah 3: Semak Alamat E-mel</h3>
<p>Pastikan alamat e-mel dalam profil anda betul dan aktif. Pergi ke Profil untuk mengesahkan.</p>

<h3>Langkah 4: Semak Tetapan Notifikasi</h3>
<p>Pastikan notifikasi e-mel diaktifkan dalam tetapan akaun anda.</p>

<h3>Langkah 5: Semak Peti Masuk Penuh</h3>
<p>Jika peti masuk anda penuh, e-mel baru tidak dapat diterima. Kosongkan ruang dalam peti masuk anda.</p>

<h3>Langkah 6: Hubungi Pentadbir E-mel</h3>
<p>Jika anda menggunakan e-mel korporat, hubungi pentadbir IT anda untuk memastikan e-mel dari sistem kami tidak disekat.</p>

<h3>Jenis E-mel yang Dihantar</h3>
<ul>
    <li>Pengesahan pendaftaran</li>
    <li>Tetapan semula kata laluan</li>
    <li>Notifikasi tiket baru</li>
    <li>Notifikasi balasan tiket</li>
    <li>Notifikasi perubahan status</li>
    <li>Notifikasi penugasan</li>
</ul>
HTML;
    }

    private function getAttachmentIssuesArticleContent(): string
    {
        return <<<HTML
<h2>Penyelesaian Masalah Muat Naik Lampiran</h2>
<p>Jika anda menghadapi masalah memuat naik lampiran ke tiket, panduan ini akan membantu anda menyelesaikannya.</p>

<h3>Had Saiz Fail</h3>
<p>Setiap lampiran mempunyai had saiz maksimum:</p>
<ul>
    <li><strong>Imej:</strong> 5MB setiap fail</li>
    <li><strong>Dokumen:</strong> 10MB setiap fail</li>
    <li><strong>Arkib:</strong> 10MB setiap fail</li>
    <li><strong>Jumlah keseluruhan:</strong> 25MB setiap tiket</li>
</ul>

<h3>Format Fail yang Disokong</h3>
<ul>
    <li><strong>Imej:</strong> PNG, JPG, JPEG, GIF, BMP</li>
    <li><strong>Dokumen:</strong> PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</li>
    <li><strong>Teks:</strong> TXT, LOG, CSV</li>
    <li><strong>Arkib:</strong> ZIP, RAR</li>
</ul>

<h3>Masalah Biasa dan Penyelesaian</h3>

<h4>Fail Terlalu Besar</h4>
<ul>
    <li>Kompres imej menggunakan alat dalam talian</li>
    <li>Kompres fail ke format ZIP</li>
    <li>Bahagikan fail besar kepada beberapa bahagian</li>
    <li>Gunakan pautan perkongsian awan (Google Drive, Dropbox)</li>
</ul>

<h4>Format Tidak Disokong</h4>
<ul>
    <li>Tukar format fail ke format yang disokong</li>
    <li>Untuk fail EXE atau format berbahaya, sertakan dalam arkib ZIP</li>
</ul>

<h4>Muat Naik Gagal</h4>
<ul>
    <li>Semak sambungan internet</li>
    <li>Cuba fail yang lebih kecil untuk menguji</li>
    <li>Kosongkan cache pelayar</li>
    <li>Cuba pelayar lain</li>
</ul>

<h3>Alternatif untuk Fail Besar</h3>
<p>Untuk fail yang melebihi had saiz:</p>
<ol>
    <li>Muat naik ke perkhidmatan awan (Google Drive, OneDrive, Dropbox)</li>
    <li>Kongsi pautan dalam balasan tiket</li>
    <li>Pastikan pautan boleh diakses oleh pasukan sokongan</li>
</ol>
HTML;
    }

    private function getPrivacyPolicyArticleContent(): string
    {
        return <<<HTML
<h2>Polisi Privasi dan Perlindungan Data</h2>
<p>Kami komited untuk melindungi privasi dan data peribadi anda. Polisi ini menerangkan bagaimana kami mengumpul, menggunakan, dan melindungi maklumat anda.</p>

<h3>Maklumat yang Dikumpul</h3>
<p>Kami mengumpul maklumat berikut:</p>
<ul>
    <li><strong>Maklumat Akaun:</strong> Nama, e-mel, nombor telefon</li>
    <li><strong>Maklumat Tiket:</strong> Kandungan tiket, lampiran, perbualan</li>
    <li><strong>Maklumat Teknikal:</strong> Alamat IP, jenis pelayar, log akses</li>
    <li><strong>Maklumat Syarikat:</strong> Nama syarikat, alamat (jika diberikan)</li>
</ul>

<h3>Penggunaan Maklumat</h3>
<p>Maklumat anda digunakan untuk:</p>
<ul>
    <li>Menyediakan perkhidmatan sokongan</li>
    <li>Menghubungi anda berkaitan tiket</li>
    <li>Meningkatkan perkhidmatan kami</li>
    <li>Mematuhi keperluan undang-undang</li>
</ul>

<h3>Perlindungan Data</h3>
<p>Kami melaksanakan langkah-langkah keselamatan berikut:</p>
<ul>
    <li>Enkripsi data dalam transit (HTTPS/TLS)</li>
    <li>Enkripsi data dalam simpanan</li>
    <li>Kawalan akses berasaskan peranan</li>
    <li>Pemantauan keselamatan 24/7</li>
    <li>Sandaran data berkala</li>
</ul>

<h3>Perkongsian Data</h3>
<p>Kami TIDAK berkongsi data anda dengan pihak ketiga kecuali:</p>
<ul>
    <li>Dengan persetujuan anda</li>
    <li>Untuk mematuhi keperluan undang-undang</li>
    <li>Dengan penyedia perkhidmatan yang terikat dengan perjanjian kerahsiaan</li>
</ul>

<h3>Hak Anda</h3>
<p>Anda mempunyai hak untuk:</p>
<ul>
    <li>Mengakses data peribadi anda</li>
    <li>Membetulkan data yang tidak tepat</li>
    <li>Meminta pemadaman data</li>
    <li>Membantah pemprosesan data</li>
    <li>Memindahkan data anda</li>
</ul>

<h3>Tempoh Penyimpanan</h3>
<p>Data anda disimpan selama:</p>
<ul>
    <li><strong>Data akaun:</strong> Selagi akaun aktif + 2 tahun</li>
    <li><strong>Data tiket:</strong> 5 tahun selepas tiket ditutup</li>
    <li><strong>Log akses:</strong> 1 tahun</li>
</ul>

<h3>Hubungi Kami</h3>
<p>Untuk sebarang pertanyaan berkaitan privasi, hubungi Pegawai Perlindungan Data kami di privacy@orien.com.my</p>
HTML;
    }

    private function getTermsArticleContent(): string
    {
        return <<<HTML
<h2>Terma dan Syarat Penggunaan</h2>
<p>Dengan menggunakan sistem helpdesk Orien, anda bersetuju dengan terma dan syarat berikut.</p>

<h3>1. Definisi</h3>
<ul>
    <li><strong>"Sistem"</strong> merujuk kepada platform helpdesk Orien</li>
    <li><strong>"Pengguna"</strong> merujuk kepada individu yang menggunakan sistem</li>
    <li><strong>"Perkhidmatan"</strong> merujuk kepada sokongan teknikal yang disediakan</li>
</ul>

<h3>2. Kelayakan Penggunaan</h3>
<ul>
    <li>Anda mestilah berumur 18 tahun ke atas</li>
    <li>Anda mestilah mempunyai kuasa untuk mewakili organisasi anda (jika berkaitan)</li>
    <li>Maklumat yang diberikan mestilah tepat dan terkini</li>
</ul>

<h3>3. Tanggungjawab Pengguna</h3>
<ul>
    <li>Menjaga kerahsiaan kata laluan</li>
    <li>Menggunakan sistem untuk tujuan yang sah sahaja</li>
    <li>Tidak menyalahgunakan sistem atau mengganggu pengguna lain</li>
    <li>Melaporkan sebarang pelanggaran keselamatan</li>
</ul>

<h3>4. Penggunaan yang Dilarang</h3>
<ul>
    <li>Menghantar kandungan yang menyalahi undang-undang</li>
    <li>Menyebarkan virus atau perisian hasad</li>
    <li>Cuba mengakses akaun pengguna lain</li>
    <li>Menggunakan sistem untuk spam atau penipuan</li>
</ul>

<h3>5. Hak Harta Intelek</h3>
<p>Semua kandungan dalam sistem adalah hak milik Orien atau pemberi lesennya. Anda tidak boleh menyalin, mengubah, atau mengedar kandungan tanpa kebenaran.</p>

<h3>6. Had Liabiliti</h3>
<p>Orien tidak bertanggungjawab atas:</p>
<ul>
    <li>Kerugian tidak langsung atau berbangkit</li>
    <li>Gangguan perkhidmatan di luar kawalan kami</li>
    <li>Tindakan pihak ketiga</li>
</ul>

<h3>7. Penamatan</h3>
<p>Kami berhak untuk menangguhkan atau menamatkan akses anda jika anda melanggar terma ini.</p>

<h3>8. Perubahan Terma</h3>
<p>Kami mungkin mengemas kini terma ini dari semasa ke semasa. Perubahan akan dimaklumkan melalui e-mel atau notifikasi dalam sistem.</p>

<h3>9. Undang-undang yang Terpakai</h3>
<p>Terma ini tertakluk kepada undang-undang Malaysia.</p>
HTML;
    }

    private function getEscalationArticleContent(): string
    {
        return <<<HTML
<h2>Prosedur Eskalasi Tiket</h2>
<p>Jika isu anda memerlukan perhatian segera atau tidak dapat diselesaikan di peringkat sokongan biasa, prosedur eskalasi akan diaktifkan.</p>

<h3>Bila Eskalasi Berlaku?</h3>
<ul>
    <li>Tiket tidak diselesaikan dalam tempoh SLA</li>
    <li>Isu memerlukan kepakaran teknikal yang lebih tinggi</li>
    <li>Pelanggan meminta eskalasi</li>
    <li>Isu melibatkan berbilang sistem atau jabatan</li>
</ul>

<h3>Peringkat Eskalasi</h3>

<h4>Peringkat 1: Sokongan Barisan Hadapan</h4>
<p>Ejen sokongan biasa yang mengendalikan tiket anda. Kebanyakan isu diselesaikan di peringkat ini.</p>

<h4>Peringkat 2: Sokongan Teknikal</h4>
<p>Pakar teknikal dengan pengetahuan mendalam. Mengendalikan isu yang lebih kompleks.</p>

<h4>Peringkat 3: Pasukan Kejuruteraan</h4>
<p>Jurutera perisian yang boleh mengakses kod sumber dan membuat perubahan sistem.</p>

<h4>Peringkat 4: Pengurusan</h4>
<p>Pengurus dan eksekutif untuk isu kritikal yang memerlukan keputusan perniagaan.</p>

<h3>Cara Meminta Eskalasi</h3>
<ol>
    <li>Balas tiket anda dengan permintaan eskalasi</li>
    <li>Nyatakan sebab mengapa anda merasakan eskalasi diperlukan</li>
    <li>Berikan maklumat tambahan yang relevan</li>
</ol>

<h3>Apa yang Berlaku Selepas Eskalasi?</h3>
<ul>
    <li>Tiket akan ditugaskan kepada pakar peringkat lebih tinggi</li>
    <li>Anda akan dimaklumkan tentang perubahan penugasan</li>
    <li>Masa respons mungkin berbeza bergantung kepada kerumitan</li>
    <li>Anda mungkin dihubungi untuk maklumat tambahan</li>
</ul>

<h3>Eskalasi Automatik</h3>
<p>Sistem akan secara automatik mengeskala tiket jika:</p>
<ul>
    <li>Tiket tidak dibalas dalam tempoh SLA</li>
    <li>Tiket dibuka semula lebih dari 2 kali</li>
    <li>Pelanggan memberikan maklum balas negatif</li>
</ul>

<h3>Tips untuk Eskalasi yang Berkesan</h3>
<ul>
    <li>Berikan konteks lengkap tentang isu</li>
    <li>Nyatakan impak kepada perniagaan anda</li>
    <li>Senaraikan langkah yang telah dicuba</li>
    <li>Bersikap profesional dalam komunikasi</li>
</ul>
HTML;
    }
}
