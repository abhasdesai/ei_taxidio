<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'Home';
$route['default_controller'] = 'Home';
$route['getData']='Home/getData';
$route['refresh']='Home/refresh';
$route['getAutoSuggestion']='Home/getAutoSuggestion';
$route['recommendedformsubmit']='Home/recommendedformsubmit';
$route['searchformsubmit']='Attractions/searchformsubmit';
$route['getAttractionData']='Home/getAttractionData';
$route['country-recommendation'] = 'Home/country_recommendation';
$route['city-attractions-ajax'] = 'Home/getCityAttractions';
$route['single-city-attractions-ajax'] = 'Attractions/getSingleCityAttractions';
$route['getAllAttractionsOfCity'] = 'Home/getAllAttractionsOfCity';
$route['attractions/(:any)/(:any)']='Home/attractions/$1/$2';
$route['getSuggestedCities']='Home/getSuggestedCities';
$route['cityAttractions']='Attractions/cityAttractions';
$route['alterAttraction']='Attractions/alterAttraction';
$route['saveOrder']='Attractions/saveOrder';
$route['saveAllOrder']='Attractions/saveAllOrder';
$route['saveSingleCountryXOrder']='Attractions/saveSingleCountryXOrder';
$route['saveSearchCityXOrder']='Attractions/saveSearchCityXOrder';
$route['saveMultiCountryXOrder']='Attractions/saveMultiCountryXOrder';


$route['openPopAjax']='Attractions/openPopAjax';
$route['alterMainAttraction']='Attractions/alterMainAttraction';
$route['addNewActivity']='Attractions/addNewActivity';
$route['addNewActivitySingle']='Attractions/addNewActivitySingle';
$route['addNewActivityMulti']='Attractions/addNewActivityMulti';
$route['getAllAttractionsOfSingleCity']='Attractions/getAllAttractionsOfSingleCity';
$route['getUserAttractionsOfSingleCountry']='Attractions/getUserAttractionsOfSingleCountry';
$route['alterMainAttractionSingle']='Attractions/alterMainAttractionSingle';
$route['saveOrderSingle']='Attractions/saveOrderSingle';
$route['multicountries/(:any)/(:any)']='Attractions/multicountries/$1/$2';
$route['attractionsFromGYG/(:any)/(:any)/(:any)']='Attractions/attractionsFromGYG/$1/$2/$3';
$route['multicity-attractions-ajax'] = 'Attractions/multicity_attractions_ajax';
$route['getDataForNewCountry'] = 'Attractions/getDataForNewCountry';
$route['saveMultiOrder'] = 'Attractions/saveMultiOrder';
$route['alterMultiAttraction']='Attractions/alterMultiAttraction';
$route['getAllAttractionsOfMultiCity'] = 'Attractions/getAllAttractionsOfMultiCity';
$route['alterCity'] = 'Attractions/alterCity';
$route['alterMultiCountryCity'] = 'Attractions/alterMultiCountryCity';
$route['signupUser'] = 'home/signupUser';
$route['signinUser'] = 'home/signinUser';
$route['logout'] = 'account/logout';
$route['save-itinerary/(:any)'] = 'account/save_itinerary/$1';
$route['save-multi-itinerary/(:any)/(:any)'] = 'account/save_multi_itinerary/$1/$2';
$route['save-searched-itinerary/(:any)'] = 'account/save_searched_itinerary/$1';
$route['showHotels/(:any)'] = 'Hotels/showHotels/$1';
$route['hotel-search-engine'] = 'home/hotels';
$route['discount-attraction-tickets'] = 'home/allattractions';
$route['searched-attraction/(:any)'] = 'attractions/getSearchedAttractions/$1';
$route['planneditineraries'] = 'home/planneditineraries';
$route['allattractions'] = 'home/allattractions';
$route['showSearchedCityHotels/(:any)'] = 'Hotels/showSearchedCityHotels/$1';
$route['city/(:any)'] = 'home/city/$1';
$route['showHotelsOfMultiCountries/(:any)'] = 'Hotels/showHotelsOfMultiCountries/$1';
$route['country/(:any)'] = 'Home/country/$1';
$route['searchAttractionsFromGYG'] = 'Attractions/searchAttractionsFromGYG';
$route['cityAttractionFromGYG/(:any)/(:any)/(:any)'] = 'Attractions/cityAttractionFromGYG/$1/$2/$3';
$route['fblogin'] = 'Home/fblogin';
$route['rfun'] = 'Home/rfun';
$route['addExtraCity'] = 'Attractions/addExtraCity';
$route['removeExtraCity'] = 'Attractions/removeExtraCity';
$route['testindex'] = 'Home/testindex';
$route['testdropdown'] = 'Home/testdropdown';
$route['multiple-destination-trip-planner'] = 'Destination';
$route['browse-destinations'] = 'Destination/browse_destinations';
$route['browse-destinations/(:num)']='Destination/browse_destinations/$1';
//CMS

