var yaan = document.getElementById('yaan').innerText;
var chengdu = document.getElementById('chengdu').innerText;
var dujiangyan = document.getElementById('dujiangyan').innerText;

	var lineChartData = {
			labels : ["雅安校区","成都校区","都江堰校区"],
			datasets : [
				{
					label: "用户分布图",
					fillColor : "rgba(48, 164, 255, 0.2)",
					strokeColor : "rgba(48, 164, 255, 1)",
					pointColor : "rgba(48, 164, 255, 1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(48, 164, 255, 1)",
					data : [yaan,chengdu,dujiangyan]
				}
			]

		}

window.onload = function(){
	var chart1 = document.getElementById("line-chart").getContext("2d");
	window.myLine = new Chart(chart1).Line(lineChartData, {
		responsive: true
	});

};