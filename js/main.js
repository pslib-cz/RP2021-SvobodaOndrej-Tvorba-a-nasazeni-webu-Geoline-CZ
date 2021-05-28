
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

    function toggleMenu() {
    var menu = document.getElementById("topnav");
    if (menu.className === "topnav") {
       menu.classList.toggle("responsive");
    } else {
       menu.className = "topnav";
    }
    }