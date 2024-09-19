import "flowbite";
import "./bootstrap";

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

// import Alpine from "alpinejs";

// window.Alpine = Alpine;

// Alpine.start();

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});

// Livewire.on("sessionRegenerated", (csrfToken) => {
//     document.head
//         .querySelector('meta[name="csrf-token"]')
//         .setAttribute("content", csrfToken);
// });
