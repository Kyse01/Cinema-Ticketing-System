/* ==============================
   SCHEDULE DATA (from PHP / DB)
================================= */
// `schedulesByBranch` is injected by the Blade template as a JSON object:
// { "SM Megamall": [ { date, time, cinema_type, cinema_hall, schedule_id }, ... ], ... }

const dateContainer  = document.getElementById("dates");
const timeContainer  = document.getElementById("times");
const noDatesMsg     = document.getElementById("noDatesMsg");
const noTimesMsg     = document.getElementById("noTimesMsg");
const branchDropdown = document.getElementById("cinemaBranchDropdown");

/* ── Populate dates for a given branch ── */
function populateDates(branch) {
  dateContainer.innerHTML = "";
  timeContainer.innerHTML = "";

  const entries = (schedulesByBranch && schedulesByBranch[branch]) || [];
  if (entries.length === 0) {
    if (noDatesMsg) noDatesMsg.classList.remove("hidden");
    return;
  }
  if (noDatesMsg) noDatesMsg.classList.add("hidden");

  // Unique dates in insertion order
  const uniqueDates = [...new Set(entries.map(e => e.date))];
  uniqueDates.forEach((date, i) => {
    const el = document.createElement("div");
    el.className = "option" + (i === 0 ? " active" : "");
    el.textContent = date;
    el.dataset.date = date;
    dateContainer.appendChild(el);
  });

  // Populate times for the first date by default
  populateTimes(branch, uniqueDates[0]);
  handleOptionGroup("dates", () => {
    const activeDate = document.querySelector("#dates .option.active")?.dataset.date;
    if (activeDate) populateTimes(branch, activeDate);
  });
}

const cinemaTypeContainer = document.getElementById("cinemaType");

/* ── Populate times for a given branch + date ── */
function populateTimes(branch, date) {
  timeContainer.innerHTML = "";
  const entries = (schedulesByBranch && schedulesByBranch[branch]) || [];
  const filtered = entries.filter(e => e.date === date);

  if (filtered.length === 0) {
    if (noTimesMsg) noTimesMsg.classList.remove("hidden");
    return;
  }
  if (noTimesMsg) noTimesMsg.classList.add("hidden");

  // Populate times
  filtered.forEach((entry, i) => {
    const el = document.createElement("div");
    el.className = "option" + (i === 0 ? " active" : "");
    el.textContent = entry.time;
    el.dataset.cinemaType = entry.cinema_type;
    el.dataset.cinemaHall = entry.cinema_hall;
    el.dataset.cinemaPrice = entry.cinema_price;
    el.dataset.cinemaDescription = entry.cinema_description;
    el.dataset.scheduleId = entry.schedule_id;
    timeContainer.appendChild(el);
  });

  // Populate cinema types dynamically from filtered entries
  populateCinemaTypes(filtered);

  handleOptionGroup("times", () => {
    const activeTime = document.querySelector("#times .option.active");
    if (activeTime) {
      const type = activeTime.dataset.cinemaType;
      // Auto-select the corresponding cinema type
      document.querySelectorAll(".cinema").forEach(c => {
        if (c.dataset.type === type) {
          c.classList.add("active");
          pricePerSeat = parseFloat(c.dataset.price);
          selectedCinemaType = c.dataset.type;
        } else {
          c.classList.remove("active");
        }
      });
      loadSeats();
    }
  });

  // Initial load for the first selected time
  loadSeats();
}

function populateCinemaTypes(filtered) {
  cinemaTypeContainer.innerHTML = "";
  
  // Unique types from the current filtered schedules
  // Using a Map to keep price and description along with the type
  const typeMap = new Map();
  filtered.forEach(entry => {
    if (!typeMap.has(entry.cinema_type)) {
      typeMap.set(entry.cinema_type, {
        price: entry.cinema_price,
        description: entry.cinema_description
      });
    }
  });

  // Render each unique type
  let index = 0;
  typeMap.forEach((data, type) => {
    const el = document.createElement("div");
    el.className = "cinema" + (index === 0 ? " active" : "");
    el.dataset.price = data.price;
    el.dataset.type = type;

    el.innerHTML = `
      <h3>${type}</h3>
      <p>${data.description || ""}</p>
      <span>₱${parseFloat(data.price).toLocaleString()}</span>
    `;
    
    cinemaTypeContainer.appendChild(el);

    if (index === 0) {
      pricePerSeat = parseFloat(data.price);
      selectedCinemaType = type;
    }
    index++;
  });

  handleCinemaTypeGroup();
}

function handleCinemaTypeGroup() {
  document.querySelectorAll(".cinema").forEach(cin => {
    cin.addEventListener("click", () => {
      document.querySelectorAll(".cinema").forEach(c => c.classList.remove("active"));
      cin.classList.add("active");

      pricePerSeat = parseFloat(cin.dataset.price);
      selectedCinemaType = cin.dataset.type;
      updateTotal();
    });
  });
}

