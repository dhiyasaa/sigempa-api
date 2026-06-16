<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $data = Berita::latest()->get();

        return view('admin.berita', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $meta = $this->ambilMetaBerita($request->link);

        Berita::create([
            'link' => $request->link,
            'judul' => $meta['judul'],
            'sumber' => $meta['sumber'],
            'tanggal' => $meta['tanggal'],
            'ringkasan' => $meta['ringkasan'],
            'gambar' => $meta['gambar'],
        ]);

        return redirect()->back()->with('success', 'Berita berhasil ditambahkan dari link!');
    }

    public function delete($id)
    {
        Berita::find($id)?->delete();

        return redirect()->back()->with('success', 'Berita berhasil dihapus!');
    }

    public function api()
    {
        return Berita::latest()->get();
    }

    private function ambilMetaBerita($url)
    {
        $default = [
            'judul' => $url,
            'sumber' => parse_url($url, PHP_URL_HOST),
            'tanggal' => now()->format('d M Y'),
            'ringkasan' => 'Baca berita lengkap melalui tautan sumber.',
            'gambar' => null,
        ];

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0'
                ])
                ->get($url);

            if (!$response->successful()) {
                return $default;
            }

            $html = $response->body();

            $judul = $this->ambilMetaContent($html, 'property="og:title"')
                ?? $this->ambilMetaContent($html, 'name="title"')
                ?? $this->ambilTitleTag($html)
                ?? $default['judul'];

            $ringkasan = $this->ambilMetaContent($html, 'property="og:description"')
                ?? $this->ambilMetaContent($html, 'name="description"')
                ?? $default['ringkasan'];

            $gambar = $this->ambilMetaContent($html, 'property="og:image"')
                ?? null;

            $sumber = $this->ambilMetaContent($html, 'property="og:site_name"')
                ?? parse_url($url, PHP_URL_HOST);

            $tanggal = $this->ambilMetaContent($html, 'property="article:published_time"')
                ?? $this->ambilMetaContent($html, 'name="pubdate"')
                ?? now()->format('d M Y');

            return [
                'judul' => trim(strip_tags($judul)),
                'sumber' => trim(strip_tags($sumber)),
                'tanggal' => trim(strip_tags($tanggal)),
                'ringkasan' => trim(strip_tags($ringkasan)),
                'gambar' => $gambar,
            ];

        } catch (\Exception $e) {
            return $default;
        }
    }

    private function ambilMetaContent($html, $attribute)
    {
        $pattern = '/<meta[^>]*' . preg_quote($attribute, '/') . '[^>]*content=["\']([^"\']*)["\'][^>]*>/i';

        if (preg_match($pattern, $html, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $patternReverse = '/<meta[^>]*content=["\']([^"\']*)["\'][^>]*' . preg_quote($attribute, '/') . '[^>]*>/i';

        if (preg_match($patternReverse, $html, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }

    private function ambilTitleTag($html)
    {
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches)) {
            return html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }
}