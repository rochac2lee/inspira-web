// CAROUSEL
!function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define("Siema",[],t):"object"==typeof exports?exports.Siema=t():e.Siema=t()}("undefined"!=typeof self?self:this,function(){return function(e){function t(r){if(i[r])return i[r].exports;var n=i[r]={i:r,l:!1,exports:{}};return e[r].call(n.exports,n,n.exports,t),n.l=!0,n.exports}var i={};return t.m=e,t.c=i,t.d=function(e,i,r){t.o(e,i)||Object.defineProperty(e,i,{configurable:!1,enumerable:!0,get:r})},t.n=function(e){var i=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(i,"a",i),i},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=0)}([function(e,t,i){"use strict";function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}Object.defineProperty(t,"__esModule",{value:!0});var n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},s=function(){function e(e,t){for(var i=0;i<t.length;i++){var r=t[i];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,i,r){return i&&e(t.prototype,i),r&&e(t,r),t}}(),l=function(){function e(t){var i=this;if(r(this,e),this.config=e.mergeSettings(t),this.selector="string"==typeof this.config.selector?document.querySelector(this.config.selector):this.config.selector,null===this.selector)throw new Error("Something wrong with your selector 😭");this.resolveSlidesNumber(),this.selectorWidth=this.selector.offsetWidth,this.innerElements=[].slice.call(this.selector.children),this.currentSlide=this.config.loop?this.config.startIndex%this.innerElements.length:Math.max(0,Math.min(this.config.startIndex,this.innerElements.length-this.perPage)),this.transformProperty=e.webkitOrNot(),["resizeHandler","touchstartHandler","touchendHandler","touchmoveHandler","mousedownHandler","mouseupHandler","mouseleaveHandler","mousemoveHandler","clickHandler"].forEach(function(e){i[e]=i[e].bind(i)}),this.init()}return s(e,[{key:"attachEvents",value:function(){window.addEventListener("resize",this.resizeHandler),this.config.draggable&&(this.pointerDown=!1,this.drag={startX:0,endX:0,startY:0,letItGo:null,preventClick:!1},this.selector.addEventListener("touchstart",this.touchstartHandler),this.selector.addEventListener("touchend",this.touchendHandler),this.selector.addEventListener("touchmove",this.touchmoveHandler),this.selector.addEventListener("mousedown",this.mousedownHandler),this.selector.addEventListener("mouseup",this.mouseupHandler),this.selector.addEventListener("mouseleave",this.mouseleaveHandler),this.selector.addEventListener("mousemove",this.mousemoveHandler),this.selector.addEventListener("click",this.clickHandler))}},{key:"detachEvents",value:function(){window.removeEventListener("resize",this.resizeHandler),this.selector.removeEventListener("touchstart",this.touchstartHandler),this.selector.removeEventListener("touchend",this.touchendHandler),this.selector.removeEventListener("touchmove",this.touchmoveHandler),this.selector.removeEventListener("mousedown",this.mousedownHandler),this.selector.removeEventListener("mouseup",this.mouseupHandler),this.selector.removeEventListener("mouseleave",this.mouseleaveHandler),this.selector.removeEventListener("mousemove",this.mousemoveHandler),this.selector.removeEventListener("click",this.clickHandler)}},{key:"init",value:function(){this.attachEvents(),this.selector.style.overflow="hidden",this.selector.style.direction=this.config.rtl?"rtl":"ltr",this.buildSliderFrame(),this.config.onInit.call(this)}},{key:"buildSliderFrame",value:function(){var e=this.selectorWidth/this.perPage,t=this.config.loop?this.innerElements.length+2*this.perPage:this.innerElements.length;this.sliderFrame=document.createElement("div"),this.sliderFrame.style.width=e*t+"px",this.enableTransition(),this.config.draggable&&(this.selector.style.cursor="-webkit-grab");var i=document.createDocumentFragment();if(this.config.loop)for(var r=this.innerElements.length-this.perPage;r<this.innerElements.length;r++){var n=this.buildSliderFrameItem(this.innerElements[r].cloneNode(!0));i.appendChild(n)}for(var s=0;s<this.innerElements.length;s++){var l=this.buildSliderFrameItem(this.innerElements[s]);i.appendChild(l)}if(this.config.loop)for(var o=0;o<this.perPage;o++){var a=this.buildSliderFrameItem(this.innerElements[o].cloneNode(!0));i.appendChild(a)}this.sliderFrame.appendChild(i),this.selector.innerHTML="",this.selector.appendChild(this.sliderFrame),this.slideToCurrent()}},{key:"buildSliderFrameItem",value:function(e){var t=document.createElement("div");return t.style.cssFloat=this.config.rtl?"right":"left",t.style.float=this.config.rtl?"right":"left",t.style.width=(this.config.loop?100/(this.innerElements.length+2*this.perPage):100/this.innerElements.length)+"%",t.appendChild(e),t}},{key:"resolveSlidesNumber",value:function(){if("number"==typeof this.config.perPage)this.perPage=this.config.perPage;else if("object"===n(this.config.perPage)){this.perPage=1;for(var e in this.config.perPage)window.innerWidth>=e&&(this.perPage=this.config.perPage[e])}}},{key:"prev",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,t=arguments[1];if(!(this.innerElements.length<=this.perPage)){var i=this.currentSlide;if(this.config.loop){if(this.currentSlide-e<0){this.disableTransition();var r=this.currentSlide+this.innerElements.length,n=this.perPage,s=r+n,l=(this.config.rtl?1:-1)*s*(this.selectorWidth/this.perPage),o=this.config.draggable?this.drag.endX-this.drag.startX:0;this.sliderFrame.style[this.transformProperty]="translate3d("+(l+o)+"px, 0, 0)",this.currentSlide=r-e}else this.currentSlide=this.currentSlide-e}else this.currentSlide=Math.max(this.currentSlide-e,0);i!==this.currentSlide&&(this.slideToCurrent(this.config.loop),this.config.onChange.call(this),t&&t.call(this))}}},{key:"next",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:1,t=arguments[1];if(!(this.innerElements.length<=this.perPage)){var i=this.currentSlide;if(this.config.loop){if(this.currentSlide+e>this.innerElements.length-this.perPage){this.disableTransition();var r=this.currentSlide-this.innerElements.length,n=this.perPage,s=r+n,l=(this.config.rtl?1:-1)*s*(this.selectorWidth/this.perPage),o=this.config.draggable?this.drag.endX-this.drag.startX:0;this.sliderFrame.style[this.transformProperty]="translate3d("+(l+o)+"px, 0, 0)",this.currentSlide=r+e}else this.currentSlide=this.currentSlide+e}else this.currentSlide=Math.min(this.currentSlide+e,this.innerElements.length-this.perPage);i!==this.currentSlide&&(this.slideToCurrent(this.config.loop),this.config.onChange.call(this),t&&t.call(this))}}},{key:"disableTransition",value:function(){this.sliderFrame.style.webkitTransition="all 0ms "+this.config.easing,this.sliderFrame.style.transition="all 0ms "+this.config.easing}},{key:"enableTransition",value:function(){this.sliderFrame.style.webkitTransition="all "+this.config.duration+"ms "+this.config.easing,this.sliderFrame.style.transition="all "+this.config.duration+"ms "+this.config.easing}},{key:"goTo",value:function(e,t){if(!(this.innerElements.length<=this.perPage)){var i=this.currentSlide;this.currentSlide=this.config.loop?e%this.innerElements.length:Math.min(Math.max(e,0),this.innerElements.length-this.perPage),i!==this.currentSlide&&(this.slideToCurrent(),this.config.onChange.call(this),t&&t.call(this))}}},{key:"slideToCurrent",value:function(e){var t=this,i=this.config.loop?this.currentSlide+this.perPage:this.currentSlide,r=(this.config.rtl?1:-1)*i*(this.selectorWidth/this.perPage);e?requestAnimationFrame(function(){requestAnimationFrame(function(){t.enableTransition(),t.sliderFrame.style[t.transformProperty]="translate3d("+r+"px, 0, 0)"})}):this.sliderFrame.style[this.transformProperty]="translate3d("+r+"px, 0, 0)"}},{key:"updateAfterDrag",value:function(){var e=(this.config.rtl?-1:1)*(this.drag.endX-this.drag.startX),t=Math.abs(e),i=this.config.multipleDrag?Math.ceil(t/(this.selectorWidth/this.perPage)):1,r=e>0&&this.currentSlide-i<0,n=e<0&&this.currentSlide+i>this.innerElements.length-this.perPage;e>0&&t>this.config.threshold&&this.innerElements.length>this.perPage?this.prev(i):e<0&&t>this.config.threshold&&this.innerElements.length>this.perPage&&this.next(i),this.slideToCurrent(r||n)}},{key:"resizeHandler",value:function(){this.resolveSlidesNumber(),this.currentSlide+this.perPage>this.innerElements.length&&(this.currentSlide=this.innerElements.length<=this.perPage?0:this.innerElements.length-this.perPage),this.selectorWidth=this.selector.offsetWidth,this.buildSliderFrame()}},{key:"clearDrag",value:function(){this.drag={startX:0,endX:0,startY:0,letItGo:null,preventClick:this.drag.preventClick}}},{key:"touchstartHandler",value:function(e){-1!==["TEXTAREA","OPTION","INPUT","SELECT"].indexOf(e.target.nodeName)||(e.stopPropagation(),this.pointerDown=!0,this.drag.startX=e.touches[0].pageX,this.drag.startY=e.touches[0].pageY)}},{key:"touchendHandler",value:function(e){e.stopPropagation(),this.pointerDown=!1,this.enableTransition(),this.drag.endX&&this.updateAfterDrag(),this.clearDrag()}},{key:"touchmoveHandler",value:function(e){if(e.stopPropagation(),null===this.drag.letItGo&&(this.drag.letItGo=Math.abs(this.drag.startY-e.touches[0].pageY)<Math.abs(this.drag.startX-e.touches[0].pageX)),this.pointerDown&&this.drag.letItGo){e.preventDefault(),this.drag.endX=e.touches[0].pageX,this.sliderFrame.style.webkitTransition="all 0ms "+this.config.easing,this.sliderFrame.style.transition="all 0ms "+this.config.easing;var t=this.config.loop?this.currentSlide+this.perPage:this.currentSlide,i=t*(this.selectorWidth/this.perPage),r=this.drag.endX-this.drag.startX,n=this.config.rtl?i+r:i-r;this.sliderFrame.style[this.transformProperty]="translate3d("+(this.config.rtl?1:-1)*n+"px, 0, 0)"}}},{key:"mousedownHandler",value:function(e){-1!==["TEXTAREA","OPTION","INPUT","SELECT"].indexOf(e.target.nodeName)||(e.preventDefault(),e.stopPropagation(),this.pointerDown=!0,this.drag.startX=e.pageX)}},{key:"mouseupHandler",value:function(e){e.stopPropagation(),this.pointerDown=!1,this.selector.style.cursor="-webkit-grab",this.enableTransition(),this.drag.endX&&this.updateAfterDrag(),this.clearDrag()}},{key:"mousemoveHandler",value:function(e){if(e.preventDefault(),this.pointerDown){"A"===e.target.nodeName&&(this.drag.preventClick=!0),this.drag.endX=e.pageX,this.selector.style.cursor="-webkit-grabbing",this.sliderFrame.style.webkitTransition="all 0ms "+this.config.easing,this.sliderFrame.style.transition="all 0ms "+this.config.easing;var t=this.config.loop?this.currentSlide+this.perPage:this.currentSlide,i=t*(this.selectorWidth/this.perPage),r=this.drag.endX-this.drag.startX,n=this.config.rtl?i+r:i-r;this.sliderFrame.style[this.transformProperty]="translate3d("+(this.config.rtl?1:-1)*n+"px, 0, 0)"}}},{key:"mouseleaveHandler",value:function(e){this.pointerDown&&(this.pointerDown=!1,this.selector.style.cursor="-webkit-grab",this.drag.endX=e.pageX,this.drag.preventClick=!1,this.enableTransition(),this.updateAfterDrag(),this.clearDrag())}},{key:"clickHandler",value:function(e){this.drag.preventClick&&e.preventDefault(),this.drag.preventClick=!1}},{key:"remove",value:function(e,t){if(e<0||e>=this.innerElements.length)throw new Error("Item to remove doesn't exist 😭");var i=e<this.currentSlide,r=this.currentSlide+this.perPage-1===e;(i||r)&&this.currentSlide--,this.innerElements.splice(e,1),this.buildSliderFrame(),t&&t.call(this)}},{key:"insert",value:function(e,t,i){if(t<0||t>this.innerElements.length+1)throw new Error("Unable to inset it at this index 😭");if(-1!==this.innerElements.indexOf(e))throw new Error("The same item in a carousel? Really? Nope 😭");var r=t<=this.currentSlide>0&&this.innerElements.length;this.currentSlide=r?this.currentSlide+1:this.currentSlide,this.innerElements.splice(t,0,e),this.buildSliderFrame(),i&&i.call(this)}},{key:"prepend",value:function(e,t){this.insert(e,0),t&&t.call(this)}},{key:"append",value:function(e,t){this.insert(e,this.innerElements.length+1),t&&t.call(this)}},{key:"destroy",value:function(){var e=arguments.length>0&&void 0!==arguments[0]&&arguments[0],t=arguments[1];if(this.detachEvents(),this.selector.style.cursor="auto",e){for(var i=document.createDocumentFragment(),r=0;r<this.innerElements.length;r++)i.appendChild(this.innerElements[r]);this.selector.innerHTML="",this.selector.appendChild(i),this.selector.removeAttribute("style")}t&&t.call(this)}}],[{key:"mergeSettings",value:function(e){var t={selector:".siema",duration:200,easing:"ease-out",perPage:1,startIndex:0,draggable:!0,multipleDrag:!0,threshold:20,loop:!1,rtl:!1,onInit:function(){},onChange:function(){}},i=e;for(var r in i)t[r]=i[r];return t}},{key:"webkitOrNot",value:function(){return"string"==typeof document.documentElement.style.transform?"transform":"WebkitTransform"}}]),e}();t.default=l,e.exports=t.default}])});


