"use strict";

$(function () {
  setTimeout(() => {
    // Esperamos un poco, Para que de tiempo a que se printen los PDFs 
    setInterval(reordenaPdfs, 5000);
  }, 900);

  function reordenaPdfs() {
    // $('#container-pdfs').shuffle();
    printPDFS();
    /*
    $(".canvas-container").each(function (i) {
      $(this).animate({
        'left': '50px'
      }).animate({
        'left': '20px'
      }, 1000)
    });*/
  }

});