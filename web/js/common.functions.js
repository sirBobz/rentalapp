$("#creation-method").change(function(){
   var method = $(this).val()
   
   if (method == 1)
       {
           $(".manualSuiteForm").css("display", "block")
           $(".autoSuiteForm").css("display", "none")
       }
    else if(method == 2)
       {
           $(".manualSuiteForm").css("display", "none")
           $(".autoSuiteForm").css("display", "block")
       }
    else
        {
            $(".manualSuiteForm").css("display", "none")
           $(".autoSuiteForm").css("display", "none")
        }
});

$("#closeAccount").click(function(evt){
    evt.preventDefault();
    $('#modal').modal('show').find('#modalContent').load($(this).attr('href'))
});