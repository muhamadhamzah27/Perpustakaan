<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admin ─────────────────────────────────────────────
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@perpustakaan.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'member_id' => 'ADMIN001',
            'status'    => 'active',
            'phone'     => '08111000001',
        ]);

        // ── 2. Sample Members ─────────────────────────────────────
        $members = [
            ['Budi Santoso',   'budi@email.com',   '08112345678', 'Jl. Merdeka No. 1, Jakarta'],
            ['Siti Rahayu',    'siti@email.com',   '08123456789', 'Jl. Pahlawan No. 5, Bandung'],
            ['Ahmad Fauzi',    'ahmad@email.com',  '08134567890', 'Jl. Sudirman No. 10, Surabaya'],
            ['Dewi Lestari',   'dewi@email.com',   '08145678901', 'Jl. Diponegoro No. 3, Yogyakarta'],
            ['Rizky Pratama',  'rizky@email.com',  '08156789012', 'Jl. Gatot Subroto No. 7, Semarang'],
        ];

        foreach ($members as $i => [$name, $email, $phone, $address]) {
            User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make('password'),
                'role'      => 'member',
                'member_id' => 'LIB' . date('Y') . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'status'    => 'active',
                'phone'     => $phone,
                'address'   => $address,
            ]);
        }

        // ── 3. Categories ─────────────────────────────────────────
        $categories = [
            ['Fiksi',          'Novel, cerita pendek, dan karya fiksi lainnya',    '#6366f1'],
            ['Non-Fiksi',      'Buku fakta, memoar, dan karya non-fiksi',          '#0ea5e9'],
            ['Sains & Teknologi','Buku ilmu pengetahuan dan teknologi terkini',    '#10b981'],
            ['Sejarah',        'Buku sejarah Indonesia dan dunia',                 '#f59e0b'],
            ['Pendidikan',     'Buku teks dan referensi akademik',                 '#8b5cf6'],
            ['Agama',          'Buku keagamaan dan spiritual',                     '#ec4899'],
            ['Bisnis',         'Buku ekonomi, bisnis, dan kewirausahaan',          '#f97316'],
            ['Anak-anak',      'Buku cerita dan edukasi untuk anak',               '#14b8a6'],
        ];

        foreach ($categories as [$name, $desc, $color]) {
            Category::create([
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => $desc,
                'color'       => $color,
            ]);
        }

        // ── 4. Books ──────────────────────────────────────────────
        $books = [
            // Fiksi (cat 1)
            ['Laskar Pelangi', 'Andrea Hirata', '978-979-1234-01-1', 1, 'Bentang Pustaka', 2005, 3, 'A-01', 'Novel tentang perjuangan anak-anak miskin di Pulau Belitung untuk mendapatkan pendidikan.', 529],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', '978-979-1234-02-2', 1, 'Lentera Dipantara', 1980, 2, 'A-02', 'Novel sejarah berlatar kolonialisme Belanda di Indonesia awal abad 20.', 535],
            ['Ronggeng Dukuh Paruk', 'Ahmad Tohari', '978-979-1234-03-3', 1, 'Gramedia', 1982, 2, 'A-03', 'Kisah cinta dan penderitaan seorang ronggeng di sebuah dukuh terpencil.', 384],
            ['Negeri 5 Menara', 'Ahmad Fuadi', '978-979-1234-04-4', 1, 'Gramedia', 2009, 3, 'A-04', 'Kisah inspiratif enam santri pesantren yang bermimpi meraih dunia.', 419],
            // Non-Fiksi (cat 2)
            ['Sapiens: Riwayat Singkat Umat Manusia', 'Yuval Noah Harari', '978-979-1234-05-5', 2, 'KPG', 2015, 2, 'B-01', 'Eksplorasi sejarah umat manusia dari Zaman Batu hingga era modern.', 512],
            ['Atomic Habits', 'James Clear', '978-979-1234-06-6', 2, 'Gramedia', 2018, 3, 'B-02', 'Panduan praktis membangun kebiasaan baik dan menghilangkan kebiasaan buruk.', 320],
            // Sains (cat 3)
            ['A Brief History of Time', 'Stephen Hawking', '978-979-1234-07-7', 3, 'Bantam Books', 1988, 2, 'C-01', 'Penjelasan ilmiah tentang alam semesta untuk pembaca awam.', 212],
            ['Sapiens: Sains Data', 'Various', '978-979-1234-08-8', 3, 'Elex Media', 2020, 2, 'C-02', 'Pengenalan ilmu data dan kecerdasan buatan untuk pemula.', 280],
            // Sejarah (cat 4)
            ['Sejarah Indonesia Modern', 'M.C. Ricklefs', '978-979-1234-09-9', 4, 'Gadjah Mada UP', 1981, 2, 'D-01', 'Sejarah lengkap Indonesia dari zaman kolonial hingga era reformasi.', 660],
            ['Indonesia dalam Arus Sejarah', 'Taufik Abdullah', '978-979-1234-10-0', 4, 'Ichtiar Baru', 2012, 1, 'D-02', 'Kompilasi sejarah Indonesia dari para sejarawan terkemuka.', 800],
            // Pendidikan (cat 5)
            ['Belajar Pemrograman Python', 'Eko Kurniawan', '978-979-1234-11-1', 5, 'Elex Media', 2021, 3, 'E-01', 'Panduan lengkap belajar Python dari dasar hingga tingkat lanjut.', 450],
            ['Matematika Dasar untuk Universitas', 'Prof. Dr. Soedijarto', '978-979-1234-12-2', 5, 'UI Press', 2015, 4, 'E-02', 'Buku teks matematika dasar untuk mahasiswa semester pertama.', 380],
            // Agama (cat 6)
            ['Fikih Islam Lengkap', 'Sulaiman Rasjid', '978-979-1234-13-3', 6, 'Sinar Baru', 1954, 3, 'F-01', 'Panduan fikih Islam yang komprehensif dan mudah dipahami.', 480],
            // Bisnis (cat 7)
            ['Rich Dad Poor Dad', 'Robert Kiyosaki', '978-979-1234-14-4', 7, 'Gramedia', 1997, 3, 'G-01', 'Pelajaran tentang keuangan dan investasi dari dua perspektif berbeda.', 336],
            ['Zero to One', 'Peter Thiel', '978-979-1234-15-5', 7, 'Crown Business', 2014, 2, 'G-02', 'Catatan tentang startup dan cara membangun masa depan.', 224],
            // Anak (cat 8)
            ['Si Kancil dan Cerita Nusantara', 'Various', '978-979-1234-16-6', 8, 'Balai Pustaka', 2018, 5, 'H-01', 'Kumpulan cerita rakyat Nusantara untuk anak-anak.', 200],
        ];

        foreach ($books as [$title, $author, $isbn, $catId, $publisher, $year, $copies, $shelf, $desc, $pages]) {
            Book::create([
                'title'            => $title,
                'author'           => $author,
                'isbn'             => $isbn,
                'category_id'      => $catId,
                'publisher'        => $publisher,
                'publish_year'     => $year,
                'total_copies'     => $copies,
                'available_copies' => $copies,
                'shelf_location'   => $shelf,
                'description'      => $desc,
                'pages'            => $pages,
                'language'         => 'Indonesia',
            ]);
        }

        // ── 5. Sample Loans ───────────────────────────────────────
        $membersDb = User::where('role', 'member')->get();
        $admin     = User::where('role', 'admin')->first();
        $allBooks  = Book::all();

        // Active loan
        $book1 = $allBooks->find(1);
        Loan::create([
            'user_id'      => $membersDb[0]->id,
            'book_id'      => $book1->id,
            'processed_by' => $admin->id,
            'loan_date'    => Carbon::today()->subDays(3),
            'due_date'     => Carbon::today()->addDays(4),
            'status'       => 'active',
        ]);
        $book1->decrement('available_copies');

        // Overdue loan
        $book2 = $allBooks->find(5);
        Loan::create([
            'user_id'      => $membersDb[1]->id,
            'book_id'      => $book2->id,
            'processed_by' => $admin->id,
            'loan_date'    => Carbon::today()->subDays(14),
            'due_date'     => Carbon::today()->subDays(7),
            'status'       => 'overdue',
            'fine_amount'  => 7000,
        ]);
        $book2->decrement('available_copies');

        // Returned loan
        $book3 = $allBooks->find(6);
        Loan::create([
            'user_id'      => $membersDb[2]->id,
            'book_id'      => $book3->id,
            'processed_by' => $admin->id,
            'loan_date'    => Carbon::today()->subDays(20),
            'due_date'     => Carbon::today()->subDays(13),
            'return_date'  => Carbon::today()->subDays(14),
            'status'       => 'returned',
            'fine_amount'  => 0,
            'fine_paid'    => true,
        ]);
    }
}
