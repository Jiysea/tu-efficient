import "./bootstrap";
import "flowbite";

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

// import Chart from "chart.js/auto";
// window.Chart = Chart;

// import Alpine from "alpinejs";

// window.Alpine = Alpine;

// Alpine.start();

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});
