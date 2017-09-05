<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * 通讯录管理 － 获取成员信息
 */

namespace wskeee\notification;

use wskeee\notification\core\TxlApi;

$api = new TxlApi();

class UserManager {

    /**
     * 根据成员ID来获取成员信息
     * @param type $instance
     */
    public static function testQueryUser($instance) {
        $id = isset($_GET["id"]) ? $_GET["id"] : "";
        print($instance->queryUserById($id));
    }

    /**
     * 根据部门ID来获取成员的基本信息（包括成员ID、成员姓名，成员所在部门）
     * @param type $instance
     */
    public static function testQueryUserByDepId($instance) {
        $id = isset($_GET["id"]) ? $_GET["id"] : 1;
        $simple = isset($_GET["simple"]) ? $_GET["simple"] : 1;
        $fetch = isset($_GET["fetch"]) ? $_GET["fetch"] : 1;

        print($instance->queryUsersByDepartmentId($id, $fetch, $simple));
    }

    //test entry	
}

$cmd = isset($_GET["cmd"]) ? $_GET["cmd"] : "dep_query";

switch ($cmd) {
    case 'query':
        UserManager::testQueryUser($api);
        break;
    case 'dep_query':
        UserManager::testQueryUserByDepId($api);
        break;
    default:
        break;
}
?>
