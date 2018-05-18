<?php
/**
 * For SEO Purpose
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2017 Agriya
 * @license    http://www.agriya.com/ Agriya Licence
 * @link       http://www.agriya.com
 */
require_once __DIR__ . '/../../config.inc.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once '../lib/vendors/Inflector.php';
require_once '../lib/database.php';
global $_server_domain_url;
$inflector = new Inflector();
$php_path = PHP_BINDIR . DIRECTORY_SEPARATOR . 'php';
$api_url_map = array(
    '/\/ads\/(?P<ad_id>.*)/' => array(
        'api_url' => '/api/v1/ads/{id}',
    ) ,
    '/\/ads(.*)/' => array(
        'api_url' => '/api/v1/ads',
        'title' => 'Ads'
    ) ,
    '/^\/users\/login$/' => array(
        'api_url' => null,
        'title' => 'Login'
    ) ,
    '/^\/users\/register$/' => array(
        'api_url' => null,
        'title' => 'Register'
    ) ,
    '/^\/users\/forgot_password$/' => array(
        'api_url' => null,
        'title' => 'Forgot Password'
    ) ,
    '/\/page\/(?P<page_id>\d+)\/(?P<slug>.*)/' => array(
        'api_url' => '/api/v1/pages/{id}',
    ) ,
    '/^\/$/' => array(
        'api_url' => null,
        'title' => 'Home'
    ) ,
);
$meta_keywords = $meta_description = $title = $site_name = '';
$og_image = $_server_domain_url . '/images/no_image_available.png';
$og_type = 'website';
$og_url = $_server_domain_url . '/#!' . $_GET['_escaped_fragment_'];
$res = Models\Setting::whereIn('name', array(
    'META_KEYWORDS',
    'META_DESCRIPTION',
    'SITE_NAME'
))->get()->toArray();
foreach ($res as $key => $arr) {
    if ($res[$key]['name'] == 'META_KEYWORDS') {
        $meta_keywords = $res[$key]['value'];
    }
    if ($res[$key]['name'] == 'META_DESCRIPTION') {
        $meta_description = $res[$key]['value'];
    }
    if ($res[$key]['name'] == 'SITE_NAME') {
        $title = $site_name = $res[$key]['value'];
    }
}
if (!empty($_GET['_escaped_fragment_'])) {
    foreach ($api_url_map as $url_pattern => $values) {
        if (preg_match($url_pattern, $_GET['_escaped_fragment_'], $matches)) { // Match _escaped_fragment_ with our api_url_map array; For selecting API call
            if (!empty($values['title'])) { //Default title; We will change title for course and user page below;
                $title = $site_name . ' | ' . $values['title'];
            }
            if (!empty($values['api_url'])) {
                $id = !empty($matches['ad_id']) ? $matches['ad_id'] : (!empty($matches['page_id']) ? $matches['page_id'] : 0);
                if (!empty($id)) {
                    $api_url = str_replace('{id}', $id, $values['api_url']); // replacing id value
                    
                } else {
                    $api_url = $values['api_url']; // using defined api_url
                    
                }
                $query_string = !empty($matches[1]) ? $matches[1] : '';
                $response = json_decode(shell_exec($php_path . " index.php " . $api_url . " GET " . $query_string), true);
                if (!empty($response['data'])) {
                    $j = 0;
                    foreach ($response['data'] as $key => $value) {
                        if ($values['api_url'] == '/api/v1/pages/{id}') {
                            if ($key == 'meta_keywords') {
                                $meta_keywords = !empty($value) ? $value : '';
                            }
                            if ($key == 'meta_description') {
                                $meta_description = !empty($value) ? $value : '';
                                ;
                            }
                        } elseif (!empty($matches['ad_id'])) {
                            $og_type = 'Product';
                            if ($key == 'title') {
                                $meta_keywords = !empty($value) ? $value : '';
                            }
                            if ($key == 'attachment') {
                                if (!empty($value['attachment'][0])) {
                                    $og_image = $_server_domain_url . '/images/normal_thumb/Ad/' . $value['attachment'][0]['id'] . '.' . md5('Ad' . $value['attachment'][0]['id'] . 'png' . 'normal_thumb') . '.' . 'png';
                                }
                            }
                            if ($key == 'slug' && !is_array($value)) {
                                $og_url = $_server_domain_url . '/#!/ad/' . $matches['ad_id'] . '/' . $value;
                            }
                            if ($key == 'address') {
                                $address = !empty($value) ? $value : '';
                            }
                            if ($key == 'mobile') {
                                $mobile = !empty($value) ? $value : '';
                            }
                            if ($key == 'city') {
                                $city = !empty($value['name']) ? $value['name'] : '';
                            }
                            if ($key == 'country') {
                                $country = !empty($value['iso_alpha2']) ? $value['iso_alpha2'] : '';
                            }
                            if ($key == 'state') {
                                $state = !empty($value['name']) ? $value['name'] : '';
                            }
                            if ($key == 'zip_code') {
                                $postalCode = !empty($value) ? $value : '';
                            }
                            if ($key == 'latitude') {
                                $latitude = !empty($value) ? $value : '';
                            }
                            if ($key == 'longitude') {
                                $longitude = !empty($value) ? $value : '';
                            }
                            if ($key == 'ad_view_count') {
                                $adViewCount = !empty($value) ? $value : '';
                            }
                            if ($key == 'ad_favorite_count') {
                                $adFavoriteCount = !empty($value) ? $value : '';
                            }
                        } else {
                            $image = '';
                            if (!empty($value['attachment'])) {
                                $image = $_server_domain_url . '/images/normal_thumb/Ad/' . $value['attachment'][0]['id'] . '.' . md5('Ad' . $value['attachment'][0]['id'] . 'png' . 'normal_thumb') . '.' . 'png';
                            }
                            $url = $_server_domain_url . '/#!/ad/' . $value['id'] . '/' . $value['slug'];
                            $ad_data[$j]['@type'] = 'Product';
                            $ad_data[$j]['name'] = $value['title'];
                            $ad_data[$j]['@id'] = $url;
                            $ad_data[$j]['url'] = $url;
                            $ad_data[$j]['image'] = $image;
                            $ad_data[$j]['aggregateRating']['@type'] = "AggregateRating";
                            $ad_data[$j]['aggregateRating']['adViewCount'] = !empty($value['adViewCount']) ? $value['adViewCount'] : '0';
                            $ad_data[$j]['aggregateRating']['adFavoriteCount'] = !empty($value['adFavoriteCount']) ? $value['adFavoriteCount'] : '0';
                            if (!empty($address)) {
                                $ad_data[$j]['address']['@type'] = "PostalAddress";
                                $ad_data[$j]['address']['streetAddress'] = $value['address1'];
                            }
                            if (!empty($city)) {
                                $ad_data[$j]['address']['addressLocality'] = $value['city']['name'];
                            }
                            if (!empty($state)) {
                                $ad_data[$j]['address']['addressRegion'] = $value['state']['name'];
                            }
                            if (!empty($country)) {
                                $ad_data[$j]['address']['addressCountry'] = $value['country']['iso_alpha2'];
                            }
                            if (!empty($postalCode)) {
                                $ad_data[$j]['address']['postalCode'] = $value['zip_code'];
                            }
                            if (!empty($value['latitude'])) {
                                $ad_data[$j]['geo']['@type'] = "GeoCoordinates";
                                $ad_data[$j]['geo']['latitude'] = $value['latitude'];
                            }
                            if (!empty($value['longitude'])) {
                                $ad_data[$j]['geo']['@type'] = "GeoCoordinates";
                                $ad_data[$j]['geo']['longitude'] = $value['longitude'];
                            }
                            if (!empty($value['mobile'])) {
                                $ad_data[$j]['telephone'] = $value['mobile'];
                            }
                        }
                        $j++;
                    }
                } else {
                    $isNoRecordFound = 1;
                }
            }
            break;
        }
    }
}
if (!empty($response->error) || !empty($isNoRecordFound) || empty($matches)) { // returning 404, if URL or record not found
    header('Access-Control-Allow-Origin: *');
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    exit;
}
$app_id = Models\Provider::where('name', 'Facebook')->first();
?>
<!DOCTYPE html><html>
<head>
  <title><?php
    echo $title; ?></title>
  <meta charset="UTF-8">
  <meta name="description" content="<?php
    echo $meta_description; ?>"/>
  <meta name="keywords" content="<?php
    echo $meta_keywords; ?>"/>
  <meta property="og:app_id" content="<?php
    echo $app_id->api_key; ?>"/>
  <meta property="og:type" content="<?php
    echo $og_type; ?>"/>
  <meta property="og:title" content="<?php
    echo $title; ?>"/>
  <meta property="og:description" content="<?php
    echo $meta_description; ?>"/>
  <meta property="og:type" content="<?php
    echo $og_type; ?>"/>
  <meta property="og:image" content="<?php
    echo $og_image; ?>"/>
  <meta property="og:site_name" content="<?php
    echo $site_name; ?>"/>
  <meta property="og:url" content="<?php
    echo $og_url; ?>"/>