// Get the modal
var modal = document.getElementById("modalTab");
// Get the button that opens the modal
var btn = document.getElementById("modal-tab-button");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close-modal-tab")[0];
// When the user clicks on the button, open the modal
btn.onclick = function() {
  modal.style.display = "block";
};
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
};

// MODAL HOME EDIT
// MODAL HOME EDIT
// MODAL HOME EDIT

var modal2 = document.getElementById("homeModal");
var btn2 = document.getElementById("home-modal-tab-button");
var span2 = document.getElementsByClassName("home-close-modal-tab")[0];
// When the user clicks the button, open the modal
btn2.onclick = function() {
  modal2.style.display = "block";
};
// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
};
// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
};


// DELETE BUTTON & CHANGE ICON

function showElement() {
  var x = document.getElementById("myButton");
  var element = document.getElementById("myButton");

  if (x.style.display === "none") {
    x.style.display = "flex";
    element.classList.remove("delButton");
    document
      .getElementById("imageId")
      .setAttribute("class", "delButton-active");
  } else {
    x.style.display = "none";
    element.classList.remove("delButton-active");
    document.getElementById("imageId").setAttribute("class", "delButton");
  }
}

//   VERTICAL MENU TABS

function openTab(evt, tabName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target.tagName == "HTML" || event.target.tagName == "BODY") {
      $(".tablinks").removeClass("active");
      $(".tabcontent").hide();
      $("#cat-home").show();
      $(".menutab").removeAttr("style");
    } else if ($(event.target).hasClass("tablinks")) {
      $(".menutab").css("background-color", "#ffae00");
    }
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the link that opened the tab
  document.getElementById(tabName).style.display = "flex";
  evt.currentTarget.className += " active";
  

}


