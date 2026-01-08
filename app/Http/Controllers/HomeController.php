<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Couchdb\Couchdb;
use App\Models\DigitalProjectLibrary;
use Illuminate\Support\Facades\Crypt;


class HomeController extends Controller
{

    public $envirment;
    public function __construct(){
        $this->envirment = environmentCheck();
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function contactUs()
    {
        return view('contact-us');
    }
    public function publicationfront()
    {
        //$list = Publication::all();
        $list = DigitalProjectLibrary::all();
        $dept_list = [];
        return view('publication_front_page',compact('list','dept_list'));
    }
    public function departmentPublication($id)
    {
        $list = [];
        $dept_list = DigitalProjectLibrary::where('dept_id',$id)->get();
        return view('publication_front_page',compact('dept_list','list'));
    }
    public function getthepublicationdocument($id,$document) {
       
        try {

            $id = 'upload_file_'.Crypt::decrypt($id);
            $extended = new Couchdb();
            $extended->InitConnection();
            $status = $extended->isRunning();
            $out = $extended->getDocument($this->envirment['database'],$id);
            $arrays = json_decode($out, true);
            if(isset($arrays)) {
                $attachments = $arrays['_attachments'];
            } else {
                return "no data";
            }
            foreach($attachments as $attachment_name => $attachment) {
                $at_name[] = $attachment_name;
            }
            if(count($at_name) > 0) {
                foreach($at_name as $atkey=>$atvalue) {
                    if(strpos($atvalue,$document) !== false) {
                        $cont = file_get_contents($this->envirment['url'].$id."/".$atvalue);                   
                        if($cont) {
                            return response($cont)->withHeaders(['Content-type'=>'application/pdf']);
                        } else {
                            return "Error fetching the document. Contact NIC";
                        }
                    }
                }
            } else {
                return 'Document not found !';
            }
        
            return response("Document not found!", 404);
        } catch (Exception $e) {
            return response("Error: " . $e->getMessage(), 500);
        }
        
    }
    
}
