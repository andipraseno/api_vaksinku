<?php

namespace App\Helpers;

class bilangan
{
    public static function terbilang($angka)
    {
        $angka = abs($angka);

        $bilangan = array(
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas"
        );

        $terbilang = "";

        if ($angka < 12) {
            $terbilang = $bilangan[$angka];
        } elseif ($angka < 20) {
            $terbilang = self::terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            $terbilang = self::terbilang($angka / 10) . " puluh " . self::terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = "seratus " . self::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = self::terbilang($angka / 100) . " ratus " . self::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = "seribu " . self::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = self::terbilang($angka / 1000) . " ribu " . self::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = self::terbilang($angka / 1000000) . " juta " . self::terbilang($angka % 1000000);
        } elseif ($angka < 1000000000000) {
            $terbilang = self::terbilang($angka / 1000000000) . " miliar " . self::terbilang(fmod($angka, 1000000000));
        } elseif ($angka < 1000000000000000) {
            $terbilang = self::terbilang($angka / 1000000000000) . " triliun " . self::terbilang(fmod($angka, 1000000000000));
        } elseif ($angka >= 1000000000000000) {
            $terbilang = "Angka terlalu besar";
        }

        return $terbilang;
    }

    public static function format_angka($num)
    {
        if (empty($num)) {
            return 0;
        }

        // Hilangkan koma (,) dari angka yang diformat gaya Amerika
        $angka = str_replace(',', '', $num);

        return is_numeric($angka) ? $angka : 0;
    }
}
