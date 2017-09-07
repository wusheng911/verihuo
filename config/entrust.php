<?php

/**
 * This file is part of Entrust,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Zizaco\Entrust
 */

return [

    'user' => 'App\User',

    /*
    |--------------------------------------------------------------------------
    | Entrust Role Model
    |--------------------------------------------------------------------------
    |
    | This is the Role model used by Entrust to create correct relations.  Update
    | the role if it is in a different namespace.
    |
    */
    'role' => 'App\Models\Role',

    /*
    |--------------------------------------------------------------------------
    | Entrust Roles Table
    |--------------------------------------------------------------------------
    |
    | This is the roles table used by Entrust to save roles to the database.
    |
    */
    'roles_table' => 'roles',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permission Model
    |--------------------------------------------------------------------------
    |
    | This is the Permission model used by Entrust to create correct relations.
    | Update the permission if it is in a different namespace.
    |
    */
    'permission' => 'App\Models\Permission',

    /*
    |--------------------------------------------------------------------------
    | Entrust Permissions Table
    |--------------------------------------------------------------------------
    |
    | This is the permissions table used by Entrust to save permissions to the
    | database.
    |
    */
    'permissions_table' => 'permissions',

    /*
    |--------------------------------------------------------------------------
    | Entrust permission_role Table
    |--------------------------------------------------------------------------
    |
    | This is the permission_role table used by Entrust to save relationship
    | between permissions and roles to the database.
    |
    */
    'permission_role_table' => 'permission_role',

    /*
    |--------------------------------------------------------------------------
    | Entrust role_user Table
    |--------------------------------------------------------------------------
    |
    | This is the role_user table used by Entrust to save assigned roles to the
    | database.
    |
    */
    'role_user_table' => 'role_user',

    /*
    |--------------------------------------------------------------------------
    | User Foreign key on Entrust's role_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'user_foreign_key' => 'user_id',

    /*
    |--------------------------------------------------------------------------
    | Role Foreign key on Entrust's role_user Table (Pivot)
    |--------------------------------------------------------------------------
    */
    'role_foreign_key' => 'role_id',

    'exguard' => 'admin',
    //todo roles
    'base_roles' => [
        // id, name, display_name, description
        // roles 必须固定在id号100以下
        [1, 'admin', '管理员', '管理员角色 管理整个系统'],
        [2, 'content-admin', '资讯管理员', ''],
        [3, 'shop-admin', '商店管理员', ''],
        [4, 'ad-admin', '广告管理员', ''],
        [5, 'content-editor', '文章编辑', ''],
        [6, 'goods-editor', '商品编辑', ''],
        [7, 'article-checker', '文章审核', ''],
        [8, 'goods-checker', '文章编辑', ''],
        [9, 'ad-editor', '广告位编辑员', ''],
        [10, 'ad-creator', '广告位创建员', ''],
        [11, 'sys-seor', '系统SEO', ''],
        [12, 'visit-admin', '访客管理员', ''],
    ],

    'base_perms' => [
        // id, name, display_name, description
        // 1, 超级管理员可以进行任何操作
        [1, 'all', '所有权限', '可以进行所有权限操作'],
        [2, 'sync-roles-perms', '更新权限', '更新配置base_roles, base_perms'],
        // 101x, 102x menu items
        [1010, 'setting-sys', '系统设置', ''],
        [1011, 'setting-ad', '广告位置设置', ''],
        [1012, 'setting-shop', '商城设置', ''],
        [1013, 'setting-content', '资讯设置', ''],
        [1014, 'setting-perms', '权限角色设置', ''],
        [1015, 'setting-customer', '客户设置', ''],

        [2010, 'article-edit', '文章编辑', ''],
        [2011, 'article-create', '文章新建', ''],
        [2012, 'article-delete', '删除文章', ''],
        [2013, 'article-seo', '文章SEO', ''],
        [3010, 'ad-edit', '广告位编辑', ''],
        [3011, 'ad-create', '广告位新建', ''],
        [3012, 'ad-delete', '广告位删除', ''],
        [3013, 'ad-genby-category', '批量生成广告位', '根据分类信息，批量生成广告位'],

        [4010, 'seo-friend-link', '友情链接设置', '友情链接设置'],

        [5010, 'visit-edit', '访客编辑', '访客编辑'],
        [5011, 'visit-scan', '访客扫码入场', '访客扫码入场'],
    ],
];
