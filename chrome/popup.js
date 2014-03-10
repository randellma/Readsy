var readsy_1 = new readsy_widget(1);
document.getElementById("btnTextArea").onclick = function(){readsy_1.StopRead(); readsy_1 = new readsy_widget(1, document.getElementById('text').value);}