$route['terms-and-condition'] = 'Cms/terms_and_condition';
$route['faq'] = 'Cms/faq';
$route['contactus'] = 'Cms/contactus';
$route['postcontactus'] = 'Cms/postcontactus';
$route['our-team'] = 'Cms/team';
$route['pricing'] = 'Cms/pricing';
$route['media'] = 'Cms/media';
$route['credit'] = 'Cms/credit';
$route['taxidio-for-business'] = 'Cms/api';
$route['contest'] = 'Cms/contest';
$route['apimodel'] = 'Cms/apimodel';
$route['thank-you'] = 'Cms/thankyou';
$route['thankyou'] = 'Cms/thankyouforapi';
$route['attractions-info/(:any)'] = 'Home/attractions_info/$1';


//$route['career'] = 'Cms/career';
$route['cookie'] = 'Cms/cookie';
$route['user-content-&-conduct-policy'] = 'Cms/user_content';
$route['policies'] = 'Cms/privacy_policy';
$route['discover-taxidio'] = 'Cms/discover_taxidio';
$route['membership'] = 'Cms/membership';

//Account
$route['profile'] = 'myaccount/profile';
$route['myprofile'] = 'myaccount/myprofile';
$route['editUser'] = 'myaccount/editUser';
$route['trip/(:any)'] = 'myaccount/trip/$1';
$route['img_save_to_file_profile'] = 'myaccount/img_save_to_file_profile';
$route['img_crop_to_file_profile'] = 'myaccount/img_crop_to_file_profile';
$route['removeProfileImage'] = 'myaccount/removeProfileImage';
$route['removeProfileImageFromStorage'] = 'myaccount/removeProfileImageFromStorage';
$route['uploadImage'] = 'myaccount/uploadImage';
$route['changepassword'] = 'account/changepassword';


// Feedback

$route['userfeedbacks']='Feedback/userfeedbacks';
$route['userfeedbacks/(:any)']='Feedback/userfeedbacks/$1';
$route['createFeedback']='Feedback/createFeedback';
$route['sendFeedback']='Feedback/sendFeedback';
$route['viewFeedback/(:any)']='Feedback/viewFeedback/$1';
$route['deleteFeedback/(:any)']='Feedback/deleteFeedback/$1';

// Forum

$route['discuss']='Forum/discuss';
$route['ask-question/(:any)']='Forum/ask_question/$1';
$route['myquestions']='Forum/myquestions';
$route['myquestions/(:any)']='Forum/myquestions/$1';
$route['addQuestion/(:any)']='Forum/addQuestion/$1';
$route['forum/(:any)']='Forum/questionInfo/$1';






//Trip

$route['trips/(:num)'] = 'myaccount/trips/$1';
$route['currenttrip'] = 'myaccount/currenttrip';
$route['currenttrip/(:num)'] = 'myaccount/currenttrip/$1';
$route['upcommingtrip'] = 'myaccount/upcommingtrip';
$route['upcommingtrip/(:num)'] = 'myaccount/upcommingtrip/$1';
$route['completedtrip'] = 'myaccount/completedtrip';
$route['completedtrip/(:num)'] = 'myaccount/completedtrip/$1';
$route['trips'] = 'myaccount/trips';
$route['deleteTrip/(:any)'] = 'trips/deleteTrip/$1';
$route['editTrip/(:any)'] = 'trips/editTrip/$1';
$route['updateTrip/(:any)'] = 'trips/updateTrip/$1';
$route['getSavedCityAttractions'] = 'Myaccount/getSavedCityAttractions';

