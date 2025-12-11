<?php

if (!function_exists('format_ph_date')) {
    /**
     * Format date in Philippine timezone
     * 
     * @param string $dateString Date string from database
     * @param string $format Date format (default: 'M d, Y g:i A')
     * @return string Formatted date string
     */
    function format_ph_date($dateString, $format = 'M d, Y g:i A')
    {
        if (empty($dateString)) {
            return 'N/A';
        }
        
        try {
            // Create DateTime object, assume UTC from database
            $date = new \DateTime($dateString, new \DateTimeZone('UTC'));
            // Convert to Philippine timezone
            $date->setTimezone(new \DateTimeZone('Asia/Manila'));
            return $date->format($format);
        } catch (\Exception $e) {
            // Fallback to simple date formatting
            return date($format, strtotime($dateString));
        }
    }
}

if (!function_exists('format_ph_date_short')) {
    /**
     * Format date in Philippine timezone (short format)
     * 
     * @param string $dateString Date string from database
     * @return string Formatted date string
     */
    function format_ph_date_short($dateString)
    {
        return format_ph_date($dateString, 'M d, Y');
    }
}