// CAROUSEL TAB ACONTECE NA ESCOLA
// CAROUSEL TAB ACONTECE NA ESCOLA
// CAROUSEL TAB ACONTECE NA ESCOLA

$(document).ready(function() {
  var item_width = $("#itens li").outerWidth();
  var left_value = item_width * -1;

  $("#itens li:first").before($("#itens li:last"));

  $("#itens ul").css({ left: left_value });

  $("#prev").click(function() {
    var left_intend = parseInt($("#itens ul").css("left")) + item_width;

    $("#itens ul").animate({ left: left_intend }, 200, function() {
      $("#itens li:first").before($("#itens li:last"));

      $("#itens ul").css({ left: left_value });
    });
  });

  $("#next").click(function() {
    var left_intend = parseInt($("#itens ul").css("left")) - item_width;

    $("#itens ul").animate({ left: left_intend }, 200, function() {
      $("#itens li:last").after($("#itens li:first"));

      $("#itens ul").css({ left: left_value });
    });
  });
});

//CAROUSEL HOME em teste
//CAROUSEL HOME
//CAROUSEL HOME

$(document).ready(function() {
  var item_width2 = $("#itens2 li").outerWidth();
  var left_value2 = item_width2 * -1;

  $("#itens2 li:first").before($("#itens2 li:last"));

  $("#itens2 ul").css({ left: left_value2 });

  $("#prev2").click(function() {
    var left_intend = parseInt($("#itens2 ul").css("left")) + item_width2;

    $("#itens2 ul").animate({ left: left_intend }, 200, function() {
      $("#itens2 li:first").before($("#itens2 li:last"));

      $("#itens2 ul").css({ left: left_value2 });
    });
  });

  $("#next2").click(function() {
    var left_intend = parseInt($("#itens2 ul").css("left")) - item_width2;

    $("#itens2 ul").animate({ left: left_intend }, 200, function() {
      $("#itens2 li:last").after($("#itens2 li:first"));

      $("#itens2 ul").css({ left: left_value2 });
    });
  });
});

