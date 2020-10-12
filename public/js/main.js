"use strict";

$(function () {
  damePdfs();
  var interval = setInterval(damePdfs, 10000);

  function damePdfs() {
    $("#container-pdfs").empty(); // limpiamos antes de pintar el html
    $.getJSON("damePdfs", function (pdfs) {
      pdfs.forEach(printPDF);
    });
  }

  function printPDF(elemento, indice) {
    let html = `<div id="pdf_viewer_${indice}" class="canvas-container">
                            <canvas id="pdf_renderer_${indice}"></canvas>
                        </div>`;

    document
      .getElementById("container-pdfs")
      .insertAdjacentHTML("beforeend", html);

    const loadPDF = pdfjsLib.getDocument("./pdfs/" + elemento).then((pdf) => {
          //cojemos solo una pagina
          pdf.getPage(1).then((page) => {
            var canvas = document.getElementById("pdf_renderer_" + indice);
            var ctx = canvas.getContext("2d");
            var viewport = page.getViewport(1);
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            page.render({
              canvasContext: ctx,
              viewport: viewport,
            });
          });
           Promise.resolve();
        });
  }
});
