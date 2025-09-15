<?php

if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $searchTerm) {
        if (empty($searchTerm)) {
            return $text;
        }
        
        // Split search term into individual words
        $searchWords = explode(' ', trim($searchTerm));
        
        foreach ($searchWords as $word) {
            if (strlen($word) > 2) { // Only highlight words longer than 2 characters
                $text = preg_replace('/(' . preg_quote($word, '/') . ')/i', 
                                   '<mark style="background: rgba(102, 126, 234, 0.2); padding: 1px 3px; border-radius: 3px;">$1</mark>', 
                                   $text);
            }
        }
        
        return $text;
    }
}