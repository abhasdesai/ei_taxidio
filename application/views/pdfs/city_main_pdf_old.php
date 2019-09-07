<?php
// add a page

if(!empty($city_details))
{
foreach ($city_details as $key => $value) 
{
  $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$data = $reports;
  $pdf->setHeaderData($ln='', $lw=0, $ht='', $hs=$value->city_name, $tc=array(0,0,0), $lc=array(0,0,0));
$pdf->setECM($reports=array());

$pdf->SetTitle($name='');
//$pdf->header('as');

$pdf->SetPrintHeader(true);
$pdf->SetPrintFooter(false);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$fontname = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/Calibri.ttf', 'TrueTypeUnicode', '', 32);
//$fontname = TCPDF_FONTS::addTTFfont('system/helpers/tcpdf/fonts/calibrib.ttf', 'TrueTypeUnicode', '', 32);
$pdf->SetFont('Calibri', '', 12);
   $pdf->AddPage();
    $count = 1;
    $html = '<div class="col-sm-12">';
    if($value->city_geographical_location!="")
    {
       
        $html .='<p><b>'.$count.'. Geographical location'.'</b></p>
        <p>'.$value->city_geographical_location.'</p>';
        
        $count++;
    }
    if($value->city_neighbours!="")
    {
       
        $html .='<p><b>'.$count.'. Neighbouring cities/countries'.'</b></p>
        <p>'.$value->city_neighbours.'</p>';
        
        $count++;
    }
    if($value->city_significance!="")
    {
       
        $html .='<p><b>'.$count.'. Significance'.'</b></p>
        <p>'.$value->city_significance.'</p>';
        
        $count++;
    }
    if($value->city_history!="")
    {
       
        $html .='<p><b>'.$count.'. History'.'</b></p>
        <p>'.$value->city_history.'</p>';
        
        $count++;
    }
    if($value->city_cultural_identity!="")
    {
       
        $html .='<p><b>'.$count.'. Cultural identity'.'</b></p>
        <p>'.$value->city_cultural_identity.'</p>';
        
        $count++;
    }
    if($value->city_natural_resources!="")
    {
       
        $html .='<p><b>'.$count.'. Natural resources'.'</b></p>
        <p>'.$value->city_natural_resources.'</p>';
        
        $count++;
    }

    if(!empty($attraction_details[$value->id]))
    {

       
        $html .='<p><b>'.$count.'. Key site seeing places (paid) '.'</b></p>';

        if(!empty($attraction_details[$value->id]['attraction_details']))
        {
           foreach ($attraction_details[$value->id]['attraction_details'] as $key1 => $value1) 
           {

               $html.= '<p><b>'.$value1->attraction_name.'</b></p>';
               $html.= '<p>'.$value1->attraction_details.'</p>';
               if($value1->attraction_known_for!='')
               {
                $html.= '<p>Known For : ';
                $html.= $value1->attraction_known_for.'</p>';
               }
               if($value1->attraction_address!='')
               {
                $html.= '<p>Address : ';
                $html.= $value1->attraction_address.'</p>';
               }
               if($value1->zipcode!='')
               {
                $html.= '<p>Zipcode : ';
                $html.= $value1->zipcode.'</p>';
               }
               if($value1->attraction_lat!='' && $value1->attraction_long!='')
               {
                $html.= '<p>Location : ';
                $html.= $value1->attraction_lat.', '.$value1->attraction_long.'</p>';
               }
               if($value1->attraction_contact!='')
               {
                $html.= '<p>Contact : ';
                $html.= $value1->attraction_contact.'</p>';
               }
               if($value1->attraction_website!='')
               {
                $html.= '<p>Website : ';
                $html.= $value1->attraction_website.'</p>';
               }
               if($value1->attraction_public_transport!='')
               {
                $html.= '<p>Nearest Public Transport : ';
                $html.= $value1->attraction_public_transport.'</p>';
               }
               if($value1->attraction_admissionfee!='')
               {
                $html.= '<p>Entry Fee : ';
                $html.= $value1->attraction_admissionfee.'</p>';
               }
               if($value1->attraction_timing!='')
               {
                $html.= '<p>Open & Close Timing : ';
                $html.= $value1->attraction_timing.'</p>';
               }
               if($value1->attraction_time_required!='')
               {
                $html.= '<p>Time Required : ';
                $html.= $value1->attraction_time_required.'</p>';
               }
               if($value1->attraction_wait_time!='')
               {
                $html.= '<p>Expected Wait Time : ';
                $html.= $value1->attraction_wait_time.'</p>';
               }
               if($value1->attraction_buy_ticket!='')
               {
                $value1->attraction_buy_ticket = ($value1->attraction_buy_ticket == 1) ? 'Yes' : 'No';
                $html.= '<p>Buy Ticket : ';
                $html.= $value1->attraction_buy_ticket.'</p>';
               }
               if($value1->attraction_getyourguid!='')
               {
                $value1->attraction_getyourguid = ($value1->attraction_getyourguid == 1) ? 'Yes' : 'No';
                $html.= '<p>Get Your Guide : ';
                $html.= $value1->attraction_getyourguid.'</p>';
               }
               if($value1->tag_star!='')
               {
                $value1->tag_star = ($value1->tag_star == 1) ? 'Yes' : 'No';
                $html.= '<p>Starting Point & Star : ';
                $html.= $value1->tag_star.'</p>';
               }

               
           } 
        }

    
    if(!empty($adventures_details[$value->id]))
    {
         $html .='<p><b>'.$count.'. Key sites sports & adventure'.'</b></p>';
         if($value->city_adventure_sports!="")
        {  
           $html .= '<p>'.$value->city_adventure_sports.'</p>';   
        }


        

        $html .='<ul>';
        foreach ($adventures_details[$value->id] as $key_ad => $value_ad) 
        {
           $html .='<li>';
           $html .= $value_ad->adventure_name." - ";
           $html .= $value_ad->adventure_details;

          if($value_ad->adventure_address!='')
           {
            $html.= '<p>Address : ';
            $html.= $value_ad->adventure_address.'</p>';
           }
           if($value_ad->adventure_lat!='' && $value_ad->adventure_long!='')
           {
            $html.= '<p>Location : ';
            $html.= $value_ad->adventure_lat.','.$value_ad->adventure_long.'</p>';
           }
           if($value_ad->adventure_contact!='')
           {
            $html.= '<p>Contact No : ';
            $html.= $value_ad->adventure_contact.'</p>';
           }
           if($value_ad->adventure_website!='')
           {
            $html.= '<p>Website : ';
            $html.= $value_ad->adventure_website.'</p>';
           }
           if($value_ad->adventure_nearest_public_transport!='')
           {
            $html.= '<p>Nearest Public Transport : ';
            $html.= $value_ad->adventure_nearest_public_transport.'</p>';
           }
           if($value_ad->adventure_admissionfee!='')
           {
            $html.= '<p>Entry Fees : ';
            $html.= $value_ad->adventure_admissionfee.'</p>';
           }
           if($value_ad->adventure_open_close_timing!='')
           {
            $html.= '<p>Open / Close Time : ';
            $html.= $value_ad->adventure_open_close_timing.'</p>';
           }
           if($value_ad->adventure_time_required!='')
           {
            $html.= '<p>Time Required : ';
            $html.= $value_ad->adventure_time_required.'</p>';
           }
           if($value_ad->adventure_wait_time!='')
           {
            $html.= '<p>Wait Time : ';
            $html.= $value_ad->adventure_wait_time.'</p>';
           }
           
            
           $html .='</li>';

           
        }

        //$count++;
    }
    


    if($value->city_local_sports_stadium!="" || !empty($stadium_details[$value->id]))
    {

       
        $html .='<p><b>'.$count.'. Adventure sports, Local sports, stadiums'.'</b></p>
        <p>'.$value->city_local_sports_stadium.'</p>';
        
        $count++;
    }

    if(!empty($stadium_details[$value->id]))
    {

        
        $html .='<ul>';
        foreach ($stadium_details[$value->id] as $key_stdium => $value_stdium) 
        {
           $html .='<li>';
           $html .= $value_stdium->stadium_name.": ";
           $html .= $value_stdium->stadium_description;
           if($value_stdium->stadium_address!='')
           {
            $html.= '<p>Address : ';
            $html.= $value_stdium->stadium_address.'</p>';
           }
           if($value_stdium->stadium_lat!='' && $value_stdium->stadium_long!='')
           {
            $html.= '<p>Location : ';
            $html.= $value_stdium->stadium_lat.','.$value_stdium->stadium_long.'</p>';
           }
           if($value_stdium->stadium_contact!='')
           {
            $html.= '<p>Contact No : ';
            $html.= $value_stdium->stadium_contact.'</p>';
           }
           if($value_stdium->stadium_website!='')
           {
            $html.= '<p>Website : ';
            $html.= $value_stdium->stadium_website.'</p>';
           }
           if($value_stdium->stadium_timing!='')
           {
            $html.= '<p>Open/Close time : ';
            $html.= $value_stdium->stadium_timing.'</p>';
           }
           
            
           $html .='</li>';
            
        }
        $html .='</ul>';
    }


    if(!empty($spa_details[$value->id]))
    {

        $html .='<p><b>'.$count.'. Spas and relaxation '.'</b></p><p>';
        $html .=$value->relaxation_spa.'</p>';
        $html .='<ul>';
        foreach ($spa_details[$value->id] as $key_spa => $value_spa) 
        {
           $html .='<li>';
           $html .= $value_spa->ras_name.": ";
           $html .= $value_spa->ras_description;
           if($value_spa->ras_address!='')
           {
            $html.= '<p>Address : ';
            $html.= $value_spa->ras_address.'</p>';
           }
           if($value_spa->ras_lat!='' && $value_spa->ras_long!='')
           {
            $html.= '<p>Location : ';
            $html.= $value_spa->ras_lat.','.$value_spa->ras_long.'</p>';
           }
           if($value_spa->ras_contact!='')
           {
            $html.= '<p>Contact No : ';
            $html.= $value_spa->ras_contact.'</p>';
           }
           if($value_spa->ras_website!='')
           {
            $html.= '<p>Website : ';
            $html.= $value_spa->ras_website.'</p>';
           }
           
            
           $html .='</li>';
            
        }
        $html .='</ul>';
    }
    if(!empty($events_details[$value->id]))
    {
        $html .='<p><b>'.$count.'. Events calendar '.'</b></p>';
        $html .='<table border="1">';
        $html .='<tr><td><b>Month</b></td><td><b>Events</b></td></tr>';
        foreach ($events_details[$value->id] as $key_events => $value_events) 
        {
           $html .='<tr><td><b>';
           $html .=$value_events->month_name;
           $html .='</b></td><td>';
           $html .='<p><b>'.$value_events->event_name.'</b></p>';
           $html .='<p>'.$value_events->event_description.'</p>';
            $html .='</td></tr>';
        }
        $html .='<table>';
        
    }
    //echo "dd";die;
    if($value->city_guides_tours!="")
    {
       
        $html .='<p><b>'.$count.'. Key local guides and tours '.'</b></p>
        <p>'.$value->city_guides_tours.'</p>';
        
        $count++;
    }
    if($value->city_transportation_hubs!="")
    {
       
        $html .='<p><b>'.$count.'. Transportation hubs '.'</b></p>
        <p>'.$value->city_transportation_hubs.'</p>';
        
        $count++;
    }
    if($value->city_transportation_costs!="")
    {
       
        $html .='<p><b>'.$count.'. Transportation cost '.'</b></p>
        <p>'.$value->city_transportation_costs.'</p>';
        
        $count++;
    }
    if($value->city_baggage_allowance!="")
    {
       
        $html .='<p><b>'.$count.'. Baggage allowance '.'</b></p>
        <p>'.$value->city_baggage_allowance.'</p>';
        
        $count++;
    }
    if($value->city_food!="")
    {
       
        $html .='<p><b>'.$count.'. Must try food and local savouries  '.'</b></p>
        <p>'.$value->city_food.'</p>';
        
        $count++;
    }
    if($value->restaurant_nightlife!="" || !empty($restaurants_details[$value->id]))
    {
       
        $html .='<p><b>'.$count.'. Restaurant & Nightlife  '.'</b></p>
        <p>'.$value->restaurant_nightlife.'</p>';
        
        $count++;
    }
    if(!empty($restaurants_details[$value->id]))
    {
        $html .='<p>Food - </p>
        <p>'.$value->restaurant_nightlife.'</p>';
        $html .='<ul>';
        foreach ($restaurants_details[$value->id] as $key_restro => $value_restro) 
        {
           $html .='<li>';
           $html .= $value_restro->ran_name.": ";
           $html .= $value_restro->ran_description;
           if($value_restro->ran_address!='')
           {
            $html.= '<p>Address : ';
            $html.= $value_restro->ran_address.'</p>';
           }
           if($value_restro->ran_lat!='' && $value_restro->ran_long!='')
           {
            $html.= '<p>Location : ';
            $html.= $value_restro->ran_lat.','.$value_restro->ran_long.'</p>';
           }
           if($value_restro->ran_contact!='')
           {
            $html.= '<p>Contact No : ';
            $html.= $value_restro->ran_contact.'</p>';
           }
           if($value_restro->ran_website!='')
           {
            $html.= '<p>Website : ';
            $html.= $value_restro->ran_website.'</p>';
           }
           if($value_restro->ran_timing!='')
           {
            $html.= '<p>Open / Close Time : ';
            $html.= $value_restro->ran_timing.'</p>';
           }
           
            
           $html .='</li>';
        }
        $html .='</ul>';
    }
    if($value->city_avg_cost_meal_drink!="")
    {
       
        $html .='<p><b>'.$count.'. Average meal cost  '.'</b></p>
        <p>'.$value->city_avg_cost_meal_drink.'</p>';
        
        $count++;
    }
    if($value->city_shopping!="")
    {
       
        $html .='<p><b>'.$count.'. Shopping  '.'</b></p>
        <p>'.$value->city_shopping.'</p>';
        
        $count++;
    }
    if($value->city_shopping!="")
    {
       
        $html .='<p><b>'.$count.'. Shopping  '.'</b></p>
        <p>'.$value->city_shopping.'</p>';
        
        $count++;
    }
    if($value->city_toursit_benefits!="")
    {
       
        $html .='<p><b>'.$count.'. Tourist benefits  '.'</b></p>
        <p>'.$value->city_toursit_benefits.'</p>';
        
        $count++;
    }
    if($value->city_essential_local_apps!="")
    {
       
        $html .='<p><b>'.$count.'. Essential local apps  '.'</b></p>
        <p>'.$value->city_essential_local_apps.'</p>';
        
        $count++;
    }
    if($value->city_driving!="")
    {
       
        $html .='<p><b>'.$count.'. Driving  '.'</b></p>
        <p>'.$value->city_driving.'</p>';
        
        $count++;
    }
    if($value->city_travel_essentials!="")
    {
       
        $html .='<p><b>'.$count.'. Travel essentials  '.'</b></p>
        <p>'.$value->city_travel_essentials.'</p>';
        
        $count++;
    }
    //Doubts here
    if($value->city_travel_essentials!="")
    {
       
        $html .='<p><b>'.$count.'. Essential clothes  '.'</b></p>
        <p>'.$value->city_travel_essentials.'</p>';
        
        $count++;
    }
    if($value->city_local_currency!="")
    {
       
        $html .='<p><b>'.$count.'. Local currency  '.'</b></p>
        <p>'.$value->city_local_currency.'</p>';
        
        $count++;
    }
    if($value->city_vendor_atm_commission!="")
    {
       
        $html .='<p><b>'.$count.'. Change vendor’s commission, ATM charges  '.'</b></p>
        <p>'.$value->city_vendor_atm_commission.'</p>';
        
        $count++;
    }
    if($value->city_staying_connected!="")
    {
       
        $html .='<p><b>'.$count.'. Staying connected   '.'</b></p>
        <p>'.$value->city_staying_connected.'</p>';
        
        $count++;
    }
    if($value->city_time_zone!="")
    {
       
        $html .='<p><b>'.$count.'. Time Zone    '.'</b></p>
        <p>'.$value->city_time_zone.'</p>';
        
        $count++;
    }
    if($value->city_language_spoken!="")
    {
       
        $html .='<p><b>'.$count.'. Languages   '.'</b></p>
        <p>'.$value->city_language_spoken.'</p>';
        
        $count++;
    }
    if($value->city_political_scenario!="")
    {
       
        $html .='<p><b>'.$count.'. Political Scenario   '.'</b></p>
        <p>'.$value->city_political_scenario.'</p>';
        
        $count++;
    }
    if($value->city_economic_scenario!="")
    {
       
        $html .='<p><b>'.$count.'. Economic Scenario   '.'</b></p>
        <p>'.$value->city_economic_scenario.'</p>';
        
        $count++;
    }
    if($value->city_religion_belief!="")
    {
       
        $html .='<p><b>'.$count.'. Religious belief    '.'</b></p>
        <p>'.$value->city_religion_belief.'</p>';
        
        $count++;
    }
    if($value->city_visa_requirements!="")
    {
       
        $html .='<p><b>'.$count.'. Visa requirements    '.'</b></p>
        <p>'.$value->city_visa_requirements.'</p>';
        
        $count++;
    }
    if($value->city_essential_vaccination!="")
    {
       
        $html .='<p><b>'.$count.'. Essential vaccination    '.'</b></p>
        <p>'.$value->city_essential_vaccination.'</p>';
        
        $count++;
    }
    if($value->city_safety!="")
    {
       
        $html .='<p><b>'.$count.'. Theft and Safety    '.'</b></p>
        <p>'.$value->city_safety.'</p>';
        
        $count++;
    }
    if($value->city_embassies_consulates!="")
    {
       
        $html .='<p><b>'.$count.'. Embassy/Consulate    '.'</b></p>
        <p>'.$value->city_embassies_consulates.'</p>';
        
        $count++;
    }
    if($value->city_restricted_accessibility!="")
    {
       
        $html .='<p><b>'.$count.'. Accessibility    '.'</b></p>
        <p>'.$value->city_restricted_accessibility.'</p>';
        
        $count++;
    }
    if($value->city_emergencies!="")
    {
       
        $html .='<p><b>'.$count.'. Emergencies    '.'</b></p>
        <p>'.$value->city_emergencies.'</p>';
        
        $count++;
    }
    if($value->city_dos_donts!="")
    {
       
        $html .='<p><b>'.$count.'. Do’s and Don’ts     '.'</b></p>
        <p>'.$value->city_dos_donts.'</p>';
        
        $count++;
    }
    if($value->city_tipping!="")
    {
       
        $html .='<p><b>'.$count.'.Tipping basics      '.'</b></p>
        <p>'.$value->city_tipping.'</p>';
        
        $count++;
    }
    
    if($value->city_pet_imp_policies!="")
    {
       
        $html .='<p><b>'.$count.'.Pet info      '.'</b></p>
        <p>'.$value->city_pet_imp_policies.'</p>';
        
        $count++;
    }
    if($value->city_conclusion!="")
    {
       
        $html .='<p><b>Conclusion – Why to select this destination      '.'</b></p>
        <p>'.$value->city_conclusion.'</p>';
        
        $count++;
    }
    
    if(!empty($tag_array[$value->id]))
    {
         $html .='<p><b>Tags for the city - Known for      '.'</b></p>
        <p>'.implode(", ", $tag_array[$value->id]).'</p>';
        
        $count++;
    }
//restaurant_nightlife
   // print_r($tag_array[$value->id]);die;
    //echo '<pre>';print_r($restaurants_details[$value->id]);die;

        

        

        $count++;
    }
    $html .='</div>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->SetPrintHeader(true);
    // add a page
    //$pdf->AddPage();

    $pdf->SetPrintFooter(true);


    $pdf->Output($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/".$value->slug.".pdf", 'F');//$flag
    //$pdf->Output($_SERVER['DOCUMENT_ROOT']."taxidio/uploads/city_pdfs/pdf1.pdf", 'F');//$flag
    if($key == 1)
    {
        echo "ff";die;
    }

    

}
}
//print_r($html);die;


// create some HTML content


// output the HTML content


// reset pointer to the last page
//$pdf->lastPage();


//$pdf->lastPage();

    
    