/**	
@description	
	Creates a slide-show (i.e. carousel) out of anything with the class "carousel". 	
@guide	
	1. Add or link this script to the bottom of your body tag, or after the DOM has 	
	   completely loaded.	
	2. After this script, create a new object with the name of your choosing.	
	:: var customName = new Carousel();	
	3. If multiple carousels are desired wrap the elements with the "carousel" class 	
	   in an element with an ID of your choosing, and then create the carousel objects.	
	:: var customName1 = new Carousel("your1WrapperID");	
	:: var customName2 = new Carousel("your2WrapperID");	
	4. An object with no ID will control all carousels.	
	5. Control the carousel with the functions: prev, next, stop, and slide.	
	:: customName.next(); 		//shows the next element in the carousel.	
	:: customName.prev(); 		//shows the previous element in the carousel.	
	:: customName.next(1000); 	//plays the carousel forward at the rate desired in milliseconds.	
	:: customName.prev(500); 	//plays the carousel backward at the rate desired in milliseconds.	
	:: customName.stop(); 		//stops the carousel. using any other function will also stop the carousel.	
	:: customName.slide(2); 	//shows the slide/more specifically the index provided by the argument. 	
	6. Video guide: https://youtu.be/1Tge4HJA7gE	
@author		Jeremy England	
@company	SimplyCoded	
@revised	04-16-2016	
*/	

