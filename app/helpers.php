<?php

if (!function_exists('fmt_date')) {
    /**
     * تحويل تاريخ قاعدة البيانات (j-n-Y) إلى صيغة (Y-m-d)
     */
    function fmt_date(?string $date): string
    {
        if (!$date) return '—';
        try {
            return \Carbon\Carbon::createFromFormat('j-n-Y', $date)->format('Y-m-d');
        } catch (\Throwable) {
            return $date;
        }
    }
}
