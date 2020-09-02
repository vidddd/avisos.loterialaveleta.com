var form = document.getElementById("formsubir");
var input = document.getElementById("pdf_pdf");
var subiendo = document.getElementById("subiendo");

var change_running = false;
input.addEventListener("change", function () {
  if (!change_running) {
    setTimeout(function () {
      change_running = true;
      form.submit();
      subiendo.style.display = "block";
      change_running = false;
    }, 300);
  }
});
