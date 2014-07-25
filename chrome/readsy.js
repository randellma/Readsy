var readsy_ids = 0;
var debouncer = false;

function GetInnerTextAfterNode(node)
{
	var lastNode = parent.lastChild;
	var text = "";
	var i = 0;
	do
	{
		i++;	
		if(textContent != undefined && textContent != null && textContent.length > 10 && node.tagName != 'ASIDE' && node.className != 'google-mobile-ads-container')
		{
			text += textContent + ' ';
		}
		node = node.nextSibling;
		if(node && node != undefined)
		{
			var textContent = node.textContent || node.innerText || "";
			textContent = textContent.trim();
			//alert(node.tagName + " - " + textContent);
		}
	}while(node && ((node.tagName != "H1" && node.tagName != "H2" && node.tagName != "H3" ) || i==1))
	return text;
}
 
//taken from stack overflow - http://stackoverflow.com/a/7557433
function readsy_IsElementInViewport (el) {
    var rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
        rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
    );
}

function Insert_Expander_Before(el)
{
	var container = document.createElement('div');
	container.className = "readsy_container";
	container.innerHTML = "<span class=\"readsy_expander\">[R]</span>";
	el.parentNode.insertBefore(container, el);
	container.firstChild.onclick = function(){Expander_Click(container);};
	return container;
}

function Expander_Click(container)
{
	//check if it already has a widget
	if(container.getElementsByClassName("readsy_widget").length > 0)
	{
		Clear_Widgets(container);
		container.firstChild.innerHTML = "[R]";
		return null;
	}
	else
	{
		return Insert_Widget(container);
	}
	
}

function Insert_Widget(container)
{
	parent = container.parentNode;
	var guid = ++readsy_ids;
	var text = GetInnerTextAfterNode(container);
	container.appendChild(readsy_UI(guid));
	container.firstChild.innerHTML= "[-]";
	return new readsy_widget(guid, text);

}

function Clear_Widgets(parent)
{
	var readsyArray = parent.getElementsByClassName("readsy_widget");
	for(var i=0; readsyArray.length > 0; i++)
	{
		readsyArray[0].parentNode.removeChild(readsyArray[0]);
	}
}

function Clear_Containers(parent)
{
	var readsyArray = parent.getElementsByClassName("readsy_container");
	for(var i=0; readsyArray.length > 0; i++)
	{
		readsyArray[0].parentNode.removeChild(readsyArray[0]);
	}
}

var require_alt = false;
document.addEventListener('dblclick', 
	function(e) 
	{
		e = e || window.event;
		var target = e.target || e.srcElement;
		var innerContent = target.textContent || target.innerText || "";

		if(innerContent.length > 10 && innerContent != null && (e.altKey && require_alt || !require_alt))
		{ 
				Clear_Containers(document.documentElement);
	    		var container = Insert_Expander_Before(target); 
				var widget = Expander_Click(container);
				if(widget)
				{
					readsy_ExternalPlayPause(widget, 800);
				}
		}
		/*switch(target.tagName)
		{
			case "P":
			break;
		}*/  
	}, false);

//loadsettings 
chrome.storage.sync.get({readsy_modifier: "false"}, 
		function(items) 
		{
			require_alt = items.readsy_modifier == 'true';
		});

