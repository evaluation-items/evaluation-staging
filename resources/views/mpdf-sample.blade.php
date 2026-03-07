<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    <style> 
      @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@300;500&display=swap');
      @page { size: portrait; margin: 20px 20px;}
      body {font-family: 'Noto Sans Devanagari', sans-serif; /* background-image: url("logo.png"); background-repeat: repeat-y; */-webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;}
      footer { display: block; /*position: fixed;*/bottom: 0px;left: 0;width: 100%; padding-top: 50px;}
      .page-break { page-break-before: always;}
      h2 { font-size: 14px;line-height: 1.5;margin: 0;}
      h3 {font-size: 17px;line-height: 1.5;margin: 0;}
      h5 {font-size: 14px;line-height: 1.5;margin: 0;}
      table,th,td {border: 0px;line-height: 1.2;border-collapse: collapse;}
      .border-tbl_bdr{ border: 1px solid #000 !important;font-size: 12px;}
      .border-tbl_bdr_td td { border: 1px solid #000 !important;font-size: 12px;padding: 3px;}
      .border-tbl_bdr_td_left td {border: 1px solid #000 !important;font-size: 12px;text-align: left;padding: 3px;}
      .border-tbl_bdr_td th {border: 1px solid #000 !important;font-size: 15px;padding: 3px;}
      .text-center{text-align: center;}
      .bold{font-weight: bold;}
      .text-left {text-align: left;}
      .text-right {text-align: right;}
      .text-blue {color: #022869;}
      .text-red { color: #b0070d;}
      .text-green {color: #056900;}
      .text-brown {color: #450104;}
      .top-right {text-align: right;font-weight: bold;font-size: 14px;}
      .top-right span {text-transform: uppercase;}
      .bg-dark {background-color: #0f74bb;color: #fff !important;}
      .padd-3 tr td { padding: 3px;}
      .font-12 tr td {font-size: 13px;}
      .font-13 tr td {font-size: 13px;padding: 5px;}
      .font-15 { font-size: 15px !important; padding: 5px !important;font-weight: bold;}
      .mt-5 { margin-top: 10px;}
      .bg-gray {background-color: #dddddd;}
      .left_sec td:nth-child(2) {text-align: left !important; padding: 4px 5px;}
      .p-4 {padding: 5px;}
      .pt-5 {padding-top: 100px !important;}
      .mt-5 {margin-top: 10px;}
      .mt-15 { margin-top: 20px;}
      table.font-12.head tr td {line-height: 1.5;}
      .padd-4 tr td {padding: 0 20px;line-height: 2;}
      .left-side tr td {line-height: 2;}
      .note {font-weight: bold;text-decoration: underline;}
      .bracket span {border-bottom: 1px dotted #000;padding: 0 10px;}
      header {border-bottom: 1px solid #ddd;padding: 10px 0;}
      .custom_tbl tr th{width: 60%; text-align: left; font-size: 13px;padding: 0px 10px;}
      .custom_tbl tr th span{font-size: 12px;}
      .custom_tbl tr td{width: 40%;}
    </style>
  </head>
  <body>
    <header>
      <table class="font-12 head" style="width: 100%;">
        <tbody>
          <tr>
            <td style="width: 15%;"></td>
            <td style="width: 70%;">
              <table style="width: 100%;">
                <tr>
                  <td class="text-center text-blue">
                    <p>ગુજરાત મેરીટાઈમ બોર્ડ </p>
                  </td>
                </tr>
                <tr>
                  <td class="text-center text-red">
                    <p>રજા માટેની અરજી </p>
                  </td>
                </tr>
              </table>
            </td>
            <td style="width: 15%;"></td>
          </tr>
        </tbody>
      </table>
    </header>
    <table class=" mt-3" style="width: 100%; margin-top: 20px;">
      <tr>
        <td class="text-center text-blue">જુઓ મુંબઈ રાજ્ય સેવા નિયમો, પુસ્તક-૧ ના નિયમ : ૬૫</td>
      </tr>
    </table>
    <table class="font-12 padd-4" style="width: 100%; margin-top: 20px;">
      <tr> <td class="text-left">બાબત ૧૩ રાજ્યપ્રસ્તુત અધિકારીઓને લાગુ પડે છે.</td> <td class="text-right text-blue bold">Application No. : </td></tr>
      <tr><td class="text-left" colspan="2">બાબત ૧૪ અને ૧૫ બિનરાજ્યપત્રિત અધિકારીશ્રીઓને લાગુ પડે છે.</td></tr>
    </table>
    <table class=" border-tbl_bdr_td_new custom_tbl font-12 mt-3 padd-4" style="width: 100%;">
      <tr>
        <td>૧. અરજદારનું નામ:</td>
        <td><b>{{$name}}</b></td>
      </tr>
      <tr>
        <td>૨. લાગુ પડતા રજાના નિયમો:</td>
        <td> - </td>
      </tr>
      <tr>
        <td>૩. જે જગ્યા ધરાવતા હોય તે:</td>
        <td><b></b></td>
      </tr>
      <tr>
        <td>૪. વિભાગ અથવા કચેરી:</td>
        <td><b> </b></td>
      </tr>
      <tr>
        <td>૫. પગાર: </td>
        <td></td>
      </tr>
      <tr>
        <td>૬. હાલની જગ્યા પર મળતું ઘરભાડા ભથ્થું, : <br> <span>(વાહન-ભથ્થું અને અન્ય વળતર ભથ્થું)</span></td>
        <td> - </td>
      </tr>
      <tr>
        <td>૭. જોઈતી રજાના પ્રકાર અને મુદત અને :<br> <span>(રજાના પ્રારંભની તારીખ ૭(ક) રજાની આગળ કે પાછળ જોડવા હોય તે રવિવારો તથા રજાના દિવસો)</span> </td>
       
            <td style="width: 50%;display: inline-block;border-right: 1px solid black;">
                 <br>
                તા. 
                 થી  સુધી <br>
                દિવસ - <br>
            </td>
          
      </tr>
      <tr>
        <td>૮. રજા માંગવાના કારણો:</td>
        <td></td>
      </tr>
      <tr>
        <td>૯. છેલ્લી રજા પરથી પાછા ફર્યાની તારીખ અને રજાના પ્રકાર :</td>
        <td> </td>
      </tr>
      <tr>
        <td>૧૦. મારી રજા ચાલું હોય તે દરમ્યાન નિવૃત્ત :<span> (થાઉ તો રજાના તફાવત વચ્ચેની રકમ હું ભરપાઈ કરવા બાંહેધરી આપું છું.)</span></td>
        <td> Yes </td>
      </tr>
      <tr>
        <td>૧૧. લેણી ન હોય અને રજાઓ ભોગવી હોય તો : <span>(તેવી રજાઓનો પગા૨ ભરપાઈ કરવા હું બાંહેધરી આપું છું.)</span></td>
        <td> Yes </td>
      </tr>
      <tr>
        <td>૧૨. ૨જા સમય દરમ્યાન રહેઠાણ અંગેનું સરનામું (ફરજિયાત દર્શાવવાનું રહેશે.)</td>
        <td> </td>
      </tr>
    </table>

    <table class="font-12" style="width: 100%; margin-top: 20px;">
      <tr>
       
        <td class="bracket">તારીખ : <span> </span></td>
        <td class="bracket text-right">અરજદારની સહી: <br><br>  <br><br> હોદ્દો: <span></span></td>
      </tr>
    </table>

    <table class="font-12 padd-4" style="width: 100%; margin-top: 10px;">
      <tr><td class="bracket">૧૩. નિયંત્રણ અધિકારીનો અભિપ્રાય અને ભલામણ :</td><td class="bracket">શ્રી/શ્રીમતી/કુ <span> </span></td></tr>
      <tr><td class="bracket">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; નિયંત્રણ અધિકારીની સહી :  <span></span> </td><td class="bracket">ની ૨જા દરમ્યાન તેઓની જગ્યાનો ચાર્જ શ્રી/</td></tr>
      <tr><td class="bracket">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; નિયંત્રણ અધિકારીનો હોદ્દો :  <span></span> </td><td class="bracket">શ્રીમતી/કુ.<span></span>સંભાળશે.</td></tr>
      <tr><td class="bracket">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; તારીખ :  <span></span> </td><td class="bracket"></td></tr>
    </table>

    <table class="font-12" style="width: 100%; margin-top: 20px;">
      <tr><td>૧૪. ઓડીટ અધિકારીનો અહેવાલ</td></tr>
      <tr>
        <td class="bracket">તારીખ : <span></span></td>
        <td class="bracket text-right">અરજદારની સહી <br><br><br><br> હોદ્દો: <span> </span></td>
      </tr>
    </table>

    <div style="break-after:page"></div><br>

    <table class="font-12 padd-4" style="width: 100%;">
      <tr><td class="bracket">૧૫. આ અરજી પહેલાં અરજદારે મેળવેલી ૨જાની વિગતો</td></tr>
    </table>


    <table class=" border-tbl_bdr_td font-12 padd-4" style="width: 100%; margin-top: 10px;">
      <tr class="text-center">
        <td style="width: 55%;">રજાના પ્રકાર</td>
        <td style="width: 15%;">ચાલુ વર્ષમાં</td>
        <td style="width: 15%;">ગયા વર્ષ દરમ્યાન</td>
        <td style="width: 15%;">કુલ</td>
      </tr>
      <tr> <td class="bracket">સરેરાશ પગારે <br> <span></span></td> <td class="text-center">--</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">તબીબી પ્રમાણપત્ર પર સરેરાશ પગારે <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">અર્ધ સરેરાશ પગારે <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">તબીબી પ્રમાણપત્ર પર અર્ધ સરેરાશ પગારે <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">ચોથા ભાગના પગારે <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">તબીબી પ્રમાણપત્ર પર ચોથા ભાગના સરેરાશ પગારે <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">ચઢેલી રજા <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">અર્ધ-પગારી રજા <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">રૂપાંતરિત રજા <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">લેણી ન થતી રજા <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">અસાધારણ રજા <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
      <tr> <td class="bracket">કુલ  <br> <span>Data</span></td> <td class="text-center">-</td><td class="text-center">-</td><td class="text-center">-</td></tr>
    </table>


    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class="text-center">મૂળભૂત નિયમો</td></tr>
      <tr><td class="bracket">૧૬. આથી પ્રમાણિત કરવામાં આવે છે કે ગુજરાત મેરીટાઈમ બોર્ડના નિયમ <span>Data</span> અનુસાર <span>Data</span>૧૯૯  થી <span>Data</span></td></tr>
    </table>

    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class="text-center">સુધારેલા રજા નિયમ</td></tr>
      <tr><td class="text-center">ચઢેલી રજા</td></tr>
      <tr><td class="bracket">તા <span>Data</span> ૧૯૯ સુધીની. <span>Data</span> મહિના <span>Data</span> દિવસની અર્ધ-પગારી રજા મળવાપાત્ર છે.</td></tr>
    </table>

    <div style="break-after:page"></div><br>

    <table class="font-12 padd-4" style=" width: 100%;">
      <tr><td colspan="2" class="text-center">સરેરાશ પગારે રજા</td></tr>
      <tr><td class="bracket ">તારીખઃ  <span>Data</span> </td><td class="bracket text-right">(સહી)   <span>Data</span> <br><br> (હોદો) <span>Data</span></td></tr>
    </table>


    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class=" text-left">૧૭. મંજૂરી આપતા અધિકારીનો હુકમ</td></tr>
      <tr><td class="bracket ">તારીખઃ  <span>Data</span> </td><td class="bracket text-right">(સહી)   <span>Data</span> <br><br> (હોદો) <span>Data</span></td></tr>
    </table>

    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class="bracket">અરજદારને વળતર મળતું હોય તો રજા પૂરી થયે આ જગ્યા પર કે આવા જ ભથ્થાવાળી બીજી કોઈ જગ્યા પર તેમના પાછા ફરવાની શકયતા છે કે કેમ તે મંજૂરી આપતા અધિકારીએ જણાવવું જોઈએ.</td></tr>
    </table>


    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class=" text-center text-blue">પરિશિષ્ટ-૧ </td></tr>
      <tr><td class="text-left">નિયંત્રણ અધિકારીએ બિનરાજયપત્રિત કર્મચારીઓ માટેનું આપવાનું પ્રમાણપત્ર</td></tr>
      <tr><td>(૧) પ્રમાણિત કરવામાં આવે છે કે શ્રી/શ્રીમતી/કુમારી (સરકારી કર્મચારીનું નામ) એ મુખ્ય મથકેથી  રજા પ્રવાસ/ વતન પ્રવાસમાં જવા અંગેનો પ્રવાસ શરૂ થાય તે તારીખે, એક વર્ષની અથવા તેથી વધુ મુદતની સળંગ નોકરી કરી છે. </td></tr>
      <tr><td class="bracket">(૨)	(2)	નાણાં વિભાગના તા.૨૮/૦૮/૨૦૧૫ ના સરકારી ઠરાવ નંબર: મસભ/૧૦૨૦૧૩/૧૪૨૯૬૯/ચ ના ફકરા-૯ અનુસાર જરૂરીયાત પ્રમાણે જરૂરી નોંધો શ્રી/શ્રીમતી/કુમારી <span>Data</span> ની સેવાપોથીમાં કરી છે.</td></tr>
      <tr><td class="text-right text-blue">(નિયંત્રણ અધિકારીની સહી અને હોદ્દો)</td></tr>
      <tr><td class="text-left text-red">*(નોન ગેઝેટેડ અધિકારીઓ માટે)</td></tr>
    </table>

     <hr>

    <table class="font-12 padd-4" style="margin-top: 20px; width: 100%;">
      <tr><td class=" text-center text-blue">પરિશિષ્ટ-૨ </td></tr>
      <tr><td class="text-left">નિયંત્રણ અધિકારીએ બિનરાજયપત્રિત કર્મચારીઓ માટેનું આપવાનું પ્રમાણપત્ર</td></tr>
      <tr><td>(૧) સને ૨૦ અને સન ૨૦   ના ચાર વર્ષના સમયગાળા સંબંધે મારા પોતાના માટે અથવા મારા કુટુંબ માટે રજા પ્રવાસ રાહત / વતન પ્રવાસ પુરતી બીજી કોઇ પણ માગણી મેં રજુ કરી નથી.</td></tr>
      <tr><td>(૨)	મેં/ મારી પત્નીએ બાળકો સાથે/ મેં પોતે કરેલા પ્રવાસ સંબંધી રજા પ્રવાસ રાહત માટેનું પ્રવાસ ભથ્થું મેં આકાર્યું છે. માગણી, અગાઉના પ્રસંગે તે ટુકડી સાથે પ્રવાસ નહી કરનાર બાળકો સાથે મારી પત્નીએ/ મેં પોતે કરેલ પ્રવાસ સંબંધી છે.</td></tr>
      <tr><td>(૩)	આ પ્રવાસ, બાળકો સાથે મેં/ પત્નીએ, એકારાર કરાયેલો વતન પ્રવાસ/ રજા પ્રવાસ રાહત જવા માટે કર્યો છે</td></tr>
      <tr><td>(૪)	પ્રમાણિત કરવામાં આવે છે કે માસ પતિ મારી પત્ની સરકારી નોકરીમાં નથી. પ્રમાણિત કરવામાં આવે છે કે મારા પતિ/ મારી પત્ની સરકારી નોકરીમાં છે અને સંબંધિત ચારવર્ષના સમયગાળા માટે તેમણે અથવા કુટુંબના કોઇ સભ્યો માટે આ છુટછાટનો લાભ લીધો નથી. </td></tr>
      <tr><td class="text-right text-blue">સરકારી કર્મચારીની સહી</td></tr>
      <tr><td class="text-left text-red">*(લાગુ ન પડતું તે કાઢી નાખવું.)</td></tr>
    </table>


  </body>
</html>