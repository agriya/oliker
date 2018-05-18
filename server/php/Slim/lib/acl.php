<?php
/**
 * Roles configurations
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OLIKER
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class ACL
{
    public function __construct($scope)
    {
        $this->scope = $scope;
    }
    public function __invoke($request, $response, $next)
    {
        global $authUser;
        if (!empty($_GET['token'])) {
            // Checking provided access token is available/not expired
            if (((empty($authUser) || (!empty($authUser['role_id']) && $authUser['role_id'] != \Constants\ConstUserTypes::Admin)) && !in_array($this->scope, explode(' ', $authUser['scope'])))) {
                $response = renderWithJson(array(), 'Authorization Failed', '', 1, 401);
            } else {
                $response = $next($request, $response);
            }
        } else {
            $response = renderWithJson(array(), 'Authorization Failed', '', 1, 401);
        }
        return $response;
    }
}
