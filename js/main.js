
    var mybutton = document.getElementById("topbtn");
    
    window.onscroll = function() {scrollFunction()};
    
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
      } else {
        mybutton.style.display = "none";
      }
    }
    
    function topFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }

    function mobileMenuFunction() {
      var menu = document.getElementById("topnav");
      var menuicon = document.getElementById("menu-icon");

      if (menu.className === "topnav") {
        menu.className += " responsive";
        menuicon.classList.toggle("open");

      } else {
        menu.className = "topnav";
        menuicon.classList.toggle("open");
      }
    }