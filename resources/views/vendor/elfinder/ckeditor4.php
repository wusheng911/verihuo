<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>elFinder 2.0</title>

    <!-- jQuery and jQuery UI (REQUIRED) -->
      <link rel="stylesheet" href="<?= asset('assets/libs/jquery-ui-themes-1.12.0-rc.2/themes/smoothness/jquery-ui.min.css') ?>" />

      <script src="<?= asset('assets/libs/jquery-1.12.4.min.js') ?> "></script>
      <script src="<?= asset('assets/libs/jquery-ui-1.12.0-rc.2/jquery-ui.min.js') ?> "></script>

    <!-- elFinder CSS (REQUIRED) -->
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/elfinder.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= asset($dir.'/css/theme.css') ?>">

    <!-- elFinder JS (REQUIRED) -->
    <script src="<?= asset($dir.'/js/elfinder.min.js') ?>"></script>

    <?php if($locale){ ?>
        <!-- elFinder translation (OPTIONAL) -->
        <script src="<?= asset($dir."/js/i18n/elfinder.$locale.js") ?>"></script>
    <?php } ?>
    
    <!-- elFinder initialization (REQUIRED) -->
    <script type="text/javascript" charset="utf-8">
        // Helper function to get parameters from the query string.
        function getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
            var match = window.location.search.match(reParam) ;

            return (match && match.length > 1) ? match[1] : '' ;
        }

        $().ready(function() {
            var funcNum = getUrlParam('CKEditorFuncNum');
            var type = getUrlParam('type');

            var elf = $('#elfinder').elfinder({
                // set your elFinder options here
                <?php if($locale){ ?>
                    lang: '<?= $locale ?>', // locale
                <?php } ?>
                customData: { 
                    _token: '<?= csrf_token() ?>',
                    type: '<?= $type ?>'
                },
                url: '<?= route("elfinder.connector") ?>',  // connector URL
                getFileCallback : function(file) {
                    window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                    window.close();
                }
            }).elfinder('instance');

            // let window resizable
            var options = {resizable:true};
            var $elfinder = $('#elfinder').elfinder(options);
            var $window = $(window);
            $window.resize(function(){
                var win_height = $window.height();
                if( $elfinder.height() != win_height ){
                    $elfinder.height(win_height).resize();
                }
            })
        });
    </script>
</head>
<body>
    <!-- Element where elFinder will be created (REQUIRED) -->
    <div id="elfinder"></div>
</body>
</html>
