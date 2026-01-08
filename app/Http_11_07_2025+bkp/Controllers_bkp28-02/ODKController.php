<?php
namespace App\Http\Controllers;

use App\Models\ODK;
use Illuminate\Http\Request;
use App\Models\Scheme;
use App\Models\status;
use App\Models\branch;
use App\Models\Activitylog;
use App\Models\statuslog;
use App\Models\TeamMember;
use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use DB;
use URL;
use Illuminate\Support\Arr;
use App\Models\ODK_Project_Form;
use App\Models\ODK_Project;
use App\Models\enumerator_list;

class ODKController extends Controller {
    public function __construct(){
      $this->middleware('auth');
    }

    public function odkgetToken(Request $request){
        $odk_projects_list = ODK_Project::all();
        $schemes = Scheme::select('schemes.scheme_name','schemes.scheme_id')->get();
        return view('dashboards.eva-dd.odkgetToken',compact('schemes','odk_projects_list'));
      }

      public function odkStudyStore(Request $request){
        $validator = \Validator::make($request->all(), [
          'title' => 'required',
          'description' => 'required',
        ]);
         ODK::create($validator);
        if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()->all()]);
        }
        return response()->json(['success'=>'ODK added successfully']);
      }

      public function enumerator_list(Request $request) {
        if(!is_null($request->project_id) && !is_null($request->form_id)){
            $url = 'https://gujevl.gujarat.gov.in/v1/sessions';
            $user_list = OdkUser::where('status', true)->latest()->first();
            $decrypt_pass = base64_decode($user_list->encrypt_password);
            $postData = array(
                'email' =>  $user_list->email,
                'password' =>  $decrypt_pass
            );
           
            $ch = curl_init( $url );
            $data = json_encode( $data );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HEADER, 1 );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array ( 'Content-Type: application/json', 'Accept-Language: application/json' ) );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        
            $response_json = curl_exec( $ch );
            $header_size = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
            $header = substr( $response_json, 0, $header_size );
            $body = substr( $response_json, $header_size );
            curl_close( $ch );
            $response = json_decode( $body, true );
            $token_get = $response[ 'token' ];
            $authorization = 'Authorization: Bearer '.$token_get;
        
            $header = substr( $header, 8, 4 );
            $header = trim( $header );
            $head = '200';
    
        
            $enumerator_list = 'https://gujevl.gujarat.gov.in/v1/projects/'.$request->project_id.'/forms/'.$request->form_id.'.svc/Submissions';
            
            $ch = curl_init( $enumerator_list );
            curl_setopt( $ch, CURLOPT_HTTPGET, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array ( 'Content-Type: application/json', 'Accept-Language: application/json', $authorization ) );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
        
            $response_json1 = curl_exec( $ch );
            curl_close( $ch );
            $enumerator_list = json_decode( $response_json1, true );
           
        }
     
        $data = array();
                foreach ( $enumerator_list as $key => $value ) {
                
                    if(count($value) > 0){
                        enumerator_list::where( 'instance_id', '=', $value['instanceId'] )->delete();    
                        enumerator_list::create([
                            'submitted_by' => $value[ '__system' ][ 'submitterName' ],
                            'submitted_at' => $value[ '__system' ][ 'submissionDate' ],
                            'q1_name_of_beneficiary' => $value[ 'q1' ],
                            'q2_respondend' => $value[ 'Q2_Respondend' ],
                            'q3_address_of_beneficiary' => $value[ 'Q3_Address_of_Beneficiary' ],
                            'q2_1_name_of_area' => $value[ 'Q2_1_Name_of_Area' ],
                            'q2_2_type_of_area' => $value[ 'Q2_2_Type_of_Area' ],
                            'q3_name_of_taluka_zone' => $value[ 'Q3_Name_of_Taluka_Zone' ],
                            'q3_1_name_of_district_mnp' => $value[ 'Q3_1_Name_of_District_MNP' ],
                            'q4_sex' => $value[ 'Q4_Sex' ],
                            'q5_age' => $value[ 'Q5_Age' ],
                            'q6_contact_number' => $value[ 'Q6_Contact_Number' ],
                            'instance_id' => $value[ '__id' ],
                        ]);
                    }
                }
       
        $enumerator = DB::select( 'select UPPER(trim(submitted_by)) as submitted_by  from  itransaction.enumerator_list  group by UPPER(trim(submitted_by))' );
        return view( 'dashboards.eva-dd.enumerator_list', [ 'enumerator'=>$enumerator ] );
    }
    
    public function show_enum_list( Request $request ) {
      $name= base64_decode( $request->name );
         $enum_list = DB::table( 'itransaction.enumerator_list' )
            ->select( 'enumerator_list.*')
            ->where( 'enumerator_list.submitted_by', 'ilike',  $name )
            ->get();
            
        $html = '<table class="table table-bordered" border="1" width="100%" ><thead><tr>
             <th >submitted_by</th>
            <th >submitted_at</th>
            <th >q1_name_of_beneficiary</th>
            <th >q2_respondend </th>
            <th >q3_address_of_beneficiary</th>
            <th >q2_1_name_of_area</th>
            <th >q2_2_type_of_area</th>
            <th >q3_name_of_taluka_zone</th>
            <th >q3_1_name_of_district_mnp</th>
            <th >q4_sex</th>
            <th >q5_age</th>
            <th >q6_contact_number</th>
            <th >instance_id</th>
            </tr></thead><tbody>';
            
        if ( !empty( $enum_list ) ) {
            foreach ( $enum_list as $ob ) {
                $html .= '<tr>
                <td>'.$ob->submitted_by.'</td>
                <td>'.$ob->submitted_at.'</td>
                <td>'.$ob->q1_name_of_beneficiary.'</td>
                <td>'.$ob->q2_respondend.'</td>
                <td>'.$ob->q3_address_of_beneficiary .'</td>
                <td>'.$ob->q2_1_name_of_area.'</td>
                <td>'.$ob->q2_2_type_of_area.'</td>
                <td>'.$ob->q3_name_of_taluka_zone.'</td>
                <td>'.$ob->q3_1_name_of_district_mnp.'</td>
                <td>'.$ob->q4_sex.'</td>
                <td>'.$ob->q5_age.'</td>
                <td>'.$ob->q6_contact_number.'</td>
                <td>'.$ob->instance_id.'</td>
                </tr>';
            }
        } else {
            $html .= '<tr>
              <td rowspan="4">There is no record</td></tr>';
          }
            $html .= '</tbody></table>';
            echo $html;        
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        echo '<pre>';
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ODK  $oDK
     * @return \Illuminate\Http\Response
     */
    public function show(ODK $oDK){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ODK  $oDK
     * @return \Illuminate\Http\Response
     */
    public function edit(ODK $oDK){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ODK  $oDK
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ODK $oDK){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ODK  $oDK
     * @return \Illuminate\Http\Response
     */
    public function destroy(ODK $oDK){
        //
    }
}