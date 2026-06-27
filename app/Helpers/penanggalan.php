<?php

namespace App\Helpers;

use Carbon\Carbon;

class penanggalan
{
    public static function angka_to_nama_bulan($int_bulan)
    {
        $bulan = [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember"
        ];

        return $bulan[$int_bulan - 1];
    }

    public static function format_tanggal($tanggal)
    {
        if (empty($tanggal)) {
            return null;
        } else {
            list($hari, $bulan, $tahun) = explode("/", $tanggal);
            $dateObj = Carbon::createFromDate($tahun, $bulan, $hari);

            return $dateObj->format('Y-m-d');
        }
    }

    public static function greeting()
    {
        $jam = date('H');

        $hasil = $jam;

        if ($jam >= 3 && $jam <= 10) {
            $hasil = 'Pagi';
        } else if ($jam > 10 && $jam <= 15) {
            $hasil = 'Siang';
        } else if ($jam > 15 && $jam <= 19) {
            $hasil = 'Sore';
        } else {
            $hasil = 'Malam';
        }

        return $hasil;
    }
}
