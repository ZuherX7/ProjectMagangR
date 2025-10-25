<?php

if (!function_exists('generateAutoTags')) {
    /**
     * Generate automatic tags from document data
     * 
     * @param string $judul Document title
     * @param string $menuName Menu name
     * @param string $kategoriName Category name
     * @return string Comma-separated tags
     */
    function generateAutoTags($judul, $menuName = '', $kategoriName = '') 
    {
        $tags = [];
        
        // 1. Extract from judul (filter common words)
        $stopWords = ['dan', 'atau', 'untuk', 'dari', 'ke', 'di', 'pada', 'yang', 'adalah', 'dengan', 'oleh', 'tentang', 'akan', 'telah', 'sudah', 'dokumen', 'file'];
        
        // Clean and split title
        $titleWords = preg_split('/[\s\-_]+/', strtolower($judul));
        foreach ($titleWords as $word) {
            $word = trim($word);
            // Only add if: 
            // - length >= 3 characters
            // - not a number only
            // - not in stopwords
            if (strlen($word) >= 3 && !is_numeric($word) && !in_array($word, $stopWords)) {
                $tags[] = $word;
            }
        }
        
        // 2. Add menu name (cleaned)
        if (!empty($menuName)) {
            $menuWords = preg_split('/[\s\-_]+/', strtolower($menuName));
            foreach ($menuWords as $word) {
                $word = trim($word);
                if (strlen($word) >= 3 && !in_array($word, $stopWords)) {
                    $tags[] = $word;
                }
            }
        }
        
        // 3. Add category name (cleaned)
        if (!empty($kategoriName)) {
            $kategoriWords = preg_split('/[\s\-_]+/', strtolower($kategoriName));
            foreach ($kategoriWords as $word) {
                $word = trim($word);
                if (strlen($word) >= 3 && !in_array($word, $stopWords)) {
                    $tags[] = $word;
                }
            }
        }
        
        // 4. Extract year if exists (2020-2030)
        if (preg_match('/\b(20[2-3][0-9])\b/', $judul, $matches)) {
            $tags[] = $matches[1];
        }
        
        // 5. Remove duplicates and limit to 8 tags
        $tags = array_unique($tags);
        $tags = array_slice($tags, 0, 8);
        
        return implode(', ', $tags);
    }
}

if (!function_exists('cleanTagInput')) {
    /**
     * Clean user input tags
     * 
     * @param string $tags Raw tag input
     * @return string Cleaned comma-separated tags
     */
    function cleanTagInput($tags)
    {
        if (empty($tags)) return '';
        
        // Split by comma, trim, lowercase, remove empty
        $tagArray = array_filter(array_map(function($tag) {
            return trim(strtolower($tag));
        }, explode(',', $tags)));
        
        // Remove duplicates and limit to 10 tags
        $tagArray = array_unique(array_slice($tagArray, 0, 10));
        
        return implode(',', $tagArray);
    }
}

if (!function_exists('getSuggestedTags')) {
    /**
     * Get suggested tags from database based on menu and category
     * 
     * @param int $menuId Menu ID
     * @param int $kategoriId Category ID
     * @return array Array of suggested tags
     */
    function getSuggestedTags($menuId, $kategoriId)
    {
        $db = \Config\Database::connect();
        
        // Get tags from documents in same menu/category
        $query = $db->query("
            SELECT tags 
            FROM dokumen 
            WHERE (menu_id = ? OR kategori_id = ?) 
            AND tags IS NOT NULL 
            AND tags != ''
            LIMIT 20
        ", [$menuId, $kategoriId]);
        
        $allTags = [];
        foreach ($query->getResult() as $row) {
            if (!empty($row->tags)) {
                $tags = explode(',', $row->tags);
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        $allTags[] = $tag;
                    }
                }
            }
        }
        
        // Count frequency and return top 10
        $tagCounts = array_count_values($allTags);
        arsort($tagCounts);
        
        return array_slice(array_keys($tagCounts), 0, 10);
    }
}