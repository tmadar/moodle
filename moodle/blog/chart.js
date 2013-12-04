var myXp = parseInt(JSON.parse($('input[name=datar]').val())['xp']);
var otherXp1 = myXp - 50;
var otherXp2 = otherXp1 - 200;
var otherXp3 = myXp / otherXp1 * otherXp2 + 10;
var myXpPerLevel = 500;
var myData = new Array(['Your XP', myXp], 
					   ['Average', otherXp1],
					   ['High', otherXp2],
					   ['Low', otherXp3]);
var myChart = new JSChart('chartcontainer', 'bar');
myChart.setDataArray(myData);
myChart.setIntervalEndY(myXpPerLevel*5);
myChart.set3D(true);
myChart.setAxisReversed(true);
myChart.draw();
