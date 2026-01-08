<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Activitylog;
use App\Models\Department;
use Illuminate\Http\Request;
use Exception;
use Validator;
use App\Couchdb\Couchdb;
use Illuminate\Support\Facades\Crypt;
use Auth;


class PublicationController extends Controller
{
    public $envirment;
    public function __construct(){
        $this->envirment = environmentCheck();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Publication::all();
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();

        return view('dashboards.admins.publication_branch.index',compact('list','departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'dept_id' => 'required',
                'branch_name' => 'required',
                'year' => 'required|string',  // Ensure it's treated as a string
                'pdf_document.*' => 'required|mimes:pdf'
            ]);
        
            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate->errors());
            } else {
                $input = $request->all();
                $input['dept_id'] = $request->dept_id;
                $input['branch_name'] = $request->branch_name;
                $input['year'] = (string) $request->year;  // Ensure it's saved as a string
    

                if($request->hasFile('pdf_document')) {
                    $upload_file = $request->file('pdf_document');
                    $extended = new Couchdb();
                    $extended->InitConnection();
                    $status = $extended->isRunning();
                    // foreach($upload_file as $key => $file) {
                        $val = random_int(100000, 999999);
                        $doc_id = "publication_branch_document_".$val;
                        $docid = 'document'; 
                        $path['id'] = $docid;
                        $path['tmp_name'] = $upload_file->getRealPath();
                        $path['extension']  = $upload_file->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                      
                        $dummy_data = array(
                            'publication_branch_document_' => $doc_id
                        );
                        $d_out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                        $array1 = json_decode($d_out, true);
                        $id = $array1['id'];
                        $rev = $array1['rev'];
                        
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                  //  }
                    $input['pdf_document'] =  $path['name']; 
                }

                Publication::create($input);
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Publication Store By Admin';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                return redirect()->back()->withSuccess('Publication branch name added successfully');
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
        
      
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $publication = Publication::find($id);
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        return view('dashboards.admins.publication_branch.edit',compact('publication','departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        try {
            $validate = Validator::make($request->all(), [
                'dept_id' => 'required',
                'branch_name' => 'required',
                'year' => 'required|string', // Ensure it's treated as a string
                'pdf_document' => $request->hasFile('pdf_document') ? 'required|mimes:pdf' : '', // Only required if a new file is uploaded
            ]);
        
            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate->errors());
            }
        
            $input = $request->all();
            $id = Crypt::decrypt($id);
          
            $publication = Publication::find($id);
          
            // Updating fields if they exist
            if (isset($request->branch_name)) {
                $input['branch_name'] = $request->branch_name;
            }
            if (isset($request->dept_id)) {
                $input['dept_id'] = $request->dept_id;
            }
            if (isset($request->year)) {
                $input['year'] = $request->year;
            }
        
            $extended = new Couchdb();
            $extended->InitConnection();
        
            if ($request->hasFile('pdf_document')) {
                $upload_file = $request->file('pdf_document');
        
                // If a previous document exists, delete it from CouchDB
                if (!is_null($publication->pdf_document) && $extended->isRunning()) {
                    $delete_doc_id = "publication_branch_document_" . random_int(100000, 999999);
                    $document = json_decode($extended->getDocument($this->envirment['database'], $delete_doc_id), true);
        
                    if (is_array($document) && isset($document['_rev'])) {
                        $delete_response = json_decode($extended->deleteDocument($this->envirment['database'], $delete_doc_id, $document['_rev']), true);
        
                        if (isset($delete_response['ok']) && $delete_response['ok']) {
                            $publication->update(['pdf_document' => null]);
                        } else {
                            return back()->with('error', 'Failed to delete document: ' . $delete_doc_id);
                        }
                    }
                }
        
                // Uploading new document to CouchDB
                if ($extended->isRunning()) {
                    $val = random_int(100000, 999999);
                    $doc_id = "publication_branch_document_" . $val;
                    $path['id'] = 'document';
                    $path['tmp_name'] = $upload_file->getRealPath();
                    $path['extension'] = $upload_file->getClientOriginalExtension();
                    $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];
        
                    $dummy_data = ['pdf_document' => $doc_id];
                    $d_out = json_decode($extended->createDocument($dummy_data, $this->envirment['database'], $doc_id), true);
                    $rev = $d_out['rev'];
        
                    // Add attachment to the new document
                    json_decode($extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path), true);
        
                    $input['pdf_document'] = $path['name']; 
                }
            } else {
                $input['pdf_document'] = $publication->pdf_document;
            }
            $publication->update($input);
            $act['userid'] = Auth::user()->id;
            $act['ip'] = $request->ip();
            $act['activity'] = 'Publication Update By Admin';
            $act['officecode'] = Auth::user()->dept_id;
            $act['pagereferred'] = $request->url();
            Activitylog::insert($act);
            return redirect()->back()->withSuccess("Pblication update successfully");
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                Publication::find($decodedId)->delete();
                $act['userid'] = Auth::user()->id;
                $act['ip'] = $request->ip();
                $act['activity'] = 'Publication Delete By Admin';
                $act['officecode'] = Auth::user()->dept_id;
                $act['pagereferred'] = $request->url();
                Activitylog::insert($act);
                return response()->json(['message' => 'Publication deleted successfully']);
            } else {
                return response()->json(['message' => 'Publication not deleted']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }

      
    }
   
}
