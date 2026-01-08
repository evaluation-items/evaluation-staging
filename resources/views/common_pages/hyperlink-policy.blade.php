@extends('layouts.app')
@section('content')
    <div class="menu-item-pages col-lg-9 col-md-8 col-sm-12 left_col">
        <h2>Hyperlink Policy</h2>
        <div class="description" style="margin-top: 1%;">
            <h4>Links to External Websites/Portals</h4>

            <p>At many places in this website, you shall find links to other websites/portals. This links have been placed for your convenience. Directorate of Economics and Statistics is not responsible for the contents and reliability of the linked websites and does not necessarily endorse the views expressed in them. Mere presence of the link or its listing on this website should not be assumed as endorsement of any kind. We cannot guarantee that these links will work all the time and we have no control over availability of linked pages.</p>

            <h4>Links to Directorate of Economics and Statistics Website by other Websites/Portals</h4>

            <p>Prior permission is required before hyperlink are directed from any website/portal to this site. Permission for the same, stating the nature of the content on the pages from where the link has to be given and the exact language of the hyperlink should be obtained by sending a request to stake holder</p>
           
        </div>
        
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12 sidebar_col">
                <div class="link-sec">
                    <div class="sub-title">
                        <h4>{{ __('message.other_offices')}}</h4>
                    </div>

                    <div class="box_wrap">
                        <div class="office-content">
                                <ul class="listed">
                                    <li style="margin-left:75px;"><a href="{{Config::get('custom_url.url')}}gujarat-social-infrastructure-development-society">{{ __('message.gujarat_social_infra')}}</a></li>
                                    <li style="margin-left:75px;"><a href="{{Config::get('custom_url.url')}}">{{ __('message.directorate_economics')}}</a></li>
                                </ul>
                        </div>
                    </div>

                    <div class="read-more">
                        <a href="{{Config::get('custom_url.url')}}other-offices">{{ __('message.more')}}</a>
                    </div>
                </div>
                <div class="external-link-info">
                    <ul>
                        <li style="display: none">
                            <a href="{{Config::get('custom_url.vibrantgujarat')}}" target="_blank">
                                <img src="{{asset('css/main_index_css/Vglogo15225.jpg')}}" alt="Image 1" class="img-responsive">
                            </a>
                        </li>
                        <li>
                            <a href="{{Config::get('custom_url.gujarattourism')}}" target="_blank">
                                <img src="{{asset('css/main_index_css/gujarat-tourism.jpg')}}" alt="Image 2" class="img-responsive">
                            </a>
                        </li>
                        <li>
                        <a href="{{Config::get('custom_url.gujaratindia')}}" target="_blank">
                            <img src="{{asset('css/main_index_css/explore-gujarat.jpg')}}" alt="Image 3" class="img-responsive">
                        </a>
                        </li>
                    </ul>
                </div>
    </div> 
@endsection