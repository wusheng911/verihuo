<?php

use Illuminate\Database\Seeder;
use Illuminate\Hashing\BcryptHasher;

class EntrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = $this->getRoles();
        $perms = $this->getPermissions();
        $roleUsers = $this->getRoleUsers();
        $permRoles = $this->getPermRoles();

        DB::table('users')->where('id', '=', 1)->delete();
        DB::table('roles')->where('id', '<=', count($roles))->delete();
        DB::table('permissions')->where('id', '<=', count($perms))->delete();

        // add admin 
        $hasher = new BcryptHasher();
        DB::table('users')->insert(
            ['id' => 1, 'name' => 'admin',
             'email' => 'admin@admin.com',
             'password' => $hasher->make('admin'),
            ]
        );

        foreach ($roles as $role) {
            DB::table('roles')->insert($role);
        }

        foreach ($perms as $perm) {
            DB::table('permissions')->insert($perm);
        }

        foreach ($roleUsers as $us) {
            DB::table('role_user')->where(
                ['role_id' => $us['role_id'],
                 'user_id' => $us['user_id']])->delete();
            DB::table('role_user')->insert($us);
        }

        foreach ($permRoles as $pm) {
            DB::table('permission_role')->where(
                ['permission_id' => $pm['permission_id'],
                 'role_id' => $pm['role_id']])->delete();
            DB::table('permission_role')->insert($pm);
        }

    }

    protected function getRoleUsers(){
        $ru = [
            ['user_id' => 1, 'role_id' => 1],
            ['user_id' => 1, 'role_id' => 2],
            ['user_id' => 1, 'role_id' => 3],
            ['user_id' => 1, 'role_id' => 4],
        ];
        return $ru;
    }

    protected function getPermRoles(){
        $pm = [
            ['permission_id' => 1, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 1],
            ['permission_id' => 3, 'role_id' => 1],
            ['permission_id' => 4, 'role_id' => 1],
            ['permission_id' => 5, 'role_id' => 1],
            ['permission_id' => 6, 'role_id' => 1],
            ['permission_id' => 7, 'role_id' => 1],
            ['permission_id' => 8, 'role_id' => 1],
            ['permission_id' => 9, 'role_id' => 1],
            ['permission_id' => 10, 'role_id' => 1],
            ['permission_id' => 11, 'role_id' => 1],
            ['permission_id' => 12, 'role_id' => 1],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 2],
        ];
        return $pm;
    }

    protected function getRoles(){
        $roles = [
            // id, name, display_name, description
            [1, 'admin', '管理员', '管理员角色 管理整个系统'],
            [2, 'content-admin', '资讯管理员', ''],
            [3, 'shop-admin', '商店管理员', ''],
            [4, 'ad-admin', '广告管理员', ''],
            [5, 'content-editor', '文章编辑', ''],
            [6, 'goods-editor', '商品编辑', ''],
            [7, 'article-checker', '商品编辑', ''],
            [8, 'goods-checker', '商品编辑', ''],
            [9, 'ad-editor', '广告位编辑员', ''],
        ];

        $roles = $this->transAsKV($roles);
        return $roles;
    }

    protected function getPermissions(){
        $perms = [
            // id, name, display_name, description
            [1, 'all', '所有权限', '可以进行所有权限操作'],
            [2, 'article-edit', '文章编辑', ''],
            [3, 'article-create', '文章新建', ''],
            [4, 'article-delete', '删除文章', ''],
            [5, 'article-seo', '文章SEO', ''],
            [6, 'ad-edit', '广告位编辑', ''],
            [7, 'ad-create', '广告位新建', ''],
            [8, 'ad-delete', '广告位删除', ''],
            [9, 'setting-sys', '系统设置', ''],
            [10, 'setting-ad', '广告位置设置', ''],
            [11, 'setting-shop', '商城设置', ''],
            [12, 'setting-content', '资讯设置', ''],
        ];
        $perms = $this->transAsKV($perms);
        return $perms;
    }

    protected function transAsKV($arr) {
        $arrNew = [];
        foreach ($arr as $k => $v) {
            $vt = [];
            $vt['id'] = $v[0];
            $vt['name'] = $v[1];
            $vt['display_name'] = $v[2];
            $vt['description'] = $v[3];
            $arrNew[$k] = $vt;
        }
        return $arrNew;
    }
}