<?php
if (empty($ad_data)) {
    $data['@type'] = $og_type;
    $data['description'] = $meta_description;
    $data['name'] = $meta_keywords;
    $data['@id'] = $og_url;
    $data['url'] = $og_url;
    $data['image'] = $og_image;
    $data['app_id'] = $app_id->api_key;
    $data['title'] = $title;
    $data['keywords'] = $meta_keywords;
    if (!empty($address)) {
        $data['address']['@type'] = "PostalAddress";
        $data['address']['streetAddress'] = $address;
    }
    if (!empty($city)) {
        $data['address']['addressLocality'] = $city;
    }
    if (!empty($state)) {
        $data['address']['addressRegion'] = $state;
    }
    if (!empty($country)) {
        $data['address']['addressCountry'] = $country;
    }
    if (!empty($postalCode)) {
        $data['address']['postalCode'] = $postalCode;
    }
    if (!empty($avg_rating) && !empty($total_reviews)) {
        $data['aggregateRating']['@type'] = "AggregateRating";
        $data['aggregateRating']['adViewCount'] = $adViewCount;
        $data['aggregateRating']['adFavoriteCount'] = $adFavoriteCount;
    }
    if (!empty($latitude)) {
        $data['geo']['@type'] = "GeoCoordinates";
        $data['geo']['latitude'] = $latitude;
    }
    if (!empty($longitude)) {
        $data['geo']['@type'] = "GeoCoordinates";
        $data['geo']['longitude'] = $longitude;
    }
    if (!empty($mobile)) {
        $data['telephone'] = $mobile;
    }
}
if (!empty($ad_data)) {
    $data['@type'] = 'ItemList';
    $data['description'] = $meta_description;
    $data['name'] = $meta_keywords;
    $data['app_id'] = $app_id->api_key;
    $data['title'] = $title;
    $data['keywords'] = $meta_keywords;
    $data['@url'] = $_server_domain_url . '/#!/ads';
    $data['numberOfItems'] = count($ad_data);
    $data['itemListElement'] = $ad_data;
}
?>
    <script type = "application/ld+json">
        <?php
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
    </script>
