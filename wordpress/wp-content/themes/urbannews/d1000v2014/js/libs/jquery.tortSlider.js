/*!
 * Simple Slider for $
 *
 * Copyright 2012, Bogatkin D. <bogatkin@tortstudio.com>
 *
 * http://tortstudio.com/
 * http://twitter.com/xment
 */

(function ($) {
    $.fn.extend({
        tortSlider:function(_params){

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// define params and default values
            var params={
                step: 			((isNaN(parseInt(_params.step))) ?
                    (1) :
                    (parseInt(_params.step))),

                animationSpeed: ((isNaN(parseInt(_params.animationSpeed))) ?
                    (500) :
                    (parseInt(_params.animationSpeed))),

                autoSlide: 		((_params.autoSlide===null) ?
                    false :
                    (_params.autoSlide==true)),	// for boolean value only

                interval: 		((isNaN(parseInt(_params.interval))) ?
                    (5000) :
                    (parseInt(_params.interval))),

                onAfterAnimation: (typeof(_params.onAfterAnimation)=="function") ? _params.onAfterAnimation : null,

                vertical:        ((_params.vertical===null) ?
                    false :
                    (_params.vertical==true)),
                cyclic:        ((_params.cyclic===null) ?
                    false :
                    (_params.cyclic==true))
            }

// define used elements
            $this=$(this);
            $items=$this.children();

// wrap slider
            $mainWrapper=$("<div>")
                .attr("id","tortSlider-wrapper-"+Math.floor(Math.random()*1000000))
                .addClass("tortSlider-wr")
                .css({
                    overflow: 	"hidden",
                    position: 	"relative"
                })
                .appendTo($this);



// fix possible non-constant width of items
            $items.width(function(){return $(this).width()+"px";});

// wrap items
            $wrapper=$("<div>")
                .addClass("tortSlider-subwr")
                .append($items)
                .appendTo($mainWrapper);
            if(params.vertical){
                $mainWrapper
                    .addClass("vertical")
                    .css({"height":$this.height()});
                $wrapper.css({
                    height: 	"9999px",
                    overflow: 	"hidden",
                    position: 	"relative",
                    top: 		"0px"
                });
            }
            else{
                $wrapper.css({
                    width: 		"9999px",
                    overflow: 	"hidden",
                    position: 	"relative",
                    left: 		"0px"
                })
            }

            $mainWrapper.on('mouseenter',function(){$(this).addClass('hover')})
            $mainWrapper.on('mouseleave',function(){$(this).removeClass('hover')})

            slideLeft=function(){

                var isVertical=false;
                if($(this).hasClass("vertical"))
                    isVertical=true;

                // define wrapper
                $wrapper=$(this).parent().children(".tortSlider-wr").children(".tortSlider-subwr")
                    .stop(true,true); // finish current animation



                // get step in px
                var animateParams;
                if(isVertical)
                    animateParams={
                        top: -($wrapper.children(":first").outerHeight(true)*params.step)
                    };
                else
                    animateParams={
                        left:-($wrapper.children(":first").outerWidth(true)*params.step)
                    }

                // first next element index. -1 for converting to zero-based index
                nextIndex=$wrapper.children().length-params.step-1;

                // clone last element to first position and hide it
                $wrapper
                    .prepend($wrapper.children(":gt("+nextIndex+")").clone(true))
                    .css(animateParams)
                    // show moved element in animation by restoring left position to 0px
                    .animate(
                    {
                        "left":"0px",
                        "top":"0px"
                    },
                    params.animationSpeed,
                    function(){
                        // remove original last element on animation end
                        $(this).children(":gt("+(nextIndex+params.step)+")").remove();

                        if(params.onAfterAnimation!==null) params.onAfterAnimation();
                    }
                );

                // block default link handler
                return false;
            }

            slideRight=function(){

                var isVertical=false;
                if($(this).hasClass("vertical"))
                    isVertical=true;

                // define wrapper
                $wrapper=$(this).parent().children(".tortSlider-wr").children(".tortSlider-subwr")
                    .stop(true,true); // finish current animation

                // get step in px
                var animateParams;
                if(isVertical)
                    animateParams={
                        top: -($wrapper.children(":first").outerHeight(true)*params.step)
                    };
                else
                    animateParams={
                        left:-($wrapper.children(":first").outerWidth(true)*params.step)
                    }

                $wrapper.append($wrapper.children(":lt("+params.step+")").clone(true))
                    .animate(
                    animateParams,
                    params.animationSpeed,
                    function(){
                        $(this)
                            .css("left","0px")
                            .css("top","0px")
                            .children(":lt("+params.step+")").remove();
                        if(params.onAfterAnimation!==null) params.onAfterAnimation();
                    }
                );

                return false;
            }

            $arrowLeft=$("<a href='#'>")
                .addClass("tortSlider-arr tortSlider-arr-l")
                .prependTo($this)
                .on("click.tortSlider",slideLeft)
                .on('mouseenter',function(){$(this).addClass('hover')})
                .on('mouseleave',function(){$(this).removeClass('hover')})

            $arrowRight=$("<a href='#'>")
                .addClass("tortSlider-arr tortSlider-arr-r")
                .prependTo($this)
                .on("click.tortSlider",slideRight)
                .on('mouseenter',function(){$(".tortSlider-wr").addClass('hover')})
                .on('mouseleave',function(){$(".tortSlider-wr").removeClass('hover')})

            if(params.vertical){
                $arrowLeft.add($arrowRight).addClass("vertical");
            }
            if(params.autoSlide) setInterval("if(!$('#"+$mainWrapper.attr("id")+"').hasClass('hover')) $('#"+$mainWrapper.attr("id")+"').parent().children('.tortSlider-arr-r').click();",params.interval);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
    })
})(jQuery);