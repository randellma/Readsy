var readsy_ids = 0;
var debouncer = false;
function Load_Expanders(parent)
{
	if(debouncer)
	{
		return;
	}
	//Clear_Widgets(parent);
	debouncer = true;
	var taglineArray = parent.getElementsByClassName("md");
	for(var i = 0; i < taglineArray.length; i++)
	{
		
		if(taglineArray[i].getElementsByClassName("readsy_expander").length > 0)
		{
			continue;
		}
		
		var text = taglineArray[i].innerText;
		if(text.length <= 0)
		{
			continue;
		}
		Insert_Expander(taglineArray[i]);
	}
	setInterval(function(){debouncer = false}, 1000);
}

function Insert_Expander(el)
{
	var container = document.createElement('div');
	container.className = "readsy_container";
	container.innerHTML = "<span class=\"readsy_expander\">[R]</span>";
	el.insertBefore(container, el.firstChild);
	container.firstChild.onclick = function(){Expander_Click(container);};
}

function Expander_Click(container)
{
	//check if it already has a widget
	if(container.getElementsByClassName("readsy_widget").length > 0)
	{
		Clear_Widgets(container);
		container.firstChild.innerHTML = "[R]";
	}
	else
	{
		Insert_Widget(container);
	}
	
}

function Insert_Widget(container)
{
	parent = container.parentNode;
	var guid = ++readsy_ids;
	var text = parent.innerText;
	container.appendChild(readsy_UI(guid));
	new readsy_widget(guid, text);
	container.firstChild.innerHTML= "[-]";

}

function Clear_Widgets(parent)
{
	var readsyArray = document.getElementsByClassName("readsy_widget");
	for(var i=0; readsyArray.length > 0; i++)
	{
		readsyArray[0].parentNode.removeChild(readsyArray[0]);
	}
}


	Load_Expanders(document.documentElement);
	document.addEventListener ("DOMNodeInserted", function(){Load_Expanders(this);});
	
	/*var expandArray = document.getElementsByClassName("comment");
	for(var i=0; i< expandArray.length; i++)
	{
		expandArray[i].addEventListener ("click", function(){Load_Expanders(this);});
	}
	*/