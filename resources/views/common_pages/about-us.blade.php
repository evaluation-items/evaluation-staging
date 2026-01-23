@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center">
                About Us
            </h4>
            
            <p class="content-text">
                In evaluation study following criteria are analyzed on the basis of feedbacks of stakeholders, observations during fields and primary/secondary data.
            </p>
            <div class="evaluation-process-img">
                <img src="{{ asset('img/eval-process.png') }}" alt="Directorate of Evaluation">
            </div>
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
             </p>

    </div>
</div>

@endsection
