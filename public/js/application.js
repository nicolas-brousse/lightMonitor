$(document).ready(function(){


  /**
   * INIT PLUGINS
   */

  // Facebox
  $('a[rel*=modal]').facebox();
  // Wysiwyg
  $(".wysiwyg").wysiwyg();



  /**
   * NAVIGATION
   */

  // Animation
  $("#main-nav li .nav-top-item").hover(
    function () {
      $(this).stop().animate({ paddingRight: "50px" }, 200);
    },
    function () {
      $(this).stop().animate({ paddingRight: "44px" });
    }
  );

  // Accordion
  $("#main-nav li ul").hide(); // Hide all sub menus
  $("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu
  $("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
    function () {
      $(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
      $(this).next().slideToggle("normal"); // Slide down the clicked sub menu
      return false;
    }
  );
  $("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
    function () {
      window.location.href=(this.href); // Just open the link instead of a sub menu
      return false;
    }
  );



  /**
   * CONTENT BOXES
   */

  $(".content-box-header h3").css({ "cursor":"s-resize" });
  $(".closed-box .content-box-content").hide();
  $(".closed-box .content-box-tabs").hide();
  $(".content-box-header h3").click(
    function () {
      var $parent = $(this).parent();
      $parent.next().slideToggle();
      $parent.parent().toggleClass("closed-box");
      $parent.find(".content-box-tabs").slideToggle();
    }
  );


    // Content box tabs
    $('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
    $('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
    $('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"
    $('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
      function() {
        $(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
        $(this).addClass('current'); // Add class "current" to clicked tab
        var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
        $(currentTab).siblings().slideUp('normal', function() { $(currentTab).slideDown(); });
        return false;
      }
    );


    //Close button:
    $(".close").click(
      function () {
        $(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
          $(this).slideUp(400);
        });
        return false;
      }
    );


    $('tbody tr:even').addClass("even");
    $('tbody tr:odd').addClass("odd");

    $('.check-all').click(
      function(){
        $(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
      }
    );


});
