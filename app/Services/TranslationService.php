<?php

namespace App\Services;

class TranslationService
{
    /**
     * Translate message into the target language.
     */
    public function translate(string $message, string $language): string
    {
        if ($language !== 'Swahili') {
            return $message;
        }

        $normalized = $this->normalize($message);

        // Load the translations from the JSON file
        $translations = json_decode(file_get_contents(base_path('lang/sw.json')), true);

        if (isset($translations[$normalized])) {
            return $translations[$normalized];
        }

        return '[Swahili] '.$message;
    }

    /**
     * Normalize string for dictionary lookup.
     */
    protected function normalize(string $message): string
    {
        // Lowercase, trim, and remove all punctuation
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9\s]/', '', (string) $message)));
    }
}
