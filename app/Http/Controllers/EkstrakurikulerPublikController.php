<?php

namespace App\Http\Controllers;

use App\Models\EkstrakurikulerPublik;
use App\Support\RoleDatabase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EkstrakurikulerPublikController extends Controller
{
    public function index(): View
    {
        $extracurriculars = $this->query()->orderBy('nama')->orderBy('id_ekstra')->get();

        return view('pages.student-life.extracurriculars', compact('extracurriculars'));
    }

    public function image(string $ekskul): StreamedResponse
    {
        $publik = $this->query()->where('id_ekstra', $ekskul)->firstOrFail();
        $disk = Storage::disk('local');
        abort_unless($publik->hasManagedImage() && $disk->exists($publik->gambar_path), 404);

        return $disk->response($publik->gambar_path, headers: ['Cache-Control' => 'no-store']);
    }

    private function query(): Builder
    {
        // The view filters published rows in both RBAC and legacy connection mode.
        return EkstrakurikulerPublik::on(RoleDatabase::public() ?? DB::getDefaultConnection())
            ->from('v_publik_ekstra');
    }
}
