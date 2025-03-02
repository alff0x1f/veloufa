App = {
  helpers:
    numFormat: (num) ->
      numStr = num+''
      rgx = /(\d+)(\d{3})/;
      while (rgx.test(numStr))
        numStr = numStr.replace(rgx, '$1' + ' ' + '$2')
      return numStr;

  setInputEvents: ->
    $("input[type='text']").each ->
      $this=$(this);
      if(!$this.attr("data-placeholder"))
        return;

      $this.off("focus").off("blur");

      $this.on("focus", ->
        $this = $(this);
        val = $this.val();
        if($.trim(val)==$this.attr("data-placeholder"))
          $this.val("")
          $this.removeClass("blured")

        $this.removeClass("error")
      )

      $this.on("blur", ->
        $this=$(this)
        val = $this.val()
        if($.trim(val)=="")
          $this.val($this.attr("data-placeholder"))
          $this.addClass("blured")
      )

      if($.trim($this.val()) == '')
        $this.blur()

  sliderSpeed: 600,

  sliderBind: ->
    $(".head .slider .arrow-r").on "click.slidePic", {
      wrapper: $(".head .pictures")
      delay: 0
      speed: 600
    }, @slideRight
    $(".head .slider .arrow-r").on "click.slideTextBlack", {
      wrapper: $(".head .text-black")
      delay: 0
      speed: 500
    }, @slideRight
    $(".head .slider .arrow-r").on "click.slideTextRed", {
      wrapper: $(".head .text-red")
      delay: 0
      speed: 700
    }, @slideRight

    $(".head .slider .arrow-l").on "click.slidePic", {
      wrapper: $(".head .pictures")
      delay: 0
      speed: 600
    }, @slideLeft
    $(".head .slider .arrow-l").on "click.slideTextBlack", {
      wrapper: $(".head .text-black")
      delay: 0
      speed: 500
    }, @slideLeft
    $(".head .slider .arrow-l").on "click.slideTextRed", {
      wrapper: $(".head .text-red")
      delay: 0
      speed: 700
    }, @slideLeft

    setInterval("$('.head .slider .arrow-r').click();", 5000);

  slideRight: (e) ->
    $slider = e.data.wrapper
    $wrapper = e.data.wrapper
    $wrapper.stop(true,true)

    $current = $wrapper.find(".slider-item:first")
    $next = $wrapper.find(".slider-item:nth-child(2)")
    return false if $current.length!=1 or $next.length!=1

    slideWidth = $slider.width()
    $current.css(width: slideWidth)
    $next.css(width: slideWidth)
    $wrapper.css(width: "9999px")

    $wrapper.animate(
      { left: -slideWidth},
      e.data.speed,
    ->
      $wrapper = $(this).attr("style","")
      $current = $wrapper.find(".slider-item:first").attr("style","").appendTo($wrapper)
      $next = $wrapper.find(".slider-item:first").attr("style","")
    )
  slideLeft: (e) ->
    $slider = e.data.wrapper
    $wrapper = e.data.wrapper
    $wrapper.stop(true,true)

    $current = $wrapper.find(".slider-item:first")
    $next = $wrapper.find(".slider-item:last")
    return false if $current.length!=1 or $next.length!=1

    slideWidth = $slider.width()
    $current.css(width: slideWidth)
    $next.css(width: slideWidth).prependTo($wrapper)
    $wrapper.css(
      width: "9999px"
      left: -slideWidth
    )

    $wrapper.animate(
      { left: 0},
      e.data.speed,
    ->
      $wrapper = $(this).attr("style","")
      $current = $wrapper.find(".slider-item:first").attr("style","")
      $next = $wrapper.find(".slider-item:last").attr("style","")
    )

}
$(document).ready ->
  App.sliderBind();
  App.setInputEvents();
  return

