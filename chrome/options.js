//--------------------General---------------------------

function DisplaySection(id)
{
	var sections = document.getElementsByClassName('popup_section');
	for (var i = sections.length - 1; i >= 0; i--) {
		sections[i].style.display = 'none'
	};

	current = document.getElementById(id);
	current.style.display = 'block';

}

function HighlightNav(id)
{
	var tabs = document.getElementById('navigation').getElementsByTagName('A');
	for (var i = tabs.length - 1; i >= 0; i--) {
		tabs[i].style.background = '#DDD';
	};

	current = document.getElementById(id);
	current.style.background = 'white';
}

document.getElementById('popup_link').addEventListener('click', function(){DisplaySection('popup'); HighlightNav('popup_link'); } );
document.getElementById('options_link').addEventListener('click', function(){DisplaySection('options'); HighlightNav('options_link'); } );
document.getElementById('help_link').addEventListener('click', function(){DisplaySection('help'); HighlightNav('help_link'); } );

//Default Section
DisplaySection('popup');
HighlightNav('popup_link');

//-------------Options----------------------

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

document.getElementById('save').addEventListener('click', Save);
document.addEventListener('DOMContentLoaded', Restore);

