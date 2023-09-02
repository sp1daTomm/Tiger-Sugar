var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button .swiper-button-next",
      prevEl: ".swiper-button .swiper-button-prev",
    },
    breakpoints: {
      481: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      1200: {
        slidesPerView: 3,
        spaceBetween: 30,
      }
    }
  });

  var swiper2 = new Swiper(".mySwiper2", {
    slidesPerView: 3,
    spaceBetween: 30,
    navigation: {
      nextEl: ".swiper-button2 .swiper-button-next",
      prevEl: ".swiper-button2 .swiper-button-prev",
    },
  });

  var swiper3 = new Swiper(".mySwiper3", {
    slidesPerView: 3,
    spaceBetween: 48,
    loop: true,
    navigation: {
      nextEl: ".swiper-button3 .swiper-button-next",
      prevEl: ".swiper-button3 .swiper-button-prev",
    },
  });


// let swiper_margin_unset = document.querySelectorAll("#news .swiper-slide");
// swiper_margin_unset.forEach(element =>{ 
//   element.style.margin="unset";
//   element.style.width="unset";

// });
