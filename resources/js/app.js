import "flowbite";
import "./bootstrap";

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});
