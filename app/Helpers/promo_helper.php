<?php

if (!function_exists('hitung_biaya_jasa')) {
    function hitung_biaya_jasa($total_harga) {
        // Jika total harga <= 10.000.000 kena 1%, jika lebih kena 2%
        if ($total_harga <= 10000000) {
            return $total_harga * 0.01;
        } else {
            return $total_harga * 0.02;
        }
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code) {
        $persen = 0;
        $voucher_code = strtoupper(trim($voucher_code));

        if ($voucher_code === 'PROMO2025') {
            $persen = 10;
        } elseif ($voucher_code === 'PROMO2026') {
            $persen = 15;
        } elseif ($voucher_code === 'AKHIRTAHUN') {
            $persen = 25;
        }

        $nominal = $total_harga * ($persen / 100);

        return [
            'persen'  => $persen,
            'nominal' => $nominal
        ];
    }
}

if (!function_exists('hitung_free_mouse')) {
    function hitung_free_mouse($total_harga) {
        // Dapat hadiah potongan Rp 150.000 jika belanja >= 15.000.000
        if ($total_harga >= 15000000) {
            return 150000;
        }
        return 0;
    }
}