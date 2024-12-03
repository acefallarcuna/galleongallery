gsap.fromTo(
  ".loading-page",
  { opacity: 1 },
  {
    opacity: 0,
    display: "none",
    duration: 1.5,
    delay: 4,
  }
);

// Wait for 5 seconds (5000 milliseconds) and then redirect
setTimeout(function() {
  window.location.href = "./pages/account/sign-up.html";
}, 5000);