$route['alterSavedCity'] = 'Myaccount/alterSavedCity';
$route['saveOrderSaved'] = 'Myaccount/saveOrderSaved';

$route['updatesave-multi-itinerary/(:any)'] = 'account/updatesave_multi_itinerary/$1';
$route['update-searched-itinerary/(:any)'] = 'account/update_searched_itinerary/$1';




//Searched city trips
$route['userSearchedCityTrip/(:any)'] = 'Trips/userSearchedCityTrip/$1';
$route['getAllAttractionsOfSingleCitySaved'] = 'Trips/getAllAttractionsOfSingleCitySaved';
$route['getUserAttractionsOfSingleCountrySaved'] = 'Trips/getUserAttractionsOfSingleCountrySaved';
$route['saveOrderSingleSaved'] = 'Trips/saveOrderSingleSaved';
$route['alterMainAttractionSingleSaved'] = 'Trips/alterMainAttractionSingleSaved';
$route['removeExtraCityFromSave'] = 'Trips/removeExtraCityFromSave';
$route['addExtraCityInSaved'] = 'Trips/addExtraCityInSaved';
$route['addNewActivityUserSavedSearchedCity'] = 'Trips/addUserActivityToSingleCountrySaved';
$route['addNewActivityToSavedSearchedCity'] = 'Trips/addNewActivityToSavedSearchedCity';
$route['savedSingleCountryXOrder'] = 'Trips/savedSingleCountryXOrder';
$route['savedMultiCountryXOrder'] = 'Trips/savedMultiCountryXOrder';

// New Routes

$route['userSingleCountryTrip/(:any)'] = 'Trips/userSingleCountryTrip/$1';
$route['updateUserSingleCountryTrip/(:any)'] = 'Account/update_single_itinerary/$1';
$route['alterMainAttractionSaved']='Trips/alterMainAttractionSaved';
$route['getUserSavedSingleCountryAttractions']='Trips/getUserSavedSingleCountryAttractions';
$route['getAllAttractionsOfCitySaved'] = 'Trips/getAllAttractionsOfCitySaved';
$route['saveSingleListing'] = 'Trips/saveSingleListing';
$route['alterSavedSingleCountryCity'] = 'Trips/alterSavedSingleCountryCity';

$route['savedSearchedCityXOrder'] = 'Trips/savedSearchedCityXOrder';


$route['hotelListsForSavedCountry/(:any)'] = 'Triphotels/hotelListsForSavedCountry/$1';
$route['hotelListsForSavedCountry-ajax/(:any)'] = 'Triphotels/hotelListsForSavedCountry_ajax/$1';

//Multi countries
$route['multicountrytrips/(:any)'] = 'myaccount/multicountrytrips/$1';
$route['savedmulticity-attractions-ajax'] = 'Trips/savedmulticity_attractions_ajax';
$route['alterSavedMultiAttraction'] = 'Trips/alterSavedMultiAttraction';
$route['saveMultiOrderSaved'] = 'Trips/saveMultiOrderSaved';
$route['alterSavedMultiCountryCity'] = 'Trips/alterSavedMultiCountryCity';
$route['getDataForNewCountryMultiSaved'] = 'Trips/getDataForNewCountryMultiSaved';
$route['getAllAttractionsOfMultiCitySaved'] = 'Trips/getAllAttractionsOfMultiCitySaved';
$route['addUserActivityToSingleCountrySaved'] = 'Trips/addUserActivityToSingleCountrySaved';
$route['addNewActivitySavedMultiCountry'] = 'Trips/addNewActivitySavedMultiCountry';


$route['hotelListsForSavedMultiCountry/(:any)'] = 'Triphotels/hotelListsForSavedMultiCountry/$1';
$route['hotelListsForSavedMultiCountry-ajax/(:any)'] = 'Triphotels/hotelListsForSavedMultiCountry_ajax/$1';

$route['hotelListsForsearchedCity/(:any)'] = 'Triphotels/hotelListsForsearchedCity/$1';
$route['hotelListsForsearchedCity-ajax/(:any)'] = 'Triphotels/hotelListsForsearchedCity_ajax/$1';


