<div class="relative h-full w-full bg-white rounded-lg shadow p-3">
    <div class="flex justify-between mb-3">
        <div class="flex justify-center items-center">

            {{-- Title of the Chart --}}
            <h5 class="text-xl font-bold leading-none text-gray-900  pe-1">Per Implementation</h5>

            {{-- The "?" Popover --}}
            <svg data-popover-target="chart-info" data-popover-placement="bottom"
                class="w-3.5 h-3.5 text-indigo-900  hover:text-indigo-500  cursor-pointer ms-1" aria-hidden="true"
                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z" />
            </svg>

            {{-- Popover for the Info of this Chart --}}
            <div data-popover id="chart-info" role="tooltip"
                class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 w-72   ">
                <div class="p-3 space-y-2">
                    <h3 class="font-semibold text-gray-900 ">Per Implementation (Male and Female)</h3>
                    <p>These charts represent the total number of Males and Females per implementation. In each
                        implementation, it is also divided by people with disability and senior citizens.
                    </p>
                    <h3 class="font-semibold text-gray-900 ">Download CSV</h3>
                    <p>All the data represented here can be downloaded as CSV file format.</p>
                </div>
                <div data-popper-arrow></div>
            </div>
        </div>
        <div>
            <button type="button" data-tooltip-target="data-tooltip" data-tooltip-placement="bottom"
                class="hidden lg:inline-flex items-center justify-center text-gray-500 w-8 h-8  hover:bg-gray-100  focus:outline-none focus:ring-4 focus:ring-gray-200  rounded-lg text-sm"><svg
                    class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 16 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3" />
                </svg><span class="sr-only">Download data</span>
            </button>
            <div id="data-tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip ">
                Download CSV
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>

    <!-- Donut Charts -->
    <div class="grid grid-cols-3">
        <div class="m-0" id="overall-chart"></div>
        <div class="m-0" id="pwd-chart"></div>
        <div class="m-0" id="senior-chart"></div>
    </div>


</div>

@script
    <script>
        let data = @json($implementationCount);
        const totalMale = data.total_male;
        const totalFemale = data.total_female;
        const totalPwdMale = data.total_pwd_male;
        const totalPwdFemale = data.total_pwd_female;
        const totalMaleSenior = data.total_senior_male;
        const totalFemaleSenior = data.total_senior_female;
        const overallValues = [parseInt(totalMale), parseInt(totalFemale)];
        const pwdValues = [parseInt(totalPwdMale), parseInt(totalPwdFemale)];
        const seniorValues = [parseInt(totalMaleSenior), parseInt(totalFemaleSenior)];

        // console.log(overallValues);
        // console.log(pwdValues);
        // console.log(seniorValues);

        let overall, pwd, senior;

        function renderCharts() {
            const options = (labelName, seriesValues, my_id) => {
                return {
                    series: seriesValues,
                    colors: ["#e74c3c", "#f1c40f"],
                    chart: {
                        id: my_id,
                        fontFamily: "Inter, sans-serif",
                        height: 250,
                        width: "100%",
                        type: "donut",
                    },
                    stroke: {
                        colors: ["transparent"],
                        lineCap: "",
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: false,
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        offsetY: 20,
                                    },
                                    total: {
                                        showAlways: true,
                                        show: true,
                                        label: labelName,
                                        formatter: function(w) {
                                            const sum = w.globals.seriesTotals.reduce(
                                                (a, b) => {
                                                    return a + b;
                                                },
                                                0
                                            );
                                            return sum;
                                        },
                                    },
                                    value: {
                                        show: true,
                                        offsetY: -20,
                                        formatter: function(value) {
                                            return value;
                                        },
                                    },
                                },
                                size: "80%",
                            },
                        },
                    },
                    grid: {
                        padding: {
                            top: -2,
                        },
                    },
                    labels: ["Male", "Female"],
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {
                        position: "bottom",
                        fontFamily: "Inter, sans-serif",
                    },
                    yaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            },
                        },
                    },
                    xaxis: {
                        labels: {
                            formatter: function(value) {
                                return value;
                            },
                        },
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                };
            };

            overall = new ApexCharts(
                document.getElementById("overall-chart"),
                options("Overall", overallValues, "overallDonut")
            );

            pwd = new ApexCharts(
                document.getElementById("pwd-chart"),
                options("PWDs", pwdValues, "pwdDonut")
            );

            senior = new ApexCharts(
                document.getElementById("senior-chart"),
                options("Senior Citizens", seniorValues, "seniorDonut")
            );

            overall.render();
            pwd.render();
            senior.render();


        };

        $wire.on('series-change', (event) => {
            let data = event[0].implementationCount;
            const totalMale = data.total_male;
            const totalFemale = data.total_female;
            const totalPwdMale = data.total_pwd_male;
            const totalPwdFemale = data.total_pwd_female;
            const totalMaleSenior = data.total_senior_male;
            const totalFemaleSenior = data.total_senior_female;

            const overallValues = [parseInt(totalMale), parseInt(totalFemale)];
            const pwdValues = [parseInt(totalPwdMale), parseInt(totalPwdFemale)];
            const seniorValues = [parseInt(totalMaleSenior), parseInt(totalFemaleSenior)];
            console.log(overallValues);
            console.log(pwdValues);
            console.log(seniorValues);

            overall.updateSeries(overallValues, true);
            pwd.updateSeries(pwdValues, true);
            senior.updateSeries(seniorValues, true);

            window.dispatchEvent(new Event('resize'));
        });
        renderCharts();
    </script>
@endscript
