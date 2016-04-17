// --> simply bootstraps the angular app to the admin interface
(function($) {
  // --> to prevent angular from preventing form submissions on forms witout an action attribute, we must add one :/
  $(document).ready(function() {
    $('form').each(function() {
      var $form  = $(this);
      if( typeof $form.attr('action') == 'undefined' ) {
        $form.attr('action','#');
      }
    });
    angular.element(document).ready(function() {
    	angular.bootstrap(document, ['app']);
    });
  });
})(jQuery)

