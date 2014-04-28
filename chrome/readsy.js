var readsy_ids = 0;
var debouncer = false;

function Load_Expanders_Experimental(root)
{
	if(debouncer)
	{
		return;
	}
	//Clear_Widgets(parent);
	debouncer = true;
	
	var pArray = root.getElementsByTagName("p");
	var coveredList = new Array();
	for(var i=0; i < pArray.length; i++)
	{
		var p = pArray[i];
		var parent = p.parentNode;
		var innerText = p.innerText;
		//check if it already has a widget
		var expanderExists = parent.getElementsByClassName("readsy_expander").length > 0;

		//Determine if we even want to count this node
		if(coveredList.indexOf(parent) != -1 || innerText == "" || innerText == undefined || innerText == null || expanderExists)
		{
			continue;
		}
		
		var text = GetInnerText(parent);

		if(text.length > 200)
		{
			Insert_Expander_Before(p);
			coveredList.push(parent);
		}
		
	}
	
	setTimeout(function(){debouncer = false}, 1000);
}

function DoubleClickExpanders(root)
{
	//Clear_Widgets(p.parentNode);
	var pArray = root.getElementsByTagName("p");
	for(var i=0; i< pArray.length; i++)
	{
		pArray[i].addEventListener ("dblclick", 
			function(){
				var container = Insert_Expander_Before(this); 
				var widget = Expander_Click(container);
				if(widget)
				{
					readsy_ExternalPlayPause(widget, 800);
				}
			});
	}
}

function GetInnerText(parent)
{
	return GetInnerTextAfterNode(parent.firstChild);
}

function GetInnerTextAfterNode(node)
{
	var lastNode = parent.lastChild;
	var text = "";
	do
	{
		var innerText = node.textContent;
		if(innerText != undefined && innerText != null && node.tagName == "P")
		{
			/*if(!readsy_IsElementInViewport(node))
			{
				break;
			}*/

			alert
			text += innerText + ' ';
		}
		node = node.nextSibling;
		var innerText = node.textContent;
	}while(node != lastNode && node != null && (node.tagName != "H1" && node.tagName != "H2" && node.tagName != "H3" ))
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


	//Load_Expanders(document.documentElement);
	//Load_Expanders_Experimental(document.getElementsByTagName("body")[0]);
	//document.addEventListener()
	
	//DoubleClickExpanders(document.documentElement);
	document.addEventListener('dblclick', 
		function(e) 
		{
			e = e || window.event;
    		var target = e.target || e.srcElement;

    		switch(target.tagName)
    		{
    			case "P":
		    		var container = Insert_Expander_Before(target); 
					var widget = Expander_Click(container);
					if(widget)
					{
						readsy_ExternalPlayPause(widget, 800);
					}
					break;
			}
        	//text = target.textContent || text.innerText;   
		}, false);

