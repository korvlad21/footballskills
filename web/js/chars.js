$(document).ready(function(){

	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ['Американские мамонты', 'Русские мамонты', 'Индийские слоны', 'Африканские слоны'],
			datasets: [{
				data: [400, 850, 600, 650],
				backgroundColor: [
				'rgba(255, 99, 132, 0.2)',
				'rgba(54, 162, 235, 0.2)',
				'rgba(255, 206, 86, 0.2)',
				'rgba(75, 192, 192, 0.2)'
				],
				borderColor: [
				'rgba(255, 99, 132, 1)',
				'rgba(54, 162, 235, 1)',
				'rgba(255, 206, 86, 1)',
				'rgba(75, 192, 192, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			legend: {
      			display: false
    		},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});

	var ctx = document.getElementById('myLineChart').getContext('2d');

	var dataFirst = {
		label: "Продажи мамонтов",
		data: [0, 300, 200, 20, 100, 380, 250],
		borderColor: [
		'rgba(75, 192, 192, 1)'
		],
		borderWidth: 1
	};
	var dataSecond = {
		label: "Продажи слонов",
		data: [100, 20, 300, 250, 130, 295, 5],
		borderColor: [
		'rgba(255, 99, 132, 1)'
		],
		borderWidth: 1
	};

	var myLine = {
		labels: ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
		datasets: [dataFirst, dataSecond]
	};


	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: myLine
	});
});