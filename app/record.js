function let_me_record()
{
	
	
	var datas=[
				document.body.scrollTop,
				document.body.style.height
			  ];
	var record=document.getElementById("record");
	record.innerHTML="";
	for(var i=0;i<datas.length;i++)
	{
		
		record.innerHTML+=datas[i]+"<br>";
	}
	return function()
	{
		let_me_record(datas);
	}
}

function record()
{
	
	var record=document.createElement("div");
	record.id="record";
	document.body.appendChild(record);
	$('#record')
		.css({
			width:"300px",
			height:"500px",
			background:"pink",
			position:"absolute",
			right:"0",
			bottom:"0",
			zIndex:"1000",
			color:"black",
			opacity:"0.7"
		})
	setTimeout("fff()",100);
	
}
function fff()
{
	setInterval(let_me_record(),10);
}
$(document).ready(function()
{
	
	record();
})