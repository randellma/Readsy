var widgetElement = readsy_UI(1);
document.getElementsByTagName("body")[0].appendChild(widgetElement);
var readsy_1 = new readsy_widget(1);
document.getElementById("readsy_text").onchange = function(){readsy_1.StopRead(); readsy_1 = new readsy_widget(1, document.getElementById('readsy_text').value);}