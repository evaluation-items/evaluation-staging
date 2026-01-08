<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;

class draftComposer
{
    protected static $draft_ids = [];

    public static function setDraftId($draft_ids)
    {
        self::$draft_ids = $draft_ids;
    }

    public static function encryptDraftId()
    {
        // Retrieve the draft_ids
        $draft_ids = self::$draft_ids;
      
        // Add your encryption logic here if needed
        return ['draft_ids' => $draft_ids];
    }

    public function compose(View $view)
    {
        // Encrypt the draft IDs
        $draft_ids = self::encryptDraftId();
       // dd($draft_ids);
    
        // Share the draft IDs with the view
        $view->with('draft_ids', $draft_ids);
    }
}