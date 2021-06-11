
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
    var menu = document.getElementById("page-nav");
    if (menu.className === "page-nav") {
       menu.classList.toggle("page-nav--responsive");
    } else {
       menu.className = "page-nav";
    }
    }