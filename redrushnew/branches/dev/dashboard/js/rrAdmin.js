//JS cendekiApp
function addCommas(str) {
    var amount = new String(str);
    amount = amount.split("").reverse();

    var output = "";
    for ( var i = 0; i <= amount.length-1; i++ ){
        output = amount[i] + output;
        if ((i+1) % 3 == 0 && (amount.length-1) !== i)output = ',' + output;
    }
    return output;
}

function smac_number(str){
	var n = parseFloat(str);
	var s = "";
	if(n>1000000){
		s = Math.round(n/1000000)+"M";
	}else if(n>1000){
		s = Math.round(n/1000)+"K";
	}else{
		s = n;
	}
	return s;
}
function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}
function two(x) {return ((x>9)?"":"0")+x;}
function three(x) {return ((x>99)?"":"0")+((x>9)?"":"0")+x;}

function time(ms) {
	var sec = Math.floor(ms/1000);
	ms = ms % 1000;
	t = three(ms);
	
	var min = Math.floor(sec/60);
	sec = sec % 60;
	//t = two(sec) + ":" + t;
	//without ms
	t = two(sec);
	
	var hr = Math.floor(min/60);
	min = min % 60;
	t = two(min) + ":" + t;
	
	var day = Math.floor(hr/60);
	hr = hr % 60;
	t = two(hr) + ":" + t;
	if (day > 0){
		if (day == 1){
			t = day + " day - " + t;
		}else{
			t = day + " days - " + t;
		}
	}
	
	return t;
}
function timeSecond(sec) {
	//var sec = Math.floor(ms/1000);
	//ms = ms % 1000;
	//t = three(ms);
	
	var min = Math.floor(sec/60);
	sec = sec % 60;
	//t = two(sec) + ":" + t;
	//without ms
	t = two(sec);
	
	var hr = Math.floor(min/60);
	min = min % 60;
	t = two(min) + ":" + t;
	
	var day = Math.floor(hr/60);
	hr = hr % 60;
	t = two(hr) + ":" + t;
	if (day > 0){
		if (day == 1){
			t = day + " day - " + t;
		}else{
			t = day + " days - " + t;
		}
	}
	
	return t;
}