$route['addSubscriber']='Home/addSubscriber';
$route['forgotPassword']='Home/forgotPassword';
$route['reset-password/(:any)/(:any)']='Home/reset_password/$1/$2';
$route['update_reset_password/(:any)/(:any)']='Home/update_reset_password/$1/$2';

$route['auth']='Home/auth';
$route['rememberUrl']='Home/rememberUrl';

$route['admins'] = 'admins/dashboard';
$route['404_override'] = 'Customerror';
$route['translate_uri_dashes'] = TRUE;


$route['planned-itinerary']='Itineraries/planned_itineraries';
$route['planned-itinerary/(:num)']='Itineraries/planned_itineraries/$1';

$route['planned-itinerary-forum/(:any)']='Itineraries/planned_itinerary_forum/$1';
$route['getPublicSavedCityAttractions']='Itineraries/getPublicSavedCityAttractions';
$route['getPublicSavedSingleCityAttractions']='Itineraries/getPublicSavedSingleCityAttractions';
$route['getPublicSavedMulticountryCityAttractions']='Itineraries/getPublicSavedMulticountryCityAttractions';
$route['getNewCountryDataFromitinerary']='Itineraries/getNewCountryDataFromitinerary';
$route['loadQuestions']='Itineraries/loadQuestions';
$route['itinerary-discussion/(:any)']='Itineraries/itinerary_discussion/$1';

$route['postComment']='Itineraries/postComment';
$route['deleteComment']='Itineraries/deleteComment';
$route['editComment']='Itineraries/editComment';
$route['deleteQuestion']='Itineraries/deleteQuestion';
$route['browse-itinerary']='Itineraries/browse_itinerary';
$route['browse-itinerary/(:num)']='Itineraries/browse_itinerary/$1';
$route['browse-itinerary-by-country']='Itineraries/browse_itinerary_by_country';
$route['copy-itinerary']='Itineraries/copy_itinerary';
$route['store-rating']='Itineraries/store_rating';

//Phase 2

$route['notification-viewed']='myaccount/notification_viewed';

//itinerary-share
$route['trip-notification']='Share_iti/trip_notification';
$route['invited-trips']='Share_iti/invited_trips';
$route['share-iti-with-email']='Share_iti/share_iti_with_email';
$route['getCoTravellers']='Share_iti/co_travellers';
$route['getCoTravellers/(:num)']='Share_iti/co_travellers/$1';
$route['check-iti-is-public']='Share_iti/get_public_modal';
// $route['share-iti-with-facebook']='Share_iti/iti_share_by_facebook2';
// $route['share-iti-with-facebook/(:any)']='Share_iti/iti_share_by_facebook/$1';
$route['share-iti-with-twitter/(:any)']='Share_iti/twitter_login/$1';
$route['share-iti-with-twitter']='Share_iti/iti_share_by_twitter';
$route['itinerary-share-by-email']='Share_iti/iti_share_by_email';
$route['share-iti-with-member-form']='Share_iti/share_iti_with_member_form';
$route['share-iti-with-member']='Share_iti/share_iti_with_member';
$route['invited-trip-status-update']='Share_iti/invited_trip_status_update';
$route['deleteInvitedTrip/(:any)']='Share_iti/deleteInvitedTrip/$1';
$route['notifications']='myaccount/notifications';
$route['addNewNote']='Attractions/addNewNote';
$route['delNote']='Attractions/delNote';
$route['getNote']='Attractions/getNote';

//Calendar Events For Hotels and activities

$route['addCalendarEvent'] = 'myaccount/addCalendarEvent';
$route['getCalEventDetails'] = 'myaccount/getCalEventDetails';
$route['deleteCalNote'] = 'myaccount/deleteCalNote';
$route['refer-friend'] = 'myaccount/referFriend';
$route['pdf_download'] = 'myaccount/pdfDownload';

$route['purchase'] = 'Package/purchase';
$route['my-orders'] = 'Myaccount/myExpense';

$route['download-guide/(:any)/(:any)'] = 'Trips/downloadGuide/$1/$2';
$route['download-guide/(:any)'] = 'Trips/downloadGuide/$1';

$route['invoce-download/(:any)'] = 'Invoice/download_invoice/$1';



