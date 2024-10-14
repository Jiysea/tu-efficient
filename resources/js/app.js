import "flowbite";
import { Livewire } from "../../vendor/livewire/livewire/dist/livewire.esm";
import "./bootstrap";

Livewire.start();

import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

document.addEventListener("livewire:navigated", () => {
    initFlowbite();
});
