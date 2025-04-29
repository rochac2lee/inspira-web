$(document).bind('keydown', function(e) {
    if(e.ctrlKey && (e.which == 83) || e.ctrlKey && (e.which == 65)) {
      e.preventDefault();      
      return false;
    }
});
