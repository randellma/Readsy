<html>

	<head>
	
		<title>Readsy</title>
		<link rel="stylesheet" type="text/css" href="style/index.css">
		<!--<link rel="stylesheet" type="text/css" href="style/widgets.css">-->
	</head>

	<body>
		<div id="readsy_read_1">

			<textarea id="text" rows="10" cols="100"></textarea>
		</div>
		<button id="btnTextArea" onclick="readsy_1.StopRead(); readsy_1 = new readsy_widget(1, document.getElementById('text').value)">Read this</button>
		
		<div class="readsy_widget" id ="readsy_widget_1">
				<canvas id="readsy_canvas_1" width="300" height="60" /></canvas>
			<div class="readsy_controls">
				<button id="readsy_pp_1">Read</button>
				<input id="readsy_wpm_1" type="number" min="100" step="50" value="400"/>
			</div>
		</div>
		
		<script type="text/javascript" src="../chrome/readsy_app.js"></script>
		
		<script>
			var readsy_1 = new readsy_widget(1);
		</script>
	</body>
</html>


