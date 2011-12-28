<?php

error_reporting(E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_PARSE);

define( 'ABS_PATH', dirname( dirname( dirname( __FILE__ ) ) ) );

set_include_path( get_include_path() . PATH_SEPARATOR . ABS_PATH . DIRECTORY_SEPARATOR . 'library' );

require_once ABS_PATH . '/config.php';

require_once 'osc/classes/database/DBConnectionClass.php';
require_once 'osc/classes/database/DBCommandClass.php';
require_once 'osc/classes/database/DBRecordsetClass.php';
require_once 'osc/classes/database/DAO.php';
require_once 'osc/Logger/Logger.php' ;
require_once 'osc/Logger/LogDatabase.php' ;
require_once 'osc/Logger/LogOsclass.php' ;
require_once 'osc/core/Params.php';
require_once 'osc/model/Preference.php' ;
require_once 'osc/helpers/hDatabaseInfo.php';
require_once 'osc/helpers/hPreference.php' ;
require_once 'osc/compatibility.php';
require_once 'osc/default-constants.php';
require_once 'osc/formatting.php';
require_once 'osc/install-functions.php';
require_once 'osc/utils.php';

if( is_osclass_installed() ) {
    die() ;
}

$json_message = array();
$json_message['status'] = '200';

$result = basic_info();
$json_message['email_status']   = $result['email_status'];
$json_message['password']       = $result['s_password'];

if( $_POST['skip-location-h'] == 0 ) {
    $msg = install_locations() ;
    $json_message['status'] = $msg;
}

echo json_encode($json_message);

function location_international() {
    $manager_country = Country::newInstance() ;
    $manager_region  = Region::newInstance() ;
    $manager_city    = City::newInstance() ;

    $countries_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=country&term=all&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    if( count($countries) ==  0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error( 'Cannot get countries' , __FILE__ . "::" . __LINE__ ) ;
        }
        return '300' ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name"           => $c->name
        )) ;
    }
    
    $regions_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=region&country=all&term=all') ;
    $regions      = json_decode($regions_json) ;

    if( count($regions) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get regions', __FILE__ . "::" . __LINE__ ) ;
    }
    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all' ) ;
        $cities      = json_decode($cities_json) ;

        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by country ' . $c->name , __FILE__ . "::" . __LINE__ ) ;
            }
        }

        unset($cities) ;
        unset($cities_json) ;
    }
    
    return '200' ;
}

function location_by_country() {
    $country = Params::getParam('country') ;
    if( $country == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) ==  0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300' ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name"           => $c->name
        )) ;
    }

    $manager_region = Region::newInstance() ;

    $regions_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=all' ) ;
    $regions      = json_decode($regions_json) ;

    if( count($regions) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get regions by - ' . implode(',', $country) , __FILE__ . "::" . __LINE__ ) ;
    }

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    $manager_city = City::newInstance() ;

    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=all' ) ;
        $cities      = json_decode($cities_json) ;

        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by country - ' . $c->name , __FILE__ . "::" . __LINE__ ) ;
            }
        }

        unset($cities) ;
        unset($cities_json) ;
    }

    return '200' ;
}

function location_by_region() {
    $country = Params::getParam('country') ;
    $region  = Params::getParam('region') ;

    if( $country == '' ) {
        return false ;
    }

    if( $region == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) == 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot get countries - ' . implode(',', $country) , __FILE__."::".__LINE__) ;
        }
        return '300' ;
    }
    
    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name"           => $c->name
        )) ;
    }

    $regions_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=region&country=' . urlencode(implode(',', $country)) . '&term=' . urlencode(implode(',', $region)) ) ;
    $regions      = json_decode($regions_json);

    $manager_region = Region::newInstance() ;

    if( count($regions) == 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error( 'Cannot get regions - ' . implode(',', $country) . '- term' . implode(',', $region) , __FILE__ . "::" . __LINE__ ) ;
        }
        return '300' ;
    }

    foreach($regions as $r) {
        $manager_region->insert(array(
            "pk_i_id"           => $r->id,
            "fk_c_country_code" => $r->country_code,
            "s_name"            => $r->name
        )) ;
    }

    $manager_city = City::newInstance() ;
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&region=' . urlencode(implode(',', $region)) . '&term=') ;
        $cities      = json_decode($cities_json) ;
        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get regions by country - ' . $c->name . '- by region ' . implode(',', $region) , __FILE__ . "::" . __LINE__ ) ;
            }
        }
        unset($cities) ;
        unset($cities_json) ;
    }
    
    return '200' ;
}

function location_by_city() {
    $country = Params::getParam('country') ;
    $city    = Params::getParam('city') ;

    if( $country == '' ) {
        return false ;
    }

    if( $city == '' ) {
        return false ;
    }

    $countries_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=country&term=' . urlencode(implode(',', $country)) . '&install=true&version=' . osc_version() ) ;
    $countries      = json_decode($countries_json) ;

    $manager_country = Country::newInstance() ;

    if( count($countries) == 0 && reportToOsclass() ) {
        LogOsclassInstaller::instance()->error( 'Cannot get countries - ' . implode(',', $country) , __FILE__ . "::" . __LINE__ ) ;
    }

    foreach($countries as $c) {
        $manager_country->insert(array(
            "pk_c_code"        => $c->id,
            "fk_c_locale_code" => $c->locale_code,
            "s_name"           => $c->name
        )) ;
    }

    $manager_city   = City::newInstance() ;
    $manager_region = Region::newInstance() ;
    foreach($countries as $c) {
        $cities_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=city&country=' . urlencode($c->name) . '&term=' . urlencode(implode(',', $city) ) ) ;
        $cities      = json_decode($cities_json) ;
        if( !isset($cities->error) ) {
            foreach($cities as $ci) {
                $regions_json = osc_file_get_contents( 'http://geo.opensourceclassifieds.org/geo.download.php?action=region&country=&id=' . $ci->region_id ) ;
                $regions      = json_decode($regions_json) ;

                if( count($regions) == 0 && reportToOsclass() ) {
                    LogOsclassInstaller::instance()->error( 'Cannot get regions by - ' . $ci->region_id , __FILE__ . "::" . __LINE__ ) ;
                }

                foreach($regions as $r) {
                    $manager_region->insert(array(
                        "pk_i_id"           => $r->id,
                        "fk_c_country_code" => $r->country_code,
                        "s_name"            => $r->name
                    )) ;
                }

                $manager_city->insert(array(
                    "pk_i_id"           => $ci->id,
                    "fk_i_region_id"    => $ci->region_id,
                    "s_name"            => $ci->name,
                    "fk_c_country_code" => $ci->country_code
                )) ;
            }
        } else {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error( 'Cannot get cities by - ' . $c->name . ' - term ' . implode(',', $city) , __FILE__ . "::" . __LINE__ ) ;
            }
            return '300' ;
        }
        
        unset($cities) ;
        unset($cities_json) ;
    }

    return '200' ;
}

function install_locations ( ) {
    if( Params::getParam('c_country') == '' ) {
        return false;
    }

    require_once 'osc/model/Country.php';
    require_once 'osc/model/Region.php';
    require_once 'osc/model/City.php';

    if( Params::getParam('city') != '' ) {
        return location_by_city() ;
    } else if( Params::getParam('region') != '' ) {
        return location_by_region() ;
    } else if( Params::getParam('country') != '' ) {
        return location_by_country() ;
    }

    return location_international ();
}

