<?php

/**
 * Hitung diskon berdasarkan total pembelian
 */
if (!function_exists('hitung_diskon')) {
    function hitung_diskon($total_harga)
    {
        $persen = 0;

        if ($total_harga >= 50000000) {
            $persen = 20;
        } elseif ($total_harga >= 25000000) {
            $persen = 12;
        } elseif ($total_harga >= 15000000) {
            $persen = 7;
        } elseif ($total_harga >= 5000000) {
            $persen = 3;
        } else {
            $persen = 0;
        }

        $nominal_diskon = ($persen / 100) * $total_harga;

        return [
            'persen'  => $persen,
            'nominal' => $nominal_diskon
        ];
    }
}