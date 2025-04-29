$(document).ready(function(){
			// $fn.scrollSpeed(step, speed, easing);
			jQuery.scrollSpeed(200, 800);
});

// Custom scrolling speed with jQuery
// Source: github.com/ByNathan/jQuery.scrollSpeed
// Version: 1.0.2

(function($) {
    
    jQuery.scrollSpeed = function(step, speed, easing) {
        
        var $document = $(document),
            $window = $(window),
            $body = $('html, body'),
            option = easing || 'default',
            root = 0,
            scroll = false,
            scrollY,
            scrollX,
            view;
            
        if (window.navigator.msPointerEnabled)
        
            return false;
            
        $window.on('mousewheel DOMMouseScroll', function(e) {

            if (e.ctrlKey || $(".modal.show").length > 0)
            {
               return;
            }
            
            var deltaY = e.originalEvent.wheelDeltaY,
                detail = e.originalEvent.detail;
                scrollY = $document.height() > $window.height();
                scrollX = $document.width() > $window.width();
                scroll = true;
            
            if (scrollY) {
                
                view = $window.height();
                    
                if (deltaY < 0 || detail > 0)
            
                    root = (root + view) >= $document.height() ? root : root += step;
                
                if (deltaY > 0 || detail < 0)
            
                    root = root <= 0 ? 0 : root -= step;
                
                $body.stop().animate({
            
                    scrollTop: root
                
                }, speed, option, function() {
            
                    scroll = false;
                
                });
            }
            
            if (scrollX) {
                
                view = $window.width();
                    
                if (deltaY < 0 || detail > 0)
            
                    root = (root + view) >= $document.width() ? root : root += step;
                
                if (deltaY > 0 || detail < 0)
            
                    root = root <= 0 ? 0 : root -= step;
                
                $body.stop().animate({
            
                    scrollLeft: root
                
                }, speed, option, function() {
            
                    scroll = false;
                
                });
            }
            
            return false;
            
        }).on('scroll', function() {
            
            if (scrollY && !scroll) root = $window.scrollTop();
            if (scrollX && !scroll) root = $window.scrollLeft();
            
        }).on('resize', function() {
            
            if (scrollY && !scroll) view = $window.height();
            if (scrollX && !scroll) view = $window.width();
            
        });       
    };
    
    jQuery.easing.default = function (x,t,b,c,d) {
    
        return -c * ((t=t/d-1)*t*t*t - 1) + b;
    };
    
})(jQuery);
(function ($) {
    "use strict"; // Start of use strict

    // Smooth scrolling using jQuery easing
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () 
    {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) 
        {
            var target = $(this.hash);

            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

            if (target.length) 
            {
                $('html, body').animate({
                    scrollTop: (target.offset().top - 54)
                }, 1000, "easeInOutExpo");

                return false;
            }
        }
    });

    // Closes responsive menu when a scroll trigger link is clicked
    $('.js-scroll-trigger').click(function () 
    {
        $('.navbar-collapse').collapse('hide');
    });

    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
        target: '#mainNav',
        offset: 112
    });

})(jQuery); // End of use strict
Chart.elements.Rectangle.prototype.draw=function(){function t(t){return s[(f+t)%4]}var r,e,i,o,_,h,l,a,b=this._chart.ctx,d=this._view,n=d.borderWidth,u=this._chart.config.options.cornerRadius;if(u<0&&(u=0),void 0===u&&(u=0),l=d.horizontal?(r=d.base,e=d.x,i=d.y-d.height/2,o=d.y+d.height/2,_=r<e?1:-1,h=1,d.borderSkipped||"left"):(r=d.x-d.width/2,e=d.x+d.width/2,i=d.y,_=1,h=(o=d.base)>i?1:-1,d.borderSkipped||"bottom"),n){var T=Math.min(Math.abs(r-e),Math.abs(i-o)),v=(n=T<n?T:n)/2,g=r+("left"!==l?v*_:0),c=e+("right"!==l?-v*_:0),C=i+("top"!==l?v*h:0),w=o+("bottom"!==l?-v*h:0);g!==c&&(i=C,o=w),C!==w&&(r=g,e=c)}b.beginPath(),b.fillStyle=d.backgroundColor,b.strokeStyle=d.borderColor,b.lineWidth=n;var s=[[r,o],[r,i],[e,i],[e,o]],f=["bottom","left","top","right"].indexOf(l,0);-1===f&&(f=0);var q=t(0);b.moveTo(q[0],q[1]);for(var m=1;m<4;m++)q=t(m),nextCornerId=m+1,4==nextCornerId&&(nextCornerId=0),nextCorner=t(nextCornerId),width=s[2][0]-s[1][0],height=s[0][1]-s[1][1],x=s[1][0],y=s[1][1],(a=u)>Math.abs(height)/2&&(a=Math.floor(Math.abs(height)/2)),a>Math.abs(width)/2&&(a=Math.floor(Math.abs(width)/2)),height<0?(x_tl=x,x_tr=x+width,y_tl=y+height,y_tr=y+height,x_bl=x,x_br=x+width,y_bl=y,y_br=y,b.moveTo(x_bl+a,y_bl),b.lineTo(x_br-a,y_br),b.quadraticCurveTo(x_br,y_br,x_br,y_br-a),b.lineTo(x_tr,y_tr+a),b.quadraticCurveTo(x_tr,y_tr,x_tr-a,y_tr),b.lineTo(x_tl+a,y_tl),b.quadraticCurveTo(x_tl,y_tl,x_tl,y_tl+a),b.lineTo(x_bl,y_bl-a),b.quadraticCurveTo(x_bl,y_bl,x_bl+a,y_bl)):width<0?(x_tl=x+width,x_tr=x,y_tl=y,y_tr=y,x_bl=x+width,x_br=x,y_bl=y+height,y_br=y+height,b.moveTo(x_bl+a,y_bl),b.lineTo(x_br-a,y_br),b.quadraticCurveTo(x_br,y_br,x_br,y_br-a),b.lineTo(x_tr,y_tr+a),b.quadraticCurveTo(x_tr,y_tr,x_tr-a,y_tr),b.lineTo(x_tl+a,y_tl),b.quadraticCurveTo(x_tl,y_tl,x_tl,y_tl+a),b.lineTo(x_bl,y_bl-a),b.quadraticCurveTo(x_bl,y_bl,x_bl+a,y_bl)):(b.moveTo(x+a,y),b.lineTo(x+width-a,y),b.quadraticCurveTo(x+width,y,x+width,y+a),b.lineTo(x+width,y+height-a),b.quadraticCurveTo(x+width,y+height,x+width-a,y+height),b.lineTo(x+a,y+height),b.quadraticCurveTo(x,y+height,x,y+height-a),b.lineTo(x,y+a),b.quadraticCurveTo(x,y,x+a,y));b.fill(),n&&b.stroke()};