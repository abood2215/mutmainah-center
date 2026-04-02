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

if (!function_exists('fmt_date_ar')) {
    /**
     * إظهار التاريخ بصيغة عربية واضحة (d-m-Y) مثل 31-03-2026
     */
    function fmt_date_ar(?string $date): string
    {
        if (!$date) return '—';
        try {
            return \Carbon\Carbon::createFromFormat('j-n-Y', $date)->format('d-m-Y');
        } catch (\Throwable) {
            // إذا كان ISO أو صيغة أخرى
            try {
                return \Carbon\Carbon::parse($date)->format('d-m-Y');
            } catch (\Throwable) {
                return $date;
            }
        }
    }
}
