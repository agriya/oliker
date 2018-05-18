<?php
/**
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 *
 */
/**
 * DELETE adFavoritesAdFavoriteIdDelete
 * Summary: Delete ad favorite
 * Notes: Deletes a single ad favorite based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_favorites/{adFavoriteId}', function ($request, $response, $args) {

    global $authUser;
    $result = array();
    $adFavorite = Models\AdFavorite::find($request->getAttribute('adFavoriteId'));
    try {
        if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $adFavorite->user_id)) {
            $adFavorite->delete();
            $result = array(
                'status' => 'success',
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Invalid user. Please, try again.', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad favorite could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdFavorite'));
/**
 * GET adFavoritesAdFavoriteIdGet
 * Summary: Fetch ad favorite
 * Notes: Returns a ad favorite based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_favorites/{adFavoriteId}', function ($request, $response, $args) {

    global $authUser;
    $adFavorite = Models\AdFavorite::with('user', 'ad', 'ip')->find($request->getAttribute('adFavoriteId'));
    if (($authUser['role_id'] == \Constants\ConstUserTypes::Admin) || ($authUser['id'] == $adFavorite->user_id)) {
        $result['data'] = $adFavorite->toArray();
        return renderWithJson($result);
    } else {
        return renderWithJson(array(), 'Invalid user. Please, try again.', '', 1);
    }
})->add(new ACL('canViewAdFavorite'));
/**
 * GET adFavoritesGet
 * Summary: Fetch all ad favorites
 * Notes: Returns all ad favorites from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_favorites', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    $count = PAGE_LIMIT;
    if (!empty($queryParams['limit'])) {
        $count = $queryParams['limit'];
    }
    try {
        $adFavorites = Models\AdFavorite::leftJoin('ads', 'ad_favorites.ad_id', '=', 'ads.id');
        $adFavorites = $adFavorites->leftJoin('users', 'ad_favorites.user_id', '=', 'users.id');
        $adFavorites = $adFavorites->leftJoin('ips', 'ad_favorites.ip_id', '=', 'ips.id');
        $adFavorites = $adFavorites->select('ad_favorites.*', 'ads.title as ad_title', 'users.username as user_username', 'ips.ip as ip_ip');
        $adFavorites = $adFavorites->with('user', 'ad', 'ip')->Filter($queryParams)->paginate($count)->toArray();
        $data = $adFavorites['data'];
        unset($adFavorites['data']);
        $results = array(
            'data' => $data,
            '_metadata' => $adFavorites
        );
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdFavorite'));
/**
 * POST adFavoritesPost
 * Summary: Creates a new ad favorite
 * Notes: Creates a new ad favorite
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_favorites', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adFavorite = new Models\AdFavorite($args);
    if (!empty($authUser['id'])) {
        $adFavorite->user_id = $authUser['id'];
    } else {
        $adFavorite->user_id = 0;
    }
    $result = array();
    try {
        $adFavorite->ip_id = saveIp();
        $validationErrorFields = $adFavorite->validate($args);
        if (empty($validationErrorFields)) {
            $adFavorite->save();
            $adFavorite = Models\AdFavorite::with('user', 'ad')->find($adFavorite->id);
            $result = $adFavorite->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad favorite could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad favorite could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdFavorite'));
