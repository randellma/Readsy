<?
			//Require code
?>

<!DOCTYPE html>

<html>

	<head>
	
		<title>Readsy</title>
		<link rel="stylesheet" type="text/css" href="style/index.css">
		<!--<link rel="stylesheet" type="text/css" href="style/widgets.css">-->
	</head>

	<body>
		<div id="readsy_read_1">

			<textarea id="text" rows="10" cols="100">It is. a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
			</textarea>
		</div>
		<button id="btnTextArea" onclick="readsy_1 = new readsy_widget(1, document.getElementById('text').value)">Read this</button>
		
		<div class="readsy_widget" id ="readsy_widget_1">
			<canvas id="readsy_canvas_1" width="300" height="50" style="border:1px solid #000000;"></canvas>
			<br />	
			<button id="readsy_pp_1" onclick="readsy_1.btn_PlayPause()">Read</button>
			<button id="readsy_reset_1" onclick="readsy_1.ResetRead()">Reset</button>
			<input id="readsy_wpm_1" onchange="readsy_1.txtChangeWpm()" type="number" min="100" step="50" value="400"/>
		</div>
		
		<script>
			/*Global functions needed because setInterval and setTimeout don't trigger in scope calls*/
			function readsy_Continue(instance, interval)
			{
				instance.place++;
				setTimeout(function(){instance.Read()}, interval);
			}
			
			/*Readsy Methods*/
			function readsy_Read()
			{
				if(this.place >= this.textArray.length)
				{
					this.StartStopRead();
				}
				if(this.reading)
				{
					var word = this.textArray[this.place];
					var lastChar = word.slice(-1);
					var interval = this.baseTimeout;
					
					this.DisplayWord(word);
					
					
					if(lastChar == "." || lastChar == "," || lastChar == "!" || lastChar == ";" 
						|| lastChar == ":")
					{
						interval = 2*this.baseTimeout;
					}
					else if(word.length >=8)
					{
						interval = 1.5*this.baseTimeout;
					}
					
					readsy_Continue(this,interval);
				}
			}
			/**/
			function readsy_DisplayWord(word)
			{
				this.DrawBlankSpace();
				
				var x = this.ctx.canvas.width/3;
				var y = this.ctx.canvas.height/2 + this.fontsize/4;
				var focusChar = Math.ceil((word.length + 1)/3);
				x -= (this.ctx.measureText(word.slice(0,focusChar-1)).width + this.ctx.measureText(word[focusChar-1]).width/2);
				
				this.FillRedCharWord(word, x, y, focusChar-1)
			}
			/**/
			function readsy_DrawBlankSpace()
			{
				var h = this.ctx.canvas.height;
				var w = this.ctx.canvas.width
				
				//draw focus line
				this.ctx.clearRect(0, 0, w, h);
				this.ctx.beginPath();
				this.ctx.lineWidth = 1;
				this.ctx.strokeStyle = '#e3e3e3';
				
				this.ctx.moveTo(w/3,h);
				this.ctx.lineTo(w/3,0);
				this.ctx.stroke();
				
				//draw progress bar
				this.ctx.beginPath();
				this.ctx.lineWidth = 10;
				
				this.ctx.moveTo(0,h);
				this.ctx.lineTo(w,h);
				this.ctx.stroke();
				
				
				//draw progress marker
				this.ctx.beginPath();
				this.ctx.lineWidth = 1;
				this.ctx.strokeStyle = '#000';
				
				this.ctx.moveTo(Math.ceil((this.place/this.textArray.length)*300),h);
				this.ctx.lineTo(Math.ceil((this.place/this.textArray.length)*300),h-5);
				this.ctx.stroke();
				
			}
			/**/
			function readsy_FillRedCharWord(word, x, y, redPos)
			{
			    for(var i = 0; i < word.length; ++i)
			    {
			    	if(i == redPos)
			    	{
						this.ctx.fillStyle = '#ff0000';
			    	}
			    	else
			    	{
				    	this.ctx.fillStyle = '#000';
			    	}
			        var ch = word[i];
			        this.ctx.fillText(ch, x, y);
			        x += this.ctx.measureText(ch).width;
			    }
			}
			/**/
			function readsy_StartStopRead()
			{
				if(this.reading)
				{
					this.reading = false;
				}
				else
				{
					this.reading = true;
					this.Read();
				}
			}
			/**/
			function readsy_ResetRead()
			{
				this.reading = false;
				this.btnPlayPause.innerHTML = "Read";
				this.place = 0;
				this.DisplayWord(this.textArray[0]);
			}
			/**/
			function readsy_btnReset()
			{
				this.ResetRead();
			}
			/**/
			function readsy_txtChangeWpm()
			{
				this.baseTimeout = 60000/this.txtWpm.value;
			}
			/**/
			function readsy_btnPlayPause()
			{
				if(!this.textArray)
				{
					return;
				}
				
				this.StartStopRead();
				if(this.reading)
				{
					this.btnPlayPause.innerHTML = "Pause";
				}
				else
				{
					this.btnPlayPause.innerHTML = "Resume";
				}
			}
			/**/
			//==============Object Definition=================
			function readsy_widget(guid, text)
			{
				//Helper Methods
				this.StartStopRead = readsy_StartStopRead;
				this.Read = readsy_Read;
				this.DrawBlankSpace = readsy_DrawBlankSpace;
				this.DisplayWord = readsy_DisplayWord;
				this.FillRedCharWord = readsy_FillRedCharWord;
				this.ResetRead = readsy_ResetRead;
				
				//Event Methods
				this.btn_PlayPause = readsy_btnPlayPause;
				this.btn_Reset = readsy_btnReset;
				this.txtChangeWpm = readsy_txtChangeWpm;
			
				//Interface references
				this.txtWpm = document.getElementById("readsy_wpm_"+guid);
				this.btnPlayPause = document.getElementById("readsy_pp_"+guid);
				this.btnReset = document.getElementById("readsy_reset_"+guid);
				this.cvsCanvas = document.getElementById("readsy_canvas_"+guid);
				
				//Globals
				this.guid = guid;
				this.reading = false;
				this.fontsize = 30;
				this.baseTimeout;
				this.textArray;
				this.reader;
				this.place = 0;
				//Set canvas context
				this.ctx = this.cvsCanvas.getContext("2d");
				this.ctx.font = this.fontsize + "px Arial";
				
				//Set Text to Read
				if(!text)
				{
					//Try to find the defined text and strip the extra html
					text = document.getElementById("readsy_read_"+guid).innerHTML
					var tmp = document.createElement("DIV");
					tmp.innerHTML = text;
					text = tmp.textContent || tmp.innerText || "";
					
				}
				this.textArray = text.trim().split(" ");
				
				//Set the default WPM
				this.txtChangeWpm();
				//Reset canvas to first word
				this.ResetRead();
			}
			var readsy_1 = new readsy_widget(1);
		
		</script>
		
		
	</body>

</html>