// CAROUSEL OBJECT	
function Carousel(containerID) {	
  this.container = document.getElementById(containerID) || document.body;	
  this.slides = this.container.querySelectorAll(".carousel");	
  this.total = this.slides.length - 1;	
  this.current = 0;	

  // start on slide 1	
  this.slide(this.current);	
}	
// NEXT	
Carousel.prototype.next = function(interval) {	
  this.current === this.total ? (this.current = 0) : (this.current += 1);	

  this.stop();	
  this.slide(this.current);	

  if (typeof interval === "number" && interval % 1 === 0) {	
    var context = this;	
    this.run = setTimeout(function() {	
      context.next(interval);	
    }, interval);	
  }	
};	
// PREVIOUS	
Carousel.prototype.prev = function(interval) {	
  this.current === 0 ? (this.current = this.total) : (this.current -= 1);	

  this.stop();	
  this.slide(this.current);	

  if (typeof interval === "number" && interval % 1 === 0) {	
    var context = this;	
    this.run = setTimeout(function() {	
      context.prev(interval);	
    }, interval);	
  }	
};	
// STOP PLAYING	
Carousel.prototype.stop = function() {	
  clearTimeout(this.run);	
};	
// SELECT SLIDE	
Carousel.prototype.slide = function(index) {	
  this.current = index;	

  //Seta a classe dos botões	
  var btnText = (this.current + 1).toString();	
  $("#btns")	
    .children("button")	
    .each(function() {	
      $(this).removeClass("active"); //Remove classe active de todos os botões	

      if (btnText == this.innerText) {	
        //Adiciona classe active ao índice atual	
        $(this).addClass("active"); // "this" is the current element in the loop	
      }	
    });	

  if (index >= 0 && index <= this.total) {	
    this.stop();	
    for (var s = 0; s <= this.total; s++) {	
      if (s === index) {	
        this.slides[s].style.display = "inline-block";	
      } else {	
        this.slides[s].style.display = "none";	
      }	
    }	
  } else {	
    alert("Index " + index + " doesn't exist. Available : 0 - " + this.total);	
  }	
};	

