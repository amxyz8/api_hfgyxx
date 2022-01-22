<?php
namespace app\admin\controller;

use app\common\lib\Show;
use tauthz\facade\Enforcer;

class Test extends AdminBase
{
    public function index()
    {
        //	    $enforcer = new Enforcer();
        //		Enforcer::addPermissionForUser('eve', 'articles', 'read');
        //		Enforcer::addRoleForUser('eve', 'writer');
        //		Enforcer::addPolicy('writer', 'articles','edit');
        //		$res = Enforcer::getAllRoles();
        //		$res = Enforcer::getAllRoles();
        //		$res = Enforcer::getPolicy();
//        给用户分配角色
//        Enforcer::addRoleForUser('admin', 'admin');
//        Enforcer::addRoleForUser('admin', 'member');
//
//        Enforcer::addPermissionForUser('member', '/admin/news', 'GET');
//        Enforcer::addPermissionForUser('admin', '/admin/news', 'POST');
//        Enforcer::addPermissionForUser('admin', '/admin/news', 'GET');

//        $res = Enforcer::enforce('admin', '/admin/news', 'GET'); // true
        //		Enforcer::deletePermissionForUser("member", "/admin/news", "GET");
        //		Enforcer::deletePermissionsForUser('admin');
        Enforcer::deleteRolesForUser('admin');

        return Show::success();
    }
}
