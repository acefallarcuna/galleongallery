gsap.fromTo(
  ".loading-page",
  { opacity: 1 },
  {
    opacity: 0,
    display: "none",
    duration: 1.5,
    delay: 2.5,
  }
);

// gsap.fromTo(
//   ".logo-name",
//   {
//     y: 50,
//     opacity: 0,
//   },
//   {
//     y: 0,
//     opacity: 1,
//     duration: 2,
//     delay: 0.5,
//   }
// );

setTimeout(() => {
  const htmlToInject = `
    <div class="container">
        <div class="banner">
            <div class="slider" style="--quantity: 10">
                <div class="item" style="--position: 1"><img src="images/dragon_1.jpg" alt=""></div>
                <div class="item" style="--position: 2"><img src="images/dragon_2.jpg" alt=""></div>
                <div class="item" style="--position: 3"><img src="images/dragon_3.jpg" alt=""></div>
                <div class="item" style="--position: 4"><img src="images/dragon_4.jpg" alt=""></div>
                <div class="item" style="--position: 5"><img src="images/dragon_5.jpg" alt=""></div>
                <div class="item" style="--position: 6"><img src="images/dragon_6.jpg" alt=""></div>
                <div class="item" style="--position: 7"><img src="images/dragon_7.jpg" alt=""></div>
                <div class="item" style="--position: 8"><img src="images/dragon_8.jpg" alt=""></div>
                <div class="item" style="--position: 9"><img src="images/dragon_9.jpg" alt=""></div>
                <div class="item" style="--position: 10"><img src="images/dragon_10.jpg" alt=""></div>
            </div>
        </div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', htmlToInject);

  const container = document.querySelector('.container');
  container.style.opacity = 0;
  container.style.transition = 'opacity 0.5s ease-in-out';   


  let opacity = 0;
  const intervalId = setInterval(() => {
    opacity += 0.05;
    container.style.opacity = opacity;

    if (opacity >= 1) {
      clearInterval(intervalId);
    }
  }, 20);
}, 3800);