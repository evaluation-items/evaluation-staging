<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <title>Print Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Gujarati&display=swap" rel="stylesheet">
<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 15mm 10mm 20mm 10mm;
    }

    body {
        font-family: 'Noto Sans Gujarati', sans-serif;
        font-size: 15px;
        line-height: 1.4;
        color: #000;
        /* margin: 0;
        padding: 0; */
    }

    /* Use border-collapse: collapse to prevent double borders */
    table {
        width: 100%;
        /* 'separate' is often more reliable for page-break borders than 'collapse' */
        border-collapse: separate; 
        border-spacing: 0;
        /* This forces the top border onto the table container */
        border-top: 1pt solid #000;
        border-left: 1pt solid #000;
        border-right: 1pt solid #000;
        page-break-inside: auto;
    }
    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    td, th {
        border-bottom: 1pt solid #000;
        border-right: 1pt solid #000;
        padding: 12px;
        vertical-align: top;
        word-wrap: break-word;
    }

    /* Clean up the right-side double border */
    td:last-child, th:last-child {
        border-right: none;
    }

    /* Ensures the row stays together on one page */
   
  .main-title {
          text-align: center;
          font-size: 20px;
          font-weight: bold;
          margin-bottom: 20px;
      }

    .watermark {
        position: fixed;
        top: 40%;
        left: 0;
        width: 100%;
        text-align: center;
        opacity: 0.1;
        font-size: 60pt;
        transform: rotate(-30deg);
        z-index: -1;
        pointer-events: none;
    }
     .page{
        page-break-after: always;
      }
}
</style>
</head>
<body>
  <div class="watermark">GOVERNMENT OF GUJARAT</div>
