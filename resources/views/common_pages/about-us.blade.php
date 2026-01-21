@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center">
                About Us
            </h4>
            
            <p class="content-text">
                In evaluation study following criteria are analyzed on the basis of feedbacks of stakeholders, observations during fields and primary/secondary data.
                <li> <h4>Relevance: </h4> the extent to which intervention/schemes or program relevant/appropriate. achieving desired results / outcomes for the target groups </li> 
                <li><h4>Efficiency: </h4> measures quantitative and qualitative outputs in relation to inputs, cost effectiveness, timely achievement and comparison to alternative approaches.</li>
                <li> <h4>Effectiveness:</h4> the extent to which program objectives were achieved or likely to be achieved as well as factor influencing achievements of the objectives.</li>
                <li> <h4>Impact: </h4> measures positive and negative results achieved by program, difference made to the target group and reasons for difference created.</li>
                <li> <h4> Sustainability: </h4> the extent of continuing of benefits after withdrawal of external support.</li>
            </p>
            <h4>Process of Evaluation:</h4>
             <p class="content-text">
                <h4>(1) Selection of schemes for Evaluation study</h4>
                <p>
                   <li>Inviting proposal from the Line Department.</li>
                   <li> After compilation of proposals, selection of scheme for which Evaluation study is to be carried out is decided by GAD (Planning).</li>
                   <li> Evaluation study of selected scheme is entrusted to Directorate of Evaluation.</li>
                </p>

                <h4>(2) Collection of detailed information</h4>
                <p>
                    <li> Collection of resolution, gazettes, circulars along with a requisition form. </li>
                    <li> Requisition form includes details such as Why the scheme is introduced, objective of the scheme, financial and physical achievement of last five years, critical areas to be investigated during evaluation survey etc. </li>
                </p>

                <h4>(3) Preparation of Study Design and Questionnaire:</h4>
                <p>
                    <li>Objective of the scheme.</li>
                    <li>Norms and administrative structure of implementation</li>
                    <li>Objective of Evaluation</li>
                    <li>Defining statistical methodology for sample selection. Preparation of questionnaire for stakeholders.</li>
                    <li>Concerned department is consulted before finalization of study design.</li>
                </p>

                <h4>(4)Field Work and Data Analysis</h4>
                <p>
                    <li>Pilot Study: Fine tuning of Evaluation Plan and Questioner.</li>
                    <li>Training to Field Staff.</li>
                    <li>Filed work by investigators.</li>
                    <li>Supervision by officers.</li>
                    <li>Data entry and computerized scrutiny.</li>
                    <li>Data Analysis.</li>
                </p>

                <h4>(5) Report Writing</h4>
                <p>
                    <li>Preparation of draft report based on data analysis and field observation.</li>
                    <li>Draft report consist of context, relevance, evidence based findings, conclusion and recommendations.</li>
                    <li>Findings based on analysis of primary and secondary data.</li>
                    <li>Draft report is sent to implementing department for suggestions and draft is updated according to appropriate suggestions.</li>
                </p>



                 <h4>(6) Submission of Draft report by DEC</h4>
                  <p>
                    <li>Draft report of Evaluation study is presented to Department Evaluation committee constituted under the chairmanship of ACS/PS/Secretary of concerned department.</li>
                    <li>Other members are as follows.</li>


                      <div class="table-responsive">
                        <table class="table table-bordered advisory-table">
                            <thead>
                                <tr>
                                    <th style="width: 10%">{{ __('message.sr_no') }}</th>
                                    <th colspan="2">Departmental Evaluation Committee (DEC)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>Head of Department of concerned implementing office </td>
                                    <td class="text-center">Member</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td>Director, Directorate of Evaluation</td>
                                    <td class="text-center">{{ __('message.member') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td>Deputy Secretary, GAD (Planning)</td>
                                    <td class="text-center">{{ __('message.member') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td>Financial Advisor of concerned department</td>
                                    <td class="text-center">{{ __('message.member') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td> Deputy Secretary of concerned department</td>
                                    <td class="text-center">{{ __('message.member_secretary') }}</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <li>Evaluation study draft report is modified as per the decisions taken in DEC.</li>
                    </p>


                     <h4>(7) Approval of Draft report by ECC</h4>
                    <p>
                        <li> Draft report of Evaluation study is presented to Evaluation Coordination committee constituted by following members.</li>
                        <div class="table-responsive">
                            <table class="table table-bordered ecc-table">
                                <thead>
                                    <tr>
                                        <th style="width:10%">{{ __('message.sr_no') }}</th>
                                        <th colspan="2">
                                            Evaluation Co-ordination Committee (ECC)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>Additional Chief Secretary/Principal Secretary/Secretary (Planning)</td>
                                        <td class="text-center">{{ __('message.chairman') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">2</td>
                                        <td>Principal Secretary (Expenditure) Finance Department</td>
                                        <td class="text-center">{{ __('message.member') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">3</td>
                                        <td>Secretary of concerned Department</td>
                                        <td class="text-center">{{ __('message.invitee') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>Director, Directorate of Evaluation</td>
                                        <td class="text-center">{{ __('message.member') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">5</td>
                                        <td>
                                            Deputy Secretary, GAD (Planning)
                                        </td>
                                        <td class="text-center">{{ __('message.member_secretary') }}</td>
                                    </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                    <li> Draft Evaluation Report is approved by Evaluation Coordination Committee after considerations.</li>
                    <li>Then after final report is published and distributed to concerned offices and officers for necessary corrective actions.</li>
                </p>
             </p>

    </div>
</div>

@endsection