</head>
<body>
<?php
if (!empty($response['data']) && !in_array(array(), $response['data'], true) && !empty($response['data'][0])) { ?>
  <dl>
    <?php
    foreach ($response['data'] as $ad_data) {
        foreach ($ad_data as $key => $value) {
            if (!is_array($value)) {
?>
    <dt><?php
                echo $inflector->humanize($key); ?></dt>
    <dd>
    <?php
    if ($key == 'title') {
        $ad_name = $value;
    }
    if ($key == 'description') {
        $description = $value;
    }
    if ($key == 'ad_view_count') {
        $ad_view_count = $value;
    } elseif ($key == 'ad_favorite_count') {
        $ad_favorite_count = $value;
    } else {
        echo $value;
    }
    if (isset($ad_view_count) && isset($ad_favorite_count) && isset($ad_name) && isset($description) && empty($ratingDisplayed)) {
        $ratingDisplayed = 1; ?>
        <div itemscope itemtype="http://schema.org/Product">
            <h2 itemprop="name"><?php
            echo $ad_name; ?></h2>
            <div itemprop="description"><?php
            echo $description; ?></div>
            <div itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
                <span itemprop="adViewCount"><?php
                echo $ad_view_count; ?></span>
                <span itemprop="adFavoriteCount"><?php
                echo $ad_favorite_count; ?></span>
            </div>
        </div>
    <?php
    } ?>
    </dd><?php
            }
        }
    } ?>
  </dl><?php
} elseif (!empty($response['data'])) { // For pages like login, register, home, contactus - we need to fill something in body... If body content is empty, in facebook lint or google search, it will not works
    
?>
          <dl>
    <?php
    foreach ($response['data'] as $key => $value) {
        if (!is_array($value)) {
?>
    <dt><?php
            echo $inflector->humanize($key); ?></dt>
    <dd>
    <?php
    if ($key == 'title') {
        $ad_name = $value;
    }
    if ($key == 'description') {
        $description = $value;
    }
    if ($key == 'ad_view_count') {
        $ad_view_count = $value;
    } elseif ($key == 'ad_favorite_count') {
        $ad_favorite_count = $value;
    } else {
        echo $value;
    }
    if (isset($ad_favorite_count) && isset($ad_view_count) && isset($ad_name) && isset($description) && empty($ratingDisplayed)) {
        $ratingDisplayed = 1; ?>
        <div itemscope itemtype="http://schema.org/Product">
            <h2 itemprop="name"><?php
            echo $ad_name; ?></h2>
            <div itemprop="description"><?php
            echo $description; ?></div>
            <div itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
                <span itemprop="adViewCount"><?php
                echo $ad_view_count; ?></span>
                <span itemprop="adFavoriteCount"><?php
                echo $ad_favorite_count; ?></span>
            </div>
        </div>
    <?php
    } ?>
    </dd><?php
        }
    } ?>
  </dl><?php
} else { ?>
    <div><?php
    echo $site_name; ?></div>
        <div><?php
        echo $meta_keywords; ?></div>
<?php
} ?>
</body>
</html>