<div class="page">
		
    <div class="main-title">SCHEME EVALUATION PROPOSAL DETAIL</div>
    
        <table class="table-bordered">
        
                <tr>
                  <th>Name of the Department (વિભાગનું નામ)</th>
                  <td>{{ department_name($proposal->dept_id) }}</td>
                </tr>
                <tr>
                <th>Whether evaluation of this scheme already done in past? (આ યોજનાનું મૂલ્યાંકન અગાઉ થઈ ચૂકેલ છે?)</th>
                <td>{{($proposal->is_evaluation == 'Y' ? 'Yes' : 'No')}}</td>
                </tr>
                          @if($proposal->is_evaluation == 'Y')
                          <tr>
                            <th>By Whom? (કોના દ્વારા?)</th>
                            <td>{{$proposal->eval_scheme_bywhom}}</td>
                          </tr>
                          <tr>
                            <th>When? (ક્યારે?)</th>
                            <td>{{$proposal->eval_scheme_when}}</td>
                          </tr>
                          <tr>
                            <th>Geographical coverage of Beneficiaries (સમાવિષ્ટ કરેલ લાભાર્થીઓનો ભૌગોલિક વિસ્તાર)</th>
                            <td>{{$proposal->eval_geographical_coverage_beneficiaries}}</td>
                          </tr>
                          <tr>
                            <th>No. of beneficiaries in sample (નિદર્શમાં સમાવિષ્ટ લાભાર્થીઓની સંખ્યા)</th>
                            <td>{{$proposal->eval_scheme_major_recommendation}}</td>
                          </tr>
                          <tr>
                            <th>Upload report (અહેવાલ.)</th>
                            <td> 
                            {{$proposal->eval_upload_report}}
                            </td>
                          </tr>
                          @endif
                          <tr>
                            <th>Name of the Nodal Officer (નોડલ અધિકારીનું નામ)</th>
                            <td>
                                {{ $proposal->convener_name }}
                            </td>
                          </tr>
                        
                          <tr>
                            <th>Designation of the Nodal Officer (નોડલ અધિકારીનો હોદ્દો)</th>
                            @php
                              if($proposal->convener_designation == 'ds'){
                                $name = 'Deputy Secretary';
                              }elseif($proposal->convener_designation == 'js'){
                                $name = 'Joint Secretary';
                              }elseif($proposal->convener_designation == 'as'){
                                $name = 'Additional Secretary';   
                              }else{
                                $name = $proposal->convener_designation;
                              }
                            @endphp
                            <td>
                              {{ $name }}
                            </td>
                          </tr>
                          <tr>
                            <th>Contact Number of the Nodal Officer (નોડલ અધિકારીનો સંપર્ક નંબર)</th>
                            <td>
                              {{ $proposal->convener_phone }}
                            </td>
                          </tr>
                          <tr>
                            <th>Mobile Number of the Nodal Officer (નોડલ અધિકારીનો મોબાઇલ નંબર)</th>
                            <td>
                              {{ $proposal->convener_mobile }}
                            </td>
                          </tr>
                          <tr>
                            <th>Email Address of the Nodal Officer (નોડલ અધિકારીનું ઇમેઇલ એડ્રેસ)</th>
                            <td>
                              {{ $proposal->convener_email }}
                            </td>
                          </tr>
                          <tr>
                            <th>Name of the scheme/ Programe to be evaluated (કરવાના થતા મૂલ્યાંકન અભ્યાસ માટેના યોજના/કાર્યક્રમનું નામ)</th>
                            <td>
                              {{ $proposal->scheme_name }}
                            </td>
                          </tr>
                          <tr>
                            <th>Short Name of the scheme/ Programe to be evaluated (મૂલ્યાંકન કરવાની યોજના/કાર્યક્રમનું ટૂંકું નામ)</th>
                            <td>
                              {{ $proposal->scheme_short_name ?? '-' }}
                            </td>
                          </tr>
                          <tr>
                            <th>Name of the Financial Adviser (નાણાકીય સલાહકારનું નામ):</th>
                            <td>
                              {{ $proposal->financial_adviser_name }}
                            </td>
                          </tr>
                          
                          <tr>
                            <th>Contact Number of the Financial Adviser (નાણાકીય સલાહકારનો સંપર્ક નંબર)</th>
                            <td>
                              {{ $proposal->financial_adviser_phone }}
                            </td>
                          </tr>
                          <tr>
                            <th>Mobile Number of the Financial Adviser (નાણાકીય સલાહકારનો મોબાઇલ નંબર) </th>
                            <td>
                              {{ $proposal->financial_adviser_mobile }}
                            </td>
                          </tr>
                          <tr>
                            <th>Email Address of the Financial Adviser (નાણાકીય સલાહકારનું ઇમેઇલ એડ્રેસ) </th>
                            <td>
                              {{ $proposal->financial_adviser_email }}
                            </td>
                          </tr>
                          <tr>
                          <th>The Reference year for which the Evaluation study to be done (મૂલ્યાંકન અભ્યાસ માટેનું સંદર્ભ વર્ષ)</th>
                            <td>From: {{ $proposal->reference_year }} <br> To: {{$proposal->reference_year2}}</td>  
                          </tr>
                          <tr>
                          <th>Major Objective of the Evaluation study (મૂલ્યાંકન અભ્યાસના મુખ્ય હેતુઓ)</th>
                          <td>
                              {{ $proposal->major_objective }}
                              {!! '<br>' !!}
                          {{$proposal->major_objective_file }}
                                
                          </td>
                        </tr>
                          <tr>
                            <th>Major Monitoring Indicators for scheme to be evaluated (મૂલ્યાંકન હાથ ધરવાની થતી યોજનાની સમીક્ષાના મુખ્ય માપદંડો)</th>
                            <td>
                                {{ $proposal->major_indicator }}
                                {!! '<br>' !!}
                              {{$proposal->major_indicator_file}}
                            </td>
                          </tr>
                          @php
                            $names    = array_map('trim', explode(',', $proposal->hod_officer_name ?? ''));
                            $emails   = array_map('trim', explode(',', $proposal->hod_email ?? ''));
                            $contacts = array_map('trim', explode(',', $proposal->implementing_office_contact ?? ''));
                            $hod_mobile = array_map('trim', explode(',', $proposal->hod_mobile ?? ''));
                          @endphp
                          <tr>
                              <th>Name of the HOD / Branch (કચેરી/શાખાનું નામ)</th>
                              <td>{{ hod_name($proposal->draft_id) }}</td>
                          </tr>

                          @foreach ($names as $i => $name)
                          <tr>
                              <th>Name</th>
                              <td>{{ $name ?: '-' }}</td>
                          </tr>
                          <tr>
                              <th>Email Address</th>
                              <td>{{ $emails[$i] ?? '-' }}</td>
                          </tr>
                          <tr>
                              <th>Contact No</th>
                              <td>{{ $contacts[$i] ?? '-' }}</td>
                          </tr>
                          <tr>
                              <th>Mobile No</th>
                              <td>{{ $hod_mobile[$i] ?? '-' }}</td>
                          </tr>
                        
                          @endforeach
                          <tr>
                            <th>Name of the Nodal Officer (HOD) (નોડલ અધિકારીનું નામ)</th>
                            <td>
                              {{ $proposal->nodal_officer_name }}
                            </td>
                          </tr>
                          <tr>
                            <th>Designation of the Nodal Officer(HOD) (નોડલ અધિકારીનો હોદ્દો)</th>
                            <td>
                              {{ $proposal->nodal_officer_designation }}
                            </td>
                          </tr>
                          <tr>
                            <th>Contact Number of the Nodal Officer (HOD) (નોડલ અધિકારીનો સંપર્ક નંબર)</th>
                            <td>
                              {{ $proposal->nodal_officer_contact }}
                            </td>
                          </tr>
                          <tr>
                            <th>Mobile Number of the Nodal Officer (HOD) (નોડલ અધિકારીનો મોબાઇલ નંબર)</th>
                            <td>
                              {{ $proposal->nodal_officer_mobile }}
                            </td>
                          </tr>
                          <tr>
                            <th>Email Address of the Nodal Officer(HOD) (નોડલ અધિકારીનું ઇમેઇલ એડ્રેસ)</th>
                            <td>
                              {{ $proposal->nodal_officer_email }}
                            </td>
                          </tr>
                          <tr>
                            <th>Fund Flow Central Govt. (યોજના માટેનો નાણાકીય સ્ત્રોત્ર કેદ્ર: __%)</th>
                            <td>
                              {{ $proposal->center_ratio }} %
                            </td>
                          </tr>
                          <tr>
                            <th>Fund Flow State Govt. (યોજના માટેનો નાણાકીય સ્ત્રોત્ર રાજ્ય: __%)</th>
                            <td>
                              {{ $proposal->state_ratio }} %
                            </td>
                          </tr>
                          <tr>
                            <th>Fund Flow State Govt. (યોજના માટેનો નાણાકીય સ્ત્રોત્ર અન્ય: __%)</th>
                            <td>
                              {{ $proposal->other_ratio ?? 0 }} %
                            </td>
                          </tr>
                          <tr>
                            <th>Remarks</th>
                            <td>
                              {{ $proposal->both_ration ?? '-' }} 
                            </td>
                          </tr>
                          <tr>
                            <th>Overview of the scheme/Background of the scheme (યોજનાની પ્રાથમિક માહિતી/યોજનાનો પરિચય)</th>
                            <td>
                              {{ $proposal->scheme_overview }}
                              <br>
                              {{ $proposal->next_scheme_overview_file }}
                            </td>
                          </tr>
                        <tr>
                          <th>Objectives of the scheme (યોજનાના હેતુઓ)</th>
                          <td>
                            {{ $proposal->scheme_objective }}
                            <br>
                            {{ $proposal->scheme_objective_file }}
                          </td>
                        </tr>
                        <tr>
                          <th>Name of Sub-schemes/components (પેટા યોજનાનું નામ/ઘટકો)</th>
                          <td>
                            {{ $proposal->sub_scheme }} <br>
                            {{$proposal->next_scheme_components_file }}
                          </td>
                        </tr>
                        <tr>
                          <th>Year of actual commencement of the scheme (યોજનાનું ખરેખર અમલીકરણ શરૂ થયા વર્ષ)</th>
                          <td>
                            {{ $proposal->commencement_year }}
                          </td>
                        </tr>
                        <tr>
                          <th>Present status of the scheme (યોજનાના અમલની વર્તમાન સ્થિતિ)</th>
                          <td>
                            {{ ($proposal->scheme_status == 'Y' ? ' Operational (કાર્યરત)' : 'Non-operational (બિન-કાર્યરત)') }}
                          </td>
                        </tr>
                          <tr>
                          <th>Sustainable Development Goals (SDG): Which specific goal(s) does this scheme follow? (સસ્ટેનેબલ ડેવલપમેન્ટ ગોલ (SDG): આ યોજના કયા ચોક્કસ લક્ષ્યાંકોને અનુસરે છે?)</th>
                          <td>
                          @if(json_decode($proposal->is_sdg) != 'no data')
                            @foreach($goals as $k => $g)
                              @if(in_array($g->goal_id,json_decode($proposal->is_sdg)))
                                <p>{{ $g->goal_name }} ({{ $g->goal_name_guj }})</p>
                              @endif
                            @endforeach
                          @endif
                          </td>
                        </tr>
                        <tr>
                          <th>Beneficiary/Community selection Criteria (લાભાર્થી/સમુદાયની પાત્રતા માટેના માપદંડો)</th>
                          <td>
                              {{ $proposal->scheme_beneficiary_selection_criteria ?? '-' }}
                              <br>
                              {{$proposal->beneficiary_selection_criteria_file }}
                            </td>
                        </tr>
                        <tr>
                          <th>Expected Major Benefits Derived from the Scheme(યોજનાના અપેક્ષિત મુખ્ય લાભો)</th>
                          <td>
                            {{ $proposal->major_benefits_text }}
                            <br>
                            {{ $proposal->major_benefits }}
                          </td>
                        </tr>
                        
                        <tr>
                          <th>Implementing procedure of the Scheme (યોજનાની અમલીકરણ માટેની પ્રક્રિયા.)</th>
                          <td>
                            {{ $proposal->scheme_implementing_procedure }} <br><br>
                            {{ $proposal->scheme_implement_file }}
                          </td>
                        </tr>
                        <tr>
                          <th>Administrative set up for Implementation of the scheme (યોજનાના અમલીકરણ માટેનું વહીવટી માળખું) </th>
                          <td>{{$proposal->implementing_procedure}} <br><br>
                            {{$proposal->implementing_procedure_file }}
                          </td>
                        </tr>
                        <tr>
                            <th style="width: 30%; background-color: #f8f9fa; vertical-align: top;">
                                <strong>Geographical Coverage </strong><br> (રાજ્યકક્ષાથી લઈ લાભાર્થી સુધીનો ભૌગોલિક વ્યાપ)
                            </th>
                            <td style="vertical-align: top;">
                                <div style="font-weight: bold; margin-bottom: 5px;">
                                    District Wise Selection
                                </div>
                                <hr style="border-top: 1px solid #eee; margin-bottom: 10px; border-bottom:none;">
                                {!! $districtTable !!}
                            </td>
                        </tr>
                      <tr>
                          <th> Remarks</th>
                          <td>
                            {{$proposal->otherbeneficiariesGeoLocal ?? '-'}} 
                          </td>
                        </tr>
                      <tr>
                        <td class="label"><strong>Scheme coverage since inception of the scheme </strong>(યોજનાની શરૂઆતથી અત્યાર સુધીનો વ્યાપ) <br>Coverage of Beneficiary/Community (લાભાર્થી/સમુદાયનો સમાવેશ)
                      </td>
                      <td>{{$proposal->coverage_beneficiaries_remarks}}<br><br>{{$proposal->beneficiaries_coverage}}</td>
                  </tr>
                  <tr><td class="label"><strong>Training/Capacity building of facilitators </strong>(સંબંધિતોની તાલીમ/ક્ષમતા નિર્માણ માટેની કામગીરી)</td><td>{{$proposal->training_capacity_remarks}}<br><br>{{$proposal->training}}</td></tr>
                    <tr><td class="label"><strong>IEC activities</strong> (પ્રચાર પ્રસારની કામગીરી)</td><td>{{$proposal->iec_activities_remarks}}<br><br>{{$proposal->iec}}</td></tr>
                            <tr><td class="label"><strong>Asset/Service creation & its maintenance plan if any </strong>(યોજના દ્વારા ઊભી થયેલ સંપત્તિ/સેવા અને તેની જાળવણી, જો હોય તો)</td><td>{{($proposal->benefit_to ?? '-')}}</td></tr>
                            {!! $convergenceRows !!}

                              <tr>
                            <td class="label"><strong>GR </strong>(ઠરાવો)</td>
                            <td>{{$formatFiles($proposal->gr_file) }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Notification</strong> (જાહેરનામું)</td>
                            <td>{{$formatFiles($proposal->notification_files) }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Brochure</strong> (બ્રોશર)</td>
                            <td>{{$formatFiles($proposal->brochure_files) }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Pamphlets</strong> (પેમ્ફલેટ્સ)</td>
                            <td>{{$formatFiles($proposal->pamphlets_files) }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Other Details</strong> (યોજનાને લાગતું અન્ય વિગત)</td>
                            <td>{{$formatFiles($proposal->otherdetailscenterstate_files) }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Beneficiary Filling form</strong><br> (લાભાર્થી ભરવાનું ફોર્મ)</td>
                            <td>
                              {{ (($proposal->beneficiary_filling_form_type == 0) ? 'Yes' : 'No') }}
                                {{ ($proposal->beneficiary_filling_form ? '<br>File: ' . $proposal->beneficiary_filling_form : '') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Major Monitoring Indicator at HOD Level</strong> <br> (ખાતાના વડાકક્ષાએ મહત્વના ઇન્ડિકેટર નુ મોનીટરીંગ)</td>
                            <td>{{($proposal->major_indicator_hod ?? '-') }}</td>
                        </tr>

                        <tr>
                            <td class="label"><strong>Physical and Financial Progress Remarks</strong></td>
                            <td>{{($proposal->fin_progress_remarks ?? '-') }}</td>
                        </tr>
                     
        </table>
        <table class="financial-table">
          <thead>
          <tr>
            <th rowspan="2" style="width: 20%;">Financial Year/નાણાકીય વર્ષ</th>
            <th colspan="3">Physical/ભૌતિક</th>
            <th colspan="2">Financial/નાણાકીય <small>(Rs in Crores)</small></th>
          </tr>
          <tr>
            <th style="font-size: 16px;">Unit - એકમ</th>
            <th style="font-size: 16px;">Target – લક્ષ્યાંક</th>
            <th style="font-size: 16px;">Achievement – સિધ્ધિ</th>
            <th style="font-size: 16px;">Provision– જોગવાઇ</th>
            <th style="font-size: 16px;">Expenditure – ખર્ચ</th>
            
          </tr>
          </thead>
              @if($financial_progress->count())
                @foreach($financial_progress as $fpk => $fpv)
                <tr>
                  <td class="text-center">{{ $fpv->financial_year }}</td>
                  {{-- <td>{{ $fpv->units }}</td> --}}
                  <td class="text-center">{{ units($fpv->selection) }}</td>
                  <td class="text-center">{{ $fpv->target }}</td>
                  <td class="text-center">{{ $fpv->achievement }}</td>
                  <td class="text-center">{{ $fpv->allocation }}</td>
                  <td class="text-center">{{ $fpv->expenditure }}</td>
                  {{-- <td class="text-center">{{ $fpv->items }}</td> --}}
                </tr>
                @endforeach
              @endif
          
        </table>
  
 </div>
</body>
</html>