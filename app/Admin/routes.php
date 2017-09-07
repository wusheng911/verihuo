<?php

Route::get('', ['as' => 'admin.dashboard', function () {
	$content = 'Define your dashboard here.';
	return AdminSection::view($content, 'Dashboard');
}]);

Route::get('information', ['as' => 'admin.information', function () {
	$content = 'Define your information here.';
	return AdminSection::view($content, 'Information');
}]);

Route::any('admin/content/catalog', ['as' => 'admin.content.catalog', function () {
	$content = '博客分类';
	return AdminSection::view($content, '博客分类');
}]);
Route::get('admin/content/article', ['as' => 'admin.content.article', function () {
	$content = '博客';
	return AdminSection::view($content, '博客');
}]);
