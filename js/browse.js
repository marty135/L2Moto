currentProviderIndex = 0;

displayProviderInfo(0, "");

function getProviderData() {
	
	file = httpGet();
	
	var allLines = file.split(/\r\n|\n/);
	var headings = allLines[0].split(',');
	var lines = [];
	var latLong = [];

	for (var i = 1; i < allLines.length; i++) {
		var data = allLines[i].split(',');
		if (data.length == headings.length) {
			var arr = [];
			for (var j = 0; j < headings.length; j++) {
				arr.push(data[j]);
			}
			lines.push(arr);
		}
	}
  
	return lines;
	
}


function nextProvider(){
	currentProviderIndex += 1;
	if (currentProviderIndex==getProviderData().length){
		currentProviderIndex = 0;
	}
	//displayProviderInfo(currentProviderIndex, "fadeInRight");
	displayProviderInfo(currentProviderIndex, "next");
}

function previousProvider(){
	currentProviderIndex -= 1;
	if (currentProviderIndex==-1){
		currentProviderIndex = getProviderData().length-1;
	}
	//displayProviderInfo(currentProviderIndex, "fadeInLeft");
	displayProviderInfo(currentProviderIndex, "prev");
}

function displayProviderInfo(index, direction){
	
	timeOut = 150
	if (direction=="next"){
		$('#providerInfoContainer').removeClass().addClass("magictime spaceOutLeft");
		var wait = window.setTimeout( function(){
			fillProviderInfoFields(index);
			$('#providerInfoContainer').removeClass().addClass("magictime spaceInRight");
			
			var wait2 = window.setTimeout(function(){
				$('#providerInfoContainer').removeClass();
			}, timeOut);
			
		}, timeOut );
	} else if (direction=="prev"){
		$('#providerInfoContainer').removeClass().addClass("magictime spaceOutRight");
		var wait = window.setTimeout( function(){
			fillProviderInfoFields(index);
			$('#providerInfoContainer').removeClass().addClass("magictime spaceInLeft");
			
			var wait2 = window.setTimeout(function(){
				$('#providerInfoContainer').removeClass();
			}, timeOut);
			
		}, timeOut );
	} else {
		fillProviderInfoFields(index);
	}
	
}

function fillProviderInfoFields(index){
	
	var dat = getProviderData()[index]
	
	var providerName = document.getElementById("modal-providerName");
	var providerAddress = document.getElementById("modal-providerAddress");
	var providerSuburb = document.getElementById("modal-providerSuburb");
	var providerPhone = document.getElementById("modal-providerPhone");
	var providerEmail = document.getElementById("modal-providerEmail");
	var providerWebsite = document.getElementById("modal-providerWebsite");
	var providerLogoImage = document.getElementById("providerInfoImage");
	
	providerName.innerHTML = dat[0];
	providerAddress.innerHTML = dat[3] + " " + dat[4];
	providerSuburb.innerHTML = dat[5] + " " + dat[6];
	providerPhone.innerHTML = dat[9] + " " + dat[10];
	providerEmail.innerHTML = dat[11];
	providerWebsite.innerHTML = dat[12];
	
	providerLogoImage.style.backgroundImage = "url('images/qride_provider_logo/" + dat[0].replace("%20", " ") + ".png')";
}


document.getElementById("btn_nextProvider").setAttribute("onclick", "nextProvider()");
document.getElementById("btn_previousProvider").setAttribute("onclick", "previousProvider()");