/* ── Generic option group click handler ── */
function handleOptionGroup(id, callback) {
  document.querySelectorAll(`#${id} .option`).forEach(opt => {
    opt.addEventListener("click", () => {
      document.querySelectorAll(`#${id} .option`).forEach(o => o.classList.remove("active"));
      opt.classList.add("active");
      if (callback) callback();
      updateTotal();
    });
  });
}

/* ── Branch change triggers date/time re-population ── */
branchDropdown?.addEventListener("change", function () {
  populateDates(this.value);
  updateTotal();
});

/* ── Initial load: if branch already selected, populate ── */
if (branchDropdown?.value) {
  populateDates(branchDropdown.value);
}


/* ==============================
   CINEMA TYPE SELECTION
================================= */
let pricePerSeat = 250;
let selectedCinemaType = "Standard";

/* ==============================
   SEAT GENERATION
================================= */
const seatWrapper = document.getElementById("seatWrapper");
const rows = ["A", "B", "C", "D", "E", "F", "G", "H"];
const cols = 10;
let selected = [];

function seatSVG(id, booked = false) {
  return `
    <svg class="seat ${booked ? "booked" : ""}" data-id="${id}"
      xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
      <rect x="8" y="20" width="48" height="30" rx="8" ry="8"/>
      <rect x="18" y="50" width="28" height="8" rx="2" ry="2"/>
    </svg>`;
}

/* ==============================
   LOAD BOOKED SEATS FROM BACKEND
================================= */
async function loadSeats() {
  const movieId = document.querySelector("#movie_id").value;
  const activeTime = document.querySelector("#times .option.active");
  const scheduleId = activeTime ? activeTime.dataset.scheduleId : null;

  try {
    const url = scheduleId ? `/booked-seats/${movieId}?schedule_id=${scheduleId}` : `/booked-seats/${movieId}`;
    const response = await fetch(url);
    const bookedSeats = await response.json();

    seatWrapper.innerHTML = `
      <div></div>
      ${Array.from({ length: cols }, (_, i) => `<div class="seat-num">${i + 1}</div>`).join("")}
    `;

    rows.forEach(row => {
      seatWrapper.innerHTML += `
        <div class="row-letter">${row}</div>
        ${Array.from({ length: cols }, (_, i) => {
        const seatId = `${row}${i + 1}`;
        const isBooked = bookedSeats.includes(seatId);
        return seatSVG(seatId, isBooked);
      }).join("")}
      `;
    });
  } catch (error) {
    console.error("Error loading seats:", error);
  }
}
loadSeats();

/* ==============================
   SEAT SELECTION LOGIC
================================= */
seatWrapper.addEventListener("click", e => {
  const seat = e.target.closest(".seat");
  if (!seat || seat.classList.contains("booked")) return;
  seat.classList.toggle("selected");

  const id = seat.dataset.id;
  selected = seat.classList.contains("selected")
    ? [...selected, id]
    : selected.filter(s => s !== id);

  updateTotal();
});

/* ==============================
   UPDATE PAYMENT SUMMARY
================================= */
function updateTotal() {
  const total = selected.length * pricePerSeat;
  const paymentSummary = document.getElementById("paymentSummary");
  paymentSummary.style.display = selected.length ? "block" : "none";

  const movieTitle = document.querySelector("#movieTitleHeader")?.textContent || "-";
  const branch = (document.getElementById("cinemaBranchDropdown").value || "")
    .replace("_", " ").toUpperCase() || "-";
  const date = document.querySelector("#dates .option.active")?.textContent || "-";
  const time = document.querySelector("#times .option.active")?.textContent || "-";
  const type = document.querySelector(".cinema.active h3")?.textContent || "-";

  document.getElementById("summaryMovieTitle").textContent = movieTitle;
  document.getElementById("summaryBranch").textContent = branch;
  document.getElementById("summaryDate").textContent = date;
  document.getElementById("summaryTime").textContent = time;
  document.getElementById("summaryType").textContent = type;
  document.getElementById("ticketCount").textContent = `${selected.length}x ₱${pricePerSeat}`;
  document.getElementById("finalTotal").textContent = total.toLocaleString();
  document.getElementById("seatList").textContent = selected.join(", ") || "None";

  document.getElementById("proceedBtn").disabled = selected.length === 0;
}

/* ==============================
   PAYMENT MODAL HANDLING
================================= */
const paymentModal = document.getElementById("paymentModal");
const paymentForm = document.getElementById("paymentForm");
const paymentFields = document.getElementById("paymentFields");
const paymentContent = document.getElementById("paymentContent");
const paymentSuccess = document.getElementById("paymentSuccess");
const proceedBtn = document.getElementById("proceedBtn");

function closePaymentModal() {
  paymentModal.classList.add("hidden");
  paymentForm.reset();
  paymentFields.innerHTML = "";
  paymentSuccess.classList.add("hidden");
  paymentContent.classList.remove("hidden");
}

proceedBtn.addEventListener("click", () => {
  paymentForm.reset();
  paymentFields.innerHTML = "";
  paymentSuccess.classList.add("hidden");
  paymentContent.classList.remove("hidden");
  document.getElementById("paymentTotal").textContent = document.getElementById("finalTotal").textContent;
  paymentModal.classList.remove("hidden");
});

