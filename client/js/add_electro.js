document
  .getElementById("applianceForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch("../server/api/add_appliance.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const applianceList = document.getElementById("applianceList");
          const applianceType = formData.get("applianceType");
          const brand = formData.get("brand");
          const model = formData.get("model");
          const kwh = formData.get("kwh");
          const pricePerKwh = formData.get("pricePerKwh");
          const usageTime = formData.get("usageTime");
          const totalCost = (kwh * pricePerKwh * usageTime).toFixed(2);

          const newAppliance = document.createElement("div");
          newAppliance.innerHTML = `
          <div>
            <h3>${applianceType}</h3>
            <p>Brand: ${brand}</p>
            <p>Model: ${model}</p>
            <p>KWh: ${kwh}</p>
            <p>Price per KWh: ${pricePerKwh}</p>
            <p>Usage Time: ${usageTime} seconds</p>
            <p>Total Cost: $${totalCost}</p>
            <div class="timer">Tiempo restante: ${usageTime} segundos</div>
            <button onclick="startTimer(this)">Iniciar temporizador</button>
            <button class="stop-timer">Detener temporizador</button>
          </div>
        `;
          applianceList.appendChild(newAppliance);

    
          const startTimerBtn = newAppliance.querySelector(".startTimerBtn");
          startTimerBtn.addEventListener("click", () => {
            const timerDiv =
              startTimerBtn.parentElement.querySelector(".timer");
            startTimer(timerDiv, usageTime);
          });
        } else {
          alert("Error adding appliance");
        }
      })
      .catch((error) => console.error("Error:", error));
  });


  function startTimer(button) {
    const applianceName = button.parentElement.querySelector('h3').innerText;
    const usageTime = parseInt(button.parentElement.querySelector('p:nth-child(6)').innerText.split(' ')[2]); 
    const timerElement = button.parentElement.querySelector('.timer');
    const stopButton = button.parentElement.querySelector('.stop-timer');
    const alarmSound = new Audio('./js/alarma.mp3'); 

    let seconds = 0;

    const timerInterval = setInterval(() => {
        seconds++;
        timerElement.innerText = 'Tiempo transcurrido: ' + seconds + ' segundos';
        if (seconds >= usageTime) {
            timerElement.style.color = 'red';
          //  stopButton.style.display = 'none';
            timerElement.innerText = 'Tiempo excedido para ' + applianceName; 
            clearInterval(timerInterval); 
            alarmSound.play(); 
        }
    }, 1000);

    stopButton.addEventListener('click', () => {
        clearInterval(timerInterval);
        timerElement.innerText = 'Temporizador detenido';
        timerElement.style.color = 'blue';
        stopButton.style.display = 'none';
        alarmSound.pause(); 
        alarmSound.currentTime = 0; 
    });
}



