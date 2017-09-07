/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){        
    $('.chm-slick-multislider').slick({
          dots: true,
          autoplay: true,
          autoplaySpeed: 5000,
          mobileFirst: true,
          arrows: false,
          fade: true,
          infinite: true,
          pauseOnDotsHover: true,
          pauseOnHover: false,
          pauseOnFocus: false,
    }); 
    
    $('.chm-slick-productslider').slick({
          dots: true,
          autoplay: false,
          autoplaySpeed: 5000,
          mobileFirst: true,
          arrows: false,
          fade: true,
          infinite: true,
          pauseOnDotsHover: true,
          pauseOnHover: false,
          pauseOnFocus: false,
    });    
});  