/* ==============================
   PAYMENT METHOD DYNAMIC FIELDS
================================= */
document.getElementById("paymentMethod").addEventListener("change", function () {
  paymentFields.innerHTML = "";

  switch (this.value) {
    case "1":
      paymentFields.innerHTML = `<label>GCash Number:</label><input type="text" name="gcash_number" placeholder="09XXXXXXXXX" required>`;
      break;
    case "2":
      paymentFields.innerHTML = `<label>PayMaya Account Number:</label><input type="text" name="paymaya_number" placeholder="09XXXXXXXXX" required>`;
      break;
    case "3":
    case "4":
      paymentFields.innerHTML = `
        <label>Card Number:</label>
        <input type="text" name="card_number" placeholder="XXXX XXXX XXXX XXXX" required>
        <label>Expiry Date:</label>
        <input type="month" name="expiry" required>
        <label>CVV:</label>
        <input type="text" name="cvv" maxlength="3" required>`;
      break;
  }
});

/* ==============================
   BOOKING SUBMISSION (to DB)
================================= */
let pendingBookingId = null;
proceedBtn.addEventListener("click", async () => {
  const activeTime = document.querySelector("#times .option.active");
  const scheduleId = activeTime ? activeTime.dataset.scheduleId : null;

  const bookingData = {
    movie_id: document.querySelector("#movie_id").value,
    movie_schedule_id: scheduleId,
    movie_title: document.querySelector("#movie_title").value,
    cinema_type: selectedCinemaType,
    seats: selected.join(","),
  };

  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    const response = await fetch("/booking/store", {
      method: "POST",
      headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": token },
      body: JSON.stringify(bookingData),
    });

    const result = await response.json();

    if (response.ok) {
      alert("Booking saved!");
      pendingBookingId = result.booking_id;
      document.querySelector("#booking_id").value = result.booking_id;
      loadSeats();
    } else alert(result.message || "Booking failed!");
  } catch (error) {
    console.error("Error:", error);
    alert("Something went wrong while booking.");
  }
});

/* ==============================
   PAYMENT CONFIRMATION (to DB)
================================= */
const confirmPayment = document.getElementById("confirmPayment");

confirmPayment.addEventListener("click", async (e) => {
  e.preventDefault();

  const totalValue = parseFloat(document.querySelector("#finalTotal").textContent.replace(/[^\d.]/g, ""));
  const paymentMethod = document.querySelector("#paymentMethod");
  const proofImageInput = document.querySelector("#proofImage");
  const bookingId = document.querySelector("#booking_id").value;

  if (!paymentMethod.value) {
    alert("Please select a payment method.");
    return;
  }

  if (!proofImageInput.files.length) {
    alert("Please upload payment proof.");
    return;
  }

  const formData = new FormData();
  formData.append("booking_id", bookingId);
  formData.append("payment_method_id", paymentMethod.value);
  formData.append("payment_method_name", paymentMethod.options[paymentMethod.selectedIndex].text);
  formData.append("total", totalValue);
  formData.append("proof_image", proofImageInput.files[0]);

  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    const response = await fetch("/payment/store", {
      method: "POST",
      headers: { "X-CSRF-TOKEN": token },
      body: formData,
    });

    const result = await response.json();

    if (response.ok && result.success) {
      alert("Payment submitted successfully! Waiting for admin approval.");
      paymentContent.classList.add("hidden");
      paymentSuccess.classList.remove("hidden");
      pendingBookingId = null;

    } else {
      alert(result.message || "Payment failed!");
    }
  } catch (error) {
    console.error("Error during payment:", error);
    alert("Something went wrong while processing payment.");
  }
});

async function deleteUnpaidBooking(bookingId) {
  try {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
    await fetch(`/booking/${bookingId}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": token },
    });
    console.log(`Booking ${bookingId} deleted.`);
  } catch (error) {
    console.error("Error deleting booking:", error);
  }
}

/* ==============================
   CANCEL BUTTON
================================= */
document.getElementById("cancelPayment").addEventListener("click", async () => {
  if (pendingBookingId) {
    await deleteUnpaidBooking(pendingBookingId);
    pendingBookingId = null;
  }
  closePaymentModal();
  selected = [];
  updateTotal();

  await loadSeats();
  window.location.reload();
});

/* ==============================
   RETURN TO MOVIE LIST
================================= */
document.getElementById("returnHome").addEventListener("click", () => {
  closePaymentModal();
  selected = [];
  updateTotal();
  window.location.href = "/movies";
});

/* =====================================================
   THE BOOKED WILL BE DELETED IF THE USER CLOSES THE TAB
======================================================== */
window.addEventListener("beforeunload", () => {
  if (pendingBookingId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    const url = `/booking/${pendingBookingId}`;
    const data = new FormData();
    data.append("_token", token);

    navigator.sendBeacon(url, data);
  }
});

