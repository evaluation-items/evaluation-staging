@extends('layouts.app')
@section('content')

<div class="container">
    <div class="col-lg-9 col-md-8 col-sm-12 left_col advisory-wrapper">
            <h4 class="page-title text-center mt-3">Help</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped who-table">
                    <thead class="table-dark">
                        <tr>
                             <tr>
                                <th colspan="2">Viewing different file formats&nbsp;</th>
                            </tr>
                            <tr>
                                <th>Document Type</th>
                                <th>Download</th>
                            </tr>
                        </tr>
                    </thead>
                    <tbody>
                       <tr>
                            <td>Portable Document Format (P.D.F.) content</td>
                            <td><a href="https://get.adobe.com/reader/otherversions/" target="_blank">Adobe Acrobat Reader</a></td>
                        </tr>
                        <tr>
                             <td>Word files</td>
                            <td><a href="https://www.microsoft.com/en-us/download/details.aspx?id=4" target="_blank">Word Viewer Microsoft Office Compatibility Pack for Word (for 2007 version)</a></td>
                        </tr>
                        <tr>
                            <td>Excel files</td>
                            <td><a href="https://www.microsoft.com/en-us/download/" target="_blank">Excel Viewer Microsoft Office Compatibility Pack for Excel (for 2007 version)</a></td>
                        </tr>
                        <tr>
                            <td>PowerPoint presentations</td>
                            <td><a href="https://www.microsoft.com/en-us/download/" target="_blank">PowerPoint Viewer Microsoft Office Compatibility Pack for PowerPoint (for 2007 version)</a></td>
                        </tr>
                        <tr>
                            <td>Flash content</td>
                            <td><a href="https://get.adobe.com/flashplayer/" target="_blank">Adobe Flash Player(Other Government website that opens in a new window)</a></td>
                        </tr>
                        <tr>
                            <td>Audio Files</td>
                            <td><a href="https://www.microsoft.com/en-in/download/windows-media-player-details.aspx" target="_blank">Windows Media Player</a></td>
                        </tr>
                    </tbody>
                </table>
           </div>
           <p  class="content-text">
                 <div class="help_info">
                        <h4>Accessibility Help</h4>

                        <p>Use the accessibility options provided by this Website to control the screen display. These options allow increasing the text spacing, changing the text size and colour scheme for clear visibility and better readability.</p>

                        <p><strong>Text Size Icons</strong> Following different options are provided in the form of icons which are available on the top of each page:</p>

                        <ul>
                            <li>
                            <div class="txt-size">A+</div>
                                <span><strong>Increase text size:</strong> Allows to increase the text size up to one levels</span></li>
                            <li>
                            <div class="txt-size">A-</div>
                                <span><strong>Decrease font size/Decrease text size:</strong> Allows to decrease the text size up to one level</span></li>
                            <li>
                            <div class="txt-size">A</div>
                               <span><strong>Normal text size:</strong> Allows to set default text size</span></li>
                        </ul>
                    </div>

                    <div class="help_info">
                          <h4>Changing the Colour Scheme</h4>

                        <p>Changing the colour scheme refers to applying a suitable background and text colour that ensures clear readability. There are three options provided to change the colour scheme. These are:</p>

                        <ul>
                            <li>
                            <div class="txt-size bg-white">A</div>
                            <span>Default contrast scheme</span></li>
                            <li>
                            <div class="txt-size bg-orange">A</div>
                            <span>Black text on Orange background</span></li>
                            <li>
                            <div class="txt-size bg-green">A</div>
                            <span>Black text on Green background.</span></li>
                        </ul>
                    </div>

                    <p class="note"><strong>Note:</strong> Changing the colour scheme does not affect the images on the screen.</p>

                    <div class="btm_content">
                        <ul>
                            <li><a href="https://gujecostat.gujarat.gov.in/accessibility-statement">Accessibility Statement</a></li>
                            <li><a href="https://gujecostat.gujarat.gov.in/accessibility-options">Accessibility Options in browsers</a></li>
                        </ul>
                    </div>
           </p>
    </div>
</div>
@endsection