<?php

use Illuminate\Database\Eloquent\Collection;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// function getLanguageRules($fieldPrefix1=null,$fieldPrefix2=null,$fieldPrefix3=null, $fieldType=null)
//{
//    $supportedLanguages = LaravelLocalization::getSupportedLocales();
//    $rules = [];
//
//    foreach ($supportedLanguages as $localeCode => $properties) {
//        $fieldPrefix1 !=null ? $rules[$fieldPrefix1 . '_' . $localeCode] = 'required|' . $fieldType: null;
//         $fieldPrefix2 !=null ? $rules[$fieldPrefix2 . '_' . $localeCode] = 'required|' . $fieldType: null;
//         $fieldPrefix3 !=null ? $rules[$fieldPrefix3 . '_' . $localeCode] = 'required|' . $fieldType: null;
//    }
//
//    return $rules;
//}

function getRulesForLanguage(array $prfixs, array $fildtypes)
{

    $supportedLanguages = LaravelLocalization::getSupportedLocales();
    $rules = [];

    foreach ($supportedLanguages as $localeCode => $properties) {
        foreach ($prfixs as $prfix) {
            foreach ($fildtypes as $fildtype) {
                $rules[$prfix . '_' . $localeCode] = 'required|' . $fildtype;
            }
        }
    }

    return $rules;
}
function getRulesForLanguageV2(array $prfixs, array $fildtypes)
{
    // Get supported locales from the Laravel Localization package
    $supportedLanguages = LaravelLocalization::getSupportedLocales();

    // Get the languages from the Accept-Language header
    $acceptedLanguages = request()->getLanguages(); // This will return an array of language codes

    $rules = [];

    foreach ($supportedLanguages as $localeCode => $properties) {
        // Apply rules only for languages in the Accept-Language header
        if (in_array($localeCode, $acceptedLanguages)) {
            foreach ($prfixs as $prfix) {
                foreach ($fildtypes as $fildtype) {
                    // Add the 'required' rule only for accepted languages
                    $rules[$prfix . '_' . $localeCode] = 'nullable|' . $fildtype;
                }
            }
        } else {
            foreach ($prfixs as $prfix) {
                foreach ($fildtypes as $fildtype) {
                    // For non-accepted languages, you may choose to leave out the 'required' rule
                    $rules[$prfix . '_' . $localeCode] = 'nullable|' . $fildtype;
                }
            }
        }
    }

    return $rules;
}




function storeLanguage(array $prefixes, array $requestData)
{
    $supportedLanguages = LaravelLocalization::getSupportedLocales();
    $data = [];

    foreach ($supportedLanguages as $localeCode => $properties) {
        foreach ($prefixes as $prefix) {
            $fieldName = $prefix . '_' . $localeCode;

            $data[$localeCode][$prefix] = $requestData[$fieldName] ?? null;
        }
    }

    return $data;
}
function getTranslation(string $field, $language,  $model)
{
    if ($language == null) {
        $language = 'en';
    }
    if (isset($model)) {
        if ($model->has('translations') && $model->translations) {
            $translation = $model->translations->where('locale', $language)->first();
            if ($translation) {
                return $translation->$field;
            } else {
                $fallbackTranslation = $model->translations->where('locale', '!=', $language)->first();
                if ($fallbackTranslation) {
                    return $fallbackTranslation->$field;
                }
                return $model->translations->where('locale', 'en')->first()->$field ?? null;
            }
        }
    }

    return null;
}
function getTranslationAndLocale(Collection $translations, string $field): array
{
    $titles = [];
    foreach ($translations as $translation) {
        $titles[] = [
            'locale' => $translation?->locale ?? "",
            $field => $translation?->$field ?? "",
        ];
    }

    return $titles;
}
function getLocalizedRules(array $columns): array
{
    $rules = [];

    foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
        foreach ($columns as $column) {
            $rules["{$column}_{$localeCode}"] = "required";
        }
    }

    return $rules;
}
// function getTranslation($field, $language,$model)
// {
//     if($language==null){
//         $language = 'en';
//     }
//     if (isset($model)){
//         if ($model->has('translations') && $model->translations) {
//             $translation = $model->translations->where('locale', $language)->first();
//             return $translation ? $translation->$field : $model->translations->where('locale', 'en')->first()->$field ?? null;
//         }
//     }
//         return null;
// }


function getNewTranslation($field, $language, $model, $filteredKey)
{

    if ($language == null) {
        $language = 'en';
    }
    if (isset($model)) {
        if (isset($model->translation)) {
            $translation = $model->translation->where('locale', $language)->where($filteredKey, $model->id)->first();
            return $translation ? $translation->$field : null;
        }
    }
    return null;
}


function getFinalField($field, $language, $model, $filteredKey)
{
    $locale_codes = config('translatable.locales');
    $translatedField = getNewTranslation(field: $field, language: $language, model: $model, filteredKey: $filteredKey);
    if ($translatedField == null) {
        foreach ($locale_codes as  $locale) {
            $translatedField = getNewTranslation(field: $field, language: $locale, model: $model, filteredKey: $filteredKey);
            if ($translatedField !== null) {
                break;
            }
        }
    }
    return $translatedField ?? '';
}

function getAcceptedLanguages(): array|string
{
    return request()->header('Accept-Language') ?? "en";

}

function getAllTranslation($field, $model)
{

    $titles = [];
    $locales = [];
    if (isset($model->translations)) {
        foreach ($model->translations as $translation) {
            $titles[] = [
                'locale' => $translation->locale,
                $field => $translation->$field,
            ];
            $locales[$translation->locale] = $translation->locale;
        }
    }

    return $titles;
}
