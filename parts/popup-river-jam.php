<div id="activityModal" class="modal customModal fade">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span id="eventStatusTxt"></span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="modalBodyText" class="modal-body">
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
jQuery(document).ready(function($){
  $("#activityModal").appendTo('body');

  $(document).on('click', '.image-post-link.post---music', function(e){
    e.preventDefault();
    var parent = $(this).parents('.infoBox');
    if( parent.find('.popupDetailsBtn').length ) {
      parent.find('.popupDetailsBtn').trigger('click');
    }
  });

  $(document).on("click", ".popdata, .popupDetailsBtn",function(e){
    e.preventDefault();
    var pageURL = $(this).attr('data-url');
    var actionName = $(this).attr('data-action');
    var pageID = $(this).attr('data-id');

    $.ajax({
      url : frontajax.ajaxurl,
      type : 'post',
      dataType : "json",
      data : {
        'action' : actionName,
        'ID' : pageID
      },
      beforeSend:function(){
        $("#loaderDiv").show();
      },
      success:function( obj ) {
      
        var content = '';
        if(obj) {
          var event_status = obj.eventstatus;
          var eventStatusTxt = '';
          if(event_status && event_status!='upcoming') {
            eventStatusTxt = '<span>'+event_status+'</span>';
          }
          content += '<div class="modaltitleDiv text-center"><h5 class="modal-title">'+obj.post_title+'</h5></div>';
          if(obj.featured_image) {
            var img = obj.featured_image;
            content += '<div class="modalImage"><img src="'+img.url+'" alt="'+img.title+'p" class="feat-image"></div>';
          }
          content += '<div class="modalText"></div>';

          if(content) {
            $("#modalBodyText").html(content);
          }

          $.get(obj.postlink,function(data){
            var textcontent = '<div class="text">'+data+'</div></div>';
            if(eventStatusTxt) {
              $("#eventStatusTxt").html(eventStatusTxt);
            } else {
              $("#eventStatusTxt").html("");
            }
            $("#modalBodyText .modalText").html(textcontent);
            $("#activityModal").modal("show");
            $("#loaderDiv").hide();
            if( $("#activityModal .flexslider").length > 0 ) {
              $('.flexslider').flexslider({
                animation: "fade",
                smoothHeight: true,
                start: function(){

                }
              });
            }
          });
        }
        
      },
      error:function() {
        $("#loaderDiv").hide();
      }
    });

  });
});
</script>