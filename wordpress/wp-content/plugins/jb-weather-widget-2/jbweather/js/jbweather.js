/*
 * @version     2.0
 * @package     J.B.Weather Widget
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      J.B.MARKET <support@jbmarket.net>
 */

function JBWeather(unique) {
    var unique = "." + unique;
    
    var glob = {
        params: null
    }
    
    this.init = function(params) {
        glob.params = params;
        
        display.setColor();
        display.adjustSize();
        listener.bindControls();
        ajax.autoComplete();
        ajax.gatherData();
        
        
    }
    
    var listener = {
        bindControls : function(){
            jQuery(unique + ' .jbww_head_search a').on('click', function(event){
               display.searchBar.show();
               event.stopPropagation();
               event.preventDefault();
            });
            
            jQuery('.jbmww_wrapper').on('click', function(event){
                display.searchBar.hide();
                event.stopPropagation();
            });
            
            jQuery(unique + ' .jbww_search_bar').on('click', function(event){
                event.stopPropagation();
            });
            
            jQuery(unique + ' .jbww_search_bar input').on("keydown", function(event){
                if (event.which == 13) {
                    var location = jQuery(this).val();
                    if (location != ""){
                        glob.params.location = location;
                        ajax.gatherData();
                        display.searchBar.hide();
                    }
                    event.stopPropagation();
                }
            });
            
            jQuery(unique + ' .jbww_search_bar a.searchButton').on("click", function(event){
                var location = jQuery(this).parent().find("input").val();
                if (location != ""){
                    glob.params.location = location;
                    ajax.gatherData();
                    display.searchBar.hide();
                }
                event.stopPropagation();
            });
        }
    }
    
    var ajax = {
        gatherData : function(){
            var data  = "location="      + glob.params.location;
                data += "&apiKey="       + glob.params.apiKey;
                data += "&curl="         + glob.params.curl;
                
            jQuery.ajax({
                async: true,
                type: "POST",
                url: glob.params.url + "/xml/xml.php",
                data : data,
                error: function(){
                    display.notFound();
                },
                success: function(data){
                    
                    if (!jQuery(data).find("location").text()) {
                        display.notFound();
                        return;
                    }
                    
                    var current, forecast;
                    
                    current = {
                        location     : jQuery(data).find("location").text(),
                        date         : jQuery(data).find("current date").text(),
                        time         : jQuery(data).find("current time").text(),
                        temperature  : {
                            c : jQuery(data).find("current temperature c").text(),
                            f : jQuery(data).find("current temperature f").text()
                        },
                        code         : jQuery(data).find("current code").text(),
                        description  : jQuery(data).find("current description").text(),
                        wind         : {
                            speed     : {
                                m: jQuery(data).find("current wind windSpeed m").text(),
                                k: jQuery(data).find("current wind windSpeed k").text()
                            },
                            direction : jQuery(data).find("current wind direction").text()
                        }
                    }

                    forecast     = {}
                    forecast.day = [];
                    jQuery(data).find("day").each(function(i){
                        forecast.day[i] = {
                            date         : jQuery(this).find("date").text(),
                            time         : jQuery(this).find("time").text(),
                            temperature  : {
                                max : {
                                    c : jQuery(this).find("temperature max c").text(),
                                    f : jQuery(this).find("temperature max f").text()
                                },
                                min : {
                                    c : jQuery(this).find("temperature min c").text(),
                                    f : jQuery(this).find("temperature min f").text()
                                }
                            },
                            code         : jQuery(this).find("code").text(),
                            description  : jQuery(this).find("description").text(),
                            wind         : {
                                speed     : {
                                    m: jQuery(this).find("wind windSpeed m").text(),
                                    k: jQuery(this).find("wind windSpeed k").text()
                                },
                                direction : jQuery(this).find("wind direction").text()
                            }
                        }
                    });
                    display.data(current, forecast);
                },
                dataType: "xml"
            });
        },
        
        autoComplete: function(){
            jQuery(".jbww_search_bar input").autocomplete({ 
                appendTo: ".jbww_search_bar",
                source: function( request, response ) {
                    jQuery.ajax({
                        url: "http://ws.geonames.org/searchJSON",
                        dataType: "jsonp",
                        data: {
                            featureClass: "P",
                            style: "full",
                            maxRows: 5,
                            name_startsWith: request.term
                        },
                        success: function( data ) {
                            response( jQuery.map( data.geonames, function( item ) {
                                return {
                                    label: item.name + ", " + item.countryName
                                }
                            }));
                        }
                    });
                },
                minLength: 3,
                select: function( event, ui ) {
                    var location = ui.item.value;
                    
                    if (location != ""){
                        glob.params.location = location;
                        ajax.gatherData();
                        display.searchBar.hide();
                    }
                },
                open: function() {},
                close: function(){}
            });
        }
    }
    
    var display = {
        
        setColor: function(){
            if (glob.params.basicColor != "") {
                jQuery(unique + ' .jbww_head').css({
                   backgroundColor: glob.params.basicColor 
                });
                jQuery(unique + ' .jbmww_wrapper .jbww_search_bar a.searchButton').css({
                   backgroundColor: glob.params.basicColor 
                });
            }
        },
        
        adjustSize: function(){
            if (parseInt(glob.params.width, 10) < 280) {
                glob.params.width = 280
            }
            
            jQuery(unique + ' .jbmww_wrapper').width(glob.params.width);
            jQuery(unique + ' .jbww_head').width(glob.params.width);
            jQuery(unique + ' .jbww_head_top').width(glob.params.width);
            jQuery(unique + ' .jbww_head_today_forecast').width(glob.params.width - 45);
             
            jQuery(unique + ' .jbww_weekly_forecast_icon').css({
                marginLeft : (glob.params.width / 2) - jQuery(unique + ' .jbww_weekly_forecast_date').width() - jQuery(unique + ' .jbww_weekly_forecast_icon').width() / 2
            });
             
        },
        
        data : function(current, forecast) {
            jQuery(unique + ' .jbww_head_location p').text(current.location);
            jQuery(unique + ' .jbww_head_location span').text(current.date);
            jQuery(unique + ' .jbww_head_today_forecast_digit p').text(glob.params.degreesUnits == "C" ? current.temperature.c + "\u00B0" : current.temperature.f + "\u00B0" );
            jQuery(unique + ' .jbww_head_today_wind_speed p').text(glob.params.windUnits == "M" ? current.wind.speed.m + " MPH" : current.wind.speed.k + " KMH");
            jQuery(unique + ' .jbww_head_today_wind_direction p').text(helper.windDirection(current.wind.direction));
            jQuery(unique + ' .jbww_head_today_forecast_icon > div').attr("class", helper.codeToClass(current.code));
        
            jQuery(unique + ' .jbww_weekly_forecast_day').each(function(i){
                jQuery(this).find('.jbww_weekly_forecast_date').text(forecast.day[i].date);
                jQuery(this).find('.jbww_weekly_forecast_deg').text(glob.params.degreesUnits == "C" ? forecast.day[i].temperature.min.c + "\u00B0" + "C" + " / " + forecast.day[i].temperature.max.c + "\u00B0" + "C": forecast.day[i].temperature.min.f + "\u00B0" + "F" + " / " + forecast.day[i].temperature.max.f + "\u00B0" + "F" )
                jQuery(this).find('.jbww_weekly_forecast_icon > div').attr("class", helper.codeToClass(forecast.day[i].code));
            });
        },
        
        searchBar: {
            show: function(){
                jQuery(unique + ' .jbww_search_bar').fadeIn(150);
            },
            hide: function(){
                jQuery(unique + ' .jbww_search_bar').fadeOut(150);
            }
        },
        
        notFound: function(){
            /*
             * Something went wrong; display n/a
             */
            
            jQuery(unique + ' .jbww_head_location p').text("Unable to find location");
            jQuery(unique + ' .jbww_head_location span').text("N/A");
            jQuery(unique + ' .jbww_head_today_forecast_digit p').text("");
            jQuery(unique + ' .jbww_head_today_wind_speed p').text("N/A");
            jQuery(unique + ' .jbww_head_today_wind_direction p').text("N/A");
            jQuery(unique + ' .jbww_head_today_forecast_icon > div').attr("class", "n-a");
        
            jQuery(unique + ' .jbww_weekly_forecast_day').each(function(i){
                jQuery(this).find('.jbww_weekly_forecast_date').text("N/A");
                jQuery(this).find('.jbww_weekly_forecast_deg').text("N/A");
                jQuery(this).find('.jbww_weekly_forecast_icon > div').attr("class", "n-a");
            });
        }
    }
    
    var helper = {
        windDirection : function(dir) {
            switch (dir) {
                case 'N' :
                    return "North";
                    break;
            
                case 'NNE' :
                    return "North-East";
                    break;
            
                case 'NE' :
                    return "North-East";
                    break;
            
                case 'ENE' :
                    return "North-East";
                    break;
            
                case 'E' :
                    return "East";
                    break;
            
                case 'ESE' :
                    return "South-East";
                    break;
            
                case 'SE' :
                    return "South-East";
                    break;
            
                case 'SSE' :
                    return "South-East";
                    break;
            
                case 'S' :
                    return "South";
                    break;
            
                case 'SSW' :
                    return "South-West";
                    break;
            
                case 'SW' :
                    return "South-West";
                    break;
            
                case 'WSW' :
                    return "South-West";
                    break;
            
                case 'W' :
                    return "West";
                    break;
            
                case 'WNW' :
                    return "North-West";
                    break;
            
                case 'NW' :
                    return "North-West";
                    break;
            
                case 'NNW' :
                    return "North-West";
                    break;
            }
        },
        
        codeToClass: function(code){
            switch (parseInt(code ,10)) {
                case (113) :
                    return "sunny";
                    break;
                case (116) :
                    return "partlycloudy";
                    break;
                case (119) :
                    return "cloudy";
                    break;
                case (122) :
                    return "cloudy";
                    break;
                case (143) :
                    return "fog";
                    break;
                case (176) :
                    return "rainy";
                    break;
                case (179) :
                    return "snowly";
                    break;
                case (182) :
                    return "snowly";
                    break;
                case (185) :
                    return "snowly";
                    break;
                case (200) :
                    return "thunder";
                    break;
                case (227) :
                    return "snowly";
                    break;
                case (230) :
                    return "snowly";
                    break;
                case (248) :
                    return "fog";
                    break;
                case (260) :
                    return "fog";
                    break;
                case (263) :
                    return "cloudy";
                    break;
                case (266) :
                    return "rainy";
                    break;
                case (281) :
                    return "sleet";
                    break;
                case (284) :
                    return "sleet";
                    break;
                case (293) :
                    return "rainy";
                    break;
                case (296) :
                    return "rainy";
                    break;
                case (299) :
                    return "rainy";
                    break;
                case (302) :
                    return "rainy";
                    break;
                case (305) :
                    return "rainy";
                    break;
                case (308) :
                    return "rainy";
                    break;
                case (311) :
                    return "rainy";
                    break;
                case (314) :
                    return "rainy";
                    break;
                case (317) :
                    return "sleet";
                    break;
                case (320) :
                    return "sleet";
                    break;
                case (323) :
                    return "snowly";
                    break;
                case (326) :
                    return "snowly";
                    break;
                case (329) :
                    return "snowly";
                    break;
                case (332) :
                    return "snowly";
                    break;
                case (335) :
                    return "snowly";
                    break;
                case (338) :
                    return "snowly";
                    break;
                case (350) :
                    return "snowly";
                    break;
                case (353) :
                    return "rainy";
                    break;
                case (356) :
                    return "rainy";
                    break;
                case (359) :
                    return "rainy";
                    break;
                case (362) :
                    return "sleet";
                    break;
                case (365) :
                    return "sleet";
                    break;
                case (368) :
                    return "sleet";
                    break;
                case (371) :
                    return "snowly";
                    break;
                case (374) :
                    return "snowly";
                    break;
                case (377) :
                    return "snowly";
                    break;
                case (386) :
                    return "thunder";
                    break;
                case (389) :
                    return "thunder";
                    break;
                case (392) :
                    return "thunder";
                    break;
                case (395) :
                    return "snowly";
                    break;
            }
        }
    }
}