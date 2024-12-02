<div>
    <div id="profitChartContainer" class="w-full overflow-auto bg-gray-200 rounded-lg mt-4">
        <div class="h-[400px] rounded-lg mt-4 w-[1000px] min-[1250px]:w-full">
            <canvas id="profitChart"></canvas>
        </div>
        <form id="hiddenFormProfit" action="/export-profit" method="post" target="_blank">
            @csrf
            <input type="hidden" id="hiddenImageProfit" name="profit_image" accept="image/*">
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fungsi untuk membuat grafik profit
            function createChart(labels, data) {
                var ctx = document.getElementById('profitChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Profit',
                            data: data,
                            backgroundColor: [
                                'rgba(240, 240, 240, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

            }
            function fetchChart(data) {
                var labels = data.map(item => item.periode);
                var profits = data.map(item => item.profit);
                createChart(labels, profits);
            }
            // Panggil fungsi createChart saat halaman dimuat dengan data default
            var initialData = {!! $profitData !!};  
            fetchChart(initialData);
        });
    </script>
    <script>
        function exportProfit() {
            var canvas = document.getElementById('profitChart');
            var canvasImg = canvas.toDataURL("image/png", 1.0);
            document.getElementById('hiddenImageProfit').value = canvasImg;
            document.getElementById('hiddenFormProfit').submit();
        }

    </script>
</div>