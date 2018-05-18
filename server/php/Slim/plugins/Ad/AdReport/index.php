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
 * DELETE adReportsAdReportIdDelete
 * Summary: Delete ad report
 * Notes: Deletes a single ad report based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_reports/{adReportId}', function ($request, $response, $args) {

    $adReport = Models\AdReport::find($request->getAttribute('adReportId'));
    try {
        $adReport->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad report could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdReport'));
/**
 * GET adReportsAdReportIdGet
 * Summary: Fetch ad report
 * Notes: Returns a ad report based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_reports/{adReportId}', function ($request, $response, $args) {

    $result = array();
    $adReport = Models\AdReport::with('ad', 'ad_report_type', 'ip')->find($request->getAttribute('adReportId'));
    if (!empty($adReport)) {
        $result['data'] = $adReport->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdReport'));
/**
 * GET adReportsGet
 * Summary: Fetch all ad reports
 * Notes: Returns all ad reports from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_reports', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $adReports['data'] = Models\AdReport::get()->toArray();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $adReports = Models\AdReport::leftJoin('ads', 'ad_reports.ad_id', '=', 'ads.id');
            $adReports = $adReports->leftJoin('ad_report_types', 'ad_reports.ad_report_type_id', '=', 'ad_report_types.id');
            $adReports = $adReports->leftJoin('users', 'ad_reports.user_id', '=', 'users.id');            
            $adReports = $adReports->select('ad_reports.*', 'ads.title as ad_title', 'ad_report_types.name as ad_report_type_name', 'users.username as user_username');
            $adReports = $adReports->with('ad', 'ad_report_type', 'ip', 'user')->Filter($queryParams)->paginate($count)->toArray();
        }
        if (!empty($adReports)) {
            $data = $adReports['data'];
            unset($adReports['data']);
            $result = array(
                'data' => $data,
                '_metadata' => $adReports
            );
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No record found', '', 1);
        }
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdReport'));
/**
 * POST adReportsPost
 * Summary: Creates a new ad report
 * Notes: Creates a new ad report
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_reports', function ($request, $response, $args) {

    global $authUser;
    $args = $request->getParsedBody();
    $adReport = new Models\AdReport($args);
    $result = array();
    try {
        $validationErrorFields = $adReport->validate($args);
        if (empty($validationErrorFields)) {
            $adReport->user_id = $authUser['id'];
            $adReport->save();
            $ad_id = $args['ad_id'];
            Models\Ad::find($ad_id)->increment('ad_report_count', 1);
            $result = $adReport->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad report could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad report could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdReport'));
/**
 * DELETE AdReportType
 * Summary: Delete AdReportType
 * Notes: Deletes a single AdReportType based on the ID supplied
 * Output-Formats: [application/json]
 */
$app->DELETE('/api/v1/ad_report_types/{adReportTypeId}', function ($request, $response, $args) {

    $adReportType = Models\AdReportType::find($request->getAttribute('adReportTypeId'));
    try {
        $adReportType->delete();
        $result = array(
            'status' => 'success',
        );
        return renderWithJson($result);
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad Report Type could not be deleted. Please, try again.', '', 1);
    }
})->add(new ACL('canDeleteAdReportType'));
/**
 * GET AdReportType
 * Summary: Fetch AdReportType
 * Notes: Returns a AdReportType based on a single ID
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_report_types/{adReportTypeId}', function ($request, $response, $args) {

    $result = array();
    $adReportType = Models\AdReportType::find($request->getAttribute('adReportTypeId'));
    if (!empty($adReportType)) {
        $result['data'] = $adReportType->toArray();
    } else {
        return renderWithJson($result, 'No record found.', '', 1);
    }
    return renderWithJson($result);
})->add(new ACL('canViewAdReportType'));
/**
 * PUT AdReportType
 * Summary: Update AdReportType by its id
 * Notes: Update AdReportType by its id
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/ad_report_types/{adReportTypeId}', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $adReportType = Models\AdReportType::find($request->getAttribute('adReportTypeId'));
    $adReportType->fill($args);
    $result = array();
    try {
        $validationErrorFields = $adReportType->validate($args);
        if (empty($validationErrorFields)) {
            $adReportType->save();
            $result = $adReportType->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad Report Type not be updated. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad Report Type not could not be updated. Please, try again.', '', 1);
    }
})->add(new ACL('canUpdateAdReportType'));
/**
 * GET AdReportType
 * Summary: Fetch all AdReportType
 * Notes: Returns all AdReportType from the system
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/ad_report_types', function ($request, $response, $args) {

    $queryParams = $request->getQueryParams();
    $results = array();
    try {
        if (!empty($queryParams['limit']) && $queryParams['limit'] == 'all') {
            $results['data'] = Models\AdReportType::get()->toArray();
        } else {
            $count = PAGE_LIMIT;
            if (!empty($queryParams['limit'])) {
                $count = $queryParams['limit'];
            }
            $adReportTypes = Models\AdReportType::Filter($queryParams)->paginate($count)->toArray();
            $data = $adReportTypes['data'];
            unset($adReportTypes['data']);
            $results = array(
                'data' => $data,
                '_metadata' => $adReportTypes
            );
        }
        return renderWithJson($results);
    } catch (Exception $e) {
        return renderWithJson($results, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new ACL('canListAdReportType'));
/**
 * POST AdReportType
 * Summary: Creates a new AdReportType
 * Notes: Creates a new AdReportType
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/ad_report_types', function ($request, $response, $args) {

    $args = $request->getParsedBody();
    $adReportType = new Models\AdReportType($args);
    $result = array();
    try {
        $validationErrorFields = $adReportType->validate($args);
        if (empty($validationErrorFields)) {
            $adReportType->save();
            $result = $adReportType->toArray();
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'Ad Report Type could not be added. Please, try again.', $validationErrorFields, 1);
        }
    } catch (Exception $e) {
        return renderWithJson($result, 'Ad Report Type could not be added. Please, try again.', '', 1);
    }
})->add(new ACL('canCreateAdReportType'));
