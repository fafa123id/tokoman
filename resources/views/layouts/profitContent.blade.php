<div class="flex flex-col">
    <div class="container text-white flex flex-col justify-center items-center">
        <h1 class="text-3x text-center my-8 font-bold">Hitung Profit</h1>
        <form id="profitForm" action="{{ route('ApiFetch') }}" method="GET" class="">
            <div class="flex flex-col md:flex-row gap-4">
                <div>
                    <label for="period">Pilih Periode:</label>
                    <select class="text-black" name="period" id="period">
                        <option value="thisMonth">Bulan ini</option>
                        <option value="thisYear">Tahun ini</option>
                        <option value="yearly">Tahunan</option>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="w-full overflow-auto bg-gray-200 rounded-lg mt-4">
        <div class="h-[600px] rounded-lg mt-4 w-[1000px] min-[1250px]:w-full" id="parentChart">
            <canvas id="profitChart"></canvas>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function createChart(labels, data) {
            var ctx = document.getElementById('profitChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Profit',
                        data: data,
                        backgroundColor: 'rgba(240, 240, 240, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
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

        function updateChart(data) {
            var labels = data.map(item => item.periode);
            var profits = data.map(item => item.profit);

            // Remove the existing canvas
            document.getElementById("profitChart").remove();

            // Create a new canvas element
            var newCanvas = document.createElement('canvas');
            newCanvas.id = "profitChart";

            // Append the new canvas to the parent container
            document.getElementById("parentChart").appendChild(newCanvas);

            // Now you can recreate the chart on the new canvas
            createChart(labels, profits);
        }
        var initialData = {!! $finalresults !!};  // Decode JSON to JavaScript object
        updateChart(initialData);
        $('#profitForm').on('change', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var url = $(this).attr('action');
            var method = "GET"

            $.ajax({
                url: url,
                type: method,
                data: formData,
                success: function (data) {
                    updateChart(data.finalresults);
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        });
    });
</script>