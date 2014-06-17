function Restore() {

	chrome.storage.sync.get({readsy_speed: '350'}, 
		function(items) 
		{
			document.getElementById("wpm").value = items.readsy_speed;
		});

	chrome.storage.sync.get({readsy_modifier: 'false'}, 
		function(items) 
		{
			document.getElementById("modifier").checked = items.readsy_modifier == "true";
		});

}


// Saves options to chrome.storage
function Save() {
  var wpm = document.getElementById('wpm').value;
  var modifier = document.getElementById('modifier').checked ? "true" : "false";

  chrome.storage.sync.set({
    readsy_speed: wpm,
    readsy_modifier: modifier
  }, function() {
    // Update status to let user know options were saved.
    var status = document.getElementById('status');
    status.textContent = 'Options saved.';
    setTimeout(function() {
      status.textContent = ' ';
    }, 1000);
  });
}

function ShowOptions(){
	if(document.getElementById('options').style.display != 'block')
	{
		document.getElementById('popup').style.display = 'none';
		document.getElementById('options').style.display = 'block';
		document.getElementById('options_link').textContent = 'Back';
	}
	else
	{
		document.getElementById('options').style.display = 'none';
		document.getElementById('popup').style.display = 'block';
		document.getElementById('options_link').textContent = 'Options';
	}
}

document.getElementById('save').addEventListener('click', Save);
document.addEventListener('DOMContentLoaded', Restore);

document.getElementById('options_link').addEventListener('click', ShowOptions);