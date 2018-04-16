

function hide()
{
	var elements = document.getElementsByClassName("questions");
	for (var i = 0, len = elements.length; i < len; i++) 
	{
		elements[i].style.display = "none";
	}
}

function show()
{
	var elements = document.getElementsByClassName("questions");
	for (var i = 0, len = elements.length; i < len; i++) 
	{
		elements[i].style.display = "block";
	}
}

function hideAll()
{
	var elements = document.getElementsByClassName("questions");
	for (var i = 1, len = elements.length; i < len; i++) 
	{
		elements[i].style.display = "none";
	}
	hideAllExecuted = true;
}


function nextQuestion(qNum)
{
	var elements = document.getElementsByClassName("questions");
	var cQNum = qNum - 1;
	if (qNum == 0)
		cQNum = 9;
	if (qNum == 7)
	{
		var qValue = document.getElementsByName("question7.1");
		for (var i = 0, length = qValue.length; i < length; i++)
		{
			if (qValue[i].checked)
			{
				if (qValue[i].value == 200)
					qNum = 8;
				break;
			}
		}
	}
	elements[cQNum].style.display = "none";
	elements[qNum].style.display = "block";
}

function submitAnswers()
{
	var totalRadios = document.getElementsByClassName("option");
	var totalValue = 0;
	for (var i = 0, length = totalRadios.length; i < length; i++)
	{
		if (totalRadios[i].checked)
		{
			var midTerm = Number(totalRadios[i].value);
			totalValue += midTerm;
		}
	}
	alert("Total Score: " + totalValue);
}