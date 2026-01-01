/**
 * Snow hero – canvasbaserad snöanimation för hero-sektion.
 *
 * Funktionalitet:
 * - Renderar animerade snöflingor med HTML5 canvas
 * - Anpassar canvasstorlek efter hero-elementet
 * - Animerar flingor med fallrörelse och lätt sidodrift
 * - Påverkar snöflingor dynamiskt baserat på musposition
 * - Återställer flingor när de lämnar synligt område
 * - Skalar om och återskapar flingor vid resize
 *
 * Animationen körs via `requestAnimationFrame` (med fallback)
 * och initieras automatiskt när DOM:en är redo.
 * Koden är inkapslad i en IIFE och körs i strict mode.
 */

(function () {
  "use strict";

  function initSnowHero() {
    const hero = document.querySelector(".hero--contact");
    const canvas = document.getElementById("canvas");

    if (!hero || !canvas) return;

    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    let flakes = [];
    const flakeCount = 250;
    let mX = -100;
    let mY = -100;

    const raf =
      window.requestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.webkitRequestAnimationFrame ||
      window.msRequestAnimationFrame ||
      function (cb) { return window.setTimeout(cb, 1000 / 60); };

    function resizeCanvas() {
      const rect = hero.getBoundingClientRect();
      canvas.width = Math.floor(rect.width);
      canvas.height = Math.floor(rect.height);
    }

    function reset(flake) {
      flake.x = Math.random() * canvas.width;
      flake.y = 0;

      flake.size = Math.random() * 3 + 1.5;
      flake.speed = Math.random() * 1 + 0.6;
      flake.velY = flake.speed;
      flake.velX = 0;

      flake.opacity = Math.random() * 0.5 + 0.25;
      flake.step = 0;
      flake.stepSize = Math.random() / 30;
    }

    function createFlake() {
      const flake = {
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        size: Math.random() * 3 + 1.5,
        speed: Math.random() * 1 + 0.6,
        velY: 0,
        velX: 0,
        opacity: Math.random() * 0.5 + 0.25,
        step: 0,
        stepSize: Math.random() / 30,
      };
      flake.velY = flake.speed;
      return flake;
    }

    function initFlakes() {
      flakes = [];
      for (let i = 0; i < flakeCount; i++) {
        flakes.push(createFlake());
      }
    }

    function draw() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      for (let i = 0; i < flakes.length; i++) {
        const flake = flakes[i];

        const minDist = 150;
        const dx = flake.x - mX;
        const dy = flake.y - mY;
        const dist = Math.sqrt(dx * dx + dy * dy);

        if (dist < minDist) {
          const force = minDist / (dist * dist);
          const xcomp = dx / dist;
          const ycomp = dy / dist;
          const deltaV = force / 2;

          flake.velX += deltaV * xcomp;
          flake.velY += deltaV * ycomp;
        } else {
          flake.velX *= 0.98;
          if (flake.velY <= flake.speed) flake.velY = flake.speed;
          flake.velX += Math.cos((flake.step += 0.05)) * flake.stepSize;
        }

        flake.y += flake.velY;
        flake.x += flake.velX;

        if (flake.y >= canvas.height || flake.y <= 0) reset(flake);
        if (flake.x >= canvas.width || flake.x <= 0) reset(flake);

        ctx.beginPath();
        ctx.fillStyle = `rgba(255,255,255,${flake.opacity})`;
        ctx.arc(flake.x, flake.y, flake.size, 0, Math.PI * 2);
        ctx.fill();
      }

      raf(draw);
    }

    window.addEventListener("mousemove", function (e) {
      const rect = hero.getBoundingClientRect();
      mX = e.clientX - rect.left;
      mY = e.clientY - rect.top;
    });

    window.addEventListener("mouseleave", function () {
      mX = -100;
      mY = -100;
    });

    window.addEventListener("resize", function () {
      resizeCanvas();
      initFlakes();
    });

    resizeCanvas();
    initFlakes();
    draw();
  }

  document.addEventListener("DOMContentLoaded", initSnowHero);
})();
