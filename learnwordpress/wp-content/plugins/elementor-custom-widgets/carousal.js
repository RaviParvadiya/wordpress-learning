(function () {
  var list = document.getElementById("repList");
  var pager = document.getElementById("bxPager");
  var visible = 3; // Number of visible cards
  var slideWidth = 380;
  var items = Array.from(list.children);
  var realTotal = items.length;

  // Clone first and last slides for infinite effect
  for (var i = 0; i < visible; i++) {
    var cloneFirst = items[i].cloneNode(true);
    cloneFirst.classList.add("bx-clone");
    list.appendChild(cloneFirst);

    var cloneLast = items[realTotal - 1 - i].cloneNode(true);
    cloneLast.classList.add("bx-clone");
    list.insertBefore(cloneLast, list.firstChild);
  }

  // Update variables after cloning
  items = list.querySelectorAll("li");
  var total = items.length;
  var current = visible; // Start at first real slide

  // Set list width
  list.style.width = slideWidth * total + "px";
  list.style.transition = "transform 0.5s";

  // Set initial position
  function setPosition(noTransition) {
    if (noTransition) list.style.transition = "none";
    list.style.transform = "translate3d(" + -slideWidth * current + "px,0,0)";
    if (noTransition)
      setTimeout(function () {
        list.style.transition = "transform 0.5s";
      }, 20);
  }
  setPosition();

  // Pager update
  function updatePager(idx) {
    var links = pager.querySelectorAll(".bx-pager-link");
    links.forEach(function (link, i) {
      if (i === idx) link.classList.add("active");
      else link.classList.remove("active");
    });
  }

  // Slide to index (with smooth transition)
  function goTo(idx) {
    var target = idx + visible;
    var diff = Math.abs(current - target);
    if (diff === 0) return;

    // Update pager immediately
    updatePager(idx);

    // For adjacent slides, animate step by step
    if (diff === 1) {
      current = target;
      setPosition();
      setTimeout(function () {
        // Looping logic
        if (current >= realTotal + visible) {
          current = visible;
          setPosition(true);
        }
        if (current < visible) {
          current = realTotal + visible - 1;
          setPosition(true);
        }
      }, 500);
    } else {
      // For long jumps, animate in one go with proportional speed
      list.style.transition = "transform " + 0.15 * diff + "s";
      current = target;
      setPosition();
      setTimeout(function () {
        list.style.transition = "transform 0.5s";
        // Looping logic
        if (current >= realTotal + visible) {
          current = visible;
          setPosition(true);
        }
        if (current < visible) {
          current = realTotal + visible - 1;
          setPosition(true);
        }
      }, 150 * diff + 50);
    }
  }

  pager.addEventListener("click", function (e) {
    if (e.target.classList.contains("bx-pager-link")) {
      e.preventDefault();
      var idx = parseInt(e.target.getAttribute("data-slide-index"));
      goTo(idx);
    }
  });

  // Optional: auto-scroll
  setInterval(function(){ goTo(((current - visible + 1) % realTotal + realTotal) % realTotal); }, 5000);

  // Responsive: update slideWidth on resize if needed
  // (You can add this if your card width is not fixed)
})();

/* (function () {
  var list = document.getElementById("repList");
  var pager = document.getElementById("bxPager");
  var items = list.querySelectorAll("li");
  var total = items.length;
  var current = 0;
  function goTo(idx) {
    if (idx < 0) idx = 0;
    if (idx >= total) idx = total - 1;
    list.style.transform = "translate3d(" + -380 * idx + "px,0,0)";
    var links = pager.querySelectorAll(".bx-pager-link");
    links.forEach(function (link, i) {
      if (i === idx) link.classList.add("active");
      else link.classList.remove("active");
    });
    current = idx;
  }
  pager.addEventListener("click", function (e) {
    if (e.target.classList.contains("bx-pager-link")) {
      e.preventDefault();
      var idx = parseInt(e.target.getAttribute("data-slide-index"));
      goTo(idx);
    }
  });
  setInterval(function () {
    goTo((current + 1) % total);
  }, 5000);
})(); */

/* (function () {
  var list = document.getElementById("repList");
  var pager = document.getElementById("bxPager");
  var items = list.querySelectorAll("li");
  var total = items.length;
  var visible = 3; // Number of visible cards
  var current = 0;

  // Clone first and last slides for infinite effect
  for (var i = 0; i < visible; i++) {
    var cloneFirst = items[i].cloneNode(true);
    cloneFirst.classList.add("bx-clone");
    list.appendChild(cloneFirst);

    var cloneLast = items[total - 1 - i].cloneNode(true);
    cloneLast.classList.add("bx-clone");
    list.insertBefore(cloneLast, list.firstChild);
  }

  // Update variables after cloning
  items = list.querySelectorAll("li");
  total = items.length;
  var realTotal = total - 2 * visible;

  // Set initial position
  var slideWidth = 380;
  var startIndex = visible;
  list.style.transform = "translate3d(" + -slideWidth * startIndex + "px,0,0)";
  current = startIndex;

  function updatePager(idx) {
    var links = pager.querySelectorAll(".bx-pager-link");
    links.forEach(function (link, i) {
      if (i === idx) link.classList.add("active");
      else link.classList.remove("active");
    });
  }

  function goTo(idx) {
    var target = idx + visible;
    animateTo(target);
  }

  // Animate slide by slide
  function animateTo(target) {
    if (current === target) return;
    var step = current < target ? 1 : -1;
    var interval = setInterval(function () {
      current += step;
      list.style.transform = "translate3d(" + -slideWidth * current + "px,0,0)";
      // When reached target
      if (current === target) {
        clearInterval(interval);
        // Looping logic
        if (current >= realTotal + visible) {
          current = visible;
          list.style.transition = "none";
          list.style.transform =
            "translate3d(" + -slideWidth * current + "px,0,0)";
          setTimeout(function () {
            list.style.transition = "transform 0.5s";
          }, 20);
        }
        if (current < visible) {
          current = realTotal + visible - 1;
          list.style.transition = "none";
          list.style.transform =
            "translate3d(" + -slideWidth * current + "px,0,0)";
          setTimeout(function () {
            list.style.transition = "transform 0.5s";
          }, 20);
        }
        updatePager((current - visible + realTotal) % realTotal);
      }
    }, 100); // 100ms per slide, adjust for speed
  }

  pager.addEventListener("click", function (e) {
    if (e.target.classList.contains("bx-pager-link")) {
      e.preventDefault();
      var idx = parseInt(e.target.getAttribute("data-slide-index"));
      goTo(idx);
    }
  });

  // Optional: auto-scroll
  // setInterval(function(){ goTo(((current - visible + 1) % realTotal + realTotal) % realTotal); }, 5000);

  // Show 3 cards at a time (CSS change required)
  list.style.display = "flex";
  list.parentElement.style.overflow = "hidden";
  list.parentElement.style.width = slideWidth * visible + "px";
})();
 */
