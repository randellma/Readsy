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
		<p id ="test"> It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
		</p>
		<button onclick="LoadTextToReadbyId('readsy_canvas', 'test')">Read this Text</button>
		<br />
		
		<div class="readsy_widget">
			<canvas id="readsy_canvas" width="300" height="50" style="border:1px solid #000000;"></canvas>
			<br />		
			<button id="playpause" onclick="btn_PlayPause()">Read</button>
			<button id="reset" onclick="ResetRead()">Reset</button>
			<input id="wpm" onchange="txtChangeWpm(this.value)" type="number" min="100" step="50" value="300"/>
		</div>
		
		<script>
			
			//Globals
			var ctx;
			var reading = false;
			var baseTimeout = 120;
			var fontsize = 30;
			var textArray;
			var reader;
			var place = 0;
			
			function LoadTextToReadbyId(canvas, id)
			{
				LoadTextToReadbyText(canvas, document.getElementById(id).innerHTML);
			}
			
			function LoadTextToReadbyText(canvas, text)
			{
				//Get Canvas
				var c = document.getElementById(canvas);
				ctx = c.getContext("2d");
				ctx.font = fontsize + "px Arial";
				
				//Get text to read
				textArray = text.trim().split(" ");
				
				//Set reading speed
				txtChangeWpm(document.getElementById("wpm").value)
				
				//Reset Canvas to first word
				ResetRead();
			}
			
			function Read()
			{
				if(place < textArray.length)
				{
					var word = textArray[place];
					var lastChar = word.slice(-1);
					
					DisplayWord(word);
					
					if(lastChar == "." || lastChar == ",")
					{
						StartStopRead()
						setTimeout(function(){StartStopRead()}, 1.25*baseTimeout);
					}
				}
				else
				{
					StartStopRead();
				}
				place++;
			}
			
			function DisplayWord(word)
			{
				DrawBlankSpace();
				
				var x = ctx.canvas.width/3;
				var y = ctx.canvas.height/2 + fontsize/4;
				var focusChar = Math.ceil((word.length + 1)/3);
				x -= (ctx.measureText(word.slice(0,focusChar-1)).width + ctx.measureText(word[focusChar-1]).width/2);
				
				FillRedCharWord(word, x, y, focusChar-1)
			}
			
			function DrawBlankSpace()
			{
				ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
				ctx.moveTo(ctx.canvas.width/3,50);
				ctx.lineTo(ctx.canvas.width/3,0);
				ctx.strokeStyle = '#e3e3e3';
				ctx.stroke();
				
			}
			
			function FillRedCharWord(word, x, y, redPos)
			{
			    for(var i = 0; i < word.length; ++i)
			    {
			    	if(i == redPos)
			    	{
						ctx.fillStyle = '#ff0000';
			    	}
			    	else
			    	{
				    	ctx.fillStyle = '#000';
			    	}
			        var ch = word[i];
			        ctx.fillText(ch, x, y);
			        x += ctx.measureText(ch).width;
			    }
			}
		
			function StartStopRead()
			{
				if(reading)
				{
					clearInterval(reader);
					reading = false;
				}
				else
				{
					reader = setInterval(function(){Read()},baseTimeout);
					reading = true;
				}
			}
			
			function btn_PlayPause()
			{
				if(!textArray)
				{
					return;
				}
				
				StartStopRead();
				if(reading)
				{
					document.getElementById('playpause').innerHTML = "Pause";
				}
				else
				{
					document.getElementById('playpause').innerHTML = "Resume";
				}
			}
			
			function txtChangeWpm(wpm)
			{
				baseTimeout = 60000/wpm;
				StartStopRead();
				StartStopRead();
			}
			
			function ResetRead()
			{
				clearInterval(reader);
				reading = false;
				document.getElementById('playpause').innerHTML = "Read";
				place = 0;
				DisplayWord(textArray[0]);
			}
			
		</script>
		
		
	</body>

</html>


