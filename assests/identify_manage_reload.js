$(".identRemove").click(function(){
  
  if (!confirm("您確定要刪除此欄位嗎？")) {
          return false;
  }
  
  var data = { id : $(this).attr("iid") };
  var url  = '/identify/del_process';

  console.log(data);
  // Send the data using post
  $.post( 
    url, 
    data,
    function() {
      $("#table").load('/identify/organ_ident_list/'+$("#createForm").find( "input[name='oId']").val(), function(){$.getScript("/assests/identify_manage_reload.js");});
    }
  );     
});

