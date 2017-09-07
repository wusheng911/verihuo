/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here. For example:
  config.height = '36em';
  config.language = 'zh-cn';
  config.allowedContent = true;
  config.extraAllowedContent = 'video image img source';
  // config.uiColor = '#AADC6E';
  config.extraPlugins = 'codemirror';
  config.extraPlugins = 'video';
  config.codemirror_theme = 'rubyblue';
  config.filebrowserBrowseUrl = '/elfinder/ckeditor';
  config.filebrowserImageBrowseUrl = '/elfinder/ckeditor/?type=images';
  config.filebrowserVideoBrowseUrl = '/elfinder/ckeditor/?type=video';
  // config.filebrowserFlashBrowseUrl = '/js/libs/ckfinder/browse.php?opener=ckeditor&type=flash';
  // config.filebrowserUploadUrl = '/js/libs/ckfinder/upload.php?opener=ckeditor&type=files';
  // config.filebrowserImageUploadUrl = '/js/libs/ckfinder/upload.php?opener=ckeditor&type=images';
  // config.filebrowserFlashUploadUrl = '/js/libs/ckfinder/upload.php?opener=ckeditor&type=flash';
  // config.filebrowserVideoUploadUrl = '/js/libs/ckfinder/upload.php?opener=ckeditor&type=video';
};
