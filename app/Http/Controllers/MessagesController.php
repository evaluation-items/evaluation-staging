<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Str;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchResults = Messages::search('thank you')->get();
        return $searchResults->pluck('text');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $text = "Hello How can i assist you?";
        Messages::create([
            'text' => $text,
        ]);
        return dd('Text entered successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Messages $messages)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Messages $messages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Messages $messages)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Messages $messages)
    {
        //
    }
    // public function getResponse(Request $request)
    // {
    //     $userInput = $request->input('message');
    //     $language = $this->detectLanguage($userInput);

    //     $response = Messages::search($userInput)
    //         ->where('language', $language)
    //         ->first();
           

    //         return response()->json([
    //             'message' => $response ? $response->text : "Sorry, I don't understand."
    //         ]);
    // }

    // private function detectLanguage($text)
    // {
    //     if (preg_match('/[\p{Gujarati}]/u', $text)) {
    //         return 'gu';
    //     } elseif (preg_match('/[\p{Devanagari}]/u', $text)) {
    //         return 'hi';
    //     }
    //     return 'en';
    // }

  
    // public function translateMessage(Request $request)
    // {
    //     $userInput = $request->input('message');
    
    //     // Remove extra spaces and lower case the input for uniform matching
    //     $userInput = strtolower(trim($userInput));
    
    //     // Check if the user wants to translate text to Gujarati
    //     if (str_contains($userInput, 'convert') || str_contains($userInput, 'translate')) {
    //         // Extract the target language (Gujarati or Hindi) based on the input
    //         if (str_contains($userInput, 'to gujarati') || str_contains($userInput, 'in gujarati')) {
    //             // Strip out the "convert to gujarati" or "translate to gujarati" part from the input
    //             $text = preg_replace('/(convert to gujarati|translate to gujarati|in gujarati)/', '', $userInput);
    //             $translated = GoogleTranslate::trans($text, 'gu'); // Translate to Gujarati
    //             return response()->json([
    //                 'translated' => $translated,
    //                 'from' => 'en',
    //                 'to' => 'gu',
    //             ]);
    //         }
    
    //         if (str_contains($userInput, 'to hindi') || str_contains($userInput, 'in hindi')) {
    //             // Similarly handle Hindi translation
    //             $text = preg_replace('/(convert to hindi|translate to hindi|in hindi)/', '', $userInput);
    //             $translated = GoogleTranslate::trans($text, 'hi');
    //             return response()->json([
    //                 'translated' => $translated,
    //                 'from' => 'en',
    //                 'to' => 'hi',
    //             ]);
    //         }
    //     }
    
    //     // Optional: Fallback to database search if no translation request
    //     $language = $this->detectLanguage($userInput);
    //     $response = Messages::where('language', $language)
    //         ->where('text', 'LIKE', '%' . $userInput . '%')
    //         ->first();
    
    //     return response()->json([
    //         'translated' => $response->text ?? "Sorry, I don't understand.",
    //         'from' => $language,
    //         'to' => $language,
    //     ]);
    // }
    
    public function translateMessage(Request $request)
    {

        $userInput = strtolower($request->input('message'));
        $languages = [
            'gujarati' => 'gu',
            'hindi' => 'hi',
            'english' => 'en'
        ];

        // Step 1: Handle translation logic first
            $keywords = Messages::pluck('text')->toArray();
            foreach ($languages as $langName => $langCode) {
                foreach ($keywords as $keyword) {
                    $pattern = $keyword . ' ' . $langName;
                    if (Str::contains($userInput, $pattern)) {
                        $text = str_replace($pattern, '', $userInput);
                        $translated = GoogleTranslate::trans(trim($text), $langCode);

                        return response()->json([
                            'translated' => $translated,
                            'from' => 'auto',
                            'to' => $langCode,
                        ]);
                    }
                }
            }

            // Step 2: Fallback to keyword-based response
            $messageData = Messages::pluck('response', 'text')->toArray();
            foreach ($messageData as $dbKeyword => $dbResponse) {
                if (Str::contains($userInput, strtolower($dbKeyword))) {
                    return response()->json([
                        'translated' => $dbResponse,
                        'from' => 'bot',
                        'to' => 'user', 
                    ]);
                }
            }


        // --- Handle chart requests ---
        // This section remains completely unchanged as per your request.
        if (
            str_contains($userInput, 'chart') || str_contains($userInput, 'charts') ||
            str_contains($userInput, 'bar') || str_contains($userInput, 'pie') ||
            str_contains($userInput, 'value') || str_contains($userInput, 'values') ||
            str_contains($userInput, 'label') || str_contains($userInput, 'labels') ||
            str_contains($userInput, 'make chart')
        ) {
            // Decide chart type (bar or pie)
            $chartType = str_contains($userInput, 'pie') ? 'pie' : 'bar';

            // Extract values (numbers)
            preg_match_all('!\d+!', $userInput, $valueMatches);
            $values = $valueMatches[0];

            // Extract labels after keyword 'labels'
            $labelString = '';
            if (preg_match('/labels(?: is| are|:)?\s*(.*)/i', $userInput, $labelMatches)) {
                $labelString = $labelMatches[1];
            }

            // Split labels by comma or space
            $labels = preg_split('/[\s,]+/', trim($labelString));
            $labels = array_filter($labels); // Remove any empty values

            // Limit labels to match the number of values
            $labels = array_slice($labels, 0, count($values));

            // Return the chart response with labels and data
            return response()->json([
                'translated' => 'Here is your chart.',
                'chart' => [
                    'type' => $chartType,
                    'labels' => $labels,
                    'data' => $values,
                ]
            ]);
        }

        // Return a default response if no specific intent is detected
        return response()->json([
            'translated' => 'Sorry, I couldn\'t understand that request.',
        ]);
    




    //   //  $keywords = Messages::pluck('text')->toArray();

   
    //     if (
    //         str_contains($userInput, 'chart') || str_contains($userInput, 'charts') ||
    //         str_contains($userInput, 'bar') || str_contains($userInput, 'pie') ||
    //         str_contains($userInput, 'value') || str_contains($userInput, 'values') ||
    //         str_contains($userInput, 'label') || str_contains($userInput, 'labels') ||
    //         str_contains($userInput, 'make chart') ) {
    //         // Decide chart type (bar or pie)
    //         $chartType = str_contains($userInput, 'pie') ? 'pie' : 'bar';
    
    //         // Extract values (numbers)
    //         preg_match_all('!\d+!', $userInput, $valueMatches);
    //         $values = $valueMatches[0];
    
    //         // Extract labels after keyword 'labels'
    //         $labelString = '';
    //         if (preg_match('/labels(?: is| are|:)?\s*(.*)/i', $userInput, $labelMatches)) {
    //             $labelString = $labelMatches[1];
    //         }
    
    //         // Split labels by comma or space
    //         $labels = preg_split('/[\s,]+/', trim($labelString));
    //         $labels = array_filter($labels); // Remove any empty values
    
    //         // Limit labels to match the number of values
    //         $labels = array_slice($labels, 0, count($values));
    
    //         // Return the chart response with labels and data
    //         return response()->json([
    //             'translated' => 'Here is your chart.',
    //             'chart' => [
    //                 'type' => $chartType,
    //                 'labels' => $labels,
    //                 'data' => $values,
    //             ]
    //         ]);
    //     }
    
    //     // Return a default response if no chart-related input is detected
    //     return response()->json([
    //         'translated' => 'Sorry, I couldn\'t understand that request.',
    //     ]);
       
    }
    
}
