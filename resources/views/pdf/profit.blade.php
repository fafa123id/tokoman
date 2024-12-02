<!DOCTYPE html>
<html>
<head>
    
    <title>Profit PDF</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/asset/logo.png" alt="Logo">
        <h1>Tokoman</h1>
        <h2>{{now()->format('d/m/Y').' '.now()->format('H:i')}}</h2>
    </div>
    <div class="chart">
        <img src="https://tokoman.s3.ap-southeast-2.amazonaws.com/profit.png" alt="Chart" style="width: 100%; height: 40%;">
    </div>
    <p>Profit bulan ini adalah Rp.{{$totalprofit}}</p>
</body>

</html>