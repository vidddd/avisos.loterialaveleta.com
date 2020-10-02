"use strict";

$(function () {
  setTimeout(() => {
    // PAra que de tiempoa que se printen los PDFs
    var container = $(".scroll-container");
    var clip = $(".scroll-clip"); //this is the element that scrolls
    var list = $(".scroll-list");
    var items = $("#container-pdfs").children();
    var itemMax = items.eq(0).outerWidth();
    var clipMax = clip.width();
    var posMin = 0;
    var posMax = items.length * itemMax;
    var addItems = Math.ceil(clipMax / itemMax);
    console.log(addItems);
    list.css("width", posMax + itemMax * addItems + "px");

    items.slice(0, addItems).clone(true).appendTo(list);

    //this.resetPosition = 0;
  }, 900);
});
