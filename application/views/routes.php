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
$route['default_controller'] = 'Temp';
$route['getData']='Home/getData';
$route['refresh']='Home/refresh';
$route['recommendedformsubmit']='Home/recommendedformsubmit';
$route['searchformsubmit']='Home/searchformsubmit';
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
$route['hotels'] = 'home/hotels';
$route['attractions'] = 'home/allattractions';
$route['planneditineraries'] = 'home/planneditineraries';
$route['allattractions'] = 'home/allattractions';
$route['showSearchedCityHotels/(:any)'] = 'Hotels/showSearchedCityHotels/$1';
$route['city/(:any)'] = 'home/city/$1';
$route['showHotelsOfMultiCountries/(:any)'] = 'Hotels/showHotelsOfMultiCountries/$1';
$route['country/(:any)'] = 'Home/country/$1';
$route['searchAttractionsFromGYG'] = 'Attractions/searchAttractionsFromGYG';
$route['cityAttractionFromGYG/(:any)/(:any)/(:any)'] = 'Attractions/cityAttractionFromGYG/$1/$2/$3';
$route['fblogin'] = 'Home/fblogin';
$route['addExtraCity'] = 'Attractions/addExtraCity';
$route['removeExtraCity'] = 'Attractions/removeExtraCity';
//CMS

$route['terms-and-condition'] = 'Cms/terms_and_condition';
$route['faq'] = 'Cms/faq';
$route['contactus'] = 'Cms/contactus';
$route['postcontactus'] = 'Cms/postcontactus';
$route['crew-and-career'] = 'Cms/team';
$route['pricing'] = 'Cms/pricing';
$route['media'] = 'Cms/media';
//$route['career'] = 'Cms/career';
$route['cookie'] = 'Cms/cookie';
$route['user-content-&-conduct-policy'] = 'Cms/user_content';
$route['privacy-policy'] = 'Cms/privacy_policy';
$route['discover-taxidio'] = 'Cms/discover_taxidio';
$route['membership'] = 'Cms/membership';

//Account
$route['profile'] = 'myaccount/profile';
$route['myprofile'] = 'myaccount/myprofile';
$route['editUser'] = 'myaccount/editUser';
$route['trip/(:any)'] = 'myaccount/trip/$1';


//Trip

$route['trips'] = 'myaccount/trips';
$route['getAllAttractionsOfCitySaved'] = 'Myaccount/getAllAttractionsOfCitySaved';
$route['getSavedCityAttractions'] = 'Myaccount/getSavedCityAttractions';
$route['alterMainAttractionSaved']='Myaccount/alterMainAttractionSaved';
$route['alterSavedCity'] = 'Myaccount/alterSavedCity';
$route['saveOrderSaved'] = 'Myaccount/saveOrderSaved';
$route['update-single-itinerary/(:any)'] = 'Account/update_single_itinerary/$1';
$route['updatesave-multi-itinerary/(:any)'] = 'myaccount/updatesave_multi_itinerary/$1';

//Multi countries
$route['multicountrytrips/(:any)'] = 'myaccount/multicountrytrips/$1';
$route['savedmulticity-attractions-ajax'] = 'myaccount/savedmulticity_attractions_ajax';
$route['alterSavedMultiAttraction'] = 'myaccount/alterSavedMultiAttraction';
$route['saveMultiOrderSaved'] = 'myaccount/saveMultiOrderSaved';
$route['alterSavedMultiCountryCity'] = 'myaccount/alterSavedMultiCountryCity';
$route['getDataForNewCountryMultiSaved'] = 'myaccount/getDataForNewCountryMultiSaved';
$route['getAllAttractionsOfMultiCitySaved'] = 'myaccount/getAllAttractionsOfMultiCitySaved';


//Searched city trips
$route['showSearchedCityTrip/(:any)'] = 'myaccount/showSearchedCityTrip/$1';
$route['getAllAttractionsOfSingleCitySaved'] = 'myaccount/getAllAttractionsOfSingleCitySaved';
$route['getUserAttractionsOfSingleCountrySaved'] = 'myaccount/getUserAttractionsOfSingleCountrySaved';
$route['saveOrderSingleSaved'] = 'myaccount/saveOrderSingleSaved';
$route['alterMainAttractionSingleSaved'] = 'myaccount/alterMainAttractionSingleSaved';
$route['removeExtraCityFromSave'] = 'myaccount/removeExtraCityFromSave';
$route['addExtraCityInSaved'] = 'myaccount/addExtraCityInSaved';


$route['admins'] = 'admins/dashboard';
$route['404_override'] = 'my404';
$route['translate_uri_dashes'] = TRUE;