//HOME CAROUSEL+MODAL AREA	

// SELECT HOME SLIDE	
Carouselhome.prototype.slide = function(index) {	
  this.current = index;	

  //Seta a classe dos botões	
  var btnText = (this.current + 1).toString();	
  $("#home-modal-btns")	
    .children("button")	
    .each(function() {	
      $(this).removeClass("active"); //Remove classe active de todos os botões	

      if (btnText == this.innerText) {	
        //Adiciona classe active ao índice atual	
        $(this).addClass("active"); // "this" is the current element in the loop	
      }	
    });	

  if (index >= 0 && index <= this.total) {	
    this.stop();	
    for (var s = 0; s <= this.total; s++) {	
      if (s === index) {	
        this.slides[s].style.display = "inline-block";	
      } else {	
        this.slides[s].style.display = "none";	
      }	
    }	
  } else {	
    alert("Index " + index + " doesn't exist. Available : 0 - " + this.total);	
  }	
};	
// END SELECT HOME SLIDE	

// HOME	
// CAROUSEL OBJECT	
function Carouselhome(containerID) {	
  this.container = document.getElementById(containerID) || document.body;	
  this.slides = this.container.querySelectorAll(".home-carousel");	
  this.total = this.slides.length - 1;	
  this.current = 0;	

  // start on slide 1	
  this.slide(this.current);	
}	
// NEXT	
Carouselhome.prototype.next = function(interval) {	
  this.current === this.total ? (this.current = 0) : (this.current += 1);	

  this.stop();	
  this.slide(this.current);	

  if (typeof interval === "number" && interval % 1 === 0) {	
    var context = this;	
    this.run = setTimeout(function() {	
      context.next(interval);	
    }, interval);	
  }	
};	
// PREVIOUS	
Carouselhome.prototype.prev = function(interval) {	
  this.current === 0 ? (this.current = this.total) : (this.current -= 1);	

  this.stop();	
  this.slide(this.current);	

  if (typeof interval === "number" && interval % 1 === 0) {	
    var context = this;	
    this.run = setTimeout(function() {	
      context.prev(interval);	
    }, interval);	
  }	
};	
// STOP PLAYING	
Carouselhome.prototype.stop = function() {	
  clearTimeout(this.run);	
};

// DELETE BUTTON & CHANGE ICON - EDIT MODAL HOME

function showElementhome() {
  var x = document.getElementById("myButtonhome");
  var element = document.getElementById("myButtonhome");

  if (x.style.display === "none") {
    x.style.display = "flex";
    element.classList.remove("delButtonhome");
    document
      .getElementById("imageIdhome")
      .setAttribute("class", "delButtonhome-active");
  } else {
    x.style.display = "none";
    element.classList.remove("delButtonhome-active");
    document
      .getElementById("imageIdhome")
      .setAttribute("class", "delButtonhome");
  }
}



