$(function(){ //DOM Ready
    $(".gridster ul").gridster({
        widget_margins: [10, 10],
        widget_base_dimensions: [200, 200]
    });
    
    var gridster = $(".gridster ul").gridster().data('gridster');

    $(document).on("click", "#ungridded a", function(e) {
      e.preventDefault();
      var target = $(e.target);
      var id = target.data("post_id");
      var title = target.html().slice(1);
      gridster.add_widget('<li data-post_id=' +
            '<li data-post_id="' + id + '" >' +
              '<div class="gridster-box">' +
                '<div class="row gridster-title">' +
                  title +
                '</div>' +
                '<div class="row">' +
                  '<button type="button" class="btn btn-info btn-block toggle-btn">Toggle Size</button>' +
                '</div>' +
                '<div class="row">' +
                  '<button type="button" class="btn btn-danger btn-block remove-btn">Remove</button>' +
                '</div>' +
              '</div>' + 
            '</li>', 1, 1);
      target.parent().remove();
    });

    $(document).on("click", ".toggle-btn", function(e) {
      var box = $(e.target).parent().parent().parent();
      var size = box.data("sizex");
      console.log(size);
      if (size != 3) {
        box.data("sizex", size + 1);
      } else {
        box.data("sizex", 1);
      }
    });

    $(document).on("click", ".remove-btn", function(e) {
      var box = $(e.target).parent().parent().parent();
      var title = "hello";
      var id= "world";
      $("#ungridded").append("<li><a href='#' data-post_id='" + id + "' class='text-primary'>+" + title + 
        "</a></li>");
      box.remove();
    });
});
