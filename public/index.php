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
				<canvas id="readsy_canvas_1" width="300" height="60" /></canvas>
			<div class="readsy_controls">
				<button id="readsy_pp_1">Read</button>
				<input id="readsy_wpm_1" type="number" min="100" step="50" value="400"/>
			</div>
		</div>
		
		<script>
			/*Global functions needed because setInterval and setTimeout don't trigger in scope calls*/
			function readsy_Continue(instance, interval)
			{
				instance.place++;
				instance.started = true;
				setTimeout(function(){instance.Read()}, interval);
			}
			
			/*Readsy Methods*/
			function readsy_Read()
			{
				if(this.place >= this.textArray.length)
				{
					this.EndRead();
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
					
					this.started = false;
					readsy_Continue(this,interval);
				}
				else
				{
					this.started = false;
				}
			}
			/**/
			function readsy_DisplayWord(word)
			{
				this.DrawBlankSpace();
				
				var x = this.w/3;
				var y = (this.h - this.progressWidth)/2 + this.fontsize/4;
				var focusChar = Math.ceil((word.length + 1)/3);
				x -= (this.ctx.measureText(word.slice(0,focusChar-1)).width + this.ctx.measureText(word[focusChar-1]).width/2);
				
				this.FillRedCharWord(word, x, y, focusChar-1)
			}
			/**/
			function readsy_DrawBlankSpace()
			{				
				//draw focus line
				this.ctx.clearRect(0, 0, this.w, this.h);
				this.ctx.beginPath();
				this.ctx.lineWidth = 1;
				this.ctx.strokeStyle = '#e3e3e3';
				
				this.ctx.moveTo(this.w/3,this.h);
				this.ctx.lineTo(this.w/3,0);
				this.ctx.stroke();
				
				//draw progress bar				
				this.ctx.fillStyle = "#e3e3e3";
				this.ctx.fillRect(0,this.h-this.progressWidth,this.w,this.progressWidth);
				
				
				this.ctx.beginPath();
				this.ctx.lineWidth = 1;
				this.ctx.strokeStyle = '#000';
				this.ctx.moveTo(0,this.h - this.progressWidth - 0.5);
				this.ctx.lineTo(this.w,this.h - this.progressWidth - 0.5);
				this.ctx.stroke();
				
				
				//draw progress marker
				this.ctx.beginPath();
				this.ctx.lineWidth = 2;
				this.ctx.strokeStyle = '#ff0000';
				
				this.ctx.moveTo(Math.ceil((this.place/this.textArray.length)*300)+0.5,this.h);
				this.ctx.lineTo(Math.ceil((this.place/this.textArray.length)*300)+0.5,this.h-this.progressWidth);
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
			function readsy_StartRead()
			{
				this.reading = true;
				if(!this.started)
				{
					this.ended = false;
					this.btnPlayPause.innerHTML = "Pause";
					this.Read();
				}
				
			}
			/**/
			function readsy_StopRead()
			{
				this.reading = false;
			}
			/**/
			function readsy_EndRead()
			{
				this.StopRead();
				this.ended = true;
				this.btnPlayPause.innerHTML = "Reread";
			}
			/**/
			function readsy_ResetRead()
			{
				this.reading = false;
				this.started = false;
				this.ended = false;
				this.btnPlayPause.innerHTML = "Read";
				this.place = 0;
				this.DisplayWord(this.textArray[0]);
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
				
				if(this.ended)
				{
					this.ResetRead();
					return;
				}
				
				if(!this.reading)
				{
					this.btnPlayPause.innerHTML = "Pause";
					this.StartRead();
				}
				else
				{
					this.btnPlayPause.innerHTML = "Resume";
					this.StopRead();
				}
			}
			function readsy_mouseDown(event)
			{
				//get coordinates and offset correctly
				var rect = this.cvsCanvas.getBoundingClientRect();
				var x = event.clientX - rect.left;
				var y = event.clientY - rect.top;
				
				if(y > this.h - this.progressWidth)
				{
					this.place = Math.floor((x/this.w)*this.textArray.length);
					this.DisplayWord(this.textArray[this.place]);
					this.mousedown = true;
					
					//momentarily pause reading until
					if(this.reading)
					{
						this.StopRead();
						this.pause = true;
					}
					
					//
					if(this.ended)
					{
						this.StartRead();
					}
				}
			}
			function readsy_mouseMove(event)
			{
				//get coordinates and offset correctly
				var rect = this.cvsCanvas.getBoundingClientRect();
				var x = event.clientX - rect.left;
				var y = event.clientY - rect.top;
				
				if(y > this.h - this.progressWidth)
				{
					this.cvsCanvas.style.cursor = "pointer";
				}
				else
				{
					this.cvsCanvas.style.cursor = "default";
				}
				
				if(this.mousedown && event.which == 1)
				{
					this.place = Math.floor((x/this.w)*this.textArray.length);
					this.DisplayWord(this.textArray[this.place]);
					this.mousedown = true;
				}
				
			}
			function readsy_mouseUp(event)
			{
				this.mousedown = false;
				
				if(this.pause)
				{
					this.StartRead();
					this.pause = false;
				}
			}
			
			/**/
			//==============Object Definition=================
			function readsy_widget(guid, text)
			{
				//Helper Methods
				this.StartRead = readsy_StartRead;
				this.StopRead = readsy_StopRead;
				this.Read = readsy_Read;
				this.DrawBlankSpace = readsy_DrawBlankSpace;
				this.DisplayWord = readsy_DisplayWord;
				this.FillRedCharWord = readsy_FillRedCharWord;
				this.ResetRead = readsy_ResetRead;
				this.EndRead = readsy_EndRead;
				
				//Event Methods
				this.btn_PlayPause = readsy_btnPlayPause;
				this.txtChangeWpm = readsy_txtChangeWpm;
				this.mouseDown = readsy_mouseDown;
				this.mouseMove = readsy_mouseMove;
				this.mouseUp = readsy_mouseUp;
			
				//Interface references
				this.wgtReadsy = document.getElementById("readsy_widget_"+guid);
				this.txtWpm = document.getElementById("readsy_wpm_"+guid);
				this.btnPlayPause = document.getElementById("readsy_pp_"+guid);
				this.cvsCanvas = document.getElementById("readsy_canvas_"+guid);
				
				//Bind Events
				var that = this;
				this.txtWpm.onclick = function(){that.txtChangeWpm();};
				this.btnPlayPause.onclick = function(){that.btn_PlayPause();};
				this.cvsCanvas.onmousedown = function(event){that.mouseDown(event);};
				this.cvsCanvas.onmouseup = function(event){that.mouseUp(event);};
				this.cvsCanvas.onmousemove = function(event){that.mouseMove(event);};
				this.wgtReadsy.onmouseup = function(event){that.mouseUp(event);};
				
				//Instance variables
				this.guid = guid;
				this.textArray;
				this.instance = this;
				this.mousedown = false;
				//Reading variables
				this.reading = false;
				this.started = false;
				this.pause = false;
				this.ended = false;
				this.place = 0;
				this.baseTimeout;
				//Display Settings
				this.fontsize = 30;	
				this.progressWidth = 10;			
				//Set canvas context
				this.ctx = this.cvsCanvas.getContext("2d");
				this.ctx.font = this.fontsize + "px Arial";
				this.h = this.ctx.canvas.height;
				this.w = this.ctx.canvas.width
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


