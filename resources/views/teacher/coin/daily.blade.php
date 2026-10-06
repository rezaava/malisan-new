<!doctype html>
<html lang="fa" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>گردونه روزانه</title>
    <style>
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Hairline.ttf") format("truetype");
        font-weight: 100;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Thin.ttf") format("truetype");
        font-weight: 200;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-UltraLight.ttf") format("truetype");
        font-weight: 250;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Light.ttf") format("truetype");
        font-weight: 300;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Regular.ttf") format("truetype");
        font-weight: 400;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Medium.ttf") format("truetype");
        font-weight: 500;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-DemiBold.ttf") format("truetype");
        font-weight: 600;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Bold.ttf") format("truetype");
        font-weight: 700;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-ExtraBold.ttf") format("truetype");
        font-weight: 800;
        font-style: normal;
        font-display: swap;
      }
      @font-face {
        font-family: "Dana";
        src: url("./fonts/DanaFaNum-Black.ttf") format("truetype");
        font-weight: 900;
        font-style: normal;
        font-display: swap;
      }
      * {
        font-family: "Dana", "Vazir", sans-serif;
        box-sizing: border-box;
      }
      html,
      body {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
      }
      body {
        overflow: hidden;
      }
      .app {
        width: 100%;
        height: 100%;
        text-align: center;
        justify-content: flex-start;
        background: linear-gradient(
          135deg,
          #0f0c29 0%,
          #302b63 50%,
          #24243e 100%
        );
        color: #fff;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: fixed;
        top: 0;
        left: 0;
        margin: 0;
        padding: 0;
      }
      .h1 {
        position: relative;
        z-index: 10;
        background: linear-gradient(#fc75e1, #a907c9);
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        font-size: 50px;
        padding: 5px;
        margin: 10px 0 15px;
      }
      .main-container {
        width: 100%;
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        z-index: 5;
        padding: 10px;
      }
      .canvas-btn {
        text-align: center;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: min(450px, 85vw);
        aspect-ratio: 1 / 1;
        container-type: inline-size;
        margin: 0 auto;
      }
      #wheel {
        width: 100%;
        height: 100%;
        display: block;
        border-radius: 50%;
        box-shadow:
          0 0 20px rgba(255, 255, 255, 0.1),
          0 0 40px rgba(218, 34, 255, 0.15);
        position: relative;
        z-index: 5;
      }
      .btn-circle {
        position: absolute;
        bottom: -70px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        width: 200px;
        height: 50px;
        font-size: 1.3rem !important;
        border: none;
        border-radius: 50px;
        color: #fff;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        background: linear-gradient(
          to right,
          #da22ff 0%,
          #cc97fa 51%,
          #da22ff 100%
        );
        transition: all 0.2s ease;
        z-index: 20;
      }
      .btn-circle:hover {
        transform: translateX(-50%) translateY(-3px);
        box-shadow: 0 10px 30px rgba(218, 34, 255, 0.6);
      }
      .btn-circle:disabled {
        cursor: not-allowed;
        opacity: 0.7;
      }
      .pointer {
        position: absolute;
        top: -22px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 30;
        width: 44px;
        height: 52px;
        filter: drop-shadow(0 6px 10px rgba(0, 0, 0, 0.4));
      }
      .pointer-triangle {
        position: absolute;
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 17px solid transparent;
        border-right: 17px solid transparent;
        border-top: 44px solid #ef4444;
        border-radius: 0 0 4px 4px;
      }
      .pointer-circle {
        position: absolute;
        left: 50%;
        top: -8px;
        transform: translateX(-50%);
        width: 18px;
        height: 18px;
        background: radial-gradient(circle at 40% 40%, #fca5a5, #dc2626);
        border-radius: 50%;
        z-index: 2;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
      }
      .led-ring {
        position: absolute;
        width: calc(100% + 22px);
        height: calc(100% + 22px);
        top: -11px;
        left: -11px;
        border-radius: 50%;
        z-index: 2;
        pointer-events: none;
        background: conic-gradient(
          from 0deg,
          #ff00cc,
          #3333ff,
          #00eaff,
          #00ff88,
          #ffff00,
          #ff00cc
        );
        -webkit-mask: radial-gradient(
          farthest-side,
          transparent calc(100% - 5px),
          #000 calc(100% - 4px)
        );
        mask: radial-gradient(
          farthest-side,
          transparent calc(100% - 5px),
          #000 calc(100% - 4px)
        );
        animation: ledRotate 5s linear infinite;
        opacity: 0.8;
        filter: drop-shadow(0 0 8px rgba(218, 34, 255, 0.7));
      }
      .led-ring.spinning {
        animation-duration: 0.7s;
        opacity: 1;
        filter: drop-shadow(0 0 12px rgba(0, 238, 255, 1))
          drop-shadow(0 0 25px rgba(218, 34, 255, 0.8));
      }
      @keyframes ledRotate {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }
      .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.45);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        box-shadow: rgba(0, 0, 0, 0.35) 0 -50px 36px -28px inset;
        animation: modalFade 0.25s ease;
      }
      @keyframes modalFade {
        from {
          opacity: 0;
        }
        to {
          opacity: 1;
        }
      }
      .modal-box {
        width: 500px;
        height: auto;
        min-height: 350px;
        border: none;
        border-radius: 25px;
        background-color: #15202b;
        padding: 30px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 20px 70px rgba(0, 0, 0, 0.5);
        animation: modalShow 0.3s ease;
      }
      @keyframes modalShow {
        from {
          transform: scale(0.8);
          opacity: 0;
        }
        to {
          transform: scale(1);
          opacity: 1;
        }
      }
      .modal-img {
        width: 100px;
        margin-top: 20px;
        transform-origin: center center;
      }
      .modal-img-animate {
        animation: breathe 2s ease-in-out infinite;
      }
      @keyframes breathe {
        0% {
          transform: scale(1);
        }
        50% {
          transform: scale(1.15);
        }
        100% {
          transform: scale(1);
        }
      }
      .h5 {
        margin-top: 10px;
        margin-bottom: 10px;
        color: #9a9999;
        font-size: 15px;
      }
      .modal-text {
        color: #fff;
        margin-bottom: 25px;
        font-weight: bold;
        direction: rtl;
        font-size: 1.5rem;
        text-align: center;
        line-height: 1.6;
        max-width: 430px;
      }
      .btns-modal {
        display: flex;
        flex-direction: row;
        justify-content: center;
        gap: 15px;
        width: 100%;
        padding: 0 20px;
        margin-top: 10px;
      }
      .modal-btn {
        padding: 12px 0;
        border: none;
        border-radius: 12px;
        background: #019840;
        color: #fff;
        border-bottom: 7px solid #6b9f81c8;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        font-size: 20px;
        width: 100%;
        transition: all 0.2s ease;
        font-weight: bold;
      }
      .modal-btn:hover {
        transform: translateY(-3px);
      }
      .modal-btn img {
        width: 50px;
      }
      @keyframes confFall {
        to {
          transform: translateY(110vh) rotate(720deg);
          opacity: 0;
        }
      }
      .confetti-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 9999;
        overflow: hidden;
      }
      .scene {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        background: radial-gradient(ellipse at 50% 0%, #1a103a 0%, #070b1a 70%);
      }
      .stars,
      .stars2,
      .stars3 {
        position: absolute;
        top: 0;
        left: 0;
        width: 200%;
        height: 200%;
        background-repeat: repeat;
        animation: starScroll linear infinite;
      }
      .stars {
        background-image:
          radial-gradient(1px 1px at 10% 20%, #fff, transparent),
          radial-gradient(1px 1px at 30% 50%, #fff, transparent),
          radial-gradient(1.5px 1.5px at 60% 15%, #fef9c3, transparent),
          radial-gradient(1px 1px at 80% 80%, #fff, transparent),
          radial-gradient(1.5px 1.5px at 45% 70%, #e0e7ff, transparent),
          radial-gradient(1px 1px at 90% 30%, #fff, transparent),
          radial-gradient(1px 1px at 15% 90%, #fff, transparent),
          radial-gradient(1.2px 1.2px at 70% 45%, #c7d2fe, transparent);
        background-size: 400px 400px;
        opacity: 0.8;
        animation-duration: 80s;
      }
      .stars2 {
        background-image:
          radial-gradient(
            1px 1px at 20% 30%,
            rgba(255, 255, 255, 0.6),
            transparent
          ),
          radial-gradient(
            1.5px 1.5px at 50% 60%,
            rgba(255, 255, 255, 0.5),
            transparent
          ),
          radial-gradient(
            1px 1px at 75% 10%,
            rgba(255, 255, 255, 0.6),
            transparent
          ),
          radial-gradient(
            1px 1px at 40% 85%,
            rgba(255, 255, 255, 0.4),
            transparent
          );
        background-size: 300px 300px;
        opacity: 0.5;
        animation-duration: 120s;
      }
      .stars3 {
        background-image:
          radial-gradient(
            2px 2px at 25% 40%,
            rgba(167, 139, 250, 0.7),
            transparent
          ),
          radial-gradient(
            2px 2px at 65% 20%,
            rgba(251, 191, 36, 0.5),
            transparent
          ),
          radial-gradient(
            2px 2px at 85% 75%,
            rgba(244, 114, 182, 0.6),
            transparent
          );
        background-size: 500px 500px;
        opacity: 0.6;
        animation-duration: 150s;
      }
      @keyframes starScroll {
        from {
          transform: translate(0, 0);
        }
        to {
          transform: translate(-50%, -50%);
        }
      }
      .nebula {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.35;
        animation: drift 25s ease-in-out infinite alternate;
      }
      .nebula-1 {
        width: 60vw;
        height: 60vw;
        top: -20%;
        left: -15%;
        background: #7c3aed;
      }
      .nebula-2 {
        width: 50vw;
        height: 50vw;
        bottom: -15%;
        right: -10%;
        background: #be185d;
        animation-delay: -8s;
      }
      .nebula-3 {
        width: 40vw;
        height: 40vw;
        top: 30%;
        right: 20%;
        background: #0891b2;
        opacity: 0.2;
        animation-delay: -15s;
      }
      @keyframes drift {
        0% {
          transform: translate(0, 0) scale(1);
        }
        100% {
          transform: translate(40px, 60px) scale(1.15);
        }
      }
      @media (min-width: 1024px) {
        .main-container {
          display: flex;
          flex-direction: row;
          justify-content: center;
          align-items: center;
          gap: 30px;
        }
      }
      @media (max-width: 1023px) {
        body {
          overflow-y: auto;
        }
        .app {
          position: relative;
          min-height: 100vh;
          height: auto;
          overflow-x: hidden;
          overflow-y: auto;
        }
        .scene {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          z-index: 0;
          pointer-events: none;
        }
        .h1 {
          z-index: 10;
          font-size: 32px !important;
          margin-bottom: 5px;
        }
        .main-container {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: flex-start;
          gap: 30px;
          padding: 20px 10px;
          height: auto;
          overflow: visible;
        }
        .canvas-btn {
          margin: 20px auto;
          width: min(450px, 85vw);
          order: 1;
        }
        .btn-circle {
          width: 150px !important;
          height: 60px !important;
          font-size: 1rem !important;
          bottom: -70px;
        }
        .modal-box {
          position: relative;
          width: 93%;
          min-height: auto;
          max-height: 90vh;
          overflow-y: auto;
        }
        .btns-modal {
          display: flex;
          flex-direction: column;
          width: 100%;
        }
        .modal-btn {
          width: 100%;
        }
      }
      @media (max-width: 500px) {
        .h1 {
          font-size: 26px !important;
        }
        .canvas-btn {
          width: min(400px, 82vw);
        }
        .modal-text {
          font-size: 1.15rem;
        }
        .modal-img {
          width: 85px;
        }
      }
    </style>
  </head>
  <body>
    <div class="app">
      <div class="scene">
        <div class="stars"></div>
        <div class="stars2"></div>
        <div class="stars3"></div>
        <div class="nebula nebula-1"></div>
        <div class="nebula nebula-2"></div>
        <div class="nebula nebula-3"></div>
      </div>
      <h1 class="h1">گردونه شانس</h1>
      <div class="main-container">
        <div class="canvas-btn">
          <div class="pointer">
            <div class="pointer-triangle"></div>
            <div class="pointer-circle"></div>
          </div>
          <div id="ledRing" class="led-ring"></div>
          <canvas id="wheel" width="450" height="450"></canvas>
          <button id="spinButton" class="btn-circle" type="button">
            بچرخون 🤸‍♂️
          </button>
        </div>
      </div>
      <div id="winnerModal" class="modal-overlay" style="display: none">
        <div class="modal-box" onclick="event.stopPropagation()">
          <img
            id="winnerIcon"
            class="modal-img modal-img-animate"
            src="./icon/J1.png"
            alt="winner"
          />
          <h5 class="h5">انجام بده وگرنه پرداخت کن</h5>
          <p id="winnerQuestion" class="modal-text"></p>
          <div class="btns-modal">
            <button id="confirmButton" class="modal-btn" type="button">
              <img src="./icon/111.png" alt="تایید" />
              تأیید
            </button>
          </div>
        </div>
      </div>
      <div id="confettiContainer" class="confetti-container"></div>
    </div>
    <script>
      const segments = [
        {
          id: 1,
          text: "پوچ",
          label: "پوچ",
          color: "#3b82f6",
          type: 0,
          status: 0,
        },
        {
          id: 2,
          text: "1 ویرا کوین",
          label: "1 ویرا کوین",
          color: "#ef4444",
          type: 1,
          status: 1,
        },
        {
          id: 3,
          text: "5 ویرا کوین",
          label: "5 ویرا کوین",
          color: "#8b5cf6",
          type: 1,
          status: 1,
        },
        {
          id: 4,
          text: "10 ویرا کوین",
          label: "10 ویرا کوین",
          color: "#10b981",
          type: 1,
          status: 1,
        },
        {
          id: 5,
          text: "25 ویرا کوین",
          label: "25 ویرا کوین",
          color: "#f97316",
          type: 1,
          status: 1,
        },
        {
          id: 6,
          text: "50 ویرا کوین",
          label: "50 ویرا کوین",
          color: "#ec4899",
          type: 1,
          status: 1,
        },
        {
          id: 7,
          text: "پوچ",
          label: "پوچ",
          color: "#14b8a6",
          type: 0,
          status: 0,
        },
        {
          id: 8,
          text: "5 ویرا کوین",
          label: "5 ویرا کوین",
          color: "#eab308",
          type: 1,
          status: 1,
        },
        {
          id: 9,
          text: "10 ویرا کوین",
          label: "10 ویرا کوین",
          color: "#6366f1",
          type: 1,
          status: 1,
        },
        {
          id: 10,
          text: "25 ویرا کوین",
          label: "25 ویرا کوین",
          color: "#f43f5e",
          type: 1,
          status: 1,
        },
      ];
      const canvas = document.getElementById("wheel");
      const ctx = canvas.getContext("2d");
      const spinButton = document.getElementById("spinButton");
      const ledRing = document.getElementById("ledRing");
      const winnerModal = document.getElementById("winnerModal");
      const winnerQuestion = document.getElementById("winnerQuestion");
      const winnerIcon = document.getElementById("winnerIcon");
      const confirmButton = document.getElementById("confirmButton");
      const confettiContainer = document.getElementById("confettiContainer");
      let rotation = 0;
      let isSpinning = false;
      function drawWheel() {
        const size = 450;
        ctx.clearRect(0, 0, size, size);
        const centerX = size / 2;
        const centerY = size / 2;
        const radius = size / 2 - 10;
        const anglePerSegment = (2 * Math.PI) / segments.length;
        segments.forEach((segment, index) => {
          const startAngle = index * anglePerSegment + rotation;
          const endAngle = startAngle + anglePerSegment;
          ctx.beginPath();
          ctx.moveTo(centerX, centerY);
          ctx.arc(centerX, centerY, radius, startAngle, endAngle);
          ctx.closePath();
          ctx.fillStyle = segment.color;
          ctx.fill();
          ctx.strokeStyle = "rgba(255,255,255,0.35)";
          ctx.lineWidth = 1;
          ctx.stroke();
          ctx.save();
          ctx.translate(centerX, centerY);
          ctx.rotate(startAngle + anglePerSegment / 2);
          ctx.textAlign = "center";
          ctx.textBaseline = "middle";
          ctx.fillStyle = "#fff";
          let shortText = segment.text;
          if (shortText.length > 10) {
            shortText = "..." + shortText.substring(0, 10);
          }
          ctx.font = "bold 25px Dana, sans-serif";
          ctx.fillText(shortText, radius * 0.55, 0);
          ctx.restore();
        });
        ctx.save();
        ctx.beginPath();
        ctx.arc(centerX, centerY, 27, 0, 2 * Math.PI);
        const gradient = ctx.createRadialGradient(
          centerX - 9,
          centerY - 9,
          2,
          centerX,
          centerY,
          30,
        );
        gradient.addColorStop(0, "#ffffff");
        gradient.addColorStop(0.5, "#cbd5e1");
        gradient.addColorStop(1, "#64748b");
        ctx.fillStyle = gradient;
        ctx.shadowColor = "rgba(255,255,255,0.9)";
        ctx.shadowBlur = 20;
        ctx.fill();
        ctx.shadowBlur = 0;
        ctx.fillStyle = "#1e293b";
        ctx.font = "20px sans-serif";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText("🎯", centerX, centerY + 2);
        ctx.restore();
      }
      function spinWheel() {
        if (isSpinning) {
          return;
        }
        if (segments.length === 0) {
          return;
        }
        isSpinning = true;
        spinButton.disabled = true;
        spinButton.innerText = "در حال چرخش ⏳";
        ledRing.classList.add("spinning");
        const extraSpin = 18;
        const startRotation = rotation;
        const targetRotation =
          startRotation + extraSpin * 2 * Math.PI + Math.random() * 2 * Math.PI;
        const duration = 2000;
        const startTime = performance.now();
        function animate(currentTime) {
          const elapsed = currentTime - startTime;
          const progress = Math.min(elapsed / duration, 1);
          const eased = 1 - Math.pow(1 - progress, 3);
          rotation = startRotation + (targetRotation - startRotation) * eased;
          drawWheel();
          if (progress < 1) {
            requestAnimationFrame(animate);
          } else {
            rotation = targetRotation;
            drawWheel();
            isSpinning = false;
            spinButton.disabled = false;
            spinButton.innerText = "بچرخون 🤸‍♂️";
            ledRing.classList.remove("spinning");
            detectWinner();
          }
        }
        requestAnimationFrame(animate);
      }
      function saveDailyCoin(receiveCoin) {
        return fetch("{{ route('teacher.coin.daily.store') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute("content"),
            Accept: "application/json",
          },
          body: JSON.stringify({
            receive_coin: receiveCoin,
          }),
        }).then(async (response) => {
          const data = await response.json();
          if (!response.ok) {
            throw new Error(data.message || "خطا در ثبت جایزه");
          }
          return data;
        });
      }
      function detectWinner() {
        const pointerAngle = -Math.PI / 2;
        const twoPi = 2 * Math.PI;
        let rawAngle = (pointerAngle - rotation) % twoPi;
        if (rawAngle < 0) {
          rawAngle += twoPi;
        }
        const anglePerSegment = twoPi / segments.length;
        const winnerIndex = Math.floor(rawAngle / anglePerSegment);
        const safeIndex = winnerIndex % segments.length;
        const winnerSegment = segments[safeIndex];
        showWinnerResult(winnerSegment);
        let receiveCoin = 0;
        if (winnerSegment.type === 1) {
          receiveCoin =
            parseInt(winnerSegment.text.replace(/\D/g, ""), 10) || 0;
        }
        saveDailyCoin(receiveCoin).catch((error) => {
          console.error("خطا در ثبت جایزه:", error);
        });
      }
      function showWinnerResult(winnerSegment) {
        if (!winnerSegment) {
          return;
        }
        winnerQuestion.innerText = winnerSegment.text;
        winnerIcon.src = getWinnerIcon(
          winnerSegment.type,
          winnerSegment.status,
        );
        winnerModal.style.display = "flex";
        fireConfetti();
      }
      function getWinnerIcon(type, status) {
        const key = `${type}_${status}`;
        switch (key) {
          case "1_1":
            return "./icon/J1.png";
          case "1_2":
            return "./icon/J2.png";
          case "2_1":
            return "./icon/h2.png";
          case "2_2":
            return "./icon/h1.png";
          default:
            return "./icon/333.png";
        }
      }
      function closeWinnerModal() {
        winnerModal.style.display = "none";
        confettiContainer.innerHTML = "";
      }
      confirmButton.addEventListener("click", function () {
        closeWinnerModal();
      });
      winnerModal.addEventListener("click", function (event) {
        if (event.target === winnerModal) {
          closeWinnerModal();
        }
      });
      function fireConfetti() {
        const cols = [
          "#fbbf24",
          "#ef4444",
          "#8b5cf6",
          "#3b82f6",
          "#10b981",
          "#ec4899",
          "#f97316",
          "#06b6d4",
        ];
        for (let i = 0; i < 80; i++) {
          setTimeout(function () {
            const el = document.createElement("div");
            el.style.position = "fixed";
            el.style.top = "-10px";
            el.style.left = Math.random() * 100 + "vw";
            el.style.zIndex = "9999";
            el.style.pointerEvents = "none";
            el.style.width = Math.random() * 8 + 5 + "px";
            el.style.height = Math.random() * 8 + 5 + "px";
            el.style.background = cols[Math.floor(Math.random() * cols.length)];
            el.style.borderRadius = Math.random() > 0.5 ? "50%" : "2px";
            el.style.animation = `confFall ${Math.random() * 2 + 1.5}s linear forwards`;
            el.style.opacity = Math.random() * 0.5 + 0.5;
            confettiContainer.appendChild(el);
            setTimeout(function () {
              if (el.parentNode) {
                el.remove();
              }
            }, 4000);
          }, i * 20);
        }
      }
      spinButton.addEventListener("click", spinWheel);
      drawWheel();
    </script>
  </body>
</html>
