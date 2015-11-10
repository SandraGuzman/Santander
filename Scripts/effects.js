function showDropDownMenu (id) {
    "use strict";
	var e = document.getElementById(id);
	if (e.style.display !== 'block') {
	  var submenus = document.getElementsByClassName('dropDownNav');
	  for (var i = 0; i < submenus.length; ++i) {
		var item = submenus[i]; 
		item.style.display = 'none';
		item.parentNode.style.backgroundColor = "#fa0011";
	  }
	  
	  e.style.display = 'block';
	  e.parentNode.style.backgroundColor = "#bd0009";
    }
}

function hideDropDownMenu (id) {
	"use strict";
	var e = document.getElementById(id);
	if (e.style.display === 'block') {
		e.style.display = 'none';
		e.parentNode.style.backgroundColor = "#fa0011";
	}
}

function showMenu (e) {
	"use strict";
	var drawer = document.querySelector('.navHor');
    drawer.classList.toggle('open');
	/*e.preventDefault();*/
}

function hiddenMenu (e) {
	"use strict";
	var drawer = document.querySelector('.navHor');
    drawer.classList.remove('open');
	/*e.preventDefault();*/
}