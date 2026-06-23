<?php

namespace App\Http\Controllers;

class LocaleController extends Controller
{
    /**
     * Ganti bahasa situs lalu kembali ke halaman sebelumnya.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
