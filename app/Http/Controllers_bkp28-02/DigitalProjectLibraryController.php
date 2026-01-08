<?php

namespace App\Http\Controllers;

use App\Models\DigitalProjectLibrary;
use App\Models\Department;
use Illuminate\Http\Request;
use Validator;
use App\Couchdb\Couchdb;
use Illuminate\Support\Facades\Crypt;
use App\Exports\LibraryReport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use function App\Helpers\PdfHelper\getPdfContent;
use Dompdf\Dompdf;
use Dompdf\Options;

class DigitalProjectLibraryController extends Controller
{
    public $envirment;
    public function __construct(){
        $this->envirment = environmentCheck();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project_list = DigitalProjectLibrary::get();
        return view('dashboards.project_library.index',compact('project_list'));
    }
    public function projectList()
    {
        $project_list = DigitalProjectLibrary::get();
        $year = DigitalProjectLibrary::distinct()->pluck('year')->toArray();
        sort($year);
        rsort($year);
        return view('dashboards.project_library.project_show_user',compact('project_list','year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        $financial_years = financialyears();
        return view('dashboards.project_library.create',compact('departments','financial_years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        try{
            $validate = Validator::make($request->all(),[
                 'study_name' => 'required',
                 'dept_id' => 'required|numeric|max:999999999',
                 'year' => 'required',
                 'org_name'=>'required',
                 'upload_file.*'=>'required|mimes:pdf'
             ]);
             if($validate->fails()) {
                return redirect()->back()->withError($validate->errors());
             } else {
                $input = $request->all();
               
                $input['study_name'] = $request->study_name;
                $input['dept_id'] = $request->dept_id;
                $input['year'] = $request->year;
                $input['org_name'] = $request->org_name;
                if($request->hasFile('upload_file')) {
                    $upload_file = $request->file('upload_file');

                    $file_name = [];    $rand_val = [];
                    $extended = new Couchdb();
                    $extended->InitConnection();
                    $status = $extended->isRunning();
                    foreach($upload_file as $key => $file) {
                        $val = random_int(100000, 999999);
                        $doc_id = "upload_file_".$val;
                        $docid = 'document'; 
                        $path['id'] = $docid;
                    
                        $path['tmp_name'] = $file->getRealPath();
                        $path['extension']  = $file->getClientOriginalExtension();
                        $path['name'] = $doc_id.'_'.$path['id'].'.'.$path['extension'];
                      
                        $dummy_data = array(
                            'upload_file' => $doc_id
                        );
                        $d_out = $extended->createDocument($dummy_data,$this->envirment['database'],$doc_id);
                        $array1 = json_decode($d_out, true);
                        $id = $array1['id'];
                        $rev = $array1['rev'];
                        
                        $out = $extended->createAttachmentDocument($this->envirment['database'],$doc_id,$rev,$path);
                        $array = json_decode($out, true);
                                    
                        $file_name[] = $path['name'];
                        $rand_val[] =  $val;
                    }
                    $input['upload_file'] =  implode(',',$file_name);
                    $input['rand_val'] = implode(',',$rand_val);
                   
                }
          
                DigitalProjectLibrary::create($input);
                return redirect()->back()->withSuccess( 'Project create successfully');
                 
             }
           }catch(Exception $e){
             return redirect()->back()->withError($e->getMessage());
           }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DigitalProjectLibrary  $digitalProjectLibrary
     * @return \Illuminate\Http\Response
     */
    public function show(DigitalProjectLibrary $digitalProjectLibrary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DigitalProjectLibrary  $digitalProjectLibrary
     * @return \Illuminate\Http\Response
     */
    public function edit(DigitalProjectLibrary $digitalProjectLibrary)
    {
        $departments = Department::active()->orderBy('dept_id', 'asc')->get();
        $financial_years = financialyears();
        return view('dashboards.project_library.edit',compact('departments','financial_years','digitalProjectLibrary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DigitalProjectLibrary  $digitalProjectLibrary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DigitalProjectLibrary $digitalProjectLibrary)
    {
       
        try{
            $validate = Validator::make($request->all(),[
                 'study_name' => 'required',
                 'dept_id' => 'required|numeric|max:999999999',
                 'year' => 'required',
                 'org_name'=>'required',
                 'upload_file.*'=>'required|mimes:pdf'
             ]);
             if($validate->fails()) {
                return redirect()->back()->withError($validate->errors());
             } else {
                $input = $request->all();
               
                if(isset($request->study_name)){
                    $input['study_name'] = $request->study_name;
                }
                if(isset($request->dept_id)){
                    $input['dept_id'] = $request->dept_id;
                }
                if(isset($request->year)){
                    $input['year'] = $request->year;
                }
                if(isset($request->org_name)){
                    $input['org_name'] = $request->org_name;
                }
                $extended = new Couchdb();
                $extended->InitConnection();

                if ($request->hasFile('upload_file')) {

                    if(!is_null($digitalProjectLibrary->upload_file) && !is_null($digitalProjectLibrary->rand_val)){
                        $items =  explode(',', $digitalProjectLibrary->upload_file);
                        $rand_val = explode(',', $digitalProjectLibrary->rand_val);

                        if(!empty($items) && count($items) > 0){
                            foreach($items as $kgrs => $file){
                                if ($extended->isRunning()) {
                                    $delete_doc_id = "upload_file_".$rand_val[$kgrs];
                                    // Check if the document exists
                                    $document = $extended->getDocument($this->envirment['database'], $delete_doc_id);
                                    $document = json_decode($document, true);

                                    
                                if (is_array($document) && isset($document['_rev'])) {
                                    $delete_response = $extended->deleteDocument($this->envirment['database'], $delete_doc_id, $document['_rev']);
                                    $delete_response_decoded = json_decode($delete_response, true);

                                    if (is_array($delete_response_decoded) && isset($delete_response_decoded['ok']) && $delete_response_decoded['ok']) {
                                        $digitalProjectLibrary->update(['upload_file' => null ,'rand_val' => null]);
                                    } else {
                                        // Handle the case where the document couldn't be deleted
                                        dd('Failed to delete document: ' . $doc_id);
                                    }
                                }
                               }
                            }
                        }
                     
                    }

                    $upload_files = $request->file('upload_file');
                    $file_names = [];
                    $rand_val = [];
                
                   
                    if ($extended->isRunning()) {
                        foreach ($upload_files as $key => $file) {
                            $val = random_int(100000, 999999);
                            $doc_id = "upload_file_" .$val;
                            $docid = 'document';
                            $path['id'] = $docid;
                            $path['tmp_name'] = $file->getRealPath();
                            $path['extension'] = $file->getClientOriginalExtension();
                            $path['name'] = $doc_id . '_' . $path['id'] . '.' . $path['extension'];
                
                            
                                // Document doesn't exist, create a new one
                                $dummy_data = array('upload_file' => $doc_id);
                                $d_out = $extended->createDocument($dummy_data,$this->envirment['database'], $doc_id);
                                $array1 = json_decode($d_out, true);
                                $id = $array1['id'];
                                $rev = $array1['rev'];
                
                                // Add attachment to the new document
                                $out = $extended->createAttachmentDocument($this->envirment['database'], $doc_id, $rev, $path);
                                $array = json_decode($out, true);
                
                                $file_names[] = $path['name'];
                                $rand_val[] = $val;
                            }
                        }
                        $input['upload_file'] = implode(',', $file_names);
                        $input['rand_val'] = implode(',', $rand_val);
                    }
                
                $digitalProjectLibrary->update($input);
                return redirect()->back()->withSuccess( 'Project Update successfully');
             }
           }catch(Exception $e){
             return redirect()->back()->withError($e->getMessage());
           }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DigitalProjectLibrary  $digitalProjectLibrary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (isset($id) && !empty($id)) {
            $decodedId = base64_decode($id);

            if (is_numeric($decodedId)) {
                DigitalProjectLibrary::find($decodedId)->delete();
                return response()->json(['message' => 'Project delete successfully']);
            } else {
                return response()->json(['message' => 'Invalid ID provided']);
            }
        } else {
            // Handle missing ID
            return response()->json(['message' => 'ID is required']);
        }

    }

    public function getthedocument($id,$document) {
       
        $id = 'upload_file_'.Crypt::decrypt($id);

        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument($this->envirment['database'],$id);
        $arrays = json_decode($out, true);
      //  dd($arrays);
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
    }
 
  
    public function summary(Request $request){
       
        if(!is_null($request->from_year) && !is_null($request->to_year)){
            $fromYear = $request->from_year;
            $toYear = $request->to_year;
                $get_item =  DigitalProjectLibrary::where(function($query) use ($fromYear, $toYear) {
                    $query->whereRaw('SUBSTRING(year, 1, 4) BETWEEN ? AND ?', [$fromYear, $toYear]);
                })->get();
            // }else{
            //     $get_item = DigitalProjectLibrary::get();
            // }
            
            if($get_item->count() > 0){
                $html = "";
                $html .= '<div class="row">
                <div class="card-body">
                  <div class="col-lg-12">
                    <table class="table table-bordered table-striped dataTable dtr-inline" id="example1">
                        <a href="'.route('digital-project', [$fromYear,$toYear]).'"><i class="fas fa-download fa-2x" style="color:green;"></i></a>
                        <a href="'. route('digital-project-pdf', [$fromYear,$toYear]).'" class="btn btn-primary float-right">PDF</a>
                      <thead>
                            <tr>
                              <th>ID</th>
                                <th>Name</th>
                                <th>Department Name</th>
                                <th>Organization Name</th>
                               
                            </tr>
                        <tbody>';
            foreach($get_item as $key => $items){
                    $html .= '<tr>
                                <td>'.++$key.'</td>
                                <td>'.$items->study_name.'</td>
                                <td width="23%">'.department_name($items->dept_id).'</td>
                                <td>'.$items->org_name.'</td>
                            </tr>';
                }
                $html .= '</tbody>
                        </table>
                    </div>
                    </div>
                </div>';
            }
            return $html;
        }
    }

    public function projectExcel($fromYear,$toYear){
       
        if(!is_null($fromYear) && !is_null($toYear)){
            try {
             //  if($year != 1){
                    $data =   DigitalProjectLibrary::where(function($query) use ($fromYear, $toYear) {
                        $query->whereRaw('SUBSTRING(year, 1, 4) BETWEEN ? AND ?', [$fromYear, $toYear]);
                    })->get();
            //    }else{
            //         $data = DigitalProjectLibrary::get();
            //    }
           
             $excel_name = 'Evaluation-summary-' . $fromYear . '-' . $toYear . '-report.xlsx';
                return Excel::download(new LibraryReport($data),$excel_name);
    
            } catch (DecryptException $e) {
                return response()->json(['error' => 'Invalid encrypted data'], 400);
            }
        }
    }
    
    public function projectPDF($fromYear,$toYear) {
       
        if(!is_null($fromYear) && !is_null($toYear)){
            try {
                // if($year != 1){
                    $data = DigitalProjectLibrary::where(function($query) use ($fromYear, $toYear) {
                        $query->whereRaw('SUBSTRING(year, 1, 4) BETWEEN ? AND ?', [$fromYear, $toYear]);
                    })->get();
                    // Extract the start year from $fromYear
                    // list($startYear, ) = explode('-', $fromYear);

                    // // Extract the end year from $toYear
                    // list(, $endYear) = explode('-', $toYear);
                  
            //    }else{
            //         $data = DigitalProjectLibrary::get();
            //         $name = "all-year";
            //    }
                $pdf = PDF::loadView('dashboards.project_library.project_pdf',compact('data'));
                $pdf_name = 'Evaluation-summary-' . $fromYear . '-' . $toYear . '-report.pdf';
                return $pdf->download($pdf_name);
               
            } catch (DecryptException $e) {
                return response()->json(['error' => 'Invalid encrypted data'], 400);
            }
        }
    }
}
